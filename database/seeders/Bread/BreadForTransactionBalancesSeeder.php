<?php

namespace Database\Seeders\Bread;

use App\Models\TransactionBalanceType;
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class BreadForTransactionBalancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       $dataType = $this->dataType('slug', 'transaction_balances');

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

        $dataRow = $this->dataRow($dataType, 'created_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => "Дата",
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 10,
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'user_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => 'Email Пользователя',
                'required' => 0,
                'browse' => 1,
                'read' => 0,
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
            'display_name' => "Email Пользователя",
            'required' => 0,
            'browse' => 1,
            'read' => 0,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'details' => [
                'model' => 'App\\Models\\User',
                'table' => 'users',
                'type' => 'belongsTo',
                'column' => 'user_id',
                'key' => 'id',
                'label' => 'email',
            ],
            'order' => 11,
        ]);

        $dataRow = $this->dataRow($dataType, 'type');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => 'Тип операции',
                'required' => 0,
                'browse' => 1,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'order' => 12,
            ])->save();
        }


        DataRow::create([
            'data_type_id' => $dataType->id,
            'field' => 'type',
            'type' => 'relationship',
            'display_name' => "Тип операции",
            'required' => 0,
            'browse' => 1,
            'read' => 0,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'details' => [
                'model' => 'App\\Models\\TransactionBalanceType',
                'table' => 'transaction_balance_types',
                'type' => 'belongsTo',
                'column' => 'type',
                'key' => 'code',
                'label' => 'name',
            ],
            'order' => 12,
        ]);


        $dataRow = $this->dataRow($dataType, 'currency_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'hidden',
                'display_name' => 'Валюта',
                'required' => 0,
                'browse' => 1,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'order' => 13,
            ])->save();
        }


        DataRow::create([
            'data_type_id' => $dataType->id,
            'field' => 'currency_id',
            'type' => 'relationship',
            'display_name' => "Валюта",
            'required' => 0,
            'browse' => 1,
            'read' => 0,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'details' => [
                'model' => 'App\\Models\\Currency',
                'table' => 'currencies',
                'type' => 'belongsTo',
                'column' => 'currency_id',
                'key' => 'id',
                'label' => 'code',
            ],
            'order' => 13,
        ]);




        $dataRow = $this->dataRow($dataType, 'amount');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'number',
                'display_name' => "Сумма",
                'required' => 0,
                'browse' => 1,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'order' => 14,
                'details' => [
                    'display' => [
                        'width' => 10
                    ],

                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'balance');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'number',
                'display_name' => "Баланс",
                'required' => 0,
                'browse' => 1,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'order' => 15,
                'details' => [
                    'display' => [
                        'width' => 10
                    ],
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'new_balance');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'number',
                'display_name' => "Новый баланс",
                'required' => 0,
                'browse' => 1,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'order' => 16,
                'details' => [
                    'display' => [
                        'width' => 10
                    ],
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($dataType, 'description');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => 'number',
                'display_name' => "Описание",
                'required' => 0,
                'browse' => 1,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'order' => 17,
                'details' => [
                    'display' => [
                        'width' => 10
                    ],
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
