<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $guarded = [];
    protected $casts = [ 'published_at' => 'datetime' ];

    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function isVisibleTo($user)
    {
        return ($this->podcast->isPublished() && $this->isPublished)
            || $this->podcast->isVisibleTo($user);
    }

    public function isEditableBy($user)
    {
        return $this->podcast->isOwnedBy($user);
    }

    public function isPublished()
    {
        return $this->published_at !== null;
    }
}
