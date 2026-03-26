<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        abort_unless(auth()->user()->isAdmin(), 403);
    }

    public function index(Request $request)
    {
        $query = User::with('region')->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('position', 'like', '%' . $request->search . '%');
            });
        }

        $users   = $query->paginate(15)->withQueryString();
        $regions = Region::orderBy('name')->get();

        return view('users.index', compact('users', 'regions'));
    }

    public function create()
    {
        $regions = Region::orderBy('name')->get();
        return view('users.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:150'],
            'email'     => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'role'      => ['required', 'in:admin,approver'],
            'region_id' => ['required', 'exists:regions,id'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'position'  => ['nullable', 'string', 'max:100'],
        ]);

        $data['password'] = Hash::make($data['password']);
        unset($data['password_confirmation']);

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'User "' . $data['name'] . '" berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load('region');
        $bookingsMade     = $user->bookings()->with('vehicle')->latest()->limit(5)->get();
        $approvalsHandled = $user->approvalActions()->with('booking')->latest()->limit(5)->get();

        return view('users.show', compact('user', 'bookingsMade', 'approvalsHandled'));
    }

    public function edit(User $user)
    {
        $regions = Region::orderBy('name')->get();
        return view('users.edit', compact('user', 'regions'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:150'],
            'email'     => ['required', 'email', 'max:150', Rule::unique('users')->ignore($user->id)],
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'      => ['required', 'in:admin,approver'],
            'region_id' => ['required', 'exists:regions,id'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'position'  => ['nullable', 'string', 'max:100'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        unset($data['password_confirmation']);

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Data user "' . $user->name . '" berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        if ($user->bookings()->exists() || $user->approvalActions()->exists()) {
            return back()->with('error', 'User tidak dapat dihapus karena memiliki riwayat pemesanan atau persetujuan.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User "' . $name . '" berhasil dihapus.');
    }
}
