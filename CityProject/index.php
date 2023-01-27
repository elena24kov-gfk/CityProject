<?php
session_start();
require_once __DIR__ . "/autoloader.php";

use Classes\CityPage\Login;

$v = new \View\NvsbrskView();
$loginInf = new \Classes\CityPage\Login();
$inf = new \Classes\CityPage\CityInfo();
$glry = new \Classes\CityPage\CityGallery();
$novSymb = new \Classes\CityPage\CitySymbols();
$schedl = new \Classes\CityPage\FlightSchedule();

$switchPage = $_GET['pageSwitch'] ?? ($_SESSION['pageSwitch']??"");
if ($_GET['pageSwitch'] ??"" == 'Login') {
    $_SESSION['stopEdit'] = false;
}
$_SESSION['pageSwitch'] = "";
$userNm = getCurrentUser()??"";
$edit = ($loginInf->isUserLogged($userNm)) && !($_SESSION['stopEdit']??false);

switch ($switchPage) {
    case 'Login':
        $switchPage = $edit ? 'Admin' : determineNextLoginStep($_POST['userName']??"",
            $_POST['pwd']??"", $loginInf);
        break;
    case 'StopEdit':
        $_SESSION['stopEdit'] = true;
        break;
    case 'LogoutMain':
        removeActiveUser($loginInf);
        break;
    case 'Admin':
        $switchPage = $edit ? 'Admin' : 'Main';
}

switch ($switchPage) {
    case 'Login':
        $v->displayLoginForm(__FILE__);
        $_SESSION['pageSwitch'] = 'Login';
        break;
    case 'LoginUsr':
        $v->displayLoginForm(__FILE__);
        echo "user does not exist";
        $_SESSION['pageSwitch'] = 'Login';
        break;
    case 'LoginPwd':
        $v->displayLoginForm(__FILE__);
        echo "password is incorrect";
        $_SESSION['pageSwitch'] = 'Login';
        break;
    case 'Gallery':
        include __DIR__ . "/Controllers/CityPage/novosib_galereya.php";
        break;
    case 'Flights':
        include __DIR__ . "/Controllers/CityPage/novosib_flights.php";
        break;
    case 'Exit':
        include __DIR__ . "/Controllers/CityPage/stop_edit.php";
        break;
    case 'Admin':
        $v->showEditableCityPage( $novSymb, 'Новосибирск', $inf, $glry, $schedl);
        $_SESSION['pageSwitch'] = 'Admin';
        break;
    default:
        $v->showCityPage( $novSymb, 'Новосибирск', $inf, $glry);
}

function determineNextLoginStep(string $name, string $pwd, Login $lgn): string
{
    if (($name == "")||($pwd == "")) {
        return 'Login';
    }
    if (!($lgn->isUser($name))) {
        return 'LoginUsr';
    }
    if ($lgn->isPasswordCorrect($name, $pwd)) {
        addActiveUser($lgn,$name);
        return 'Admin';
    } else {
        return 'LoginPwd';
    }
    return 'Login';
}

function getCurrentUser(): ?string
{
    if ( isset($_SESSION['id']) ) {
        return $_SESSION['id'];
    } else {
        return null;
    }
}

function addActiveUser(Login $lgn, string $userNm): void
{
    $lgn->addActiveUserDB($userNm);
    $_SESSION['id'] = $lgn->convertPassword($userNm);
    $_SESSION['stopEdit'] = false;
}

function removeActiveUser(Login $lgn): void
{
    $activeUsrNameHashed = getCurrentUser();
    if ($activeUsrNameHashed) {
        $lgn->removeActiveUser($activeUsrNameHashed);
    }
    $_SESSION['stopEdit'] = true;
    $_SESSION['id'] = '';
}