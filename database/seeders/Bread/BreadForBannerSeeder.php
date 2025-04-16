<?php

namespace Database\Seeders\Bread;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class BreadForBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 'banners');

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

        $dataRow = $this->dataRow($dataType, 'page_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => 'Название страницы',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 10,
            ])->save();
        }
        DataRow::create([
            'data_type_id' => $dataType->id,
            'field' => 'page_id',
            'type' => 'relationship',
            'display_name' => "Название страницы",
            'required' => 0,
            'browse' => 1,
            'read' => 0,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'details' => [
                'model' => 'App\\Models\\Page',
                'table' => 'pages',
                'type' => 'belongsTo',
                'column' => 'page_id',
                'key' => 'id',
                'label' => 'name',
            ],
            'order' => 10,
        ]);

        $dataRow = $this->dataRow($dataType, 'banner_belongsto_page_relationship');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'relationship',
                'display_name' => "Название страницы",
                'required' => 1,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => [
                    'model' => 'App\\Models\\Page',
                    'table' => 'pages',
                    'type' => 'belongsTo',
                    'column' => 'page_id',
                    'key' => 'id',
                    'label' => 'name',
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
                    ],
                    "validation" => [
                        "rule" => 'required|string|max:254',
                    ]
                ]
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
                'order' => 12,
                'details' => [
                    'display' => [
                        'width' => 10
                    ],
                    "validation" => [
                        "rule" => 'required|string|max:254',
                    ]
                ]
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
                'order' => 12,
                'details' => [
                    'display' => [
                        'width' => 10
                    ],
                    "validation" => [
                        "rule" => 'required|string|max:254',
                    ]
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'file');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'file',
                'display_name' => 'Файл',
                'required' => 1,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 14,
                'details' => [
                    'display' => [
                        'width' => 10
                    ],

                    'is_multiple' => false,

                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'alt');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'text',
                'display_name' => "Alt",
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 12,
                'details' => [
                    'display' => [
                        'width' => 10
                    ],
                    "validation" => [
                        "rule" => 'required|string|max:254',
                    ]
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'url');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'text',
                'display_name' => "Url",
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 12,
                'details' => [
                    'display' => [
                        'width' => 10
                    ],
                    "validation" => [
                        "rule" => 'required|string|max:254',
                    ]
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'is_active');

        $dataRow->fill([
            'type' => 'checkbox',
            'display_name' => 'Активен',
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 12,
            'details' => [
                'checked' => true
            ]
        ])->save();

        $dataRow = $this->dataRow($dataType, 'display_order');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'number',
                'display_name' => "Порядок отображения",
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 16,
                'details' => [
                    'display' => [
                        'width' => 10
                    ],
                    "validation" => [
                        'validation' => ['rule' => 'required|integer|digits_between:1,8|min:1'],
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
