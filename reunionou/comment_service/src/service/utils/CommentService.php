<?php

namespace reunionou\src\service\utils;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use reunionou\backoffice\app\utils\Writer;
use renionou\src\models\Comment;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client as Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use orders\errors\exceptions\OrderExceptionNotFound;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentService
{

    /**
     * 
     * @api {GET} /messages/{id} Get Message
     * @apiName GetMessageById
     * @apiGroup Message
     * @apiVersion  1.0.0
     * 
     * @apiDescription Récupérer un message par son ID.
     * 
     * @apiParam  {String} id ID du message
     * 
     * @apiSuccess (Success (200)) {String} id ID du message
     * @apiSuccess (Success (200)) {String} content Contenu du message 
     * @apiSuccess (Success (200)) {String} member_id ID du membre ayant créé le message
     * @apiSuccess (Success (200)) {String} event_id ID de l'event associé au message
     * @apiSuccess (Success (200)) {JSON} media Media contenu dans le message
     * @apiSuccess (Success (200)) {date} created_at Date de création du message
     * @apiSuccess (Success (200)) {date} updated_at Date de la dernière modification du message
     * 
     * @apiSuccessExample Success-Response:
     * {
     * "type": "ressource",
     * "message": {
     *     "id": "2ade231a-11e5-49b6-bfbc-ac82cb406a7f",
     *     "content": "ceci est un message",
     *     "member_id": "b1858803-2305-47f4-be67-1efc10a91da7",
     *     "event_id": "e04cc94c-77a7-4671-8e52-34eb1d781d57",
     *     "media": "{&quot;name&quot;:&quot;Charleville&quot;,&quot;latitude&quot;:64.9483319,&quot;longitude&quot;:-145.3501717}",
     *     "updated_at": "28-03-2022 08:29:40",
     *     "created_at": "28-03-2022 08:29:40"
     *            }
     * }
     */
    public function getOrders(?string $client=null, ?string $sort=null): Array
    {

        $query = Comment::select('id', 'content', 'media');

        try {

            return $query->get()->toArray();

        }catch (ModelNotFoundException $e) {
            throw new OrderExceptionNotFound("orders not found");
        }
    }
    // public function getMessage(Request $req, Response $resp, $args): Response {

    //     try {
    //     $client = new \GuzzleHttp\Client([
    //         'base_uri' => $this->container->get('settings')['events_service'],
    //         'timeout' => 5.0
    //     ]);

    //     $id_message = $args['id'];
    //     $response = $client->request('GET', '/messages/' . $id_message);

    //     $resp = Writer::json_output($resp, $response->getStatusCode());
    //     $resp->getBody()->write($response->getBody());
    //     return $resp;
    // } 
    // catch (ClientException $e) { 
    //     $responseBodyAsString = $e->getResponse()->getBody()->getContents();
    //     return Writer::json_error_data($resp, 401, $responseBodyAsString);
    // } 
    // catch (ServerException $e) {
    //     $responseBodyAsString = $e->getResponse()->getBody()->getContents();
    //     return Writer::json_error_data($resp, 500, $responseBodyAsString);
    // }  

    // }


     /**
     * 
     * @api {POST} /messages Create Message
     * @apiName CreateMessage
     * @apiGroup Message
     * @apiVersion  1.0.0
     * 
     * @apiDescription Créer un message
     * 
     * @apiBody  {String} content Contenu du message 
     * @apiBody  {String} member_id ID du membre ayant créé le message
     * @apiBody  {String} event_id ID de l'event associé au message
     * @apiBody  {JSON} media Media contenu dans le message
     * 
     * @apiParamExample Request-Example:
     * {
     *   "content": "ceci est un message",
     *   "member_id": "b1858803-2305-47f4-be67-1efc10a91da7",
     *   "event_id": "e04cc94c-77a7-4671-8e52-34eb1d781d57",
     *   "media": "{\"name\":\"Charleville\",\"latitude\":64.9483319,\"longitude\":-145.3501717}"
     * }
     * 
     * @apiSuccess (Success (201)) {String} id ID du message
     * @apiSuccess (Success (201)) {String} content Contenu du message 
     * @apiSuccess (Success (201)) {String} member_id ID du membre ayant créé le message
     * @apiSuccess (Success (201)) {String} event_id ID de l'event associé au message
     * @apiSuccess (Success (201)) {JSON} media Media contenu dans le message
     * @apiSuccess (Success (201)) {date} created_at Date de création du message
     * @apiSuccess (Success (201)) {date} updated_at Date de la dernière modification du message
     * 
     * @apiSuccessExample Success-Response:
     * {
     * "type": "ressource",
     * "message": {
     *     "id": "2ade231a-11e5-49b6-bfbc-ac82cb406a7f",
     *     "content": "ceci est un message",
     *     "member_id": "b1858803-2305-47f4-be67-1efc10a91da7",
     *     "event_id": "e04cc94c-77a7-4671-8e52-34eb1d781d57",
     *     "media": "{&quot;name&quot;:&quot;Charleville&quot;,&quot;latitude&quot;:64.9483319,&quot;longitude&quot;:-145.3501717}",
     *     "updated_at": "28-03-2022 08:29:40",
     *     "created_at": "28-03-2022 08:29:40"
     *            }
     * }
     */
    public function createMessage(Request $req, Response $resp, $args): Response {

        try {
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->container->get('settings')['events_service'],
            'timeout' => 5.0
        ]);

        $received_message = $req->getParsedBody();
        if(isset($received_message['media'])) {
            $response = $client->request('POST', '/messages', [
                    'form_params'=> [
                        'content' => $received_message['content'],
                        'member_id' => $received_message['member_id'],
                        'event_id' => $received_message['event_id'],
                        'media' => $received_message['media']
                    ]]  );
        } else {
            $response = $client->request('POST', '/messages', [
                    'form_params'=> [
                        'content' => $received_message['content'],
                        'member_id' => $received_message['member_id'],
                        'event_id' => $received_message['event_id']
                    ]]  );
        }} 
        catch (ClientException $e) { 
            $responseBodyAsString = $e->getResponse()->getBody()->getContents();
            return Writer::json_error_data($resp, 401, $responseBodyAsString);
        } 
        catch (ServerException $e) {
            $responseBodyAsString = $e->getResponse()->getBody()->getContents();
            return Writer::json_error_data($resp, 500, $responseBodyAsString);
        }  


        $resp = Writer::json_output($resp, $response->getStatusCode());
        
        $resp->getBody()->write($response->getBody());
        return $resp;

    }

     /**
     * 
     * @api {DELETE} /messages/{id} Delete a Message
     * @apiName DeleteMessageById
     * @apiGroup Message
     * @apiVersion  1.0.0
     * 
     * @apiDescription Supprimer un message.
     * 
     * @apiParam  {String} id ID du message
     * 
     * @apiSuccess (Success (200)) {String} id ID du message
     * @apiSuccess (Success (200)) {String} content Contenu du message 
     * @apiSuccess (Success (200)) {String} member_id ID du membre ayant créé le message
     * @apiSuccess (Success (200)) {String} event_id ID de l'event associé au message
     * @apiSuccess (Success (200)) {JSON} media Media contenu dans le message
     * @apiSuccess (Success (200)) {date} created_at Date de création du message
     * @apiSuccess (Success (200)) {date} updated_at Date de la dernière modification du message
     * 
     * @apiSuccessExample Success-Response:
     *  {
     * "type": "message",
     * "message": {
     *     "id": "559407d8-73c1-4251-8fab-f9413a48fec6",
     *     "content": "In est risus, auctor sed, tristique in, tempus sit amet, sem. Fusce consequat. Nulla nisl.",
     *     "member_id": "1190e0fc-524f-4c32-a087-4bed56091978",
     *     "event_id": "0447ff47-e257-4bfc-b1a6-913a2c6cbd79",
     *     "media": "{\"img\":\"http://dummyimage.com/122x100.png/cc0000/ffffff\",\"link\":\"http://dummyimage.com/249x100.png/5fa2dd/ffffff\"}",
     *     "created_at": "2021-04-03T12:07:25.000000Z",
     *     "updated_at": "2021-09-16T10:10:39.000000Z"
     * },
     * "response": "message deleted"
     * }
     */
    // public function deleteMessageById(Request $req, Response $resp, $args): Response {

    //     try{
    //     $client = new \GuzzleHttp\Client([
    //         'base_uri' => $this->container->get('settings')['events_service'],
    //         'timeout' => 5.0
    //     ]);

    //     $id_message = $args['id'];
    //     $response = $client->request('DELETE', '/messages/' . $id_message);

    //     $resp = Writer::json_output($resp, $response->getStatusCode());
    //     $resp->getBody()->write($response->getBody());
    //     return $resp;
    // } 
    // catch (ClientException $e) { 
    //     $responseBodyAsString = $e->getResponse()->getBody()->getContents();
    //     return Writer::json_error_data($resp, 401, $responseBodyAsString);
    // } 
    // catch (ServerException $e) {
    //     $responseBodyAsString = $e->getResponse()->getBody()->getContents();
    //     return Writer::json_error_data($resp, 500, $responseBodyAsString);
    // }  

    // }

}