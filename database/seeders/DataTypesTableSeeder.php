<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;

class DataTypesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 'categories');
        $dataType->fill([
            'name' => 'categories',
            'display_name_singular' => "Категория",
            'display_name_plural' => "Категории",
            'icon' => 'voyager-categories',
            'model_name' => 'App\\Models\\Category',
            'controller' => 'App\\Http\\Controllers\\CategoriesController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 1,
            'details' => [
                'orderBy' => 'id'
            ]
        ])->save();

        $dataType = $this->dataType('slug', 'child_categories');
        $dataType->fill([
            'name' => 'child_categories',
            'display_name_singular' => "Подкатегория",
            'display_name_plural' => "Подкатегории",
            'icon' => 'voyager-categories',
            'model_name' => 'App\\Models\\ChildCategory',
            'controller' => 'App\\Http\\Controllers\\ChildCategoriesController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 1,
            'details' => [
                'orderBy' => 'id'
            ]
        ])->save();

        $dataType = $this->dataType('slug', 'roles');
        if (!$dataType->exists) {
            $dataType->fill([
                'name' => 'roles',
                'display_name_singular' => __('voyager::seeders.data_types.role.singular'),
                'display_name_plural' => __('voyager::seeders.data_types.role.plural'),
                'icon' => 'voyager-lock',
                'model_name' => 'TCG\\Voyager\\Models\\Role',
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController',
                'generate_permissions' => 1,
                'server_side' => 1,
                'description' => '',
            ])->save();
        }

        $dataType = $this->dataType('slug', 'users');
        if (!$dataType->exists) {
            $dataType->fill([
                'name' => 'users',
                'display_name_singular' => __('voyager::seeders.data_types.user.singular'),
                'display_name_plural' => __('voyager::seeders.data_types.user.plural'),
                'icon' => 'voyager-person',
                'model_name' => 'App\\Models\\User',
                'policy_name' => 'TCG\\Voyager\\Policies\\UserPolicy',
                'controller' => 'App\\Http\\Controllers\\UserController',
                'generate_permissions' => 1,
                'description' => '',
                'server_side' => 1,

            ])->save();
        }

        $dataType = $this->dataType('slug', 'companies');
        $dataType->fill([
            'name' => 'companies',
            'display_name_singular' => "Компания",
            'display_name_plural' => "Компании",
            'icon' => 'voyager-medal-rank-star',
            'model_name' => 'App\\Models\\Company',
            'controller' => 'App\\Http\\Controllers\\CompaniesController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 1
        ])->save();

        $dataType = $this->dataType('slug', 'advertisements');
        $dataType->fill([
            'name' => 'advertisements',
            'display_name_singular' => "Объявление",
            'display_name_plural' => "Объявления",
            'icon' => 'voyager-medal-rank-star',
            'model_name' => 'App\\Models\\Advertisement',
            'controller' => 'App\\Http\\Controllers\\AdvertisementController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 1,
            'details' => [
                'orderBy' => 'id'
            ]
        ])->save();

        $dataType = $this->dataType('slug', 'transaction_balances');
        $dataType->fill([
            'name' => 'transaction_balances',
            'display_name_singular' => "История балансов",
            'display_name_plural' => "История балансов",
            'icon' => 'voyager-dollar',
            'model_name' => 'App\\Models\\TransactionBalance',
            'controller' => 'App\\Http\\Controllers\\TransactionBalanceController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 1,
            'details' => [
                'orderBy' => 'id'
            ]
        ])->save();

        $dataType = $this->dataType('slug', 'banners');
        $dataType->fill([
            'name' => 'banners',
            'display_name_singular' => "Банер",
            'display_name_plural' => "Банеры",
            'icon' => 'voyager-shop',
            'model_name' => 'App\\Models\\Banner',
            'controller' => 'App\\Http\\Controllers\\BannerController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 1,
            'details' => [
                'orderBy' => 'id'
            ]
        ])->save();

        $dataType = $this->dataType('slug', 'bonuses');
        $dataType->fill([
            'name' => 'bonuses',
            'display_name_singular' => "Бонус",
            'display_name_plural' => "Бонусы",
            'icon' => 'voyager-magnet',
            'model_name' => 'App\\Models\\Bonus',
            'controller' => 'App\\Http\\Controllers\\BonusController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 1,
            'details' => [
                'orderBy' => 'id'
            ]
        ])->save();

        $dataType = $this->dataType('slug', 's_e_o_s');
        $dataType->fill([
            'name' => 's_e_o_s',
            'display_name_singular' => "SEO",
            'display_name_plural' => "SEO",
            'icon' => 'voyager-browser',
            'model_name' => 'App\\Models\\SEO',
            'controller' => 'App\\Http\\Controllers\\SeoController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 1,
            'details' => [
                'orderBy' => 'id'
            ]
        ])->save();


        $dataType = $this->dataType('slug', 'complaint_types');
        $dataType->fill([
            'name' => 'complaint_types',
            'display_name_singular' => "Тип Жалобы",
            'display_name_plural' => "Типы Жалоб",
            'icon' => 'voyager-bomb',
            'model_name' => 'App\\Models\\ComplaintType',
            'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerBaseController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 1,
            'details' => [
                'orderBy' => 'id'
            ]
        ])->save();

        $dataType = $this->dataType('slug', 'advertisement_author_complaints');
        $dataType->fill([
            'name' => 'advertisement_author_complaints',
            'display_name_singular' => "На Автора",
            'display_name_plural' => "На Автора",
            'icon' => 'voyager-bomb',
            'model_name' => 'App\\Models\\AdvertisementAuthorComplaint',
            'controller' => 'App\\Http\\Controllers\\AdvertisementAuthorComplaintController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 1,
            'details' => [
                'orderBy' => 'id',
            ]
        ])->save();

        $dataType = $this->dataType('slug', 'advertisement_complaints');
        $dataType->fill([
            'name' => 'advertisement_complaints',
            'display_name_singular' => "На Объявление",
            'display_name_plural' => "На Объявление",
            'icon' => 'voyager-bomb',
            'model_name' => 'App\\Models\\AdvertisementComplaint',
            'controller' => 'App\\Http\\Controllers\\AdvertisementComplaintController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 1,
            'details' => [
                'orderBy' => 'id',
            ]
        ])->save();


        $dataType = $this->dataType('slug', 'feedback_types');
        $dataType->fill([
            'name' => 'feedback_types',
            'display_name_singular' => "Тема",
            'display_name_plural' => "Темы",
            'icon' => 'voyager-forward',
            'model_name' => 'App\\Models\\FeedbackType',
            'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerBaseController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 1,
            'details' => [
                'orderBy' => 'id'
            ]
        ])->save();

        $dataType = $this->dataType('slug', 'feedback');
        $dataType->fill([
            'name' => 'feedback',
            'display_name_singular' => "Сообщение",
            'display_name_plural' => "Сообщения",
            'icon' => 'voyager-forward',
            'model_name' => 'App\\Models\\Feedback',
            'controller' => 'App\\Http\\Controllers\\FeedbackController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 1,
            'details' => [
                'orderBy' => 'id'
            ]
        ])->save();

        $dataType = $this->dataType('slug', 'languages');
        $dataType->fill([
            'name' => 'languages',
            'display_name_singular' => "Язык",
            'display_name_plural' => "Языки",
            'icon' => 'voyager-compass',
            'model_name' => 'App\\Models\\Language',
            'controller' => 'App\\Http\\Controllers\\LanguageController',
            'generate_permissions' => 1,
            'server_side' => 1,
            'description' => '',
        ])->save();

        $dataType = $this->dataType('slug', 'front_variables_lang');
        $dataType->fill([
            'name' => 'front_variables_lang',
            'display_name_singular' => "Перевод",
            'display_name_plural' => "Переводы",
            'icon' => 'voyager-font',
            'model_name' => 'App\\Models\\FrontVariablesLang',
            'controller' => 'App\\Http\\Controllers\\FrontVariablesLangController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 1
        ])->save();

       /*
        $dataType = $this->dataType('slug', 'front_variables_lang');
        $dataType->fill([
            'name' => 'front_variables_lang',
            'display_name_singular' => "front variables lang",
            'display_name_plural' => "front variables lang",
            'icon' => 'voyager-font',
            'model_name' => 'App\\Models\\FrontVariablesLang',
            'controller' => 'App\\Http\\Controllers\\FrontVariablesLangController',
            'generate_permissions' => 1,
            'description' => '',
            'server_side' => 0
        ])->save();
        */






        $dataType = $this->dataType('slug', 'menus');
        if (!$dataType->exists) {
            $dataType->fill([
                'name' => 'menus',
                'display_name_singular' => __('voyager::seeders.data_types.menu.singular'),
                'display_name_plural' => __('voyager::seeders.data_types.menu.plural'),
                'icon' => 'voyager-list',
                'model_name' => 'TCG\\Voyager\\Models\\Menu',
                'controller' => '',
                'generate_permissions' => 1,
                'description' => '',
            ])->save();
        }

    }

    /**
     * [dataType description].
     *
     * @param $field
     * @param $for
     * @return  [type] [description]
     */
    protected function dataType($field, $for)
    {
        return DataType::firstOrNew([$field => $for]);
    }
}
