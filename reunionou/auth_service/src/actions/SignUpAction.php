<?php

namespace reunionou\auth\actions;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\auth\errors\exceptions\HttpAlreadyExists;
use reunionou\auth\services\DbService;
use reunionou\auth\errors\exceptions\HttpInputNotValid;
use Slim\Exception\HttpInternalServerErrorException;

/** 
 * @OA\Post(
 *   path="/signup",
 *   tags={"Authentification"},
 *   @OA\RequestBody(
 *     description="Informations de l'utilisateur",
 *     required=true,
 *     @OA\JsonContent(
 *       type="object",
 *       @OA\Property(
 *         property="email",
 *         type="string",
 *         example="example@example.com"
 *         ),
 *         @OA\Property(
 *           property="username",
 *           type="string",
 *           example="JohnDoe"
 *           ),
 *           @OA\Property(
 *             property="firstname",
 *             type="string",
 *             example="John"
 *             ),
 *             @OA\Property(
 *               property="lastname",
 *               type="string",
 *               example="Doe"
 *               ),
 *               @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 example="password"
 *                 )
 *                 )
 *                 ),
 *                 @OA\Response(
 *                   response="201",
 *                   description="Utilisateur créé",
 *                  ),
 *                  @OA\Response(
 *                    response="400",
 *                    description="Les données envoyées ne sont pas valides",
 *                    @OA\JsonContent(
 *                      type="object",
 *                      @OA\Property(
 *                        property="type",
 *                        type="string",
 *                        example="error"
 *                        ),
 *                        @OA\Property(
 *                          property="message",
 *                          type="string",
 *                          example="Les données envoyées ne sont pas valides"
 *                          ),
 *                          @OA\Property(
 *                            property="code",
 *                            type="integer",
 *                            example="400"
 *                            )
 *                            )
 *                            ),
 *                            @OA\Response(
 *                              response="409",
 *                              description="L'utilisateur existe déjà",
 *                              @OA\JsonContent(
 *                                type="object",
 *                                @OA\Property(
 *                                  property="type",
 *                                  type="string",
 *                                  example="error"
 *                                  ),
 *                                  @OA\Property(
 *                                    property="message",
 *                                    type="string",
 *                                    example="L'utilisateur existe déjà"
 *                                    ),
 *                                    @OA\Property(
 *                                      property="code",
 *                                      type="integer",
 *                                      example="409"
 *                                      )
 *                                      )
 *                                      )
 *                                      )
 */
final class SignUpAction extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        if (null === $rq->getParsedBody()) {
            $body = json_decode($rq->getBody()->getContents(), true);
        } else {
            $body = $rq->getParsedBody();
        }
        if (
            (!isset($body['email'])) || !v::email()->validate($body['email']) ||
            (!isset($body['username'])) || !v::stringVal()->validate($body['username']) ||
            (!isset($body['firstname'])) || !v::stringVal()->validate($body['firstname']) ||
            (!isset($body['lastname'])) || !v::stringVal()->validate($body['lastname']) ||
            (!isset($body['password'])) || !v::stringVal()->validate($body['password'])
        ) {
            return (throw new HttpInputNotValid($rq, "Les données envoyées ne sont pas valides"));
        }

        $db_service = new DbService($this->container->get('mongo_url'));
        $body['email'] = strtolower($body['email']);

        try {
            $user = $db_service->signUp($body);
        } catch (\Exception $th) {
            if ($th->getCode() == 409) {
                return (throw new HttpAlreadyExists($rq, $th->getMessage()));
            }

            return (throw new HttpInternalServerErrorException($rq, $th->getMessage()));
        }

        $routeContext = RouteContext::fromRequest($rq);
        $routeParser = $routeContext->getRouteParser();

        $rs = $rs->withStatus(201);
        return $rs;
    }
}
