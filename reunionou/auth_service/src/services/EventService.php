<?php

namespace reunionou\auth\services;

use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use reunionou\event\models\Event;

final class EventService
{
    private String $mongo;

    public function __construct(string $link)
    {
        $this->mongo = $link;
    }

    public function getUserEvents(string $user_id): array
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("event");

            $events = $db->find(['organizer.id' => $user_id]);
            return $events->toArray();
        } catch (\Throwable $th) {
            return null;
        }
    }
}
