<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class AdditionalPhotos extends AbstractHandler
{
    protected $codename = 'additional_photos';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {

        return view('voyager::formfields.additional_photos', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }

}
