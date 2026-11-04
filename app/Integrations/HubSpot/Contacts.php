<?php

namespace Boilerplate\Integrations\HubSpot;

class Contacts
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function upsert(array $properties): array
    {
        return $this->client->post('/crm/v3/objects/contacts', [
            'properties' => $properties,
        ]);
    }
}
