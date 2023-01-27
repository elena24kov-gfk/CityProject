<?php
namespace Classes\CityPage;

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