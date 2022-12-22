<?php
/** 1. Создайте объект класса View, удовлетворяющий следующим требованиям:
 * 1) Конструктор не имеет аргументов (а может быть вообще не нужен никакой конструктор)
 * 2) Есть метод assign($name, $value), чья задача - сохранить данные, передаваемые в шаблон
 * по заданному имени (используйте защищенное свойство - массив для хранения этих данных)
 * 3) Есть метод display($template), который отображает указанный шаблон с заранее сохраненными данными
 * 4*) Метод render($template), который аналогичен методу display(), но не выводит шаблон с данными в браузер,
 * а возвращает его - комментарий: это не сделано
 * 2. Перепишите предыдущее ДЗ, используя ВЕЗДЕ объекты класса View для отображения страниц сайта
 */
require_once __DIR__ . "/TextFile.php";
require_once __DIR__."/GuestBook.php";
require_once __DIR__."/News.php";
require_once __DIR__."/NewsDB.php";

class View
{
    public function display(GuestBook $gbook): void
    {
        if (count($gbook->getData()) > 0) {
            $this->displayList($gbook);
            $this->displayForm($gbook->getData()[0]);
        } else {
            echo " No data available to display ";
        }
    }

    public function displayList(GuestBook $gbook): void
    {
        $j = 0;
        if (count($gbook->getData()) > 0) {
            foreach ($gbook->getData() as $record) {
                $names = $record->getNames();
                $values = $record->getValues();
                $j++;
                echo "<pre></pre> Запись $j:";
                for ($i = 0; $i < sizeof($values); $i++) {
                    echo " $names[$i]$values[$i] <pre></pre> ";
                }
            }
        } else {
            echo "No guestbook records yet";
        }
        ?>
        <html>
            <hr>
        </html>
        <?php
    }

    public function displayForm(GuestBookRecord $record): void // input should be $template
    {
        $names = $record->getNames(); // should depend on $template
        ?>
        <html>
        <br/><br/>
        <form action="assign.php" method="post">
        <?php
        for ($i = 0; $i < sizeof($names); $i++) {
            echo "<pre></pre>$names[$i]"; ?><input type="text" name="vars[]" >
            type="text" name="userName"
            <?php
        }?>
            <button type="submit">Отправить</button>
        </html>
        <?php
    }

    public function showNews(News $news): void
    {
        $i = 0;
        foreach ($news->getData() as $record) {
            echo '<a href="article.php?id='.$i.'"/>'.$record->getHeader().'</a>'.
                ($record->getAuthor()? ' (источник: '.$record->getAuthor().')': '')."<pre></pre>";
            echo $record->getHalfText()."...<pre> </pre>";
            $i++;
        }
    }
    public function showArticle(Article $article): void
    {
        echo '<div style="font-size:1.25em;color:#0e3c68;font-weight:bold;">'
            .$article->getHeader().'</div>'."<pre></pre>";
        foreach ($article->getArticle() as $paragraph) {
            echo $paragraph."<pre></pre>";
        }
        if ($article->getAuthor()) {
            ?>
            &copy
            <?php
            echo '<div style="font-size:0.75em;color:#0e3c68;">'
                .$article->getAuthor()." ".date("Y").'</div>';
        }
    }

    public function showHeader(CitySymbols $symb, string $cityName): void
    {
        // header
        echo '<div style="text-align:center; font-size:4em;color:#0e3c68;">
<img src="'.$symb->getGerb().'" style="height:5%;width:auto">'." $cityName ".
            '<img src="'.$symb->getGerb().'" style="height:5%;width:auto"></div>';
        echo '<div align="center"><img src="'.$symb->getMainPhoto().
            '" style="height:40%;width:auto"></div>';
    }

    public function showCityPage(CitySymbols $symb, string $cityName,
                                 CityInfo $info, CityGallery $gallery): void
    {
        // header
        $this->showHeader($symb, $cityName);

        //city information
        $citySummary = $info->getInfoWhole();
        echo $citySummary;
        echo "<pre></pre><hr/>";

        // ssilka na raspisanie poletov
        echo '&#9992;<a href="novosib_flights.php"/>Расписание полётов </a><pre></pre>';

        // ssilka na galereyu
        echo '&#128247;<a href="novosib_galereya.php"/> Галерея </a><pre></pre>';
        echo '<a href="novosib_galereya.php"/> <img src="'.$gallery->getPictures()[0]
            .'" style="height:7%;width:auto"></a>';
        // start editing mode
        ?>
        <html>
        <center>
            <button type="submit"><a href="novosib_login.php"/>Зайти в панель администратора</a></button>
            <br/><br/>
        </center>
        </html>
        <?php
    }

    public function showEditableCityPage(CitySymbols $symb, string $cityName,
                             CityInfo $info, CityGallery $gallery, FlightSchedule $flights): void
    {
        // header
        $this->showHeader($symb, $cityName);

        //city information
        $citySummary = $info->getInfoWhole();
        ?>
        <html>
        <form method="post">
            <input type = "text" value = "<?= $citySummary ?>" size = <?= mb_strlen($citySummary)-120 ?> name = "var" />
            <input type="submit" value = "Применить изменения" />
        </form>
        </html>
        <?php
        $newVal = ($_POST['var'])??"";
        if( $newVal != "" && $newVal != $citySummary) {
            $citySummary = $newVal;
            $info->updateText($newVal);
        }
        echo "<pre></pre><hr/>";

        // ssilka na raspisanie poletov
        echo '&#9992; Расписание полётов <pre></pre>';
        $listFlights = $flights->getFlightsList();?>
        <html>
        <table>
            <tr><td> Время </td><td> Направление </td><td> Авиакомпания </td></tr>
        </table>
        <form method="post">
        <?php
        $i = 0;
        foreach ($listFlights as $flight) {
            $timeName = "timeInfo$i";
            $destinationName = "destinationInfo$i";
            ?>
            <input type = "text" value = "<?= $flight->getTime() ?>"  name = "<?= $timeName ?>" />
            <input type = "text" value = "<?= $flight->getDest() ?>"  name = "<?= $destinationName ?>" />
            <input type = "text" value = "<?=  $flight->getAirline() ?>"  />
            <br><br/>
            <?php
            $i++;
        }?>
            <button type="submit"> Изменить расписание рейсов </button>
        </form>
        </html>
        <?php
        for ($j = 0; $j < $i; $j++) {
            $time2Check = ($_POST["timeInfo$j"])??$flights->getTime($j);
            if ($time2Check!==$flights->getTime($j)) {
                $flights->updateTime($j,$time2Check);
            }
            $dest2Check = ($_POST["destinationInfo$j"])??$flights->getDestination($j);
            if ($dest2Check!==$flights->getDestination($j)) {
                $flights->updateDestination($j,$dest2Check);
            }
        }

        // ssilka na galereyu
        echo '&#128247; Галерея <pre></pre>';
        $this->displayCityGallery($gallery,'15%');
        ?>
        <http>
            <br><br/>
            <br><br/>
            <br><br/><br/>
            <left>
                <form method="post" enctype="multipart/form-data">
                    <input type="file" name="myimg" />
                    <input type = "submit" value=" Отправить " />
                </form>
            </left>
        </http>
        <?php
        if ( (isset($_FILES['myimg'])) ) {
            if (0 == $_FILES['myimg']['error']) {
                $gallery->addPicture($_FILES['myimg']['tmp_name'], $_FILES['myimg']['name']);
            }
        }

        // vozmozhnost vijti iz redaktirovaniya
    ?>
        <html>
            <center>
                <form action="../CityPage/stop_edit.php" method="post" id = "exitEdit">
                    <button type="submit" name = "editExit" form = "exitEdit">Выйти</button>
                </form>
            </center>
        </html>
        <?php
}

    public function displayCityGallery(CityGallery $gallery, string $size = '30%'): void
    {
        $photoList = $gallery->getPictures();
        foreach ($photoList as $pic) {
            echo '<img src="'.$pic.'" style="height:'.$size.';width:auto" align = "left">';
        }
    }

    public function displayFlights(FlightSchedule $flights): void
    {
        echo '<table>';
        echo '<tr><td>';
        echo " Время ";
        echo '</td><td>';
        echo " Направление ";
        echo '</td><td>';
        echo " Авиакомпания ";
        echo '</td></tr>';
        foreach($flights->getFlightsList() as $item) {
            # list(…)
        echo '<tr><td>';
        echo " ".$item->getTime()." ";
        echo '</td><td>';
        echo " ".$item->getDest()." ";
        echo '</td><td>';
        echo " ".$item->getAirline()." ";
        echo '</td></tr>';
        }
        echo '</table>';
    }

    public function editFlights(FlightSchedule $flights): void
    {
        echo '<table>';
        echo '<tr><td>';
        echo " Время ";
        echo '</td><td>';
        echo " Направление ";
        echo '</td><td>';
        echo " Авиакомпания ";
        echo '</td></tr>';
        foreach($flights->getFlightsList() as $item) {
            # list(…)
            echo '<tr><td>';
            echo " ".$item->getTime()." ";
            echo '</td><td>';
            echo " ".$item->getDest()." ";
            echo '</td><td>';
            echo " ".$item->getAirline()." ";
            echo '</td></tr>';
        }
        echo '</table>';
    }

    public function displayLoginForm(string $fileName): void
    {
        ?>
        <html>
        <form action="/../novosib_login.php" method="post">
        <table>
        <tr>
            <td align="right">Имя пользователя: </td>
            <td align="left"><input type="text" name="userName" /></td>
        </tr>
        <tr>
            <td align="right">Пароль: </td>
            <td align="left"><input type = "password" name = "pwd"/></td>
        </tr>
        <tr>
            <td align="right"> </td>
            <td align="center"><input type="submit" value=" Зайти "/></td>
        </tr>
        </table>
        </form>
        </html>
        <?php
    }
}