<?php

namespace Database\Seeders\Bread;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class BreadForLangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 'languages');
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


        $dataRow = $this->dataRow($dataType, 'name');

        $dataRow->fill([
            'type' => 'text',
            'display_name' => __('voyager::seeders.data_rows.name'),
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 2,
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
            'order' => 3,
        ])->save();

        $dataRow = $this->dataRow($dataType, 'image');

        $dataRow->fill([
            'type' => 'image',
            'display_name' => 'Картинка',
            'required' => 0,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 4,
        ])->save();
        $dataRow = $this->dataRow($dataType, 'enabled');

        $dataRow->fill([
            'type' => 'checkbox',
            'display_name' => 'Включено',
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 5,
            'details' => [
                'checked' => true
            ]
        ])->save();
        $dataRow = $this->dataRow($dataType, 'rtl');

        $dataRow->fill([
            'type' => 'checkbox',
            'display_name' => 'Текст справа налево',
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 6,
            'details' => [
                'checked' => false
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
