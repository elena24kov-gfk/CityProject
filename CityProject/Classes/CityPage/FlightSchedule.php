<?php
namespace Classes\CityPage;

use Classes\Base\TextFile;
use Classes\Base\AccessDB;

class FlightSchedule extends TextFile
{
    protected AccessDB $flightsDb;
    protected int $recLength;

    public function __construct()
    {
        $this->flightsDb = new AccessDB();
        $this->records = [];
        $tmp = $this->flightsDb->query('SELECT `flightSchedule`.id as id, time, 
    destination, airlines.name as al_name FROM `flightSchedule` 
        JOIN `airlines` ON flightSchedule.airline_id = airlines.id 
                        ORDER BY time', []);
        foreach ($tmp as $flightrcd)
        {
            $flRcd = new \Classes\CityPage\FlightInfo($flightrcd["id"],$flightrcd["time"],
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