<?php

namespace TorfsICT\Symfony\Service;

use GuzzleHttp\Client;

class Unoconv
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function convert(string $src, string $format = 'pdf')
    {
        if (is_file($src)) {
            $contents = fopen($src, 'r');
        } else {
            $contents = $src;
        }

        try {
            $response = $this->client->post(sprintf('/unoconv/%s', $format), [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => $contents
                    ]
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                return (string)$response->getBody();
            } else {
                return '';
            }
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}