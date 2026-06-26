<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\ArtworkGroup;
use App\Models\Tag;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtworkController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage artworks')->except(['index', 'show']);
        $this->middleware('permission:view artworks')->only(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Artwork::with(['artist', 'groups', 'tags']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('art_type', 'like', "%{$search}%")
                  ->orWhereHas('artist', fn ($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        if ($type = $request->input('type')) {
            $query->where('art_type', $type);
        }

        if ($tagIds = $request->input('tags')) {
            $query->whereHas('tags', fn ($q) => $q->whereIn('id', $tagIds));
        }

        $artworks = $query->latest()->paginate(12)->withQueryString();
        $types    = Artwork::TYPES;
        $allTags  = Tag::withCount('artworks')->orderBy('name')->get();
        $featured = Artwork::with('artist')->whereNotNull('image_path')->latest()->take(6)->get();

        return view('artworks.index', compact('artworks', 'types', 'allTags', 'featured'));
    }

    public function create()
    {
        $artists        = Artist::orderBy('name')->get();
        $groups         = ArtworkGroup::orderBy('name')->get();
        $types          = Artwork::TYPES;
        $allTags        = Tag::orderBy('name')->get();
        $selectedGroups = [];
        $selectedTags   = [];
        $artwork        = new Artwork();

        return view('artworks.create', compact('artists', 'groups', 'types', 'allTags', 'selectedGroups', 'selectedTags', 'artwork'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'artist_id'    => ['required', 'exists:artists,id'],
            'title'        => ['required', 'string', 'max:255', 'unique:artworks,title'],
            'year_created' => ['required', 'integer', 'min:1000', 'max:' . date('Y')],
            'art_type'     => ['required', 'string', 'in:' . implode(',', Artwork::TYPES)],
            'price'        => ['required', 'numeric', 'min:0'],
            'description'  => ['nullable', 'string'],
            'groups'       => ['nullable', 'array'],
            'groups.*'     => ['exists:artwork_groups,id'],
            'tags'         => ['nullable', 'array'],
            'tags.*'       => ['exists:tags,id'],
            'image'        => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('artworks', 'public');
        }

        $artwork = Artwork::create($data);
        $artwork->groups()->sync($data['groups'] ?? []);
        $artwork->tags()->sync($data['tags'] ?? []);

        ActivityLogger::log('create', "Created artwork: {$artwork->title}", 'Artwork', $artwork);

        return redirect()->route('artworks.index')
            ->with('success', "Artwork \"{$artwork->title}\" has been created.");
    }

    public function show(Artwork $artwork)
    {
        $artwork->load('artist', 'groups', 'tags');
        return view('artworks.show', compact('artwork'));
    }

    public function edit(Artwork $artwork)
    {
        $artists        = Artist::orderBy('name')->get();
        $groups         = ArtworkGroup::orderBy('name')->get();
        $types          = Artwork::TYPES;
        $allTags        = Tag::orderBy('name')->get();
        $selectedGroups = $artwork->groups->pluck('id')->toArray();
        $selectedTags   = $artwork->tags->pluck('id')->toArray();

        return view('artworks.edit', compact('artwork', 'artists', 'groups', 'types', 'allTags', 'selectedGroups', 'selectedTags'));
    }

    public function update(Request $request, Artwork $artwork)
    {
        $data = $request->validate([
            'artist_id'    => ['required', 'exists:artists,id'],
            'title'        => ['required', 'string', 'max:255', 'unique:artworks,title,' . $artwork->id],
            'year_created' => ['required', 'integer', 'min:1000', 'max:' . date('Y')],
            'art_type'     => ['required', 'string', 'in:' . implode(',', Artwork::TYPES)],
            'price'        => ['required', 'numeric', 'min:0'],
            'description'  => ['nullable', 'string'],
            'groups'       => ['nullable', 'array'],
            'groups.*'     => ['exists:artwork_groups,id'],
            'tags'         => ['nullable', 'array'],
            'tags.*'       => ['exists:tags,id'],
            'image'        => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($artwork->image_path) {
                Storage::disk('public')->delete($artwork->image_path);
            }
            $data['image_path'] = $request->file('image')->store('artworks', 'public');
        }

        if ($request->boolean('remove_image') && $artwork->image_path) {
            Storage::disk('public')->delete($artwork->image_path);
            $data['image_path'] = null;
        }

        $artwork->update($data);
        $artwork->groups()->sync($data['groups'] ?? []);
        $artwork->tags()->sync($data['tags'] ?? []);

        ActivityLogger::log('update', "Updated artwork: {$artwork->title}", 'Artwork', $artwork);

        return redirect()->route('artworks.index')
            ->with('success', "Artwork \"{$artwork->title}\" has been updated.");
    }

    public function destroy(Artwork $artwork)
    {
        $title = $artwork->title;

        if ($artwork->image_path) {
            Storage::disk('public')->delete($artwork->image_path);
        }

        $artwork->delete();

        ActivityLogger::log('delete', "Deleted artwork: {$title}", 'Artwork', $artwork);

        return redirect()->route('artworks.index')
            ->with('success', "Artwork \"{$title}\" has been deleted.");
    }
}
