<?php


namespace Database\Seeders\Bread;


use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class BreadForChildCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 'child_categories');

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

        $dataRow = $this->dataRow($dataType, 'category_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => 'Категория',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'order' => 2,
            ])->save();
        }


        DataRow::create([
            'data_type_id' => $dataType->id,
            'field' => 'category_id',
            'type' => 'relationship',
            'display_name' => "Категория",
            'required' => 1,
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
            ],
            'order' => 2,
        ]);


        $dataRow = $this->dataRow($dataType, 'name');
        $dataRow->fill([
            'type' => 'text',
            'display_name' => "Название",
            'required' => 1,
            'browse' => 1,
            'read' => 0,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'order' => 3,
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
            'read' => 0,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'order' => 4,
            'details' => [
                'display' => [
                    'width' => 10
                ]
            ]
        ])->save();








        $dataRow = $this->dataRow($dataType, 'num_left');
        $dataRow->fill([
            'type' => 'number',
            'display_name' => "num_left",
            'required' => 1,
            'browse' => 0,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'order' => 6,
            'details' => [
                'display' => [
                    'width' => 10
                ]
            ]
        ])->save();



        $dataRow = $this->dataRow($dataType, 'keyword');
        $dataRow->fill([
            'type' => 'text',
            'display_name' => "keyword",
            'required' => 0,
            'browse' => 1,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'order' => 9,

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
