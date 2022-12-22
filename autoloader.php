<?php

function my_autoloader($class) {
    $cityClassName = __DIR__."/Classes/CityPage/". $class . ".php";
    $generalClassName = __DIR__."/Classes/" .str_replace("CityPage", "",$class). ".php";
    if (file_exists($cityClassName)) {
        include $cityClassName;
    } else {
        include $generalClassName;
    }
}

spl_autoload_register('my_autoloader');