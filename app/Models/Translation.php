<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Translation extends Model
{
    use HasFactory;
     // for uuid
    public $incrementing=false;

    protected static function boot()
    {
        parent::boot();

        // Clear cache on create, update, and delete
        static::created(function ($translation) {
            self::clearCache($translation->lang);
        });

        static::updated(function ($translation) {
            self::clearCache($translation->lang);
        });

        static::deleted(function ($translation) {
            self::clearCache($translation->lang);
        });
    }

    protected static function clearCache($lang)
    {
        // Clear the cache for the specific language
        Cache::forget("translations_{$lang}");
    }
}
