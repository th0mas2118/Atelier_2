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

    public function createEvent(array $data): ?string
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("event");

            $event = $db->insertOne($data);

            return $event->getInsertedId();
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

    public function updateParticipation(string $event_id, string $user_id, string $state): bool
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("event");

            $event = $db->findOne(['_id' => new ObjectId($event_id)]);

            if (!$event) return null;

            $filter = [
                'participants.user.id' => $user_id,
            ];

            $update = [
                '$set' => [
                    'participants.$.status' => $state
                ]
            ];

            $result = $db->updateOne($filter, $update);
            return $result->getModifiedCount() > 0;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function getEvents(): array
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("event");

            $events = $db->find();

            return $events->toArray();
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function deleteEvent(string $id): ?bool
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("event");

            $eventExists = $db->findOne(['_id' => new ObjectId($id)]);

            if (!isset($eventExists)) return null;

            $event = $db->deleteOne(['_id' => new ObjectId($id)]);

            return $event->getDeletedCount() > 0;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function updateEvent(string $id, array $data): ?bool
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("event");

            $eventExists = $db->findOne(['_id' => new ObjectId($id)]);

            if (!$eventExists) return null;

            $event = $db->updateOne(['_id' => new ObjectId($id)], ['$set' => $data]);

            return $event->getModifiedCount() > 0;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
