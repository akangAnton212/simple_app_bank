<?php
use Carbon\Carbon;
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
$router->get('/', function() {
    return 'WELCOME';
});

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->group(['prefix' => 'api/v1/'], function () use ($router) {
        $router->group(['prefix' => 'customer'], function () use ($router) {
            $router->get('list', 'CustomerController@list');
            $router->post('add', 'CustomerController@add');
            $router->post('addCustomerAccount', 'CustomerController@addCustomerAccount');
        });

        $router->group(['prefix' => 'user'], function () use ($router) {
            $router->get('profile', 'UserController@userProfile');
        });
    });
});

$router->group(['middleware' => 'auth:customer'], function () use ($router) {
    $router->group(['prefix' => 'api/v1/'], function () use ($router) {
        $router->get('customer/info', 'AuthCustomerController@UserLogin');

        $router->group(['prefix' => 'transaction'], function () use ($router) {
            $router->get('balance', 'TransactionController@getBalanceAccount');
            $router->post('transfer', 'TransactionController@transferAccount');
            $router->get('getTransferHistoryOut/{uid}', 'TransactionController@getTransferHistoryOut');
            $router->get('getMutation/{uid}', 'TransactionController@getMutation');
            $router->get('getCheckingAccount/{account_number}/{nominal}', 'TransactionController@getCheckingAccount');
            $router->post('changePasswordAccount', 'TransactionController@changePasswordAccount');
        });
    });
    
});

$router->group(['prefix' => 'customer'], function () use ($router) {
    $router->post('login', 'AuthCustomerController@login');
});
