<?php


namespace App\Http\Helpers;


use App\Http\Enums\ChatTypeEnum;
use App\Http\Requests\getChatRequest;
use App\Http\Requests\OfferYourPriceRequest;
use App\Http\Requests\SendMessageRequest;
use App\Jobs\SendEmailMessageJob;
use App\Mail\SendEmailAboutMessage;
use App\Models\Advertisement;
use App\Models\Chat;
use App\Models\ChatMessageFile;
use App\Models\ChatMessageStatus;
use App\Models\ChatUser;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Image;
use App\Models\Message;
use App\Models\OfferYourPrice;
use App\Models\User;
use App\Models\UserSetting;
use DateTime;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ChatHelper
{


    /**
     * @var CommonHelper
     */
    private $commonHelper;

    /**
     * @var LanguageHelper
     */
    private $languageHelper;

    public function __construct(CommonHelper $commonHelper, LanguageHelper $languageHelper)
    {
        $this->commonHelper = $commonHelper;
        $this->languageHelper = $languageHelper;
    }

    /**
     * @param $request SendMessageRequest | GetChatRequest | OfferYourPriceRequest
     * @return Chat
     */
    public function getOrCreatChat($request)
    {
        $id = $request->chat_id;
        if (!$id) {
            $chat = $this->findCommonChat($request);
            if (!$chat) {
                $chat = Chat::create([
                    "type" => $this->defineChatType($request)
                ]);
                $this->addUsersToChat($chat, $request);
            }
            return $chat;
        }
        return Chat::find($id);
    }

    public function getAnotherUser($chat)
    {

        $current_user = auth()->user();
        $users = $chat->users;
        $filtered_users = $users->filter(function ($user) use ($current_user) {
            if ($user->user_id != $current_user->id)
                return $user;
        });
        return $filtered_users;
    }

    /**
     * @param $request
     * @return Chat|null
     */
    protected function findCommonChat($request)
    {
        $from = ChatUser::where([
            ["user_id", "=", $request->from_id],
            ["company_id", "=", $request->from_company_id ?: null],
        ])->get();
        $to = ChatUser::where([
            ["user_id", "=", $request->to_id],
            ["company_id", "=", $request->to_company_id ?: null]
        ])->get();

        foreach ($from as $item_from)
            foreach ($to as $item_to)
                if ($item_from->chat_id === $item_to->chat_id)
                    return Chat::find($item_from->chat_id);

        return null;
    }

    protected function defineChatType($request)
    {
        if ($request->from_company_id || $request->to_company_id)
            return ChatTypeEnum::Company;
        return ChatTypeEnum::Private;
    }

    /**
     * @param $chat Chat
     * @param $request SendMessageRequest
     * @return ChatUser
     */
    protected function addUsersToChat($chat, $request)
    {
        return ChatUser::insert([
            [
                "chat_id" => $chat->id,
                "user_id" => $request->to_id,
                "company_id" => $request->to_company_id ?: null,
            ],
            [
                "chat_id" => $chat->id,
                "user_id" => $request->from_id,
                "company_id" => $request->from_company_id ?: null,
            ]
        ]);
    }


    /**
     * @param $chat Chat
     * @param $request SendMessageRequest
     * @return Message;
     */
    public function makeMessage($chat, $request)
    {
        $user_from = $chat->users->where("user_id", $request->from_id)->first();

        $message = [
            "chat_id" => $chat->id,
            "advertisement_id" => $request->advertisement_id,
            "user_id" => $request->from_id,
            "text" => $request->text,

        ];
        if ($chat->type == ChatTypeEnum::Company && !empty($user_from->company_id))
            $message["company_id"] = $user_from->company_id;

        $result = Message::create($message);

        $files = $request->file('files');
        if (!empty($files)) {
            foreach ($files as $file) {
                $url = $this->commonHelper->uploadedFile('chats/files', $file);

                ChatMessageFile::create([
                    'message_id' => $result->id,
                    'file' => $url,
                ]);
            }
        }

        $chatUserTo = $this->getAnotherUser($chat)->first();

        ChatMessageStatus::create([
            "chat_id" => $chat->id,
            "message_id" => $result->id,
            "user_id" => $chatUserTo->user_id,
            "company_id" => $chatUserTo->company_id,
            'is_read' => false
        ]);

        $userSetting = UserSetting::where('user_id', $chatUserTo->user_id)->first();
        if (!empty($userSetting)) {
            if ($userSetting->is_receive_messages_by_email) {
                // SendEmailMessageJob::dispatch($result);

                try {
                    $userFrom = User::find($request->from_id);
                    $userTo = User::find($chatUserTo->user_id);
                    Mail::to($userTo->email)->send(new SendEmailAboutMessage($userFrom, $userTo->email, $chat->id));
                } catch (\Throwable $throwable) {
                    Log::error($throwable->getMessage());
                }

            }


            if ($userSetting->is_receive_messages_by_email) {
                // todo
            }
        }


        return $result;
    }

    /**
     * @param $chat Chat
     * @param $request OfferYourPriceRequest
     * @return  OfferYourPrice;
     */
    public function makeOfferYourPrice($chat, $request)
    {
        $user_from = $chat->users->where("user_id", $request->from_id)->first();

        $message = [
            "chat_id" => $chat->id,
            "advertisement_id" => $request->advertisement_id,
            "user_id" => $request->from_id,
        ];

        if ($chat->type == ChatTypeEnum::Company)
            $message["company_id"] = $user_from->company_id;

        // todo
        //   1 добавить перевод для сообщения
        //   2 перевод для валюты
        //   3 проверку $chat->type ?

        $text = 'Для объявления №' . $request->advertisement_id . ' поступило предложение: Цена: ' . $request->price . ' руб.';
        $userFrom = User::find($request->from_id);
        $userPhone = $userFrom->phone;
        $message["text"] = $text . 'Тел: ' . $userPhone . ' Если Вас устраивает предложенная цена, пожалуйста перезвоните по указанному номеру телефона.';

        $m = Message::create($message);
        OfferYourPrice::create([
            "message_id" => $m->id,
            "price" => $request->price,
            "currency_id" => $request->currency_id
        ]);

        $chatUserTo = $this->getAnotherUser($chat)->first();

        ChatMessageStatus::create([
            "chat_id" => $chat->id,
            "message_id" => $m->id,
            "user_id" => $chatUserTo->user_id,
            "company_id" => $chatUserTo->company_id,
            'is_read' => false
        ]);

        $userSetting = UserSetting::where('user_id', $request->to_id)->first();
        if (!empty($userSetting)) {
            if ($userSetting->is_receive_messages_by_email) {
                // SendEmailMessageJob::dispatch($m);

                try {
                    $userTo = User::find($request->to_id);
                    Mail::to($userTo->email)->send(new SendEmailAboutMessage($userFrom, $userTo->email, $chat->id));
                } catch (\Throwable $throwable) {
                    Log::error($throwable->getMessage());
                }

            }


            if ($userSetting->is_receive_messages_by_phone) {
                // todo
            }
        }

        return $m;
    }

    /**
     * @param $user
     * @param $chats
     * @param $user_chats
     * @return array
     */
    public function getChatCounts($user, $chats, $user_chats)
    {
        $count_unread = ChatMessageStatus::where('is_read', false)->
        where('user_id', $user->id)->get();

        return [
            "count_all" => $user_chats->count(),
            "count_blocked" => $user_chats->where('blocked', true)->count(),
            "count_favorite" => $user_chats->where('favorite', true)->count(),
            "count_private" => $chats->where('type', ChatTypeEnum::Private)->count(),
            "count_company" => $chats->where('type', ChatTypeEnum::Company)->count(),
            "count_all_unread" => $user_chats->whereIn('chat_id', $count_unread->pluck('chat_id'))
                ->count(),
            "count_blocked_unread" => $user_chats->whereIn('chat_id', $count_unread->pluck('chat_id'))->where('blocked', true)->unique('chat_id')->count(),
            "count_favorite_unread" => $user_chats->whereIn('chat_id', $count_unread->pluck('chat_id'))->where('favorite', true)->unique('chat_id')->count(),
            "count_private_unread" => $chats->whereIn('chat_id', $count_unread->pluck('chat_id'))->where('type', ChatTypeEnum::Private)->unique('chat_id')->count(),
            "count_company_unread" => $chats->whereIn('chat_id', $count_unread->pluck('chat_id'))->where('type', ChatTypeEnum::Company)->unique('chat_id')->count(),
        ];
    }


    public function getChats($user, $type = null, $isFavorite = null, $isBlocked = null)
    {
        $chats = collect([]);

        $user_chats = ChatUser::join('chats', 'chats.id', '=', 'chat_user.chat_id')
            ->select('chats.id as chat_id', 'chats.type as chat_type', 'chat_user.favorite', 'chat_user.blocked')
            ->where('chat_user.user_id', $user->id);

        if (!empty($type))
            $user_chats = $user_chats->where('chats.type', $type)
                ->where('chat_user.blocked', false);
        elseif (!empty($isFavorite))
            $user_chats = $user_chats->where('chat_user.favorite', true)
                ->where('chat_user.blocked', false);
        elseif (!empty($isBlocked))
            $user_chats = $user_chats->where('chat_user.favorite', false)
                ->where('chat_user.blocked', true);

        $user_chats = $user_chats->get();

        if ($user_chats->isEmpty())
            return $chats;

        $chatIds = $user_chats->pluck('chat_id')->all();
        $toUserChats = ChatUser::whereIn('chat_id', $chatIds)
            ->where('user_id','!=', $user->id)
            ->get();

        $user_chats
            ->unique('chat_id')
            ->each(function ($user_chat) use (&$chats, $user, $toUserChats) {

                $message = Message::where('chat_id', $user_chat->chat_id)
                    ->orderBy('created_at', 'DESC')
                    ->first();

                if (empty($message))
                    return true;

                $toUser = $toUserChats->where('chat_id', $user_chat->chat_id)->first();

                if (empty($toUser))
                    return true;

                $author = User::find($toUser->user_id);

                if (empty($author))
                    return true;

                $item['author_name'] = $author->name;
                $item['author_logo'] = (!empty($author->avatar) && $author->avatar != 'users/default.png') ?
                    asset(Storage::url($author->avatar)) : url('storage/default/user.png');

                $item['chat_id'] = $user_chat->chat_id;
                $item['is_favorite'] = $user_chat->favorite;
                $item['is_blocked'] = $user_chat->blocked;
                $item['message_date'] = $message->created_at->format('d.m.Y H:i');
                $item['message_text'] = $message->text;
                $item['advertisement_title'] = '';

                if (!empty($message->advertisement_id)) {
                    $advertisement = Advertisement::find($message->advertisement_id);

                    if (!empty($advertisement))
                        $item['advertisement_title'] = $advertisement->title;
                }

                $item['count_unread'] = ChatMessageStatus::where('chat_id', $user_chat->chat_id)
                    ->where('is_read', false)
                    ->where('user_id', $user->id)
                    ->count();

                $chats->push($item);
            });

        return $chats->sortByDesc(function ($chat) {
            $date = new DateTime($chat['message_date']);
            return $date->getTimestamp();
        });

        //return $chats->sortBy('message_date');
    }

    public function getChatMessages($user, $userFrom, $chatId)
    {
        $chat = [];

        $chatMessages = Message::leftjoin('offer_your_prices as oyf', 'oyf.message_id', '=', 'chat_message.id')
            ->leftjoin('currencies as c', 'c.id', '=', 'oyf.currency_id')
            ->select('chat_message.id as id',
                'chat_message.advertisement_id as advertisement_id',
                'chat_message.text as text',
                'chat_message.user_id as user_id',
                'chat_message.created_at as created_at',
                'oyf.price as price',
                'oyf.price as price',
                'c.code as currency_code',
                'c.name as currency_name',
            )
            ->where('chat_message.chat_id',  $chatId)
            ->orderBy('chat_message.created_at', 'ASC')
            ->get();

        $currencyCodes = $chatMessages->pluck('currency_code')->unique()->values()->all();
        $currencies = $this->languageHelper->getTranslations($currencyCodes, App::getLocale());


        $chatMessages ->each(function ($message) use (&$chat, $user, $userFrom, $currencies) {
            $item = [];

            $item['message_id'] = $message->id;
            $item['message_date'] = $message->created_at->format('d.m.Y H:i');

            $files  = [];
            ChatMessageFile::where('message_id', $message->id)
                ->each(function ($file) use (&$files) {

                    $result = json_decode($file->file, true) [0];
                    $result['download_link'] = url('storage' .  $result['download_link']);
                    $files[] = $result;

                });
            $item['files'] = $files;

            $item['author'] = 'to';
            if ($user->id == $message->user_id)
                $item['author'] = 'from';


            $item['message']['text'] = $message->text;
            $item['message']['price'] = '';
            $item['message']['translation_currency_code'] = '';
            $item['message']['phone'] = '';

            if(!empty($message->price)) {
                $item['message']['text'] = 'Если Вас устраивает предложенная цена, пожалуйста перезвоните по указанному номеру телефона.';
                $item['message']['price'] = $message->price;
                $item['message']['translation_currency_code'] =
                    !empty($message->currency_code) ? $currencies[$message->currency_code] : '';

                $phone = $userFrom->phone ? $userFrom->phone : '';
                if ($user->id == $message->user_id)
                    $phone = $user->phone ? $user->phone : '';

                $item['message']['phone'] = $phone;
            }

            $item['message']['advertisement'] = [];
            if (!empty($message->advertisement_id)) {
                $advertisement = Advertisement::find($message->advertisement_id);

                if (!empty($advertisement)) {
                    $item['message']['advertisement']['id'] = $advertisement->id;
                    $item['message']['advertisement']['title'] = $advertisement->title;
                    $item['message']['advertisement']['image'] = $advertisement->photo_id ?
                        asset(Storage::url(Image::find($advertisement->photo_id)->photo_url)) :
                        url('storage/default/product.png');
                }

            }

            $chat[] = $item;
        });

        return $chat;
    }

}
