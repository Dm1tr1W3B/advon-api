<?php

namespace Database\Seeders\Bread;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class BreadForBonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       $dataType = $this->dataType('slug', 'bonuses');

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

       $dataRow = $this->dataRow($dataType, 'bonus_type_id');
       if (!$dataRow->exists) {
           $dataRow->fill([
               'type' => 'hidden',
               'display_name' => 'Тип бонуса',
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
            'field' => 'bonus_type_id',
            'type' => 'relationship',
            'display_name' => "Тип бонуса",
            'required' => 0,
            'browse' => 1,
            'read' => 0,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'details' => [
                'model' => 'App\\Models\\BonusType',
                'table' => 'bonus_types',
                'type' => 'belongsTo',
                'column' => 'bonus_type_id',
                'key' => 'id',
                'label' => 'name',
            ],
            'order' => 11,
        ]);


        $dataRow = $this->dataRow($dataType, 'bonus_belongsto_bonus_type_relationship');
       if (!$dataRow->exists) {
           $dataRow->fill([
               'type' => 'relationship',
               'display_name' => "Тип бонуса",
               'required' => 1,
               'browse' => 0,
               'read' => 1,
               'edit' => 1,
               'add' => 1,
               'delete' => 1,
               'details' => [
                   'model' => 'App\\Models\\BonusType',
                   'table' => 'bonus_types',
                   'type' => 'belongsTo',
                   'column' => 'bonus_type_id',
                   'key' => 'id',
                   'label' => 'name',
               ],
               'order' => 11,
           ])->save();
       }

       $dataRow = $this->dataRow($dataType, 'amount');
       if (!$dataRow->exists) {
           $dataRow->fill([
               'type' => 'number',
               'display_name' => "Сумма бонуса",
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
                       "rule" => 'required',
                   ]
               ]
           ])->save();
       }

       $dataRow = $this->dataRow($dataType, 'currency_id');
       if (!$dataRow->exists) {
           $dataRow->fill([
               'type' => 'hidden',
               'display_name' => 'Валюта',
               'required' => 1,
               'browse' => 0,
               'read' => 0,
               'edit' => 1,
               'add' => 1,
               'delete' => 1,
               'order' => 13,
           ])->save();
       }
       $dataRow = $this->dataRow($dataType, 'bonus_belongsto_currency_relationship');
       if (!$dataRow->exists) {
           $dataRow->fill([
               'type' => 'relationship',
               'display_name' => "Валюта",
               'required' => 1,
               'browse' => 0,
               'read' => 0,
               'edit' => 1,
               'add' => 1,
               'delete' => 1,
               'details' => [
                   'model' => 'App\\Models\\Currency',
                   'table' => 'currencies',
                   'type' => 'belongsTo',
                   'column' => 'currency_id',
                   'key' => 'id',
                   'label' => 'code',
               ],
               'order' => 13,
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
           'order' => 14,
           'details' => [
               'checked' => true
           ]
       ])->save();


       $dataRow = $this->dataRow($dataType, 'is_real_balance');

       $dataRow->fill([
           'type' => 'checkbox',
           'display_name' => 'На реальный баланс',
           'required' => 1,
           'browse' => 1,
           'read' => 1,
           'edit' => 1,
           'add' => 1,
           'delete' => 1,
           'order' => 15,
           'details' => [
               'checked' => true
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
