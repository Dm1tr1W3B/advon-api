<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;

class MenuItemsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('name', 'admin')->firstOrFail();

//        $menuItem = MenuItem::firstOrNew([
//            'menu_id' => $menu->id,
//            'title'   => __('voyager::seeders.menu_items.dashboard'),
//            'url'     => '',
//            'route'   => 'voyager.dashboard',
//        ]);
//        if (!$menuItem->exists) {
//            $menuItem->fill([
//                'target'     => '_self',
//                'icon_class' => 'voyager-boat',
//                'color'      => null,
//                'parent_id'  => null,
//                'order'      => 1,
//            ])->save();
//        }


        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Категории',
            'url'     => '',
            'route'   => 'voyager.categories.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-categories',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 2,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Подкатегории',
            'url'     => '',
            'route'   => 'voyager.child_categories.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-categories',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 3,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => "Роли",
            'url'     => '',
            'route'   => 'voyager.roles.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-lock',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 4,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => "Пользователи",
            'url'     => '',
            'route'   => 'voyager.users.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-person',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 5,
            ])->save();
        }
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Компании',
            'url'     => '',
            'route'   => 'voyager.companies.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-medal-rank-star',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 6,
            ])->save();
        }

        $advertisementMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => "Объявления",
            'url'     => '',
        ]);
        if (!$advertisementMenuItem->exists) {
            $advertisementMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-medal-rank-star',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 7,
            ])->save();
        }


        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => "Новые",
            'url'     => 'admin/advertisements?sort_order=desc&new=1',
            'route'   => null,
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-medal-rank-star',
                'color'      => null,
                'parent_id'  => $advertisementMenuItem->id,
                'order'      => 1,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => "Все",
            'url'     => '',
            'route'   => 'voyager.advertisements.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-medal-rank-star',
                'color'      => null,
                'parent_id'  => $advertisementMenuItem->id,
                'order'      => 2,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'История балансов',
            'url'     => '',
            'route'   => 'voyager.transaction_balances.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-dollar',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 8,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Бонусы',
            'url'     => '',
            'route'   => 'voyager.bonuses.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-magnet',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 9,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Баннеры',
            'url'     => '',
            'route'   => 'voyager.banners.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-shop',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 10,
            ])->save();
        }

        //todo new tz

        $complaintMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => "Жалобы",
            'url'     => '',
        ]);
        if (!$complaintMenuItem->exists) {
            $complaintMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-warning',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 11,
            ])->save();
        }

            $menuItem = MenuItem::firstOrNew([
                'menu_id' => $menu->id,
                'title'   => "Типы Жалоб",
                'url'     => '',
                'route'   => 'voyager.complaint_types.index',
            ]);
            if (!$menuItem->exists) {
                $menuItem->fill([
                    'target'     => '_self',
                    'icon_class' => 'voyager-bomb',
                    'color'      => null,
                    'parent_id'  => $complaintMenuItem->id,
                    'order'      => 1,
                ])->save();
            }

            $menuItem = MenuItem::firstOrNew([
                'menu_id' => $menu->id,
                'title'   => "На автора",
                'url'     => 'admin/advertisement_author_complaints?sort_order=desc&new=1',
                'route'   => null,
            ]);
            if (!$menuItem->exists) {
                $menuItem->fill([
                    'target'     => '_self',
                    'icon_class' => 'voyager-bomb',
                    'color'      => null,
                    'parent_id'  => $complaintMenuItem->id,
                    'order'      => 2,
                ])->save();
            }
            $menuItem = MenuItem::firstOrNew([
                'menu_id' => $menu->id,
                'title'   => "На обьявление",
                'url'     => 'admin/advertisement_complaints?sort_order=desc&new=1',
                'route'   => null
            ]);
            if (!$menuItem->exists) {
                $menuItem->fill([
                    'target'     => '_self',
                    'icon_class' => 'voyager-bomb',
                    'color'      => null,
                    'parent_id'  => $complaintMenuItem->id,
                    'order'      => 3,
                ])->save();
            }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'SEO',
            'url'     => '',
            'route'   => 'voyager.s_e_o_s.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-browser',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 12,
            ])->save();
        }

        $feedbackMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => "Обратная связь",
            'url'     => '',
        ]);

        $feedbackMenuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-forward',
            'color'      => null,
            'parent_id'  => null,
            'order'      => 16,
        ])->save();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => "Темы",
            'url'     => '',
            'route'   => 'voyager.feedback_types.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-forward',
                'color'      => null,
                'parent_id'  => $feedbackMenuItem->id,
                'order'      => 1,
            ])->save();
        }
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => "Сообщения",
            'url'     => 'admin/feedback?sort_order=desc&new=1',
            'route'   => null,
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-forward',
                'color'      => null,
                'parent_id'  => $feedbackMenuItem->id,
                'order'      => 1,
            ])->save();
        }





        $LanguagesMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => "Локализация",
            'url'     => '',
        ]);

        $LanguagesMenuItem->fill([
            'target'     => '_self',
            'icon_class' => 'voyager-world',
            'color'      => null,
            'parent_id'  => null,
            'order'      => 15,
        ])->save();

            $LangMenuItem = MenuItem::firstOrNew([
                'menu_id' => $menu->id,
                'title'   => "Языки",
                'url'     => '',
                'route'   => 'voyager.languages.index',

            ]);

            $LangMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-world',
                'color'      => null,
                'parent_id'  => $LanguagesMenuItem->id,
                'order'      => 2,
            ])->save();
            $TranslationsMenuItem = MenuItem::firstOrNew([
                'menu_id' => $menu->id,
                'title'   => "Переводы",
                'url'     => '',
                'route'   => 'voyager.front_variables_lang.index',

            ]);

            $TranslationsMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-font',
                'color'      => null,
                'parent_id'  => $LanguagesMenuItem->id,
                'order'      => 3,
            ])->save();



        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.settings'),
            'url'     => '',
            'route'   => 'voyager.settings.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-settings',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 16,
            ])->save();
        }









        // todo del

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.media'),
            'url'     => '',
            'route'   => 'voyager.media.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-images',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 20,
            ])->save();
        }


        $toolsMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.tools'),
            'url'     => '',
        ]);
        if (!$toolsMenuItem->exists) {
            $toolsMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-tools',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 21,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.menu_builder'),
            'url'     => '',
            'route'   => 'voyager.menus.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-list',
                'color'      => null,
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 22,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.database'),
            'url'     => '',
            'route'   => 'voyager.database.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-data',
                'color'      => null,
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 23,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.compass'),
            'url'     => '',
            'route'   => 'voyager.compass.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-compass',
                'color'      => null,
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 24,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.bread'),
            'url'     => '',
            'route'   => 'voyager.bread.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-bread',
                'color'      => null,
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 25,
            ])->save();
        }



    }
}
