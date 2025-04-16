<?php

namespace Database\Seeders\Bread;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class BreadForAdvertisementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 'advertisements');

        $dataRow = $this->dataRow($dataType, 'id');
        $dataRow->fill([
            'type' => 'number',
            'display_name' => __('voyager::seeders.data_rows.id'),
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'order' => 1,

        ])->save();

        $dataRow = $this->dataRow($dataType, 'photo_id');
        $dataRow->fill([
            'type' => 'image_id',
            'display_name' => "Фото",
            'required' => 0,
            'browse' => 1,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 1,
            'order' => 10,
            'details' => [
                'model' => 'App\\Models\\Image',
                'table' => 'images',
                'type' => 'belongsTo',
                'key' => 'id',
                'column' => 'photo_id'
            ],

        ])->save();

        $dataRow = $this->dataRow($dataType, 'additional_photos');

        $dataRow->fill([
            'type' => 'additional_photos',
            'display_name' => "Дополнительные Фото",
            'required' => 0,
            'browse' => 0,
            'read' => 0,
            'edit' => 1,
            'add' => 0,
            'delete' => 1,
            'order' => 10,
            'details' => [
                'model' => 'App\\Models\\Image',
                'table' => 'images',
                'type' => 'belongsToMany',
                'column' => 'image_id',
                'key' => 'id',
                'label' => 'photo_url',
                'pivot_table' => 'advertisement_images',
                'pivot' => 0,
                "validation" => [
                    "rule" => "image|mimes:jpg,png",
                ]
            ],
        ])->save();


        $dataRow = $this->dataRow($dataType, 'user_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => 'Владелец',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 1,
                'order' => 11,
            ])->save();
        }

        DataRow::create([
            'data_type_id' => $dataType->id,
            'field' => 'user_id',
            'type' => 'relationship',
            'display_name' => "Email Владелеца",
            'required' => 0,
            'browse' => 1,
            'read' => 0,
            'edit' => 0,
            'add' => 0,
            'delete' => 1,
            'details' => [
                'model' => 'App\\Models\\User',
                'table' => 'users',
                'type' => 'belongsTo',
                'column' => 'user_id',
                'key' => 'id',
                'label' => 'email',
                'display' => [
                    'width' => 10
                ],
            ],
            'order' => 11,
        ]);

        $dataRow = $this->dataRow($dataType, 'advertisement_belongsto_user_relationship');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'relationship',
                'display_name' => "Владелец",
                'required' => 1,
                'browse' => 0,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 1,
                'details' => [
                    'model' => 'App\\Models\\User',
                    'table' => 'users',
                    'type' => 'belongsTo',
                    'column' => 'user_id',
                    'key' => 'id',
                    'label' => 'email',
                ],
                'order' => 11,
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'company_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => 'Комания',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 1,
                'order' => 12,
            ])->save();
        }

        DataRow::create([
            'data_type_id' => $dataType->id,
            'field' => 'company_id',
            'type' => 'relationship',
            'display_name' => "Комания",
            'required' => 0,
            'browse' => 1,
            'read' => 0,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'details' => [
                'model' => 'App\\Models\\Company',
                'table' => 'companies',
                'type' => 'belongsTo',
                'column' => 'company_id',
                'key' => 'id',
                'label' => 'name',
                'display' => [
                    'width' => 10
                ],
            ],
            'order' => 12,
        ]);

        $dataRow = $this->dataRow($dataType, 'advertisement_belongsto_company_relationship');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'relationship',
                'display_name' => "Комания",
                'required' => 1,
                'browse' => 0,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 1,
                'details' => [
                    'model' => 'App\\Models\\Company',
                    'table' => 'companies',
                    'type' => 'belongsTo',
                    'column' => 'company_id',
                    'key' => 'id',
                    'label' => 'name',
                ],
                'order' => 12,
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'type');
        $dataRow->fill([
            'type' => 'select_dropdown',
            'display_name' => "Тип Обьяления",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 1,
            'order' => 13,
            'details' => [
                'display' => [
                    'width' => 10
                ],
                'options' => ['performer' => 'Испольнитель', 'employer' => 'Наниматель']
            ]
        ])->save();





        $dataRow = $this->dataRow($dataType, 'category_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => 'Категория',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 1,
                'order' => 14,
            ])->save();
        }

        DataRow::create([
            'data_type_id' => $dataType->id,
            'field' => 'category_id',
            'type' => 'relationship',
            'display_name' => "Категория",
            'required' => 0,
            'browse' => 1,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'details' => [
                'model' => 'App\\Models\\Category',
                'table' => 'categories',
                'type' => 'belongsTo',
                'column' => 'category_id',
                'key' => 'id',
                'label' => 'name',
                'display' => [
                    'width' => 10
                ],
            ],
            'order' => 14,
        ]);

        $dataRow = $this->dataRow($dataType, 'advertisement_belongsto_company_relationship');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'relationship',
                'display_name' => "Категория",
                'required' => 1,
                'browse' => 0,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 1,
                'details' => [
                    'model' => 'App\\Models\\Category',
                    'table' => 'categories',
                    'type' => 'belongsTo',
                    'column' => 'category_id',
                    'key' => 'id',
                    'label' => 'name',
                ],
                'order' => 14,
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'child_category_id');
        $dataRow->fill([
            'type' => 'hidden',
            'display_name' => __('voyager::seeders.data_rows.id'),
            'required' => 0,
            'browse' => 0,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 1,
            'order' => 15,
        ])->save();

        $dataRow = $this->dataRow($dataType, 'advertisement_belongsto_child_category_relationship');
        $dataRow->fill([
            'type' => 'relationship',
            'display_name' => "Подкатегория",
            'required' => 0,
            'browse' => 0,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 1,
            'details' => [
                'model' => 'App\\Models\\ChildCategory',
                'table' => 'child_categories',
                'type' => 'belongsTo',
                'column' => 'child_category_id',
                'key' => 'id',
                'label' => 'name',
                'display' => [
                    'width' => 10
                ],
            ],
            'order' => 15,
        ])->save();

        $dataRow = $this->dataRow($dataType, 'title');
        $dataRow->fill([
            'type' => 'text',
            'display_name' => "Название",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 0,
            'delete' => 1,
            'order' => 16,
            'details' => [
                'display' => [
                    'width' => 10
                ]
            ]
        ])->save();

        $dataRow = $this->dataRow($dataType, 'description');
        $dataRow->fill([
            'type' => 'rich_text_box',
            'display_name' => "Описание",
            'required' => 1,
            'browse' => 0,
            'read' => 1,
            'edit' => 1,
            'add' => 0,
            'delete' => 1,
            'order' => 17,
            'details' => [
                "validation" => [
                    "rule" => "required",
                    "messages" => [
                        "required" => ":attribute Обязательное поле",
                    ]
                ]
            ]
        ])->save();

        $this->numberFields([['price', 'Цена'],], 18);

        $dataRow = $this->dataRow($dataType, 'currency_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => 'Валюта',
                'required' => 1,
                'browse' => 0,
                'read' => 0,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 18,
            ])->save();
        }
        $dataRow = $this->dataRow($dataType, 'bonus_belongsto_currency_relationship');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'relationship',
                'display_name' => "Валюта",
                'required' => 1,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 0,
                'delete' => 1,
                'details' => [
                    'model' => 'App\\Models\\Currency',
                    'table' => 'currencies',
                    'type' => 'belongsTo',
                    'column' => 'currency_id',
                    'key' => 'id',
                    'label' => 'code',
                ],
                'order' => 19,
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'price_type');
        $dataRow->fill([
            'type' => 'select_dropdown',
            'display_name' => 'Дополнительные условия к цене',
            'required' => 1,
            'browse' => 0,
            'read' => 1,
            'edit' => 1,
            'add' => 0,
            'delete' => 1,
            'order' => 20,
            'details' => [
                'display' => [
                    'width' => 10
                ],
                'options' => [0 => 'Не используется', 1 => 'Торг возможен', 8 =>'Договорная']

            ]
        ])->save();

        $dataRow = $this->dataRow($dataType, 'payment');
        $dataRow->fill([
            'type' => 'select_dropdown',
            'display_name' => 'Оплата',
            'required' => 1,
            'browse' => 0,
            'read' => 1,
            'edit' => 1,
            'add' => 0,
            'delete' => 1,
            'order' => 21,
            'details' => [
                'display' => [
                    'width' => 10
                ],
                'options' => ['Не используется', 'В день', 'В неделю', 'В месяц', 'В год' , 'На 20 лет', 'навсегда']

            ]
        ])->save();

        $dataRow = $this->dataRow($dataType, 'hashtags');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hashtags',
                'display_name' => 'Хештеги',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 0,
                'delete' => 1,
                'order' => 22,
                'details' => [
                    'type' => 'array',
                    'display' => [
                        'width' => 10
                    ]
                ]
            ])->save();
        }

        $this->useYesOrNo('travel_abroad', 'Выезд за границу',23);
        $this->useYesOrNo('ready_for_political_advertising', 'Готовы к политической рекламе', 24);
        $this->useYesOrNo('photo_report', 'Фотоотчет', 25);
        $this->useYesOrNo('make_and_place_advertising', 'Сами изготовим и разместим рекламу', 26);

        $this->numberFields([
            ['reach_audience', 'Охват аудитории'],
            ['amount', 'Количество'],
            ['length', 'Длина'],
            ['width', 'Ширина'],

        ], 27);

        $dataRow = $this->dataRow($dataType, 'is_published');
        $dataRow->fill([
            'type' => 'checkbox',
            'display_name' => 'Активировать',
            'required' => 0,
            'browse' => 0,
            'read' => 1,
            'edit' => 1,
            'add' => 0,
            'delete' => 1,
            'order' => 30,
            'details' => [
                'checked' => true,
                'on' => 'Да',
                'off' => 'Нет',
            ]
        ])->save();

        $dataRow = $this->dataRow($dataType, 'published_at');
        $dataRow->fill([
            'type' => 'date',
            'display_name' => "Активирован в:",
            'required' => 0,
            'browse' => 0,
            'read' => 1,
            'edit' => 1,
            'add' => 0,
            'delete' => 1,
            'order' => 31,
            'details' => [
                'display' => [
                    'width' => 10
                ]
            ]
        ])->save();

        $dataRow = $this->dataRow($dataType, 'is_hide');
        $dataRow->fill([
            'type' => 'checkbox',
            'display_name' => 'Скрыть',
            'required' => 0,
            'browse' => 0,
            'read' => 1,
            'edit' => 1,
            'add' => 0,
            'delete' => 1,
            'order' => 32,
            'details' => [
                'checked' => true,
                'on' => 'Да',
                'off' => 'Нет',
            ]
        ])->save();

        $dataRow = $this->dataRow($dataType, 'coordinates');

        $dataRow->fill([
            'type' => 'coordinates',
            'display_name' => "Контакты",
            'required' => 0,
            'browse' => 0,
            'read' => 1,
            'edit' => 1,
            'add' => 0,
            'delete' => 1,
            'order' => 33,
        ])->save();

        $dataRow = $this->dataRow($dataType, 'is_allocate_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => "Дата завершения пакета Выделение",
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 34,
                'details' => [
                    'display' => [
                        'width' => 10
                    ]
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'is_top_country_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => "Дата завершения пакета Поднятие по всей стране",
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 35,
                'details' => [
                    'display' => [
                        'width' => 10
                    ]
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'is_urgent_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => "Дата завершения пакета Срочно",
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 35,
                'details' => [
                    'display' => [
                        'width' => 10
                    ]
                ]
            ])->save();
        }


    }

    protected function useYesOrNo($filed, $name, $order)
    {
        $dataType = $this->dataType('slug', 'advertisements');

        $dataRow = $this->dataRow($dataType, $filed);
        $dataRow->fill([
            'type' => 'select_dropdown',
            'display_name' => $name,
            'required' => 1,
            'browse' => 0,
            'read' => 1,
            'edit' => 1,
            'add' => 0,
            'delete' => 1,
            'order' => $order,
            'details' => [
                'display' => [
                    'width' => 10
                ],
                'options' => ['0' => 'Не используется', '1' => 'Нет', '2' => 'Да']
            ]
        ])->save();
    }

    protected function numberFields($fields, $order)
    {
        $dataType = $this->dataType('slug', 'advertisements');


        foreach ($fields as $field) {

            $dataRow = $this->dataRow($dataType, $field[0]);
            $dataRow->fill([
                'type' => 'number',
                'display_name' => $field[1],
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 0,
                'delete' => 1,
                'order' => $order++,
                'details' => [
                    'display' => [
                        'width' => 7
                    ],
                ]
            ])->save();
        }
    }

    protected function dataRow($type, $field)
    {
        return DataRow::firstOrNew([
            'data_type_id' => $type->id,
            'field' => $field,
        ]);
    }

    protected function dataType($field, $for)
    {
        return DataType::firstOrNew([$field => $for]);
    }
}
