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

    public function createInvitation(string $event_id, ?string $member_id): ?string
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("invitation");

            $data = [];
            $data["event_id"] = $event_id;
            $data["member_id"] = $member_id;
            $data["expired"] = false;
            $data["accepted"] = false;
            $data["expiration_date"] = $member_id ? null : new \MongoDB\BSON\UTCDateTime(strtotime("+7 days") * 1000);

            $invitation = $db->insertOne($data);

            return $invitation->getInsertedId();
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function createUserInvitation(string $event_id, string $event_title, mixed $organizer, mixed $user): ?string
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("invitation");

            $data = [];
            $data["event_id"] = $event_id;
            $data["user"] = $user;
            $data["expired"] = false;
            $data["accepted"] = false;
            $data["expiration_date"] = null;
            $data["organizer"] = $organizer;
            $data["event_title"] = $event_title;

            $invitation = $db->insertOne($data);

            return $invitation->getInsertedId();
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getEventInvitations(string $event_id): array
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("invitation");

            $invitations = $db->find(['event_id' => $event_id]);
            return $invitations->toArray();
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getUserInvitations(string $user_id): array
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("invitation");

            $invitations = $db->find(['user_id' => $user_id]);

            return $invitations->toArray();
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

    public function findAndUpdateParticipation(string $event_id, string $user_id, string $status): bool
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("invitation");

            $invitation = $db->findOneAndUpdate(
                ['event_id' => $event_id, 'user.id' => $user_id],
                ['$set' => ['accepted' => $status == 'confirmed' ? true : false]],
            );

            return $invitation->getModifiedCount() > 0;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
