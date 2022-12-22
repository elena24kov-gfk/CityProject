<?php

class CityGallery
{
    protected string $galleryLocation;
    protected array $picFiles;
    protected array $permissibleExtentions;

    public function __construct()
    {
        $this->galleryLocation = __DIR__ . '/../../Novosib_project/gallery';
        $this->permissibleExtentions = ['jpg', 'JPG', 'jpeg', 'png', 'PNG', 'jfif', 'pjpeg', 'pjp'];
        $files = scandir($this->galleryLocation);
        $this->picFiles = [];
        foreach ($files as $fl){
            $fl_array = explode('.', $fl);
            if ( (strlen($fl) > 2) &&
                (in_array(end($fl_array), $this->permissibleExtentions)) ) {
                $this->picFiles[] = '/Novosib_project/gallery/' . $fl;
            }
        }
    }

    public function getPictures(): array
    {
        return $this->picFiles;
    }

    public function addPicture(string $fileNameTmp, string $fileName): void
    {
        $names = explode('.', $fileName);
        if ( in_array(end($names), $this->permissibleExtentions) ) {
            $res = move_uploaded_file($fileNameTmp,$this->galleryLocation.'/'.$fileName);
            $this->picFiles[] = '/Novosib_project/gallery/' . $fileName;
        } else {
            echo " Файл $fileName не загружен: недопустимый формат ";
        }
    }
}

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

