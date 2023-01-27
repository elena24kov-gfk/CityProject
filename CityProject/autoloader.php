<?php

function my_autoloader($class) {
    $cityClassName = __DIR__."/".str_replace("\\", "/",$class). ".php";
    include $cityClassName;
}

spl_autoload_register('my_autoloader');