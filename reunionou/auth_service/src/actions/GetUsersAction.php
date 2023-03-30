<?php

namespace reunionou\auth\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\auth\services\DbService;
use Respect\Validation\Validator as v;

final class GetUsersAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $search = $req->getQueryParams();
        if (!isset($search['search'])) {
            //all users
            $db_service = new DbService($this->container->get('mongo_url'));
            $users = $db_service->getUsers();
        } else {
            if (!v::stringVal()->validate($search['search'])) {
                return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
            }
            //search users
            $db_service = new DbService($this->container->get('mongo_url'));
            $users = $db_service->findUser($search['search']);
        }


        foreach ($users as $key => $user) {
            unset($users[$key]['acces_token']);
            unset($users[$key]['password']);
            unset($users[$key]['refresh_token']);
            unset($users[$key]['friends']);
        }
        $data = [
            'type' => 'collection',
            "count" => count($users),
            'users' => $users
        ];

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');
        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
