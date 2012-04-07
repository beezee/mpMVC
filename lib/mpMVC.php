<?php

class mpMVC
{
    private $_models;
    public $routeshandler;
    public $routes = array();
    
    public function registerModel($classname, mpMVCModel $model)
    {
        if (!isset($this->_models[$classname])) $this->_models[$classname] = $model;
    }
    
    public function model($modelName, $findBy=false)
    {
        if (!$findBy)
        {
            if (isset($this->_models[$modelName])) return $this->_models[$modelName];
            else F3::error(404);
        }
        else
        {
            $found = false;
            foreach($this->_models as $model)
            {
                if ($model->$findBy == $modelName) $found = $model;
            }
            if (!$found) F3::error(404);
            else return $found;
        }
    }
    
    public function setRoutesHandler(mpMVCRouter $router)
    {
        $this->routeshandler = $router;
    }
    
    public function setRoutes(array $routes)
    {
        $this->routes = array_merge($this->routes, $routes);
    }
}