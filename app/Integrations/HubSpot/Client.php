<?php

namespace Boilerplate\Integrations\HubSpot;

class Client
{
    private string $baseUrl = 'https://api.hubapi.com';
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function post(string $endpoint, array $payload): array
    {
        $response = wp_remote_post($this->baseUrl . $endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type'  => 'application/json',
            ],
            'body'    => wp_json_encode($payload),
            'timeout' => 10,
        ]);

        return $this->parseResponse($response);
    }

    private function parseResponse($response): array
    {
        if (is_wp_error($response)) {
            return [
                'success' => false,
                'error'   => $response->get_error_message(),
            ];
        }

        return [
            'success' => true,
            'status'  => wp_remote_retrieve_response_code($response),
            'body'    => json_decode(wp_remote_retrieve_body($response), true),
        ];
    }
}
