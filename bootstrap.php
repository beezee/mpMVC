<?php

require_once 'lib/base.php';

require_once 'lib/Stamp.php';

require_once 'lib/baseModel.php';

require_once 'lib/rb.php';

require_once 'config.php';

$app->baseurl = $baseurl;

R::setup("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpw);

//discover and register models

foreach (glob("models/*.php") as $filename)
{
    $model_file_contents = file_get_contents($filename);
    $classes = array();
    $tokens = token_get_all($model_file_contents);
    $count = count($tokens);
    for ($i = 2; $i < $count; $i++) {
      if (   $tokens[$i - 2][0] == T_CLASS
          && $tokens[$i - 1][0] == T_WHITESPACE
          && $tokens[$i][0] == T_STRING) {
    
          $class_name = $tokens[$i][1];
          $classes[] = $class_name;
      }
    }
    
    if (count($classes) > 0)
    {
        require_once $filename;
        foreach($classes as $class)
        {
            $model = new $class($app);
            if (isset($model->properties) and is_array($model->properties) and !empty($model->properties)) $app->registerModel($class, $model);
        }
    }
}
