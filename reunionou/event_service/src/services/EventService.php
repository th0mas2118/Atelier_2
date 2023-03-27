<?php

namespace reunionou\event\services;

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

    public function createEvent(array $data): mixed
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("event");

            $event = $db->insertOne($data);

            return $event;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getEvent(string $id): mixed
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("event");

            $event = $db->findOne(['_id' => new ObjectId($id)]);

            return $event;
        } catch (\Throwable $th) {
            return null;
        }
    }
}
