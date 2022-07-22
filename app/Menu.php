<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    public static $MENU_TYPE_HEADER = 'header';
    public static $MENU_TYPE_FOOTER = 'footer';
    public static $MENU_TYPE_SOCIAL = 'social';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'parent_id',
        'title', 'title_es',
        'url', 'url_es',
        'post_id',
        'display_order', 'menu_type'
    ];

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function parent()
    {
        return $this->belongsTo('App\Menu', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Menu', 'parent_id')->orderBy('display_order');
    }

    public function getMaxDisplayNumber()
    {
        return Menu::max('display_number');
    }

    public function getRealUrlAttribute()
    {
        return $this->url;
    }

    public function getRealUrlEsAttribute()
    {
        return $this->url_es;
    }

    public function getLocaleUrlAttribute()
    {
        $locale = app('translator')->getLocale();
        if ($locale === 'es') {
            if (!empty($this->real_url_es)) {
                return $this->real_url_es;
            }
        }
        return $this->real_url;
    }

    public function getLocaleTitleAttribute()
    {
        $locale = app('translator')->getLocale();
        if ($locale === 'es') {
            if (!empty($this->title_es)) {
                return $this->title_es;
            }
        }
        return $this->title;
    }
}
