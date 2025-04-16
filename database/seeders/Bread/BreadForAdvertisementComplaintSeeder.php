<?php

namespace Database\Seeders\Bread;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class BreadForAdvertisementComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 'advertisement_complaints');

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

        $dataRow = $this->dataRow($dataType, 'advertisement_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => 'Обьявление',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'order' => 10,
            ])->save();
        }

        DataRow::create([
            'data_type_id' => $dataType->id,
            'field' => 'advertisement_id',
            'type' => 'relationship',
            'display_name' => "Обьявление",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'details' => [
                'model' => 'App\\Models\\Advertisement',
                'table' => 'advertisements',
                'type' => 'belongsTo',
                'column' => 'advertisement_id',
                'key' => 'id',
                'label' => 'title',
            ],
            'order' => 10,
        ]);

        $dataRow = $this->dataRow($dataType, 'user_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => 'От пользователя',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'order' => 11,
            ])->save();
        }

        DataRow::create([
            'data_type_id' => $dataType->id,
            'field' => 'user_id',
            'type' => 'relationship',
            'display_name' => "От пользователя",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'details' => [
                'model' => 'App\\Models\\User',
                'table' => 'users',
                'type' => 'belongsTo',
                'column' => 'user_id',
                'key' => 'id',
                'label' => 'name',
            ],
            'order' => 11,
        ]);

        $dataRow = $this->dataRow($dataType, 'advertisement_belongsto_type_relationship');
        $dataRow->fill([
            'type' => 'relationship',
            'display_name' => "Жалобы",
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'details'      => [
                'model' => 'App\\Models\\ComplaintType',
                'table'       => 'complaint_types',
                'type'        => 'belongsToMany',
                'column'      => 'advertisement_complaint_id',
                'key'         => 'id',
                'label'       => 'name',
                'pivot_table' => 'advertisement_complaint_types',
                'pivot'       => '1',
                'taggable'    => '0',
            ],
            'order' => 12,
        ])->save();

        $dataRow = $this->dataRow($dataType, 'created_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'date',
                'display_name' => "Дата",
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 13,

            ])->save();
        }





        /*
        $dataRow = $this->dataRow($dataType, 'message');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'text',
                'display_name' => "Сообщение",
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'order' => 13,

            ])->save();
        }

        */


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
