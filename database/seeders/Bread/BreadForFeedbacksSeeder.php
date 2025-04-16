<?php

namespace Database\Seeders\Bread;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class BreadForFeedbacksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 'feedback');

        $dataRow = $this->dataRow($dataType, 'id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => __('voyager::seeders.data_rows.id'),
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'order' => 1,

            ])->save();
        }


        $dataRow = $this->dataRow($dataType, 'email');
        $dataRow->fill([
            'type' => 'text',
            'display_name' => "Email",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'order' => 10,
            'details' => [
                'display' => [
                    'width' => 10
                ]
            ]
        ])->save();

        $dataRow = $this->dataRow($dataType, 'name');
        $dataRow->fill([
            'type' => 'text',
            'display_name' => "Имя",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'order' => 11,
            'details' => [
                'display' => [
                    'width' => 10
                ]
            ]
        ])->save();

        $dataRow = $this->dataRow($dataType, 'feedback_type_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => 'Тема',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'order' => 12,
            ])->save();
        }


        DataRow::create([
            'data_type_id' => $dataType->id,
            'field' => 'feedback_type_id',
            'type' => 'relationship',
            'display_name' => "Тема",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'details' => [
                'model' => 'App\\Models\\FeedbackType',
                'table' => 'feedback_types',
                'type' => 'belongsTo',
                'column' => 'feedback_type_id',
                'key' => 'id',
                'label' => 'name',
            ],
            'order' => 12,
        ]);


        $dataRow = $this->dataRow($dataType, 'message');
        $dataRow->fill([
            'type' => 'text',
            'display_name' => "Сообщение",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'order' => 13,
            'details' => [
                'display' => [
                    'width' => 10
                ]
            ]
        ])->save();



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
