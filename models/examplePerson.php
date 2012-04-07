<?php

class Person extends mpMVCModel
{
    public $properties;
    
    public function __construct()
    {
        parent::__construct();
        $this->single = 'person';
        $this->plural = 'people';
        $this->toString = 'name';
        $this->properties = array(
            'name' => array( 'type' => 'string'),
            'gender' => array(
                'type'=> 'multiple',
                'options' => array(
                    'male' => 'Male',
                    'female' => 'Female'
                )
            )
        );
        $this->add_form_title = 'Add a new Person';
    }

    
}