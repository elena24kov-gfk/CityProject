<html>
<form action="index.php" method="get" id = "exitEdit">
    <button type="submit" name = "pageSwitch" form = "exitEdit" value="Main">Вернуться на главную страницу</button>
</form>
</html>
<?php
require_once __DIR__ . "/../../autoloader.php";

$view = new \View\NvsbrskView();
$flights = new \Classes\CityPage\FlightSchedule();
$view->displayFlights($flights);