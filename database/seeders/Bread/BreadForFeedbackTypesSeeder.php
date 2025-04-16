<?php

namespace Database\Seeders\Bread;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class BreadForFeedbackTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 'feedback_types');

        $dataRow = $this->dataRow($dataType, 'id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => __('voyager::seeders.data_rows.id'),
                'required' => 1,
                'browse' => 1,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'order' => 1,

            ])->save();
        }


        $dataRow = $this->dataRow($dataType, 'name');
        $dataRow->fill([
            'type' => 'text',
            'display_name' => "Название",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 2,
            'details' => [
                'display' => [
                    'width' => 10
                ]
            ]
        ])->save();

        $dataRow = $this->dataRow($dataType, 'key');
        $dataRow->fill([
            'type' => 'text',
            'display_name' => "Ключ",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 3,
            'details' => [
                'display' => [
                    'width' => 10
                ]
            ]
        ])->save();


        $dataRow = $this->dataRow($dataType, 'display_order');
        $dataRow->fill([
            'type' => 'number',
            'display_name' => "Порядок",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 4,
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
