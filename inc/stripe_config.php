<?php
require __DIR__ . '/../vendor/autoload.php';

\Stripe\Stripe::setApiKey('<secret key>'); // your secret key
define('STRIPE_PUBLISHABLE_KEY', '<publishable key>'); // publishable key for frontend

define('SITE_URL', 'https://uncohesively-polyspermia-nan.ngrok-free.dev/'); // use the new ngrok URL


?>
