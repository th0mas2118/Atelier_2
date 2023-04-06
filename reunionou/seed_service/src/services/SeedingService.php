<?php

namespace reunionou\seed\services;

use Faker\Factory;
use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use Psr\Container\ContainerInterface;

final class SeedingService
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function seedUsers(int $count): void
    {
        $faker = Factory::create();
        $client = new \MongoDB\Client($this->container->get('auth_url'));

        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");

        for ($i = 0; $i < $count; $i++) {
            $address = $faker->streetAddress . ", " . $faker->postcode . " " . $faker->postcode . ", " . $faker->country;
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $user = [
                'username' => strtolower($firstName . "." . $lastName),
                'firstname' => $firstName,
                'lastname' => $lastName,
                'mail' => $faker->email,
                'password' => '$2y$10$1P8pEuaSoFErkw3gjdIdreywDTwElk2lu5lFS.sXXR6SCg5XpK04u',
                'level' => 0,
                'avatar' => null,
                'refresh_token' => null,
                'access_token' => '',
                'adresse' => $address,
            ];

            try {
                $db->insertOne($user);
            } catch (\Throwable $th) {
            }
        }
    }

    public function seedEvents(int $count): void
    {
        $client = new \MongoDB\Client($this->container->get('auth_url'));
        $db = $client->selectDatabase("auth_reunionou")->selectCollection("user");
        $users = $db->find()->toArray();

        $client = new \MongoDB\Client($this->container->get('event_url'));
        $db = $client->selectDatabase("reunionou")->selectCollection("event");

        $faker = Factory::create();

        // Events
        for ($i = 0; $i < $count; $i++) {
            $randomUser = $users[rand(0, count($users) - 1)];
            $participantsCount = rand(0, 10);
            $participants = [];

            // Participants
            for ($i = 0; $i < $participantsCount; $i++) {
                $randomParticipant = $users[rand(0, count($users) - 1)];
                if ($randomParticipant->_id != $randomUser->_id) {
                    $status = ['waiting', 'declined', 'confirmed'][rand(0, 2)];
                    $participant = [
                        'type' => 'user',
                        'status' => $status,
                        'user' => [
                            'id' => strval($randomParticipant->_id),
                            'username' => $randomParticipant->username,
                            'firstname' => $randomParticipant->firstname,
                            'lastname' => $randomParticipant->lastname,
                            'mail' => $randomParticipant->mail,
                        ]
                    ];
                    array_push($participants, $participant);
                }
            }

            $event = [
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph(3),
                'date' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d\TH:i'),
                'address' => $faker->streetAddress . ", " . $faker->postcode . " " . $faker->postcode . ", " . $faker->country,
                'gps' => [
                    'lat' => $faker->latitude,
                    'lng' => $faker->longitude
                ],
                'organizer' => [
                    'id' => strval($randomUser->_id),
                    'username' => $randomUser->username,
                    'firstname' => $randomUser->firstname,
                    'lastname' => $randomUser->lastname,
                    'email' => $randomUser->mail,
                ],
                'icon' => '🎂',
                'isPrivate' => true,
                'participants' => $participants,
            ];

            try {
                $result = $db->insertOne($event);

                // Invitations
                if ($result->getInsertedCount() == 1) {
                    $db = $client->selectDatabase("reunionou")->selectCollection("invitation");

                    foreach ($participants as $participant) {
                        $invitation = [
                            'event_id' => strval($result->getInsertedId()),
                            'event_title' => $event['title'],
                            'accepted' => $participant['status'] == 'confirmed',
                            'user' => [
                                'id' => strval($participant['user']['id']),
                                'username' => $participant['user']['username'],
                                'firstname' => $participant['user']['firstname'],
                                'lastname' => $participant['user']['lastname'],
                                'mail' => $participant['user']['mail'],
                            ],
                            'organizer' => [
                                'id' => strval($event['organizer']['id']),
                                'username' => $event['organizer']['username'],
                                'firstname' => $event['organizer']['firstname'],
                                'lastname' => $event['organizer']['lastname'],
                                'email' => $event['organizer']['email'],
                            ],
                        ];

                        $db->insertOne($invitation);
                    }
                }

                // Comments
                $client = new \MongoDB\Client($this->container->get('comment_url'));
                $db = $client->selectDatabase("reunionou")->selectCollection("comment");

                $commentCount = rand(0, 10);

                for ($i = 0; $i < $commentCount; $i++) {
                    $randomParticipant = $participants[rand(0, count($participants) - 1)];
                    $comment = [
                        'event_id' => strval($result->getInsertedId()),
                        'member_id' => strval($randomParticipant['user']['id']),
                        'content' => $faker->paragraph(1),
                        'firstname' => $randomParticipant['user']['firstname'],
                        'lastname' => $randomParticipant['user']['lastname'],
                    ];

                    $db->insertOne($comment);
                }
            } catch (\Throwable $th) {
            }
        }
    }
}
