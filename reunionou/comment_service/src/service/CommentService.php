<?php

namespace reunionou\comment\service;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use reunionou\backoffice\app\utils\Writer;
use MongoDB\BSON\ObjectId;
use renionou\src\models\Comment;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client as Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use orders\errors\exceptions\OrderExceptionNotFound;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentService
{

    private String $mongo;

    public function __construct(string $link)
    {
        $this->mongo = $link;
    }

    public function getComment(String $id): mixed
    {
        $client = new \MongoDB\Client($this->mongo);
            
        try {
            $db = $client->selectDatabase("reunionou")->selectCollection("comment");

            $comment = $db->findOne(['_id' => new ObjectId($id)]);
            return $comment;
        } catch (\Throwable $th) {
            return null;
        }
    }
    public function createComment($data): ?String {

        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("comment");

            $comment = $db->insertOne($data);

            return $comment->getInsertedId();
        } catch (\Throwable $th) {
            return null;
        }

    }

    public function deleteComment($commentId): bool {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("comment");
            $result = $db->deleteOne(["_id" => new ObjectID($commentId)]);
    
            return $result->getDeletedCount() === 1;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function updateComment($commentId, $newComment): ?String{
        try{
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("reunionou")->selectCollection("comment");

            $result = $db->updateOne(["_id" => new ObjectId($commentId)],["set" => new ObjectId($newComment)]);

            return $result->getModifiedCount();

        }catch(\Throwable $th) {
            return false;
        }
    }
    
    // public function deleteComment($data): ?String {

    //     try {
    //         $client = new \MongoDB\Client($this->mongo);
    //         $db = $client->selectDatabase("reunionou")->selectCollection("comment");

    //         $comment = $db->deleteOne($data);

    //         return $comment->getInsertedId();
    //     } catch (\Throwable $th) {
    //         return null;
    //     }

    // }


}