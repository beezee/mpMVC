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
    
    public function beforeRoute()
    {
        $app = F3::get('app');
        if ($app->secureurl() and (!isset($_GET['pw']) or $_GET['pw'] != 'eep')) F3::error(401);
    }
    
    public function home()
    {
        $app = F3::get('app');
        echo $app->render($app->modelsList());
    }
    
    public function modelAdd()
    {
        $app = F3::get('app');
        $model = $app->model(F3::get('PARAMS["model"]'), 'single');
        if (!$model->scaffold()) F3::error(404);
        echo $app->render($model->renderToForm('add'));
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
        F3::reroute($app->baseurl.$model->plural.'/all');   
    }
    
    public function modelEdit()
    {
        $id = F3::get('PARAMS["id"]');
        $app = F3::get('app');
        $model = $app->model(F3::get('PARAMS["model"]'), 'plural');
        if (!$model->scaffold()) F3::error(404);
        $instance = $model->load($id);
        echo $app->render($model->renderToForm('edit', $instance));      
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
        F3::reroute($app->baseurl.$model->plural.'/all');   
    }
    
    public function modelView()
    {
        $app = F3::get('app');
        $model = $app->model(F3::get('PARAMS["model"]'), 'plural');
        $id = F3::get('PARAMS["id"]');
        if (!$model->scaffold()) F3::error(404);
        echo $app->render($model->renderItem($id));        
    }
    
    public function modelList()
    {
        $app = F3::get('app');
        $model = $app->model(F3::get('PARAMS["model"]'), 'plural');
        if (!$model->scaffold()) F3::error(404);
        echo $app->render($model->renderList());
    }
}

F3::run();