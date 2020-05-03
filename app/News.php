<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\NewsSource;

class News extends Model
{

    /*
     * Gets all news from database.
     */
    static public function getAll()
    {
        $news = self::all();

        $newsList = [];

        foreach ($news as $n) {
            $source = NewsSource::getSource($n->source_id);
            if ($source == false)
                $source = "";

            array_push($newsList, [
                'source' => $source,
                'author' => $n->author,
                'title' => $n->title,
                'description' => $n->description,
                'content' => $n->content,
                'publishedAt' => $n->published_at,
                'url' => $n->url,
                'urlToImage' => $n->url_to_image,
            ]);
        }

        return $newsList;
    }

    /*
     * Adds news to database.
     *
     * Arguments
     *  news: news object.
     */
    static public function addNews($news)
    {
        $n = new News();

        $newsSource = $news['source']['name'];
        $sourceId = NewsSource::createIfDoesntExists($newsSource);
        $n->source_id = $sourceId;

        $n->author = $news['author'];
        if ($n->author == null) $n->author = "";

        $n->title = $news['title'];
        if ($n->title == null) $n->title = "";

        $n->description = $news['description'];
        if ($n->description == null) $n->description = "";

        $n->content = $news['content'];
        if ($n->content == null) $n->content = "";

        $n->published_at = $news['publishedAt'];
        if ($n->published_at == null) $n->published_at = "";

        $n->url = $news['url'];
        if ($n->url == null) $n->url = "";

        $n->url_to_image = $news['urlToImage'];
        if ($n->url_to_image == null) $n->url_to_image = "";

        $n->save();
    }

    /*
     * Checks that news with specified url already exists in database.
     */
    static public function isNewsWithUrlExists($url)
    {
        return self::where('url', $url)->exists();
    }

}
