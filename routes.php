<?php

class routes extends mpmvcRouter
{
    //add custom route handlers and override defaults here
    public function home()
    {
        echo 'overriding home route';
    }
    
    public function about()
    {
        echo 'heres an about page';
    }
}

$app->setRoutesHandler(new routes());


$app->setRoutes(array(
    //define additional routes here
    'GET /about' => 'about'
));

$app->secureRoutes(array(
    'edit',
    'new',
    'remove'
));
