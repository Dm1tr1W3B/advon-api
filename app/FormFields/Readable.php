<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class Readable extends AbstractHandler
{
    protected $codename = 'readable';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {

        return view('voyager::formfields.readable', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }

}
