<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Regions ───────────────────────────────────────────
        $regions = [
            ['name' => 'Kantor Pusat Jakarta',   'type' => 'head_office'],
            ['name' => 'Kantor Cabang Sulawesi',  'type' => 'branch'],
            ['name' => 'Tambang Sorowako',        'type' => 'mine'],
            ['name' => 'Tambang Bahodopi',        'type' => 'mine'],
            ['name' => 'Tambang Pomalaa',         'type' => 'mine'],
            ['name' => 'Tambang Halmahera',       'type' => 'mine'],
        ];

        foreach ($regions as $r) {
            Region::create($r);
        }

        // ── Users ─────────────────────────────────────────────
        $password = Hash::make('d');

        User::create([
            'name'      => 'Admin Pusat',
            'email'     => 'admin@demo.com',
            'password'  => $password,
            'role'      => 'admin',
            'region_id' => 1,
            'position'  => 'Staff Pool Kendaraan',
        ]);

        User::create([
            'name'      => 'Budi Santoso',
            'email'     => 'approver1@demo.com',
            'password'  => $password,
            'role'      => 'approver',
            'region_id' => 1,
            'position'  => 'Kepala Bagian Umum',
        ]);

        User::create([
            'name'      => 'Sari Dewi',
            'email'     => 'approver2@demo.com',
            'password'  => $password,
            'role'      => 'approver',
            'region_id' => 1,
            'position'  => 'Manajer Operasional',
        ]);

        User::create([
            'name'      => 'Eko Prasetyo',
            'email'     => 'approver3@demo.com',
            'password'  => $password,
            'role'      => 'approver',
            'region_id' => 2,
            'position'  => 'Kepala Cabang',
        ]);

        // ── Vehicles ──────────────────────────────────────────
        $vehicles = [
            ['license_plate' => 'B 1234 ABC', 'brand' => 'Toyota',    'model' => 'Innova',       'year' => 2022, 'type' => 'passenger', 'ownership' => 'owned',  'fuel_type' => 'bensin', 'region_id' => 1],
            ['license_plate' => 'B 5678 DEF', 'brand' => 'Toyota',    'model' => 'Fortuner',     'year' => 2023, 'type' => 'passenger', 'ownership' => 'owned',  'fuel_type' => 'solar',  'region_id' => 1],
            ['license_plate' => 'DD 1111 GH', 'brand' => 'Mitsubishi','model' => 'L300',         'year' => 2021, 'type' => 'cargo',     'ownership' => 'owned',  'fuel_type' => 'solar',  'region_id' => 2],
            ['license_plate' => 'DD 2222 IJ', 'brand' => 'Isuzu',     'model' => 'Elf',          'year' => 2020, 'type' => 'cargo',     'ownership' => 'rented', 'fuel_type' => 'solar',  'region_id' => 2],
            ['license_plate' => 'DT 3333 KL', 'brand' => 'Toyota',    'model' => 'Land Cruiser', 'year' => 2022, 'type' => 'passenger', 'ownership' => 'owned',  'fuel_type' => 'solar',  'region_id' => 3],
        ];

        foreach ($vehicles as $v) {
            Vehicle::create(array_merge($v, ['status' => 'available']));
        }

        // ── Drivers ───────────────────────────────────────────
        $drivers = [
            ['name' => 'Ahmad Fauzi',   'license_number' => 'SIM-001-2024', 'license_expiry' => '2026-12-31', 'phone' => '081234567890', 'region_id' => 1],
            ['name' => 'Rizky Maulana', 'license_number' => 'SIM-002-2024', 'license_expiry' => '2025-09-30', 'phone' => '081298765432', 'region_id' => 1],
            ['name' => 'Hendra Wijaya', 'license_number' => 'SIM-003-2024', 'license_expiry' => '2027-03-15', 'phone' => '082112345678', 'region_id' => 2],
        ];

        foreach ($drivers as $d) {
            Driver::create(array_merge($d, ['status' => 'available']));
        }
    }
}
