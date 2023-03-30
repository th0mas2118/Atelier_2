<?php

namespace reunionou\auth\services;

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

    public function getUserInvitations(string $user_id): array
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("invitation");

            $invitations = $db->find(['user.id' => $user_id]);
            return $invitations->toArray();
        } catch (\Throwable $th) {
            return null;
        }
    }
}
