<?php
require __DIR__ . '/../vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_YOUR_STRIPE_SECRET_KEY_HERE'); // your secret key
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_YOUR_STRIPE_PUBLISHABLE_KEY_HERE'); // publishable key for frontend

define('SITE_URL', 'https://uncohesively-polyspermia-nan.ngrok-free.dev/'); // use the new ngrok URL


?>
