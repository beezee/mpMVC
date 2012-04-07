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
    
    public function showPerson()
    {
        $id = $this->get('PARAMS["id"]');
        $person= new Person();
        $micropost = new Micropost();
        $instance = $person->load($id);
        $output = $person->renderItem($id, 'personView');
        $posts = $micropost->find('Person_id', $id);
        $postlist = '';
        foreach($posts as $post)
        {
            $postlist .= $output->copy('micropost')
                ->replace('base_url', $this->app->baseurl)
                ->replace('pid', $post->id)
                ->replace('ptitle', $post->title);
        }
        echo $this->app->render($output->replace('micropost', $postlist));
    }
}

$app->setRoutesHandler(new routes());


$app->setRoutes(array(
    //define additional routes here
    'GET /about' => 'about',
    'GET /people/@id' => 'showPerson'
));

$app->secureRoutes(array(
    'edit',
    'new',
    'remove'
));
