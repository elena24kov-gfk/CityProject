<?php
require_once __DIR__ . "/../../autoloader.php";

class CityInfo extends TextFile
{
    protected string $sourceLocation;
    protected string $contents;

    public function __construct()
    {
        TextFile::__construct();
        $this->sourceLocation = __DIR__ . "/../../Novosib_project/City_info.txt";
        if (file_exists($this->sourceLocation)) {
            $fop = fopen($this->sourceLocation, 'r');
            while (!feof($fop)) {
                $this->records[] = mb_convert_encoding(fgets($fop, 2020),
                    'UTF-8', 'UTF-8');
            }
            fclose($fop);
            $this->contents = file_get_contents($this->sourceLocation);
        }
    }

    public function getInfo(): array
    {
        return $this->records;
    }

    public function getInfoWhole(): string
    {
        return $this->contents;
    }

    public function updateParagraph(int $i, string $newVal): void
    {
        if (count($this->records) > $i) {
            $this->records[$i] = $newVal;
        }
        $this->save();
    }

    public function updateText(string $newVal): void
    {
        $this->contents = $newVal;
        file_put_contents($this->sourceLocation, $this->contents);
    }

    public function save(): void
    {
        $fop = fopen($this->sourceLocation, 'w');
        foreach ($this->records as $line) {
            //if ( $line->noError() ) {
            fwrite($fop, $line."\n");
            //}
        }
        fclose($fop);
    }
}

class FlightSchedule extends TextFile
{
    protected NewsDB $flightsDb;
    protected int $recLength;

    public function __construct()
    {
        $this->flightsDb = new NewsDB();
        $this->records = [];
        $tmp = $this->flightsDb->query('SELECT `flightSchedule`.id as id, time, 
    destination, airlines.name as al_name FROM `flightSchedule` 
        JOIN `airlines` ON flightSchedule.airline_id = airlines.id 
                        ORDER BY time', []);
        foreach ($tmp as $flightrcd)
        {
            $flRcd = new FlightInfo($flightrcd["id"],$flightrcd["time"],
                $flightrcd["destination"],$flightrcd["al_name"]);
            $this->records[] = $flRcd;
        }
        $this->recLength = sizeof($this->records);
    }

    public function getFlightsTop(): string
    {
        if (count($this->records) > 0) {
            return $this->records[0]->shortInfo();
        } else {
            return "";
        }
    }

    public function getFlightsList(): array
    {
        return $this->records;
    }

    public function getRecordsLength(): int
    {
        return $this->recLength;
    }

    public function getIndex(int $ind): int
    {
        if ($ind < $this->recLength) {
            return $this->records[$ind]->getIndex();
        }
        return $this->getRecordsLength();
    }

    public function getTime(int $ind): string
    {
        if ($ind < $this->recLength) {
            return $this->records[$ind]->getTime();
        }
        return "";
    }

    public function getDestination(int $ind): string
    {
        if ($ind < $this->recLength) {
            return $this->records[$ind]->getDest();
        }
        return "";
    }

    public function updateTime(int $ind, string $newTime): void
    {
        if ($ind < $this->recLength) {
            $this->records[$ind]->updateTime($newTime);
            $idModify = $this->records[$ind]->getIndex($ind);
            $tmp = $this->flightsDb->execute('UPDATE `flightSchedule` SET `time` = "'
                .$newTime.'" WHERE `flightSchedule`.`id` = '.$idModify.' ;');
        }
    }

    public function updateDestination(int $ind, string $newDest): void
    {
        if ($ind < $this->recLength) {
            $this->records[$ind]->updateDest($newDest);
            $idModify = $this->records[$ind]->getIndex($ind);
            $this->flightsDb->execute('UPDATE `flightSchedule` SET `destination` = "'
                .$newDest.'" WHERE `flightSchedule`.`id` = '.$idModify.' ;');
        }
    }
}

class FlightInfo {
    protected int $id;
    protected string $time;
    protected string $destination;
    protected string $airline;

    public function __construct(int $id, string $tm, string $dest, string $arln)
    {
        $this->id = $id;
        $this->time = $tm;
        $this->destination = $dest;
        $this->airline = $arln;
    }

    public function shortInfo(): string
    {
        return "Время вылета: $this->time Направление: $this->destination Авиакомпания: $this->airline";
    }

    public function getIndex(): int
    {
        return $this->id;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function getDest(): string
    {
        return $this->destination;
    }

    public function getAirline(): string
    {
        return $this->airline;
    }

    public function updateDest(string $newDest): void
    {
        $this->destination = $newDest;
    }

    public function updateTime(string $newTime): void
    {
        $this->time = $newTime;
    }
}