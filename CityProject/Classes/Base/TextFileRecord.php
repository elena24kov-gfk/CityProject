<?php
namespace Classes\Base;
class TextFileRecord
{
    public string $message;

    public function __construct(string $text)
    {
        $this->message = $text;
    }
}