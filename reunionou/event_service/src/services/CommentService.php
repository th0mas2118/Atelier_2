<?php

namespace reunionou\event\services;

use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use reunionou\event\models\Event;

final class CommentService
{
    private String $mongo;

    public function __construct(string $link)
    {
        $this->mongo = $link;
    }

    public function getEventComments(string $id): array
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("comment");

            $comments = $db->find(['event_id' => $id]);
            return $comments->toArray();
        } catch (\Throwable $th) {
            return null;
        }
    }
}
