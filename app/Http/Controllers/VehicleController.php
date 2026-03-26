<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Region;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function __construct()
    {
        // Hanya admin yang bisa akses semua method
        abort_unless(auth()->user()->isAdmin(), 403);
    }

    public function index(Request $request)
    {
        $query = Vehicle::with('region')->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('ownership')) {
            $query->where('ownership', $request->ownership);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('license_plate', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%')
                  ->orWhere('model', 'like', '%' . $request->search . '%');
            });
        }

        $vehicles = $query->paginate(15)->withQueryString();
        $regions  = Region::orderBy('name')->get();

        return view('vehicles.index', compact('vehicles', 'regions'));
    }

    public function create()
    {
        $regions = Region::orderBy('name')->get();
        return view('vehicles.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles,license_plate'],
            'brand'         => ['required', 'string', 'max:80'],
            'model'         => ['required', 'string', 'max:80'],
            'year'          => ['nullable', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'type'          => ['required', 'in:passenger,cargo'],
            'ownership'     => ['required', 'in:owned,rented'],
            'status'        => ['required', 'in:available,in_use,maintenance'],
            'region_id'     => ['required', 'exists:regions,id'],
            'color'         => ['nullable', 'string', 'max:40'],
            'fuel_type'     => ['required', 'in:bensin,solar,listrik'],
            'notes'         => ['nullable', 'string'],
        ]);

        Vehicle::create($data);

        return redirect()->route('vehicles.index')
            ->with('success', 'Kendaraan ' . $data['license_plate'] . ' berhasil ditambahkan.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load('region');
        $bookings = $vehicle->bookings()
            ->with(['requester', 'driver'])
            ->latest()
            ->limit(10)
            ->get();

        return view('vehicles.show', compact('vehicle', 'bookings'));
    }

    public function edit(Vehicle $vehicle)
    {
        $regions = Region::orderBy('name')->get();
        return view('vehicles.edit', compact('vehicle', 'regions'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles,license_plate,' . $vehicle->id],
            'brand'         => ['required', 'string', 'max:80'],
            'model'         => ['required', 'string', 'max:80'],
            'year'          => ['nullable', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'type'          => ['required', 'in:passenger,cargo'],
            'ownership'     => ['required', 'in:owned,rented'],
            'status'        => ['required', 'in:available,in_use,maintenance'],
            'region_id'     => ['required', 'exists:regions,id'],
            'color'         => ['nullable', 'string', 'max:40'],
            'fuel_type'     => ['required', 'in:bensin,solar,listrik'],
            'notes'         => ['nullable', 'string'],
        ]);

        $vehicle->update($data);

        return redirect()->route('vehicles.index')
            ->with('success', 'Data kendaraan ' . $vehicle->license_plate . ' berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->bookings()->whereIn('status', ['pending_l1', 'pending_l2', 'approved'])->exists()) {
            return back()->with('error', 'Kendaraan tidak dapat dihapus karena masih memiliki pemesanan aktif.');
        }

        $plate = $vehicle->license_plate;
        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Kendaraan ' . $plate . ' berhasil dihapus.');
    }
}
