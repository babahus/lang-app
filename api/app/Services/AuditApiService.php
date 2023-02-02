<?php

namespace App\Services;

use App\Models\Audit;
use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;

class AuditApiService
{

    private Client $client;
    /**
     * @var string[]
     */
    private array $headers;

    public function __construct()
    {
        $this->client = new Client();
        $this->headers = [
            'Authorization' => 'Token ' . config('services.assemblayai.api_key'),
            'Content-Type' => 'application/json',
        ];

    }

    public function uploadAudio(int $id)
    {
        $auditObj = Audit::whereId($id)->first();
        $file = 'storage/'.$auditObj->path;
        $response = $this->client->post(config('services.assemblayai.upload_url'), [
            'headers' => $this->headers,
            'body' => file_get_contents($file)
        ]);
        $upload_url = json_decode($response->getBody(), true);
        // Return the transcription as a response
        return $upload_url['upload_url'];
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function transcriptAudio(string $upload_url, int $auditId)
    {
        $parameters = [
            'audio_url' => $upload_url,
            'webhook_url' => config('services.assemblayai.webhook_url'),
        ];

        // Send the transcription request to AssemblyAI
        $response = $this->client->post(config('services.assemblayai.transcript_url'), [
            'headers' => $this->headers,
            'json' => $parameters,
        ]);

        // Get the transcription from the response
        $transcription = json_decode($response->getBody(), true);
        // Return the transcription as a response
        $auditModel = Audit::whereId($auditId)->first();
        $auditModel->request_id = $transcription['id'];
        $auditModel->request_status = $transcription['status'];
        $auditModel->save();

        return $transcription['id'];
    }

    public function getResult(array $transcriptResult)
    {
        $auditModel = Audit::whereRequestId($transcriptResult['transcript_id'])->first();
        $url = 'https://api.assemblyai.com/v2/transcript/' . $transcriptResult['transcript_id'];
        $response = $this->client->get($url, [
            'headers' => $this->headers,
        ]);
        $result = (json_decode($response->getBody(), true));
        $auditModel->request_status = $result['status'];
        $auditModel->transcription = $result['text'];
        $auditModel->save();
        return true;
    }
}
