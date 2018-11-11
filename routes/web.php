<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    //products API
    $router->group(['prefix' => 'products'],
        function () use ($router) {
            //add product
            $router->post('add','productsController@addProduct');

            //selected product
            $router->get('{id}','productsController@selectedProduct');

            //delete selected product
            $router->delete('{id}','productsController@deleteSelectedProduct');

            //edit product
            $router->put('{id}','productsController@editProduct');

            //product search
            $router->post('search','productsController@productSearch');
        });

    //products API
    $router->group(['prefix' => 'discounts'],
        function () use ($router) {
            //add product
            $router->post('add','discountController@addDisocunt');

            //delete selected product
            $router->delete('{id}','discountController@deleteDiscount');

            //edit discount
            $router->put('{id}','discountController@editDiscount');
        });

    //cart API
    $router->group(['prefix' => 'cart'],
        function () use ($router) {
            //add product
            $router->get('add/{product_id}','orderController@addToCart');

            //delete customer from cart
            $router->delete('{id}','orderController@deleteCart');

            //remove item from cart
            $router->delete('product/{id}','orderController@removeProductFromOrder');

            //order information
            $router->get('/','orderController@customerCart');
        });
});
