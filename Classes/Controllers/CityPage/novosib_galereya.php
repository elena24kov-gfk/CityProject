<?php
require_once __DIR__ . "/../../autoloader.php";
$view = new View();
$galereya = new CityGallery();
$view->displayCityGallery($galereya);