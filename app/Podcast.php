<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Podcast extends Model
{
    protected $guarded = [];
    protected $casts = [ 'published_at' => 'datetime' ];

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

    public function recentEpisodes($count = 5)
    {
        return $this->episodes()->recent()->take($count)->get();
    }

    public function isVisibleTo($user)
    {
        return $this->isPublished() || $this->isOwnedBy($user);
    }

    public function isPublished()
    {
        return $this->published_at !== null;
    }

    public function isOwnedBy($user)
    {
        return $this->user_id == $user->getKey();
    }
}
