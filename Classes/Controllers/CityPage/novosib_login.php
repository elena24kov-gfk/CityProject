<?php
session_start();
require_once __DIR__ . "/../../autoloader.php";
$loginInf = new Login();
$v = new View();
$userNm = $loginInf->getCurrentUser()??"";
if($loginInf->isUserLogged($userNm)) {
    $_SESSION['stopEdit'] = false;
    header("Location: novosib.php");
    echo "you logged in already";
} else {
    $v->displayLoginForm(__FILE__);
    $usr = $_POST['userName']??"";
    $pwd = $_POST['pwd']??"";
    if ($loginInf->isUser($usr)) {
        if ($loginInf->isPasswordCorrect($usr, $pwd)) {
            $_SESSION['id'] = $loginInf->convertPassword($usr);
            $_SESSION['stopEdit'] = false;
            header("Location: novosib.php");
        } else {
            $_SESSION['id'] = "";
            echo " The password is incorrect ";
        }
    }
}