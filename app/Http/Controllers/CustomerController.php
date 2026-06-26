<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\ArtworkGroup;
use App\Models\Customer;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage customers')->except(['index', 'show']);
        $this->middleware('permission:view customers')->only(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Customer::withCount(['preferredArtists', 'preferredGroups']);

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $artists = Artist::orderBy('name')->get();
        $groups  = ArtworkGroup::orderBy('name')->get();

        return view('customers.create', compact('artists', 'groups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:255', 'unique:customers,name'],
            'address'           => ['required', 'string'],
            'total_spent'       => ['nullable', 'numeric', 'min:0'],
            'preferred_artists' => ['nullable', 'array'],
            'preferred_artists.*' => ['exists:artists,id'],
            'preferred_groups'  => ['nullable', 'array'],
            'preferred_groups.*'=> ['exists:artwork_groups,id'],
        ]);

        $customer = Customer::create([
            'name'        => $data['name'],
            'address'     => $data['address'],
            'total_spent' => $data['total_spent'] ?? 0,
        ]);

        $customer->preferredArtists()->sync($data['preferred_artists'] ?? []);
        $customer->preferredGroups()->sync($data['preferred_groups'] ?? []);

        ActivityLogger::log('create', "Created customer: {$customer->name}", 'Customer', $customer);

        return redirect()->route('customers.index')
            ->with('success', "Customer \"{$customer->name}\" has been created.");
    }

    public function show(Customer $customer)
    {
        $customer->load('preferredArtists', 'preferredGroups');
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $artists          = Artist::orderBy('name')->get();
        $groups           = ArtworkGroup::orderBy('name')->get();
        $selectedArtists  = $customer->preferredArtists->pluck('id')->toArray();
        $selectedGroups   = $customer->preferredGroups->pluck('id')->toArray();

        return view('customers.edit', compact('customer', 'artists', 'groups', 'selectedArtists', 'selectedGroups'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:255', 'unique:customers,name,' . $customer->id],
            'address'           => ['required', 'string'],
            'total_spent'       => ['nullable', 'numeric', 'min:0'],
            'preferred_artists' => ['nullable', 'array'],
            'preferred_artists.*' => ['exists:artists,id'],
            'preferred_groups'  => ['nullable', 'array'],
            'preferred_groups.*'=> ['exists:artwork_groups,id'],
        ]);

        $customer->update([
            'name'        => $data['name'],
            'address'     => $data['address'],
            'total_spent' => $data['total_spent'] ?? $customer->total_spent,
        ]);

        $customer->preferredArtists()->sync($data['preferred_artists'] ?? []);
        $customer->preferredGroups()->sync($data['preferred_groups'] ?? []);

        ActivityLogger::log('update', "Updated customer: {$customer->name}", 'Customer', $customer);

        return redirect()->route('customers.index')
            ->with('success', "Customer \"{$customer->name}\" has been updated.");
    }

    public function destroy(Customer $customer)
    {
        $name = $customer->name;
        $customer->delete();

        ActivityLogger::log('delete', "Deleted customer: {$name}", 'Customer', $customer);

        return redirect()->route('customers.index')
            ->with('success', "Customer \"{$name}\" has been deleted.");
    }
}
