<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage artworks');
    }

    public function index()
    {
        $tags = Tag::withCount('artworks')->orderBy('name')->get();
        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        return view('tags.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255', 'unique:tags,name'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        Tag::create($data);

        return redirect()->route('tags.index')
            ->with('success', "Tag \"{$data['name']}\" has been created.");
    }

    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255', 'unique:tags,name,' . $tag->id],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $tag->update($data);

        return redirect()->route('tags.index')
            ->with('success', "Tag \"{$tag->name}\" has been updated.");
    }

    public function destroy(Tag $tag)
    {
        $name = $tag->name;
        $tag->delete();

        return redirect()->route('tags.index')
            ->with('success', "Tag \"{$name}\" has been deleted.");
    }
}
