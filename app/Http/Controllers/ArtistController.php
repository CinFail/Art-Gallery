<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function __construct()
    {
        // Staff and Admin can manage; Viewer can only view
        $this->middleware('permission:manage artists')->except(['index', 'show']);
        $this->middleware('permission:view artists')->only(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Artist::withCount('artworks');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('art_style', 'like', "%{$search}%")
                  ->orWhere('birthplace', 'like', "%{$search}%");
        }

        $artists = $query->latest()->paginate(10)->withQueryString();

        return view('artists.index', compact('artists'));
    }

    public function create()
    {
        return view('artists.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255', 'unique:artists,name'],
            'birthplace' => ['required', 'string', 'max:255'],
            'age'        => ['required', 'integer', 'min:1', 'max:150'],
            'art_style'  => ['required', 'string', 'max:255'],
            'bio'        => ['nullable', 'string'],
        ]);

        $artist = Artist::create($data);

        ActivityLogger::log('create', "Created artist: {$artist->name}", 'Artist', $artist);

        return redirect()->route('artists.index')
            ->with('success', "Artist \"{$artist->name}\" has been created.");
    }

    public function show(Artist $artist)
    {
        $artist->load('artworks.groups');
        return view('artists.show', compact('artist'));
    }

    public function edit(Artist $artist)
    {
        return view('artists.edit', compact('artist'));
    }

    public function update(Request $request, Artist $artist)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255', 'unique:artists,name,' . $artist->id],
            'birthplace' => ['required', 'string', 'max:255'],
            'age'        => ['required', 'integer', 'min:1', 'max:150'],
            'art_style'  => ['required', 'string', 'max:255'],
            'bio'        => ['nullable', 'string'],
        ]);

        $artist->update($data);

        ActivityLogger::log('update', "Updated artist: {$artist->name}", 'Artist', $artist);

        return redirect()->route('artists.index')
            ->with('success', "Artist \"{$artist->name}\" has been updated.");
    }

    public function destroy(Artist $artist)
    {
        $name = $artist->name;
        $artist->delete();

        ActivityLogger::log('delete', "Deleted artist: {$name}", 'Artist', $artist);

        return redirect()->route('artists.index')
            ->with('success', "Artist \"{$name}\" has been deleted.");
    }
}
