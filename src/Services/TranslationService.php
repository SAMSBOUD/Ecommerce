<?php
namespace App\Services;

use Jefs42\LibreTranslate;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TranslationService
{
    private HttpClientInterface $client;
    private LibreTranslate $translator;
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->translator = new LibreTranslate();
    }

    public function translate($tring): array
    {
        //dd($this->translator->Languages());
        $translatedText = $this->translator->translate($tring, "fr", "en");
        /*$response = $this->client->request('POST', 'https://libretranslate.com/translate', [
            'json' => [
                'q' => $tring,
                'source' => 'fr',
                'target' => 'en',
            ],
        ]);*/
        dd($translatedText);
        // Décoder la réponse JSON et retourner le résultat
       // return $response->toArray();
    }
}
