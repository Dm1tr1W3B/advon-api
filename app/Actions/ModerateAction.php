<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class ModerateAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Промодерировать';
    }

    public function getIcon()
    {
        return 'voyager-eye';
    }

    public function getPolicy()
    {
        return 'moderate';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-success pull-right',
        ];
    }

    public function getDefaultRoute()
    {
        return route('voyager.'.$this->dataType->slug.'.moderate', $this->data->{$this->data->getKeyName()});
    }


}
