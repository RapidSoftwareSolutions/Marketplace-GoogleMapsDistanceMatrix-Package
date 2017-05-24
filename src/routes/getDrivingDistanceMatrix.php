<?php
$app->post('/api/GoogleMapsDistanceMatrix/getDrivingDistanceMatrix', function ($request, $response, $args) {
    $settings = $this->settings;

    //checking properly formed json
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'origins', 'destinations']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    //forming request to vendor API
    $query['key'] = $post_data['args']['apiKey'];
    $query['mode'] = 'driving';
    $query['origins'] = $post_data['args']['origins'];
    $query['destinations'] = $post_data['args']['destinations'];
    if (!empty($post_data['args']['language']) && strlen($post_data['args']['language']) > 0) {
        $query['language'] = $post_data['args']['language'];
    };
    if (!empty($post_data['args']['units']) && strlen($post_data['args']['units']) > 0) {
        $query['units'] = $post_data['args']['units'];
    };
    if (!empty($post_data['args']['departureTime']) && strlen($post_data['args']['departureTime']) > 0) {
        if (is_numeric($post_data['args']['departureTime']) || $post_data['args']['departureTime'] == 'now') {
            $query['departureTime'] = $post_data['args']['departureTime'];
        } else {
            $date = new DateTime($post_data['args']['departureTime']);
            $query['departureTime'] = $date->format('u');
        }
    };
    if (!empty($post_data['args']['departureTime']) && !empty($post_data['args']['trafficModel']) && strlen($post_data['args']['trafficModel']) > 0) {
        $query['trafficModel'] = $post_data['args']['trafficModel'];
    };
    if (!empty($post_data['args']['avoid']) && strlen($post_data['args']['avoid']) > 0) {
        $query['avoid'] = $post_data['args']['avoid'];
    };

    $query_str = $settings['api_url'];

    //requesting remote API
    $client = $this->httpClient;
    try {

        $resp = $client->get($query_str,
            [
                'query' => $query,
                'verify' => false
            ]);
        $responseBody = $resp->getBody()->getContents();
        $rawBody = json_decode($resp->getBody());

        $all_data[] = $rawBody;


        if (!empty(json_decode($responseBody)->rows) && json_decode($responseBody)->status == 'OK') {
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