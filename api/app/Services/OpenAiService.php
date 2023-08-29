<?php

namespace App\Services;

use App\Contracts\OpenAiContract;
use Orhanerday\OpenAi\OpenAi;

class OpenAiService implements OpenAiContract
{
    private static ?OpenAiService $instance = null;
    private OpenAi $openAi;

    private function __construct()
    {
        $openAiKey  = getenv('OPENAI_API_KEY');
        $this->openAi = new OpenAi($openAiKey);
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    // Method to generate text using OpenAI
    public function generateText(string $message)
    {
        $response = $this->openAi->chat([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    "role" => "user",
                    "content" => $message
                ],
            ],
            'temperature' => 1.0,
            'max_tokens' => 4000,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ]);
        var_dump($response);

        return json_decode($response)->choices[0]->message->content;
    }

    // Method to generate images using OpenAI
    public function generateImage()
    {
        // Implement your logic to generate images here
        // Example: return "Generated image";
    }

    // Method to generate audio files using OpenAI
    public function generateAudio()
    {
        // Implement your logic to generate audio files here
        // Example: return "Generated audio";
    }

    // Method to transcribe text, images, or audio files
    public function transcribe($data)
    {
        // Implement your logic to transcribe data here
        // Example: return "Transcription of data";
    }
}
