<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
    public $timestamps = false;

    /*
     * Creates source row in news sources table if source doesn't exists.
     * If already exists just returns id.
     *
     * Returns
     *  Source id.
     */
    static public function createIfDoesntExists($source) {
        $s = self::where('source', $source);
        if ($s->exists()) {
            return $s->value('id');
        }

        $ns = new NewsSource();
        $ns->source = $source;
        $ns->save();
        return $ns->value('id');
    }

    /*
     * Gets source text by source id.
     *
     * Returns
     *  false if source not found
     *  source text if success
     */
    static public function getSource($sourceId) {
        $s = self::where('id', $sourceId);
        if ($s->exists() == false)
            return false;

        return $s->value('source');
    }

}
