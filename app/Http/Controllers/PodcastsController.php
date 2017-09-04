<?php

namespace App\Http\Controller;

use App\Podcast;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PodcastsController extends Controller
{
    public function index()
    {
        $podcasts = Podcast::published()->paginate();

        return view('podcasts.index', [
            'podcasts' => $podcasts
        ]);
    }

    public function create()
    {
        return view('podcasts.create');
    }

    public function store()
    {
        request()->validate([
            'title' => ['required', 'max:150'],
            'description' => ['max:500'],
            'website' => ['url'],
        ]);

        $podcast = Auth::user()->podcasts()->create(request([
            'title',
            'description',
            'website'
        ]));

        return redirect("/podcasts/{$podcasts->id}");
    }

    public function show($id)
    {
        $podcast = Podcast::findOrFail($id);

        abort_unless($podcast->isVisibleTo(Auth::user()), 404);

        return view('podcasts.show', [
            'podcast' => $podcast,
            'episodes' => $podcast->recentEpisodes(5)
        ]);
    }

    public function edit($id)
    {
        $podcast = Auth::user()->podcasts()->findOrFail($id);

        return view('podcasts.edit', [
            'podcasts' => $podcasts
        ]);
    }

    public function update($id)
    {
        $podcast = Auth::user()->podcasts()->findOrFail($id);

        request()->validate([
            'title' => ['required', 'max:150'],
            'description' => ['max:500'],
            'website' => ['url'],
        ]);

        $podcast->update(request([
            'title',
            'description',
            'website',
        ]));

        return redirect("/podcasts/{$podcast->id}");
    }

    public function destroy($id)
    {
        $podcast = Auth::user()->podcasts()->findOrFail($id);

        $podcast->delete();

        return redirect("/podcasts");
    }
}
