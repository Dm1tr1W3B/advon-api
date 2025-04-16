<?php

namespace Database\Seeders\Bread;


use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class BreadForFrontVariablesLangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 'front_variables_lang');
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


        $dataRow = $this->dataRow($dataType, 'key');

        $dataRow->fill([
            'type' => 'text',
            'display_name' => 'Ключ',
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 2,
        ])->save();
        $dataRow = $this->dataRow($dataType, 'value');

        $dataRow->fill([
            'type' => 'text',
            'display_name' => 'Значение',
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 3,
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
