<?php
echo " Вы действительно хотите закончить редактирование? ";
?>
<html>
<form action="index.php" method="get" id = "radioQ">
    <input type="radio" name = "pageSwitch" value= "Admin" checked /><span>Продолжить редактирование</span><br><br/>
    <input type="radio" name = "pageSwitch" value= "StopEdit" checked /><span>Закончить редактирование</span><br><br/>
    <input type="radio" name = "pageSwitch" value= "LogoutMain" checked /><span>Закончить редактирование и выйти из аккаунта</span><br><br/>
    <button type="submit" form = "radioQ">Ответить</button>
</form>
</html>