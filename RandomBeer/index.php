<?php

namespace RandomBeer;

use RandomBeer\Application\Beer;
use RandomBeer\Application\Model;
use RandomBeer\Config\Database;
use RandomBeer\API\API;

require_once __NAMESPACE__ . '/inc.php';

// This is our application heart, a sort of controller.
$beer_app = new Beer();
// And the database instance.
$db = new Database();
// This is our beer model (beer data access layer).
$model = new Model($db);
// Now, we can either get random beer using our API with all its constraints, ie. JSON response and HTTP headers
// or we can omit API and instead use beer Model directly - this is what we're gona do for the initial page request.
// For an AJAX call we'll use the API directly to show it also works nice.
if (isset($_GET['action']) && $_GET['action'] === 'api') {
    // we're passing model to the API (Dependency Injection)
    $api = new API($model);
    echo $api->getRandomBeer();
} else {
    $beer_app->getRandomBeer($model);
    $beer_app->render();
}


