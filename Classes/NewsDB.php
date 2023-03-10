<?php
/** Создайте класс DB:
1) В конструкторе устанавливается и сохраняется соединение с базой данных.
 * Параметры соединения берем из файла конфига
2) Метод execute(string $sql) выполняет запрос и возвращает true либо false в зависимости от того,
 * удалось ли выполнение
3) Метод query(string $sql, array $data) выполняет запрос, подставляет в него данные $data,
 * возвращает данные результата запроса либо false, если выполнение не удалось */

class NewsDB
{
    protected PDO $pdoNews;

    public function __construct()
    {
        $this->pdoNews = new PDO('mysql:dbname=homework;host=mysql_db', 'root', 'root');
    }

    public function execute(string $sql): bool
    {
        $query = $this->pdoNews->prepare($sql);
        return $query->execute();
    }

    public function query(string $sql, array $data): array|false
    {
        $query1 = $this->pdoNews->prepare($sql);
        $query1->execute($data);
        return $query1->fetchAll(PDO::FETCH_ASSOC);
    }
}

/**
 * 2. Создайте таблицу news с полями "заголовок", "текст", "автор". Заполните ее 3-5 новостями.
 * Запишите запросы на языке SQL, которые вы использовали и приложите к своему ДЗ
 */
//$test = new NewsDB();
//$sqlSt = "INSERT INTO `news_list`(`id`, `author_id`, `header`,`article_text`) VALUES (:id,:autid,:hdr,:artcltxt)";
//$sqlSt = 'SELECT * FROM authors WHERE id = :id';
//var_dump($test->query($sqlSt,['id' => 6, 'autid' => '3', 'hdr' => 'dummy', 'artcltxt' => 'dummy']));
//var_dump($test->execute($sqlSt));

// populate table `authors`
//$sqlSt = "INSERT INTO `authors`(`id`, `author_name`, `author_location`) VALUES (:id,:name,:location)";
//$test->query($sqlSt,['id' => 1, 'name' => 'Panorama.pub', 'location' => 'Russian Federation']);
//$test->query($sqlSt,['id' => 2, 'name' => 'RIA Novosti', 'location' => 'Moscow, Russian Federation']);
//$test->query($sqlSt,['id' => 3, 'name' => 'Bloomberg', 'location' => 'New York, USA']);

/**
 * populate table `news_list`
$sqlSt = "INSERT INTO `news_list`(`id`, `author_id`, `header`,`article_text`) VALUES (:id,:autid,:hdr,:artcltxt)";
$test->query($sqlSt,['id' => 1, 'autid' => '1',
'hdr' => 'В Лондоне загримированный под Ван Гога мужчина облил овощным супом эко-активистов',
'artcltxt' => 'В Лондоне неизвестный, нарядившийся Винсентом Ван Гогом, атаковал офис одной из организаций, устраивающих акции против изменения климата. Правоохранительные органы отказались реагировать на ситуацию, поэтому мужчина бесчинствовал около тридцати минут, а затем беспрепятственно скрылся.
Злоумышленник прибыл к офису экоактивистов в полдень. Первым делом он обезвредил охранника, показав ему извлечённый из своей машины катализатор выхлопных газов. Пока тот лежал в обмороке, «Ван Гог» проследовал в офис и начал нападать на людей. Когда ему попадался сотрудник организации, хулиган прижимал его к стене, извлекал из рюкзака банку с овощным супом и обливал.
Активисты неоднократно звонили в полицию, однако там под разными предлогами задерживали выезд. Сначала им ответили, что в акционизме нет ничего противозаконного. Другому сотруднику полицейский заявил, что рад был бы отреагировать, но не желает загрязнять атмосферу выбросами бензиновой машины – его отделение ещё не укомплектовали электромобилями. Когда стражи порядка всё же прибыли в офис, «Ван Гог» уже давно скрылся, причём один из полицейских имел следы грима на лице и всё время странно смеялся.']);
 * $test->query($sqlSt,['id' => 2, 'autid' => '1',
'hdr' => 'Индекс Хирша для российских учёных заменят индексом Луначарского',
'artcltxt' => 'Рабочая группа при Минобрнауки завершила подготовку программы по полному выходу России из Болонского процесса. Последняя редакция документа была посвящена оценке продуктивности деятельности отечественных учёных. Если ранее для этого использовался h-индекс (индекс Хирша)  — наукометрический показатель, предложенный физиком Хорхе Хиршем, то после его обнуления и удаления публикаций россиян из ведущих научных изданий мира, возникла дискуссия об импортозамещении в этой отрасли.
Большинство собравшихся на обсуждении пришли к выводу, что оценивать научные публикации стоит по индексу Луначарского, названного в честь одного из отцов российской науки, первого наркома просвещения РСФСР.
По мнению чиновников, индекс Луначарского позволит заново переоценить достижения отечественных учёных, полностью аннулировав показатель цитируемости их публикаций в иностранных изданиях. После внедрения новации качество будет оцениваться по цитированию в российских изданиях и изданиях исключительно на русском языке в дружественных государствах, кроме того, в расчёт будут браться и публикации в СМИ и работах студентов и учеников школ.
«Это позволит нам, учёным, понять, насколько важен наш труд именно для граждан РФ, а не для иностранного научного сообщества, где мы эти несколько десятилетий были на птичьих правах», – прокомментировал инициативу председатель комиссии.']);
 * $test->query($sqlSt,['id' => 3, 'autid' => '1',
'hdr' => 'Boston Dynamics представила робота-кошку, способного воровать еду у солдат врага',
'artcltxt' => 'Компания Boston Dynamics и Пентагон на совместной презентации в Мэриленде представили новинку – четвероногого робота-кошку. Машина обладает основными навыками своего реального прототипа: может мяукать, просить хозяина его погладить, метить территорию и воровать еду.
Представитель Пентагона, старший уорент-офицер Джеймс Хаддли пояснил, что новый робот будет предназначен для диверсионных операций в тылу врага.
«Воруя провиант у солдат врага, мы подавляем его волю. Всё же без еды и воды вести боевые действия настолько же тяжело, как и без боеприпасов, – заметил он. – Нынешняя версия робота-кошки пока научилась только утилизировать провиант, но мы рассчитываем, что в будущем она будет приносить еду хозяину»
После окончания брифинга часть его участников пожаловались на исчезновение личных вещей, однако военные отказались комментировать инцидент или каким-то образом его расследовать.']);
 * $test->query($sqlSt,['id' => 4, 'autid' => '1',
'hdr' => '«Газпром» сменил номер горячей линии из-за звонков коллекторских агентств',
'artcltxt' => 'После неисполнения выплат по дивидендам за 2021 год компания «Газпром» столкнулась с наплывом звонков из мелких коллекторских агентств из стран Европы. Из-за них россияне часами не могли дождаться ответа оператора. Для обеспечения безопасности компанией было принято решение об изменении номера горячей линии.
«Мы обязательно выплатим дивиденды, но это будет в следующем году или через год. Как и положено по закону, недружественным странам – в рублях, дружественным – в валюте. А чтобы решить сегодняшнюю проблему, было принято решение сменить номер нашей горячей линии. Европейские коллекторы его не знают и больше не будут отвлекать наших операторов», – прояснил ситуацию глава правления компании Алексей Миллер.
Председатель правления «Газпрома» добавил, что для того, чтобы избежать нежелательных звонков и из России, компания временно не будет объявлять новый номер горячей линии.
«В будущем мы планируем обратиться к депутатам Госдумы с предложением засекретить все телефонные номера государствообразующих компаний и предприятий. Думаю, речь должна идти не только о предприятиях оборонки, но и энергетики, которая столь же важный элемент государственного суверенитета», – заключил он.']);
 */