<?php
$app->post('/api/GoogleMapsDistanceMatrix/getTransitDistanceMatrix', function($request, $response, $args){
    $settings = $this->settings;

    //checking properly formed json
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey','origins', 'destinations']);
    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    //forming request to vendor API
    $query['key'] = $post_data['args']['apiKey'];
    $query['mode'] = 'transit';
    $query['origins'] = implode('|',$post_data['args']['origins']);
    $query['destinations'] = implode('|',$post_data['args']['destinations']);

    if(!empty($post_data['args']['language'])) {
        $query['language'] = $post_data['args']['language'];
    };
    if(!empty($post_data['args']['units'])) {
        $query['units'] = $post_data['args']['units'];
    };
    if(!empty($post_data['args']['transitMode'])) {
        $query['transitMode'] = $post_data['args']['transitMode'];
    };
    if(!empty($post_data['args']['transitRoutingPreference'])) {
        $query['transitRoutingPreference'] = $post_data['args']['transitRoutingPreference'];
    };
    if(!empty($post_data['args']['timeRestriction'])) {
        $restriction = explode("=", $post_data['args']['timeRestriction']);
        if($restriction[0] == "departureTime") {
            $query['departureTime'] = $restriction[1];
        } elseif ($restriction[0] == "arrivalTime"){
            $query['arrivalTime'] = $restriction[1];
        }
    };


    $query_str = $settings['api_url'];

    //requesting remote API
    $client = $this->httpClient;
    try {

        $resp = $client->get( $query_str,
            [
                'query' => $query,
                'verify' => false
            ]);
        $responseBody = $resp->getBody()->getContents();
        $rawBody = json_decode($resp->getBody());

        $all_data[] = $rawBody;


        if(!empty(json_decode($responseBody)->rows) && json_decode($responseBody)->status == 'OK') {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = is_array($all_data) ? $all_data : json_decode($all_data);
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to']['status_code'] = 'API_ERROR';
            $result['contextWrites']['to']['status_msg'] = is_array($responseBody) ? $responseBody : json_decode($responseBody);
        }

    } catch (\GuzzleHttp\Exception\ClientException $exception) {

        $responseBody = $exception->getResponse()->getBody();
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = json_decode($responseBody);

    } catch (GuzzleHttp\Exception\ServerException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = json_decode($responseBody);

    } catch (GuzzleHttp\Exception\BadResponseException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = json_decode($responseBody);

    }


    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);

});