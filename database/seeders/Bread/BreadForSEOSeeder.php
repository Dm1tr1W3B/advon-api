<?php

namespace Database\Seeders\Bread;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class BreadForSEOSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 's_e_o_s');

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

        $dataRow = $this->dataRow($dataType, 'language_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => 'Язык',
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
            'field' => 'language_id',
            'type' => 'relationship',
            'display_name' => "Язык",
            'required' => 0,
            'browse' => 1,
            'read' => 0,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'details' => [
                'model' => 'App\\Models\\Language',
                'table' => 'languages',
                'type' => 'belongsTo',
                'column' => 'language_id',
                'key' => 'id',
                'label' => 'name',
            ],
            'order' => 10,
        ]);

        $dataRow = $this->dataRow($dataType, 'banner_belongsto_page_relationship');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'relationship',
                'display_name' => "Язык",
                'required' => 1,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => [
                    'model' => 'App\\Models\\Language',
                    'table' => 'languages',
                    'type' => 'belongsTo',
                    'column' => 'language_id',
                    'key' => 'id',
                    'label' => 'name',
                ],
                'order' => 10,
            ])->save();
        }



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
                'order' => 11,
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
            'order' => 11,
        ]);

        $dataRow = $this->dataRow($dataType, 'seo_belongsto_page_relationship');
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
                'order' => 11,
            ])->save();
        }


        $dataRow = $this->dataRow($dataType, 'title');
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

        $dataRow = $this->dataRow($dataType, 'description');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'text_area',
                'display_name' => "Описание",
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 13,
                'details' => [
                    'display' => [
                        'width' => 10
                    ],
                    "validation" => [
                        "rule" => 'required|string',
                    ]
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'seo_text');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'rich_text_box',
                'display_name' => "SEO текст",
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 14,
                'details' => [
                    'display' => [
                        'width' => 10
                    ],
                    "validation" => [
                        "rule" => 'required|string',
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
