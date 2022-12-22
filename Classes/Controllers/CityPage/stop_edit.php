<?php
echo " Вы действительно хотите закончить редактирование? ";
?>
<html>
<form action="novosib.php" method="post" id = "radioQ">
    <input type="radio" name = "exitEdit" value= "1" checked /><span>Выйти</span><br><br/>
    <input type="radio" name = "exitEdit" value= "0" checked /><span>Продолжить</span><br><br/>
    <button type="submit" form = "radioQ">Ответить</button>
</form>
</html>

