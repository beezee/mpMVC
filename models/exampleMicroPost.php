<?php

class MicroPost extends mpMVCModel
{
    public $properties;
    
    public function __construct()
    {
        parent::__construct();
        $this->single = 'micropost';
        $this->plural = 'microposts';
        $this->toString = 'title';
        $this->properties = array(
            'title' => array( 'type' => 'string'),
            'content' => array('type'=> 'longtext')
        );
        $this->add_form_title = 'Add a new Micropost';
    }
    
}
