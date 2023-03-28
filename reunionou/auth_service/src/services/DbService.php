<?php

namespace reunionou\auth\services;

use MongoDB\BSON\ObjectId;


function jsonPrint($printable)
{
    $json = json_encode($printable, JSON_PRETTY_PRINT);
    return "<pre>{$json}</pre>";
}

final class DbService
{
    private String $mongo;

    public function __construct(string $link)
    {
        $this->mongo = $link;
    }


    public function signUp($data)
    {
        $test = new \MongoDB\Client($this->mongo);
        $db = $test->auth_reunionou;

        $user = $db->user->findOne(['username' => $data['username']]);
        if ($user) {
            throw new \Exception("User already exists", 409);
        }
        if (!isset($data['level'])) {
            $data['level'] = 0;
        }
        $user = [
            'username' => $data['username'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'mail' => $data['email'],
            'level' => $data['level'],
        ];

        $refresh_token = bin2hex(random_bytes(32));
        $user['refresh_token'] = $refresh_token;
        $db->user->insertOne($user);
        return $user;
    }


    public function signOut($acces_token)
    {
        $test = new \MongoDB\Client($this->mongo);
        $db = $test->auth_reunionou;

        $user = $db->user->findOne(['acces_token' => $acces_token]);

        if (!$user) {
            throw new \Exception("User not found", 404);
        } else {
            $db->user->updateOne(['_id' => $user->_id], ['$set' => ['acces_token' => null]]);
        }
    }

    public  function getUser($uid)
    {
        $test = new \MongoDB\Client($this->mongo);
        $db = $test->auth_reunionou;

        $user = $db->user->findOne(['_id' => new ObjectId($uid)]);
        if (!$user) {
            throw new \Exception("User not found", 404);
        }
        return $user;
    }

    public  function signIn($username, $password)
    {
        $test = new \MongoDB\Client($this->mongo);
        $db = $test->auth_reunionou;

        $user = $db->user->findOne(['username' => $username]);
        if (!$user) {
            $user = $db->user->findOne(['mail' => $username]);
            if (!$user) {
                throw new \Exception("User not found", 404);
            }
        }
        if (!password_verify($password, $user->password)) {
            throw new \Exception("Invalid password", 401);
        } else {
            return $user;
        }
    }

    public function updateToken($uid, $token)
    {
        $test = new \MongoDB\Client($this->mongo);
        $db = $test->auth_reunionou;

        $user = $db->user->findOne(['_id' => $uid]);
        if (!$user) {
            throw new \Exception("User not found", 404);
        }
        $db->user->updateOne(['_id' => $uid], ['$set' => ['acces_token' => $token]]);
    }

    public function validate($token)
    {
        $test = new \MongoDB\Client($this->mongo);
        $db = $test->auth_reunionou;

        $user = $db->user->findOne(['acces_token' => $token]);
        if (!$user) {
            throw new \Exception("User not found", 404);
        }
        return 200;
    }



    public function modifyAvatar($id, $data)
    {
        $test = new \MongoDB\Client($this->mongo);
        $db = $test->auth_reunionou;

        $user = $db->user->findOne(['_id' => new ObjectId($id)]);
        if (!$user) {
            throw new \Exception("User not found", 404);
        }
        $db->user->updateOne(['_id' => new ObjectId($id)], ['$set' => ['avatar' => $data]]);
    }

    public function modifyAdress($id, $data)
    {
        $test = new \MongoDB\Client($this->mongo);
        $db = $test->auth_reunionou;

        $user = $db->user->findOne(['_id' => new ObjectId($id)]);
        if (!$user) {
            throw new \Exception("User not found", 404);
        }
        $db->user->updateOne(['_id' => new ObjectId($id)], ['$set' => ['adress' => $data]]);
    }
}
