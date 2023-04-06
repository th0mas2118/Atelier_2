<?php

namespace reunionou\seed\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\seed\services\SeedingService;

final class SeedEventsAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $count = $req->getQueryParams()['count'] ?? 5;

        $seedingService = new SeedingService($this->container);
        $seedingService->seedEvents($count);

        $rs->withStatus(204);
        return $rs;
    }
}
