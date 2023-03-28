<?php

namespace reunionou\event\services;

use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use reunionou\event\models\Event;

final class InvitationService
{
    private String $mongo;

    public function __construct(string $link)
    {
        $this->mongo = $link;
    }

    public function createInvitation(array $data): ?string
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("invitation");

            $invitation = $db->insertOne($data);

            return $data['event_id'] . $invitation->getInsertedId() . $data['user_id'];
        } catch (\Throwable $th) {
            return null;
        }
    }
}
