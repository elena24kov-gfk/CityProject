<?php
session_start();
require_once __DIR__ . "/../../autoloader.php";

//create classes for information

$inf = new CityInfo();
$glry = new CityGallery();
$novSymb = new CitySymbols();
$schedl = new FlightSchedule();
$view = new View();

$stopEdit = $_POST['exitEdit']??"0";
if ($stopEdit) {
    $_SESSION['stopEdit'] = true;
}
$stopEditFlag = $_SESSION['stopEdit']??false;
$userId = $_SESSION['id'] ?? "";
$loginInf = new Login();
$isLogged = $loginInf->isUserLogged($userId);
$edit = $isLogged && !$stopEditFlag;

$edit? $view->showEditableCityPage( $novSymb, 'Новосибирск', $inf, $glry, $schedl) :
    $view->showCityPage( $novSymb, 'Новосибирск', $inf, $glry);