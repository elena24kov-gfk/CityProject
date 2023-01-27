<?php
namespace Classes\Base;


class TextFile
{
    protected array $records;
    protected string $sourceEncoding;
    protected string $toEncoding;

    public function __construct()
    {
        $this->sourceEncoding = 'UTF-8';
        $this->toEncoding = 'UTF-8';
        $this->records = [];
    }

    public function getData(): array
    {
        return $this->records;
    }

    public function append(TextFileRecord $text): void
    {
        $this->records[] = $text;
    }
}