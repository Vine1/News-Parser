<?php

namespace App\Jobs;

use App\News;

class ParseJob extends Job
{
    const API_KEY = "a085efa87fd846dbb0bd8229a2cb6097";

    const KEYWORDS = [
        'Bitcoin',
        'Litecoin',
        'Ripple',
        'Dash',
        'Ethereum'
    ];

    /*
     * Formats timestamp to YEAR-MONTH-DAY time string.
     *
     * Example
     *  2020-05-02
     */
    private function formatTime($timestamp)
    {
        return date('Y-m-d', $timestamp);
    }

    /*
     * Builds news api query params with keywords.
     */
    private function buildApiQueryParams($fromTime)
    {
        return http_build_query([
            'qInTitle' => join(" OR ", self::KEYWORDS),
            'from' => $fromTime,
            'apiKey' => self::API_KEY,
            'pageSize' => 100
        ]);
    }

    /*
     * Requests news from news api.
     */
    private function requestNews($queryParams)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://newsapi.org/v2/everything?" . $queryParams);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // disable printing result
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /*
     * Handles getting one news.
     * Adds news to database if news with same url doesn't exists.
     *
     * Returns
     *  boolean: true if news added to database.
     */
    private function handleNews($news)
    {
        if (News::isNewsWithUrlExists($news['url']) == false) {
            News::addNews($news);
            return true;
        }
        return false;
    }

    /*
     * Handles response from news api.
     */
    private function handleResponse($responseJson)
    {

        $response = json_decode($responseJson, true);

        if ($response['status'] == 'ok') {
            $newsAdded = 0;

            foreach ($response['articles'] as $article) {
                if ($this->handleNews($article)) {
                    $newsAdded++;
                }
            }

            return json_encode([
                'status' => 'ok',
                'added_news' => $newsAdded
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'code' => $response['code'],
                'message' => $response['message']
            ]);
        }
    }

    /*
     * Parses news about bitcoins, etc from news api into database.
     *
     * GET params
     *  from: published news from time, current day by default
     */
    public function parse()
    {
        if (isset($_GET['from'])) {
            $fromTimestamp = strtotime($_GET['from']);
        } else {
            $fromTimestamp = time();
        }

        $fromTime = $this->formatTime($fromTimestamp);
        $queryParams = $this->buildApiQueryParams($fromTime);

        $responseJson = $this->requestNews($queryParams);

        try {
            return $this->handleResponse($responseJson);
        } catch (Exception $e) {
            if (strstr($e->getMessage(), "Undefined index") == "") {
                throw $e;
            }

            return json_encode([
                'status' => 'error',
                'reason' => 'bad response'
            ]);
        }
    }

    public function handle()
    {
        return $this->parse();
    }
}
