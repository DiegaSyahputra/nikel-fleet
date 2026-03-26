<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function __construct()
    {
        abort_unless(auth()->user()->isAdmin(), 403);
    }

    public function index(Request $request)
    {
        $query = Region::withCount(['users', 'vehicles', 'drivers'])->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $regions = $query->paginate(15)->withQueryString();
        return view('regions.index', compact('regions'));
    }

    public function create()
    {
        return view('regions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:100', 'unique:regions,name'],
            'type'    => ['required', 'in:head_office,branch,mine'],
            'address' => ['nullable', 'string'],
        ]);

        Region::create($data);

        return redirect()->route('regions.index')
            ->with('success', 'Region "' . $data['name'] . '" berhasil ditambahkan.');
    }

    public function show(Region $region)
    {
        $region->loadCount(['users', 'vehicles', 'drivers']);
        $region->load(['users', 'vehicles' => fn($q) => $q->latest()->limit(5), 'drivers' => fn($q) => $q->latest()->limit(5)]);
        return view('regions.show', compact('region'));
    }

    public function edit(Region $region)
    {
        return view('regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:100', 'unique:regions,name,' . $region->id],
            'type'    => ['required', 'in:head_office,branch,mine'],
            'address' => ['nullable', 'string'],
        ]);

        $region->update($data);

        return redirect()->route('regions.index')
            ->with('success', 'Region "' . $region->name . '" berhasil diperbarui.');
    }

    public function destroy(Region $region)
    {
        $counts = $region->loadCount(['users', 'vehicles', 'drivers']);

        if ($counts->users_count > 0 || $counts->vehicles_count > 0 || $counts->drivers_count > 0) {
            return back()->with('error', 'Region tidak dapat dihapus karena masih memiliki data user, kendaraan, atau driver.');
        }

        $name = $region->name;
        $region->delete();

        return redirect()->route('regions.index')
            ->with('success', 'Region "' . $name . '" berhasil dihapus.');
    }
}
