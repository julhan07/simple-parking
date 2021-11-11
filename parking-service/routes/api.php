<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/booking/check-availability', 'bookingController@get_check_availability');
$router->post('/booking/store', 'bookingController@store');
$router->post('/booking/calculate-price', 'bookingController@get_calculate_price');
$router->put('/booking/{id}/pay', 'bookingController@update_pay');

$router->get('/', function () use ($router) {
    return "Welcome to Simple Parking Booking System";
});