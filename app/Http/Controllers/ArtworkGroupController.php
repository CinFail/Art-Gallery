<?php

namespace App\Http\Controllers;

use App\Models\ArtworkGroup;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class ArtworkGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage groups')->except(['index', 'show']);
        $this->middleware('permission:view groups')->only(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = ArtworkGroup::withCount('artworks');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $groups = $query->latest()->paginate(10)->withQueryString();

        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:artwork_groups,name'],
            'description' => ['nullable', 'string'],
        ]);

        $group = ArtworkGroup::create($data);

        ActivityLogger::log('create', "Created artwork group: {$group->name}", 'ArtworkGroup', $group);

        return redirect()->route('groups.index')
            ->with('success', "Group \"{$group->name}\" has been created.");
    }

    public function show(ArtworkGroup $group)
    {
        $group->load('artworks.artist');
        return view('groups.show', compact('group'));
    }

    public function edit(ArtworkGroup $group)
    {
        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, ArtworkGroup $group)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:artwork_groups,name,' . $group->id],
            'description' => ['nullable', 'string'],
        ]);

        $group->update($data);

        ActivityLogger::log('update', "Updated artwork group: {$group->name}", 'ArtworkGroup', $group);

        return redirect()->route('groups.index')
            ->with('success', "Group \"{$group->name}\" has been updated.");
    }

    public function destroy(ArtworkGroup $group)
    {
        $name = $group->name;
        $group->delete();

        ActivityLogger::log('delete', "Deleted artwork group: {$name}", 'ArtworkGroup', $group);

        return redirect()->route('groups.index')
            ->with('success', "Group \"{$name}\" has been deleted.");
    }
}
