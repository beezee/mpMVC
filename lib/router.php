<?php

$app->router = new mpMVCRouter();

$app->router->route('GET /', array($app->router, 'home'));
$app->router->route('GET /@model/new', array($app->router, 'modelAdd'));
$app->router->route('POST /@model/new', array($app->router, 'modelCreate'));
$app->router->route('GET /all/@model', array($app->router, 'modelList'));
$app->router->route('GET /@model/@id', array($app->router, 'modelView'));
$app->router->route('GET /@model/@id/edit', array($app->router, 'modelEdit'));
$app->router->route('POST /@model/@id/edit', array($app->router, 'modelUpdate'));
$app->router->route('GET /@model/@id/remove', array($app->router, 'modelRemove'));

require_once('routes.php');

foreach($app->routes as $route => $method)
{
    $app->router->route($route, array($app->router, $method));
}

$app->router->set('app', $app);

class mpMVCRouter extends F3instance
{
    
    public function beforeRoute()
    {
        $this->app = $this->get('app');
        if ($this->app->secureurl() and (!isset($_GET['pw']) or $_GET['pw'] != 'eep')) $this->app->router->error(401);
    }
    
    public function home()
    {
        echo $this->app->render($this->app->modelsList());
    }
    
    public function modelAdd()
    {
        $model = $this->app->model($this->get('PARAMS["model"]'), 'single');
        if (!$model->scaffold()) $this->error(404);
        echo $this->app->render($model->renderToForm('add'));
    }
    
    public function modelCreate()
    {
        $model = $this->app->model($this->get('PARAMS["model"]'), 'single');
        if (!$model->scaffold()) $this->error(404);
        $newModel = $model->processParams($model->create(), $_POST['params']);
        $id = $model->store($newModel);
        $this->reroute($this->app->baseurl.'all/'.$model->plural);   
    }
    
    public function modelEdit()
    {
        $id = $this->get('PARAMS["id"]');
        $model = $this->app->model($this->get('PARAMS["model"]'), 'plural');
        if (!$model->scaffold()) $this->error(404);
        $instance = $model->load($id);
        echo $this->app->render($model->renderToForm('edit', $instance));      
    }
    
    public function modelUpdate()
    {
        $id = $this->get('PARAMS["id"]');
        $model = $this->app->model($this->app->router->get('PARAMS["model"]'), 'plural');
        if (!$model->scaffold()) $this->error(404);
        $instance = $model->processParams($model->load($id), $_POST['params']);
        $model->store($instance);
        $this->reroute($this->app->baseurl.'all/'.$model->plural);   
    }
    
    public function modelRemove()
    {
        $id = $this->get('PARAMS["id"]');
        $model = $this->app->model($this->get('PARAMS["model"]'), 'single');
        if (!$model->scaffold()) $this->error(404);
        $model->remove($id);
        $this->reroute($this->app->baseurl.'all/'.$model->plural);
    }
    
    public function modelView()
    {
        $model = $this->app->model($this->get('PARAMS["model"]'), 'plural');
        $id = $this->get('PARAMS["id"]');
        if (!$model->scaffold()) $this->error(404);
        echo $this->app->render($model->renderItem($id));        
    }
    
    public function modelList()
    {
        $model = $this->app->model($this->get('PARAMS["model"]'), 'plural');
        if (!$model->scaffold()) $this->error(404);
        echo $this->app->render($model->renderList());
    }
}

$app->router->run();