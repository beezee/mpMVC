<?php

class mpMVCModel
{
    public $properties = array();
    public $name = '';
    
    public function __construct()
    {
        $this->name = get_class($this);
        $this->scaffold = true;
    }
    
    public function scaffold()
    {
        return $this->scaffold;
    }
    
    public function create()
    {
        return R::dispense($this->name);
    }
    
    public function find($prop, $val)
    {
        return R::find($this->name, "$prop = ?", array($val));
    }
    
    public function load($id)
    {
        return R::load($this->name, $id);
    }
    
    public function findAll()
    {
        return R::find($this->name);
    }
    
    public function store($model)
    {
        R::store($model);
    }
    
    public function renderToForm($action, $instance=false)
    {
        $title_property = $action.'_form_title';
        $formTitle = (isset($this->$title_property)) ? $this->$title_property : ucfirst($action).' '.$this->name;
        $tpl = new Stamp(Stamp::load(dirname(__file__).'/../views/formControls.tpl'));
        $form = $tpl->copy('form');
        $controls = '';
        $options = '';
        foreach ($this->properties as $property => $data)
        {
            $title = $tpl->copy('control_title')->replace('property', ucfirst($property));
            $control = $tpl->copy($data['type'])
                ->replace('id', $property.'_'.$data['type'])
                ->replace('name', $property);
            if (isset($data['options']) and is_array($data['options']))
            {
                foreach ($data['options'] as $value => $text)
                {
                    $selected = ($value == $instance->$property) ? 'selected="selected"' : '';
                    $options .= $tpl->copy('option')
                        ->replace('value', $value)
                        ->replace('text', $text);
                }
                $control = $control->replace('option', $options);
            }
            else
            {
                $value = ($instance) ? $instance->$property : '';
                $control = $control->replace('value', $value);
            }
            $controls .= $title.$control;
        }
        return $form->replace('controls', $controls)->replace('title', $formTitle);
    }
    
    public function renderList()
    {
        $tpl = new Stamp(Stamp::load(dirname(__file__).'/../views/modelList.tpl'));
        $models = $this->findAll();
        $items = '';
        foreach($models as $model)
        {
            $item = $tpl->copy('item');
            $items .= $item
                ->replace('id', $model->id)
                ->replace('property', '')
                ->replace('propVal', $model->{$this->toString})
                ->replace('plural', $this->plural)
                ->replace('toString', $model->{$this->toString});
        }
        return $items;
    }
    
    public function renderItem($id)
    {
        $tpl = new Stamp(Stamp::load(dirname(__file__).'/../views/modelList.tpl'));
        $model = $this->load($id);
        if (!$model) F3::error(404);
        $props = '';
        foreach($this->properties as $prop => $val)
        {
            $props .= $tpl->copy('property')->replace('propName', $prop)->replace('propVal', $model->$prop);
        }
        return $tpl->copy('item')
            ->replace('id', $model->id)
            ->replace('property', '')
            ->replace('propVal', $model->{$this->toString})
            ->replace('plural', $this->plural)
            ->replace('toString', $model->{$this->toString})
            ->replace('property', $props);
    }
}