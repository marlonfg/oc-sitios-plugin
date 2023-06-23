<?php namespace MarlonFreire\Sitios\Controllers;

use Backend\Behaviors\ReorderController;

class ReorderProductController extends ReorderController
{
    public function __construct($controller)
    {
        parent::__construct($controller);

    }

    protected function validateModel()
    {
        $model = $this->controller->reorderGetModel();
        
        $this->sortMode = 'simple';

        return $model;
    }

}