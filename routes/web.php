<?php


$router->get('/', function () {
    return "";
});

/*
 * Api to manually parse data.
 *
 * GET params:
 *  from: date from which to receive news.
 */
$router->get('/parse', "ParseController@parse");

/*
 * Api to get news
 *
 * GET params:
 *  source: news source filter.
 *  from: news published date filter.
 *  theme: theme keyword filter.
 */
$router->get('/get', "NewsController@get");
