<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Region;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function __construct()
    {
        abort_unless(auth()->user()->isAdmin(), 403);
    }

    public function index(Request $request)
    {
        $query = Driver::with('region')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('license_number', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $drivers = $query->paginate(15)->withQueryString();
        $regions = Region::orderBy('name')->get();

        return view('drivers.index', compact('drivers', 'regions'));
    }

    public function create()
    {
        $regions = Region::orderBy('name')->get();
        return view('drivers.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:150'],
            'license_number' => ['required', 'string', 'max:30', 'unique:drivers,license_number'],
            'license_expiry' => ['required', 'date', 'after:today'],
            'phone'          => ['required', 'string', 'max:20'],
            'status'         => ['required', 'in:available,on_duty,off'],
            'region_id'      => ['required', 'exists:regions,id'],
        ]);

        Driver::create($data);

        return redirect()->route('drivers.index')
            ->with('success', 'Driver ' . $data['name'] . ' berhasil ditambahkan.');
    }

    public function show(Driver $driver)
    {
        $driver->load('region');
        $bookings = $driver->bookings()
            ->with(['requester', 'vehicle'])
            ->latest()
            ->limit(10)
            ->get();

        return view('drivers.show', compact('driver', 'bookings'));
    }

    public function edit(Driver $driver)
    {
        $regions = Region::orderBy('name')->get();
        return view('drivers.edit', compact('driver', 'regions'));
    }

    public function update(Request $request, Driver $driver)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:150'],
            'license_number' => ['required', 'string', 'max:30', 'unique:drivers,license_number,' . $driver->id],
            'license_expiry' => ['required', 'date'],
            'phone'          => ['required', 'string', 'max:20'],
            'status'         => ['required', 'in:available,on_duty,off'],
            'region_id'      => ['required', 'exists:regions,id'],
        ]);

        $driver->update($data);

        return redirect()->route('drivers.index')
            ->with('success', 'Data driver ' . $driver->name . ' berhasil diperbarui.');
    }

    public function destroy(Driver $driver)
    {
        if ($driver->bookings()->whereIn('status', ['pending_l1', 'pending_l2', 'approved'])->exists()) {
            return back()->with('error', 'Driver tidak dapat dihapus karena masih memiliki pemesanan aktif.');
        }

        $name = $driver->name;
        $driver->delete();

        return redirect()->route('drivers.index')
            ->with('success', 'Driver ' . $name . ' berhasil dihapus.');
    }
}
