<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class ImageId extends AbstractHandler
{
    protected $codename = 'image_id';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {

        return view('voyager::formfields.image_id', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }

}
