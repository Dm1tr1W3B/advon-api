<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class HashTags extends AbstractHandler
{
    protected $codename = 'hashtags';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {

        return view('voyager::formfields.hashtags', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }

}
