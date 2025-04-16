<?php

namespace Database\Seeders\Bread;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class BreadForCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 'companies');

        $dataRow = $this->dataRow($dataType, 'id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => __('voyager::seeders.data_rows.id'),
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 1,

            ])->save();
        }


        $dataRow = $this->dataRow($dataType, 'owner_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => 'Владелец',
                'required' => 1,
                'browse' => 0,
                'read' => 0,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 10,
            ])->save();
        }


        DataRow::create([
            'data_type_id' => $dataType->id,
            'field' => 'owner_id',
            'type' => 'relationship',
            'display_name' => "Email Владелеца",
            'required' => 0,
            'browse' => 1,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'details' => [
                'model' => 'App\\Models\\User',
                'table' => 'users',
                'type' => 'belongsTo',
                'column' => 'owner_id',
                'key' => 'id',
                'label' => 'email',
                'display' => [
                    'width' => 10
                ],
            ],
            'order' => 10,
        ]);



        $dataRow = $this->dataRow($dataType, 'company_belongsto_owner_relationship');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'relationship',
                'display_name' => "Email Владелеца",
                'required' => 1,
                'browse' => 0,
                'read' => 0,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => [
                    'model' => 'App\\Models\\User',
                    'table' => 'users',
                    'type' => 'belongsTo',
                    'column' => 'owner_id',
                    'key' => 'id',
                    'label' => 'email',
                ],
                'order' => 10,
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'name');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'text',
                'display_name' => "Название",
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 11,
                'details' => [
                    'display' => [
                        'width' => 10
                    ]
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'logo_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'image_id',
                'display_name' => "Лого",
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 12,
                'details' => [
                    'model' => 'App\\Models\\Image',
                    'table' => 'images',
                    'type' => 'belongsTo',
                    'key' => 'id',
                    'column' => 'logo_id',
                    "validation" => [
                        "rule" => "image|mimes:jpg,png",
                    ]
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'additional_photos');

        $dataRow->fill([
            'type' => 'additional_photos',
            'display_name' => "Дополнительные Фото",
            'required' => 0,
            'browse' => 0,
            'read' => 0,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 13,
            'details' => [
                'model' => 'App\\Models\\Image',
                'table' => 'images',
                'type' => 'belongsToMany',
                'column' => 'image_id',
                'key' => 'id',
                'label' => 'photo_url',
                'pivot_table' => 'company_images',
                'pivot' => 0,
                "validation" => [
                    "rule" => "image|mimes:jpg,png",
                ]
            ],
        ])->save();

        $dataRow = $this->dataRow($dataType, 'description');
        $dataRow->fill([
            'type' => 'text_area',
            'display_name' => "Описание",
            'required' => 0,
            'browse' => 0,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 14,
        ])->save();

        $dataRow = $this->dataRow($dataType, 'site_url');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'text',
                'display_name' => 'Сайт',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 15,
                'details' => [
                    'display' => [
                        'width' => 10
                    ]
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'hashtags');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hashtags',
                'display_name' => 'HashTags',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 16,
                'details' => [
                    'display' => [
                        'width' => 10
                    ]
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'video_url');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'text',
                'display_name' => 'Video Url',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 17,
                'details' => [
                    'display' => [
                        'width' => 10
                    ]
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'audio_url');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'file',
                'display_name' => 'Audio',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 18,
                'details' => [
                    'display' => [
                        'width' => 10
                    ],
                    "accept" => ".mp3,audio/*",
                    'is_multiple' => false,
                    "allowed" => [
                        "audio",

                        'image'
                    ]
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'document_url');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'file',
                'display_name' => 'Документ, подтверждающий, что вы имеет отношнение к компании',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 19,
                'details' => [
                    'display' => [
                        'width' => 10
                    ],

                    'is_multiple' => false,

                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'is_verification');

        $dataRow->fill([
            'type' => 'checkbox',
            'display_name' => 'Верифицировать',
            'required' => 0,
            'browse' => 0,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 20,
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
            'add' => 1,
            'delete' => 1,
            'order' => 21,
        ])->save();

        $dataRow = $this->dataRow($dataType, 'phone');

        $dataRow->fill([
            'type' => 'text',
            'display_name' => "Телефон",
            'required' => 0,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 22,
            'details' => [
                'display' => [
                    'width' => 6
                ],
                "validation" => [
                    "rule" => "unique:companies,phone",
                    "messages" => [
                        "unique" => "Please use an unique phone."
                    ]
                ]
            ]
        ])->save();
        $dataRow = $this->dataRow($dataType, 'phone_verified_at');

        $dataRow->fill([
            'type' => 'date',
            'display_name' => "Телефон Подтвержден в:",
            'required' => 0,
            'browse' => 0,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 23,
            'details' => [
                //'empty' => 'Телефон еще не подтверждён',
                'display' => [
                    'width' => 3
                ]
            ]
        ])->save();




        $dataRow = $this->dataRow($dataType, 'email');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'text',
                'display_name' => __('voyager::seeders.data_rows.email'),
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 24,
                'details' => [
                    'display' => [
                        'width' => 6
                    ],
                    "validation" => [
                        "rule" => "unique:companies,email",
                    ]
                ]
            ])->save();
        }
        $dataRow = $this->dataRow($dataType, 'email_verified_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'date',
                'display_name' => "Email Подтвержден в: ",
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 25,
                'details' => [
                    'empty' => 'Email not verified yet',
                    'display' => [
                        'width' => 3
                    ]
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'is_top_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => "Дата завершения пакета Закрепление",
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 26,
                'details' => [
                    'display' => [
                        'width' => 10
                    ]
                ]
            ])->save();
        }

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
                'order'        => 27,
                'details' => [
                    'display' => [
                        'width' => 10
                    ]
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
