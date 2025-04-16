<?php


namespace App\Http\Controllers\Api;


use App\Http\Helpers\ChatHelper;
use App\Http\Helpers\CommonHelper;
use App\Http\Requests\getChatRequest;
use App\Http\Requests\GetChatsRequest;
use App\Http\Requests\OfferYourPriceRequest;
use App\Http\Requests\SendMessageRequest;
use App\Http\Requests\TurnBlockChatRequest;
use App\Http\Requests\TurnChatFavoriteRequest;
use App\Models\Advertisement;
use App\Models\Chat;
use App\Models\ChatMessageStatus;
use App\Models\ChatUser;
use App\Models\Company;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class ChatApiController
 * @package App\Http\Controllers\Api
 * @group Chat
 */
class ChatApiController
{

    /**
     * @var int
     */
    protected static $paginateMessages = 10;

    /**
     * @var int
     */
    protected static $paginateChats = 7;

    /**
     * @var ChatHelper
     */
    protected $chatHelper;

    /**
     * @var Chat
     */
    protected $chat;
    /**
     * @var Message
     */
    protected $message;

    /**
     * @var CommonHelper
     */
    private $commonHelper;

    /**
     * ChatController constructor.
     * @param ChatHelper $chatHelper
     */
    public function __construct(ChatHelper $chatHelper, CommonHelper $commonHelper)
    {
        $this->chatHelper = $chatHelper;
        $this->commonHelper = $commonHelper;
    }

    /**
     * @authenticated
     * @param getChatRequest $request
     * @return JsonResponse
     *
     * @responseFile storage/responses/chat/getMessages.json
     */
    public function getMessages(getChatRequest $request)
    {
        $user = auth()->user();
        $this->chat = Chat::find($request->chat_id);

        $chatUsers = ChatUser::where('chat_id', $this->chat->id)->get();
        if ($chatUsers->isEmpty())
            return response()->json(['non_field_error' => [__("chat.This is not your chat")]], 400);

        $result['user_to'] = [
            'name' => $user->name,
            'avatar' => (!empty($user->avatar) && $user->avatar != 'users/default.png') ?
                asset(Storage::url($user->avatar)) : url('storage/default/user.png')
        ];

        $chatUserTo = $chatUsers->where('user_id', $user->id)->first();
        $chatUserFrom = $chatUsers->where('user_id', '!=', $user->id)->first();
        if (empty($chatUserFrom))
            return response()->json(['non_field_error' => [__("chat.chatUserFrom not found")]], 400);

        $userIdFrom = $chatUserFrom->user_id;
        if (empty($userIdFrom))
            return response()->json(['non_field_error' => [__("chat.userIdFrom not found")]], 400);

        $userFrom = User::find($userIdFrom);
        if (empty($userFrom))
            return response()->json(['non_field_error' => [__("chat.userFrom not found")]], 400);

        $lastMessageFrom = Message::where('chat_id',  $this->chat->id)
            ->where('user_id', $userIdFrom)
            ->orderBy('created_at', 'DESC')
            ->first();

        $result['user_from'] = [
            'id' => $userFrom->id,
            'name' => $userFrom->name,
            'avatar' => (!empty($userFrom->avatar) && $userFrom->avatar != 'users/default.png') ?
                asset(Storage::url($userFrom->avatar)) : url('storage/default/user.png'),
            'message_date' => empty($lastMessageFrom) ? '' : $lastMessageFrom->created_at->format('d.m.Y H:i'),
        ];

        $result['chat'] = $this->chatHelper->getChatMessages($user, $userFrom, $this->chat->id);

        $result['is_favorite'] = $chatUserTo->favorite;
        $result['is_blocked'] = $chatUserTo->blocked;

        ChatMessageStatus::where("chat_id", $this->chat->id)
            ->where("user_id", $user->id)
            ->update(["is_read"=> true]);


        return response()->json(['data' => $result]);

    }

    /**
     * @authenticated
     *
     * @param GetChatsRequest $request
     * @return JsonResponse
     *
     * @queryParam type string The chat type private or company. Example: private
     * @queryParam is_favorite boolean
     * @queryParam is_blocked boolean
     * @queryParam number_items_page integer For paginate
     * @queryParam page integer  For paginate
     * @responseFile storage/responses/chat/getChats.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getChats(GetChatsRequest $request)
    {
        $user = auth()->user();

        if (empty($user->email_verified_at))
            return response()->json(['non_field_error' => [__('auth.The user email address is not verified')]], 400);

        $chats = $this->chatHelper->getChats($user, $request->type, $request->is_favorite, $request->is_blocked);

        $prePage = 10;
        if (!empty($request->number_items_page))
            $prePage = (int)$request->number_items_page;

        $query = [
            'type' => $request->type,
            'is_favorite' => $request->is_favorite,
            'is_blocked' => $request->is_blocked
        ];

        $paginator = $this->commonHelper->paginate($chats, $prePage, $request->page, ['path' => url('/api/v1/getChats'), 'query' => $query ]);
        return response()->json(['data' => $paginator]);
    }

    /**
     * @authenticated
     * @param SendMessageRequest $request
     * @return JsonResponse
     * @responseFile storage/responses/chat/sendMessage.json
     */
    public function sendMessage(SendMessageRequest $request)
    {
        $user = auth()->user();

        if (empty($user->email_verified_at))
            return response()->json(['non_field_error' => [__('auth.The user email address is not verified')]], 400);

        if (empty($request->chat_id) && empty($request->to_id) && empty($request->to_company_id) && empty($request->advertisement_id))
            return response()->json(['non_field_error' => [__('chat.Invalid input parameters')]], 400);

        $company = $user->company;
        $request->merge([
            "from_id" => $user->id,
            "from_company_id" => empty($company) ? null :  $company->id,
        ]);

        if (!empty($request->advertisement_id)) {
            $advertisement = Advertisement::where('id', $request->advertisement_id)->first();

            if (empty($advertisement))
                return response()->json(['non_field_error' => [__('advertisement.Advertisement not found')]], 400);

            $request->merge([
                "to_id" => $advertisement->user_id,
                "to_company_id" => $advertisement->company_id,
            ]);
        }

        if (!empty($request->to_company_id) && empty($request->to_id)) {
            $company = Company::find($request->to_company_id);

            $request->merge([
                "to_id" => $company->owner_id,
            ]);
        }

        if (empty($request->to_company_id) && !empty($request->to_id)) {
            $company = Company::where('owner_id', $request->to_id)->first();

            $request->merge([
                "to_company_id" => empty($company) ? null : $company->id,
            ]);
        }

        if ($user->id == $request->to_id)
            return response()->json(['non_field_error' => [__('chat.You cannot write to yourself')]], 400);

        try {
            $this->chat = $this->chatHelper->getOrCreatChat($request);

            if ($this->chat->users->where('blocked', true)->first())
                return response()->json(['non_field_error' => [__("chat.Chat is blocked")]], 400);

            if ($this->chat->users)
                $this->message = $this->chatHelper->makeMessage($this->chat, $request);

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [$throwable->getMessage()]], 400);
        }

        return response()->json(["data" => [
            "message" => 'added_message',
        ]]);

    }

    /**
     * @authenticated
     * @param OfferYourPriceRequest $request
     * @return JsonResponse
     *
     */
    public function offerYourPrice(OfferYourPriceRequest $request)
    {
        $user = auth()->user();

        if (empty($user->email_verified_at))
            return response()->json(['non_field_error' => [__('auth.The user email address is not verified')]], 400);

        $company = $user->company;

        $advertisement = Advertisement::where('id', $request->advertisement_id)->first();

        if (empty($advertisement))
            return response()->json(['non_field_error' => [__('advertisement.Advertisement not found')]], 400);

        $request->merge([
            "from_id" => $user->id,
            "to_id" => $advertisement->user_id,
            "to_company_id" => $advertisement->company_id,
            "from_company_id" => empty($company) ? null :  $company->id,
        ]);

        if ($user->id == $request->to_id)
            return response()->json(['non_field_error' => [__('chat.You cannot write to yourself')]], 400);

        try {
            $this->chat = $this->chatHelper->getOrCreatChat($request);

            if ($this->chat->users->where('blocked', true)->first())
                return response()->json(['non_field_error' => [__("chat.Chat is blocked")]], 400);

            if ($this->chat->users)
                $this->message = $this->chatHelper->makeOfferYourPrice($this->chat, $request);

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [$throwable->getMessage()]], 400);
        }

        return response()->json(["data" => [
            "message" => 'added_offer_your_price',
        ]]);
    }

    /**
     * @authenticated
     * @param getChatRequest $request
     * @return JsonResponse
     * @responseFile storage/responses/chat/deleteChat.json
     */
    public function deleteChat(getChatRequest $request)
    {
        $user = auth()->user();
        $user_chat = ChatUser::where('user_id', $user->id)->where('chat_id', $request->chat_id)->first();

        if (!$user_chat)
            return response()->json(['non_field_error' => [__("chat.This is not your chat")]], 400);
        try {
            Chat::destroy($request->chat_id);
            return response()->json([
                'chat_id' => $request->chat_id,
                'deleted' => true
            ]);
        } catch (\Exception $exception) {
            return response()->json(['non_field_error' => [__("chat.This chat can not be deleted"), $exception]], 400);
        }
    }

    /**
     * @authenticated
     * @param TurnChatFavoriteRequest $request
     * @return JsonResponse
     * @responseFile storage/responses/chat/turnChatFavorite.json
     */
    public function turnChatFavorite(TurnChatFavoriteRequest $request)
    {
        $user = auth()->user();
        $user_chat = ChatUser::where('user_id', $user->id)->where('chat_id', $request->chat_id)->first();

        if (!$user_chat)
            return response()->json(['non_field_error' => [__("chat.This is not your chat")]], 400);

        if ($request->is_favorite)
            $user_chat->blocked = false;

        $user_chat->favorite = $request->is_favorite;
        $user_chat->save();

        return response()->json([
            'chat_id' => $request->chat_id,
            'favorite' => $user_chat->favorite
        ]);
    }

    /**
     * @authenticated
     * @param TurnBlockChatRequest $request
     * @return JsonResponse
     * @responseFile storage/responses/chat/turnBlockChat.json
     */
    public function turnBlockChat(TurnBlockChatRequest $request)
    {
        $user = auth()->user();
        $user_chat = ChatUser::where('user_id', $user->id)->where('chat_id', $request->chat_id)->first();

        if (!$user_chat)
            return response()->json(['non_field_error' => [__("chat.This is not your chat")]], 400);

        if ($request->is_blocked)
            $user_chat->favorite = false;

        $user_chat->blocked = $request->is_blocked;
        $user_chat->save();

        return response()->json([
            'chat_id' => $request->chat_id,
            'blocked' => $user_chat->blocked
        ]);

    }

}
