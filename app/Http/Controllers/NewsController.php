<?php

namespace App\Http\Controllers;

use App\News;

class NewsController extends Controller
{
    /*
     * Checks that news theme match theme GET param.
     *
     * Arguments
     *  n: news object
     */
    private function isNewsThemeMatch($n) {
        if (isset($_GET['theme']) == false)
            return true;

        $theme = $_GET['theme'];
        return stristr($n['title'], $theme) != '';
    }

    /*
     * Checks that news source match source GET param.
     *
     * Arguments
     *  n: news object
     */
    private function isNewsSourceMatch($n) {
        if (isset($_GET['source']) == false)
            return true;

        $source = $_GET['source'];
        return strcasecmp($source, $n['source']) == 0;
    }

    /*
     * Checks that news published time later than specified time param.
     *
     * Arguments
     *  n: news object
     */
    private function isNewsTimeMatch($n) {
        if (isset($_GET['from']) == false)
            return true;

        $fromTime = strtotime($_GET['from']);
        $publishedTime = strtotime($n['publishedAt']);
        return $publishedTime > $fromTime;
    }

    /*
     * Checks that news match filter.
     *
     * Arguments
     *  n: news object
     */
    private function isNewsMatchFilter($n) {
        return (
            $this->isNewsThemeMatch($n) &&
            $this->isNewsTimeMatch($n) &&
            $this->isNewsSourceMatch($n)
        );
    }

    /* Gets all news as JSON array. */
    public function get()
    {
        $news = News::getAll();

        // filter news with get params
        $filteredNews = [];
        foreach($news as $n) {
            if ($this->isNewsMatchFilter($n)) {
                array_push($filteredNews, $n);
            }
        }

        return json_encode($filteredNews);
    }

}
