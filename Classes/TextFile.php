<?php

class TextFile
{
    protected array $records;
    public string $error;
    protected string $sourceEncoding;
    protected string $toEncoding;

    public function __construct()
    {
        $this->sourceEncoding = 'UTF-8';
        $this->toEncoding = 'UTF-8';
        $this->records = [];
        $this->error = "";
    }

    /* 2) */
    public function getData(): array
    {
        return $this->records;
    }

    /* 3) */
    public function append(TextFileRecord $text): void
    {
        $this->records[] = $text;
    }
}

class TextFileRecord
{
    public string $message;
    protected string $error;

    public function __construct(string $text)
    {
        $this->message = $text;
    }
}