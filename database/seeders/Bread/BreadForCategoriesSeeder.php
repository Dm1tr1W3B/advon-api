<?php


namespace Database\Seeders\Bread;


use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class BreadForCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 'categories');

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
            'display_name' => "Название",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'order' => 2,
            'details' => [
                'display' => [
                    'width' => 10
                ]
            ]
        ])->save();
        $dataRow = $this->dataRow($dataType, 'title_ograph');
        $dataRow->fill([
            'type' => 'text',
            'display_name' => "Слоган",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 0,
            'delete' => 0,
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


        $dataRow = $this->dataRow($dataType, 'image_id');
        $dataRow->fill([
            'type' => 'image_id',
            'display_name' => "Фото",
            'required' => 0,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 0,
            'delete' => 0,
            'order' => 4,
            'details' => [
                'model' => 'App\\Models\\Image',
                'table' => 'images',
                'type' => 'belongsTo',
                'key' => 'id',
                'column' => 'image_id'
            ],

        ])->save();
        $dataRow = $this->dataRow($dataType, 'type');
        $dataRow->fill([
            'type' => 'select_dropdown',
            'display_name' => "Тип Категории",
            'required' => 1,
            'browse' => 0,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 1,
            'order' => 5,
            'details' => [
                'display' => [
                    'width' => 10
                ],
                'options' => ['performer' => 'Испольнитель', 'employer' => 'Рекламодатель']
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
        $dataRow = $this->dataRow($dataType, 'num_right');
        $dataRow->fill([
            'type' => 'number',
            'display_name' => "num_right",
            'required' => 1,
            'browse' => 0,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'order' => 7,
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
            'read' => 0,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'order' => 8,

        ])->save();

        $dataRow = $this->dataRow($dataType, 'keyword');
        $dataRow->fill([
            'type' => 'text',
            'display_name' => "keyword",
            'required' => 0,
            'browse' => 0,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'order' => 9,

        ])->save();

        $dataRow = $this->dataRow($dataType, 'limit');
        $dataRow->fill([
            'type' => 'number',
            'display_name' => "Лимит",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 0,
            'delete' => 0,
            'order' => 10,
            'details' => [
                'display' => [
                    'width' => 10
                ]
            ]
        ])->save();

        $dataRow = $this->dataRow($dataType, 'period');
        $dataRow->fill([
            'type' => 'number',
            'display_name' => "Период",
            'required' => 1,
            'browse' => 0,
            'read' => 1,
            'edit' => 1,
            'add' => 0,
            'delete' => 0,
            'order' => 11,
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
