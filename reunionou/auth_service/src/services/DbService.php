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
        $client = new \MongoDB\Client($this->mongo);
        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");

        $user = $db->findOne(['username' => $data['username']]);
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
            'avatar' => null,
            'adress' => null
        ];

        $refresh_token = bin2hex(random_bytes(32));
        $user['refresh_token'] = $refresh_token;
        $db->insertOne($user);
        return $user;
    }


    public function signOut($acces_token)
    {
        $client = new \MongoDB\Client($this->mongo);
        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");

        $user = $db->findOne(['acces_token' => $acces_token]);

        if (!$user) {
            throw new \Exception("User not found", 404);
        } else {
            $db->updateOne(['_id' => $user->_id], ['$set' => ['acces_token' => null]]);
        }
    }

    public  function getUser($uid)
    {
        $client = new \MongoDB\Client($this->mongo);
        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");

        $user = $db->findOne(['_id' => new ObjectId($uid)]);
        if (!$user) {
            throw new \Exception("User not found", 404);
        }
        return $user;
    }

    public function findUser($data)
    {
        $client = new \MongoDB\Client($this->mongo);
        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");

        $user = $db->findOne(['username' => $data]);
        if (!$user) {
            $user = $db->findOne(['mail' => $data]);
            if (!$user) {
                $user = $db->findOne(['firstname' => $data]);
                if (!$user) {
                    $user = $db->findOne(['lastname' => $data]);
                    if (!$user) {
                        throw new \Exception("User not found", 404);
                    }
                }
            }
        }
        return $user;
    }

    public function getUsers()
    {
        try {
            $client = new \MongoDB\Client($this->mongo);
            $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");

            $users = $db->find();

            return $users->toArray();
        } catch (\Throwable $th) {
            return null;
        }
    }


    public  function signIn($username, $password)
    {
        $client = new \MongoDB\Client($this->mongo);
        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");

        $user = $db->findOne(['username' => $username]);
        if (!$user) {
            $user = $db->findOne(['mail' => $username]);
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
        $client = new \MongoDB\Client($this->mongo);
        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");

        $user = $db->findOne(['_id' => $uid]);
        if (!$user) {
            throw new \Exception("User not found", 404);
        }
        $db->updateOne(['_id' => $uid], ['$set' => ['acces_token' => $token]]);
    }

    public function validate($token)
    {
        $client = new \MongoDB\Client($this->mongo);
        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");

        $user = $db->findOne(['acces_token' => $token]);
        if (!$user) {
            throw new \Exception("User not connected", 404);
        }
        return 200;
    }



    public function modifyAvatar($id, $data)
    {
        $client = new \MongoDB\Client($this->mongo);
        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");
        $user = $db->findOne(['_id' => new ObjectId($id)]);
        if (!$user) {
            throw new \Exception("User not found", 404);
        }
        $db->updateOne(['_id' => new ObjectId($id)], ['$set' => ['avatar' => $data]]);
    }

    public function modifyAdress($id, $data)
    {
        $client = new \MongoDB\Client($this->mongo);
        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");

        $user = $db->findOne(['_id' => new ObjectId($id)]);
        if (!$user) {
            throw new \Exception("User not found", 404);
        }
        $db->updateOne(['_id' => new ObjectId($id)], ['$set' => ['adress' => $data]]);
    }

    public function deleteUser($id)
    {
        $client = new \MongoDB\Client($this->mongo);
        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");

        $user = $db->findOne(['_id' => new ObjectId($id)]);
        if (!$user) {
            throw new \Exception("User not found", 404);
        }
        $db->deleteOne(['_id' => new ObjectId($id)]);
    }



    //FRIEND déplacer collection a part
    public function addFriend($id, $friend)
    {
        $client = new \MongoDB\Client($this->mongo);
        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");

        $user = $db->findOne(['_id' => new ObjectId($id)]);
        if (!$user) {
            throw new \Exception("User not found", 404);
        }
        if (!isset($user['friends'])) {
            $db->updateOne(['_id' => new ObjectId($id)], ['$push' => ['friends' => $friend]]);
            return;
        }
        //convert MongoDB\\Model\\BSONArray to array
        $user['friends'] = json_decode(json_encode($user['friends']), true);
        //add to db
        if (in_array($friend, $user['friends'])) {
            throw new \Exception("Friend already added", 400);
        }
        $db->updateOne(['_id' => new ObjectId($id)], ['$push' => ['friends' => $friend]]);
    }
    public function getFriendsList($id)
    {
        $client = new \MongoDB\Client($this->mongo);
        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");

        $user = $db->findOne(['_id' => new ObjectId($id)]);
        if (!$user) {
            throw new \Exception("User not found", 404);
        }
        if (!isset($user['friends'])) {
            throw new \Exception("User has no friends", 404);
        }
        //convert MongoDB\\Model\\BSONArray to array
        $user['friends'] = json_decode(json_encode($user['friends']), true);
        return $user['friends'];
    }
    public function removeFriend($id, $friend)
    {
        $client = new \MongoDB\Client($this->mongo);
        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");

        $user = $db->findOne(['_id' => new ObjectId($id)]);
        if (!$user) {
            throw new \Exception("User not found", 404);
        }
        if (!isset($user['friends'])) {
            throw new \Exception("User has no friends", 404);
        }
        //convert MongoDB\\Model\\BSONArray to array
        $user['friends'] = json_decode(json_encode($user['friends']), true);
        //add to db
        if (!in_array($friend, $user['friends'])) {
            throw new \Exception("Friend not found", 404);
        }
        $db->updateOne(['_id' => new ObjectId($id)], ['$pull' => ['friends' => $friend]]);
    }
}
