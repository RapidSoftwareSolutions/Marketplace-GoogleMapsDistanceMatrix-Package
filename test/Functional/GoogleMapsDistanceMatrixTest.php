<?php

namespace Test\Functional;

require_once(__DIR__ . '/../../src/Models/checkRequest.php');
require_once (__DIR__.'/../../test/Functional/BaseTestCase.php');

class GoogleMapsDistanceMatrixTest extends BaseTestCase {

    public function testListMetrics() {

        $routes = [
            'getTransitDistanceMatrix',
            'getDrivingDistanceMatrix',
            'getWalkingDistanceMatrix',
            'getBicyclingDistanceMatrix',
        ];

        foreach($routes as $file) {
            $var = '{}';
            $post_data = json_decode($var, true);

            $response = $this->runApp('POST', '/api/GoogleMapsDistanceMatrix/'.$file, $post_data);



            $this->assertEquals(200, $response->getStatusCode(), 'Error in '.$file.' method');
        }
    }

}
