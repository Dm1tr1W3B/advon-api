<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class CountryRegionCity extends AbstractHandler
{
    protected $codename = 'country_region_city';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {

        return view('voyager::formfields.country_region_city', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }

}
