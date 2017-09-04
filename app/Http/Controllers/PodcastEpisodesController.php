<?php

namespace App\Http\Controller;

use App\Podcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PodcastEpisodesController extends Controller
{
    public function index($id)
    {
        $podcasts = Podcast::with('episodes')->findOrFail($id);

        abort_unless($podcasts->isVisibleTo(Auth::user()), 404);

        return view('podcast-episodes.index', [
            'podcasts' => $podcasts
        ]);
    }

    public function create($id)
    {
        $podcasts = Auth::user()->podcasts()->findOrFail($id);

        return view('podcast-episodes.create' [
            'podcasts' => $podcasts
        ]);
    }

    public function store($id)
    {
        $podcasts = Auth::user()->podcasts()->findOrFail($id);

        request()->validate([
            'title' => ['required', 'max:150'],
            'description' => ['max:500'],
            'download_url' => ['required', 'url'],
        ]);

        $podcasts = $podcasts->episodes()->create(required([
            'title',
            'description',
            'download_url',
        ]));

        return redirect("/episodes/{$episodes->id}");
    }
}
