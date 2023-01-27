<?php
namespace Classes\CityPage;

class CitySymbols
{
    protected string $pictLocation;
    protected string $gerb;
    protected string $photo;

    public function __construct()
    {
        $this->pictLocation = '/Novosib_project/';
        $this->gerb = 'Gerb.png';
        $this->photo = 'MainPhoto.jpg';
    }

    public function getGerb(): string
    {
        return $this->pictLocation.$this->gerb;
    }

    public function getMainPhoto(): string
    {
        return $this->pictLocation.$this->photo;
    }
}