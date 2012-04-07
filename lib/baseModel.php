<?php

class mpMVCModel
{
    public $properties = array();
    public $name = '';
    
    public function __construct()
    {
        global $app;
        $this->app = $app;
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
    
    public function remove($id)
    {
        $model = $this->load($id);
        R::trash($model);
    }
    
    public function findAll()
    {
        return R::find($this->name);
    }
    
    public function store($model)
    {
        R::store($model);
    }
    
    public function processParams($model, $params)
    {
        $models = $this->app->models();
        foreach($params as $key => $val)
        {
            if (in_array($key, array_keys($models)))
            {
                $instance = $models[$key]->load($val);
            }
            $model->$key = (isset($instance) and $instance) ? $instance : $val;
        }
        return $model;
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
            $type = ($data['type'] == 'model') ? 'multiple' : $data['type'];
            $control = $tpl->copy($type)
                ->replace('id', $property.'_'.$data['type'])
                ->replace('name', $property);
            if ($data['type'] == 'model')
            {
                $model = new $property;
                $allModels = $model->findAll();
                $data['options'] = array();
                foreach($allModels as $oModel)
                {
                    $data['options'][$oModel->id] = $oModel->{$model->toString};
                }
            }
            if (isset($data['options']) and is_array($data['options']))
            {
                foreach ($data['options'] as $value => $text)
                {
                    if ($instance) $propval = (is_object($instance) and is_object($instance->$property)) ? $instance->$property->id : $instance->$property;
                    else $propval = '';
                    $selected = ($instance && $value == $propval) ? 'selected="selected"' : '';
                    $options .= $tpl->copy('option')
                        ->replace('value', $value)
                        ->replace('text', $text)
                        ->replace('selected', $selected);
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
    
    public function renderList($view=false)
    {
        $view = ($view) ? $view : 'modelList';
        $tpl = new Stamp(Stamp::load(dirname(__file__).'/../views/'.$view.'.tpl'));
        $output = new Stamp(Stamp::load(dirname(__file__).'/../views/'.$view.'.tpl'));
        $models = $this->findAll();
        $items = '';
        foreach($models as $model)
        {
            $item = $tpl->copy('item');
            $items .= $item
                ->replace('id', $model->id)
                ->replace('property', '')
                ->replace('plural', $this->plural)
                ->replace('toString', $model->{$this->toString});
        }
        return $output->replace('all_link', '')
            ->replace('item', $items)
            ->replace('base_url', $this->app->baseurl)
            ->replace('single', $this->single);
    }
    
    public function renderItem($id, $view=false)
    {
        $view = ($view) ? $view : 'modelList';
        $tpl = new Stamp(Stamp::load(dirname(__file__).'/../views/'.$view.'.tpl'));
        $output = new Stamp(Stamp::load(dirname(__file__).'/../views/'.$view.'.tpl'));
        $models = $this->app->models();
        $model = $this->load($id);
        if (!$model) F3::error(404);
        $props = '';
        foreach($this->properties as $prop => $val)
        {
            $propval = (is_object($model->$prop)) ? $model->$prop->{$models[$prop]->toString} : $model->$prop;
            $props .= $tpl->copy('property')->replace('propname', $prop)->replace('propval', $propval);
        }
        return $output
            ->replace('new_link', '')
            ->replace('id', $model->id)
            ->replace('property', $props)
            ->replace('propval', $model->{$this->toString})
            ->replace('plural', $this->plural)
            ->replace('single', $this->single)
            ->replace('toString', $model->{$this->toString})
            ->replace('base_url', $this->app->baseurl);
    }
}