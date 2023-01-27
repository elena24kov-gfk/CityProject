<?php
namespace Classes\CityPage;

use Classes\Base\TextFile;

class CityInfo extends TextFile
{
    protected string $sourceLocation;
    protected string $contents;

    public function __construct()
    {
        try{
            TextFile::__construct();
            $this->sourceLocation = __DIR__ . "/../../Novosib_project/City_info.txt";
            if (!file_exists($this->sourceLocation)) {
                throw new Exception('Text file '.$this->sourceLocation.' not found');
            }
            $fop = fopen($this->sourceLocation, 'r');
            while (!feof($fop)) {
                $this->records[] = mb_convert_encoding(fgets($fop, 2020),
                    'UTF-8', 'UTF-8');
            }
            fclose($fop);
            $this->contents = file_get_contents($this->sourceLocation);
        } catch (\Throwable $e) {
            echo "City information is temporary not available".$e->getMessage();
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
            fwrite($fop, $line."\n");
        }
        fclose($fop);
    }
}