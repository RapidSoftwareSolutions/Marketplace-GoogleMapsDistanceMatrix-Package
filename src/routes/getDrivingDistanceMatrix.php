<?php
$app->post('/api/GoogleMapsDistanceMatrix/getDrivingDistanceMatrix', function ($request, $response, $args) {

    $languageArr = [
        "Afrikaans" => "af",
        "Amharic" => "am",
        "Arabic" => "ar",
        "Basque" => "eu",
        "Bengali" => "bn",
        "Bulgarian" => "bg",
        "Catalan" => "ca",
        "Chinese (Hong Kong)" => "zh-HK",
        "Chinese (Simplified)" => "zh-CN",
        "Chinese (Traditional)" => "Chinese (Traditional)",
        "Croatian" => "hr",
        "Czech" => "cs",
        "Danish" => "da",
        "Dutch" => "nl",
        "English (UK)" => "en-GB",
        "English (US)" => "	en-US",
        "Estonian" => "et",
        "Filipino" => "fil",
        "Finnish" => "fi",
        "French" => "fr",
        "French (Canadian)" => "fr-CA",
        "Galician" => "","gl",
        "German" => "de",
        "Greek" => "el",
        "Gujarati" => "gu",
        "Hebrew" => "iw",
        "Hindi" => "hi" ,
        "Hungarian" => "hu",
        "Icelandic" => "is",
        "Indonesian" => "id",
        "Italian" => "it",
        "Japanese" => "ja",
        "Kannada" => "kn",
        "Korean" => "ko",
        "Latvian" => "lv",
        "Lithuanian" => "lt",
        "Malay" => "ms",
        "Malayalam" => "ml",
        "Marathi" => "mr",
        "Persian" => "fa",
        "Polish" => "pl",
        "Portuguese (Brazil)" => "pt-BR",
        "Portuguese (Portugal)" => "pt-PT",
        "Romanian" => "ro",
        "Russian" => "ru",
        "Serbian" => "sr",
        "Slovak" => "sk",
        "Slovenian" => "sl",
        "Spanish" => "es",
        "Spanish (Latin America)" => "es-419",
        "Swahili" => "sw",
        "Swedish" => "sv" ,
        "Tamil" => "ta",
        "Telugu" => "te",
        "Thai" => "th",
        "Turkish" => "tr",
        "Ukrainian" => "uk",
        "Urdu" => "ur",
        "Vietnamese" => "vi",
        "Zulu" => "zu",
        "Tagalog" => "tl",
        "Portuguese" => "pt",
        "Norwegian" => "no",
        "English" => "en",
        "Farsi" => "fa"];

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
    $query['origins'] = implode('|',$post_data['args']['origins']);
    $query['destinations'] = implode('|',$post_data['args']['destinations']);
    if (!empty($post_data['args']['language']) && strlen($post_data['args']['language']) > 0) {
        $query['language'] = $languageArr[$post_data['args']['language']];
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