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
    
    public function render($content)
    {
        $output = new Stamp(Stamp::load(dirname(__FILE__).'/../views/appLayout.tpl'));
        return $output
            ->replace('app_name', $this->app_name)
            ->replace('base_url', $this->baseurl)
            ->replace('yield', $content);
    }
}