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

    public function createInvitation(string $id): ?string
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("invitation");

            $data = [];
            $data["event_id"] = $id;
            $data["expired"] = false;
            $data["accepted"] = false;
            $data["expiration_date"] = new \MongoDB\BSON\UTCDateTime(strtotime("+7 days") * 1000);

            $invitation = $db->insertOne($data);

            return $invitation->getInsertedId();
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getInvitation(string $id): mixed
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("invitation");

            $invitation = $db->findOne(['_id' => new ObjectId($id)]);

            return $invitation;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function deleteInvitation(string $id): ?bool
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("invitation");

            $invitationExists = $db->findOne(['_id' => new ObjectId($id)]);

            if (!isset($invitationExists)) return null;

            $invitation = $db->deleteOne(['_id' => new ObjectId($id)]);

            return $invitation->getDeletedCount() > 0;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function updateInvitation(string $id, array $data): ?bool
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("invitation");

            $invitationExists = $db->findOne(['_id' => new ObjectId($id)]);

            if (!isset($invitationExists)) return null;

            $invitation = $db->updateOne(['_id' => new ObjectId($id)], ['$set' => $data]);

            return $invitation->getModifiedCount() > 0;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
