<?php


$router->get('/', function () {
    return "";
});

// Api to manually parse data
$router->get('/parse', "ParseController@parse");

// Api to get news
$router->get('/get', "NewsController@get");
