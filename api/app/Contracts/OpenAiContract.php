<?php

namespace App\Contracts;

interface OpenAiContract
{
    public function transcribe($data);
    public function generateAudio();
    public function generateImage();
    public function generateText(string $message);
    public static function getInstance();
}
