<?php

$app->routeshandler = new mpMVCRouter();

F3::route('GET /', array($app->routeshandler, 'home'));
F3::route('GET /@model/add', array($app->routeshandler, 'modelAdd'));
F3::route('POST /@model/add', array($app->routeshandler, 'modelCreate'));
F3::route('GET /@model/all', array($app->routeshandler, 'modelList'));
F3::route('GET /@model/@id', array($app->routeshandler, 'modelView'));
F3::route('GET /@model/@id/edit', array($app->routeshandler, 'modelEdit'));
F3::route('POST /@model/@id/edit', array($app->routeshandler, 'modelUpdate'));

require_once('routes.php');

foreach($app->routes as $route => $method)
{
    F3::route($route, array($app->routeshandler, $method));
}

F3::set('app', $app);

class mpMVCRouter
{
    
    public function home()
    {
        echo 'Welcome to mpMVC';
    }
    
    public function modelAdd()
    {
        $app = F3::get('app');
        $model = $app->model(F3::get('PARAMS["model"]'), 'single');
        if (!$model->scaffold()) F3::error(404);
        echo $model->renderToForm('add');
    }
    
    public function modelCreate()
    {
        $app = F3::get('app');
        $model = $app->model(F3::get('PARAMS["model"]'), 'single');
        if (!$model->scaffold()) F3::error(404);
        $newModel = $model->create();
        foreach($_POST['params'] as $key => $val)
        {
            $newModel->$key = $val;
        }
        $id = $model->store($newModel);
        F3::set('PARAMS["model"]', $model->plural);
        $this->modelList();
    }
    
    public function modelEdit()
    {
        $id = F3::get('PARAMS["id"]');
        $app = F3::get('app');
        $model = $app->model(F3::get('PARAMS["model"]'), 'plural');
        if (!$model->scaffold()) F3::error(404);
        $instance = $model->load($id);
        echo $model->renderToForm('edit', $instance);      
    }
    
    public function modelUpdate()
    {
        $app = F3::get('app');
        $id = F3::get('PARAMS["id"]');
        $model = $app->model(F3::get('PARAMS["model"]'), 'plural');
        if (!$model->scaffold()) F3::error(404);
        $instance = $model->load($id);
        foreach($_POST['params'] as $key => $val)
        {
            $instance->$key = $val;
        }
        $model->store($instance);
        F3::set('PARAMS["model"]', $model->plural);
        $this->modelList();        
    }
    
    public function modelView()
    {
        $app = F3::get('app');
        $model = $app->model(F3::get('PARAMS["model"]'), 'plural');
        $id = F3::get('PARAMS["id"]');
        if (!$model->scaffold()) F3::error(404);
        echo $model->renderItem($id);        
    }
    
    public function modelList()
    {
        $app = F3::get('app');
        $model = $app->model(F3::get('PARAMS["model"]'), 'plural');
        if (!$model->scaffold()) F3::error(404);
        echo $model->renderList();
    }
}

F3::run();