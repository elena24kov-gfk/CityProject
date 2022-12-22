<?php
require_once __DIR__ . "/../../autoloader.php";

$view = new View();
$infoTmp = new CityInfo();
$flights = new FlightSchedule();
$view->displayFlights($flights);