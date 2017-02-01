<?php
$routes = [
    'getTransitDistanceMatrix',
    'getDrivingDistanceMatrix',
    'getWalkingDistanceMatrix',
    'getBicyclingDistanceMatrix',
    'metadata'
];
foreach($routes as $file) {
    require __DIR__ . '/../src/routes/'.$file.'.php';
}

