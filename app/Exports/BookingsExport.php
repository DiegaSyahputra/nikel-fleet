<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BookingsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    private int $row = 1;

    public function __construct(private array $filters = []) {}

    public function query()
    {
        $query = Booking::with(['requester', 'vehicle', 'driver', 'approverL1', 'approverL2'])
            ->orderBy('departure_at');

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('departure_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('departure_at', '<=', $this->filters['date_to']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Pemesanan',
            'Tanggal Berangkat',
            'Tanggal Kembali',
            'Pemohon',
            'Kendaraan',
            'Plat Nomor',
            'Driver',
            'Tujuan',
            'Keperluan',
            'Penumpang',
            'Approver L1',
            'Approver L2',
            'Status',
            'Tanggal Dibuat',
        ];
    }

    public function map($booking): array
    {
        return [
            $this->row++,
            $booking->booking_code,
            $booking->departure_at->format('d/m/Y H:i'),
            $booking->return_at->format('d/m/Y H:i'),
            $booking->requester->name,
            $booking->vehicle->brand . ' ' . $booking->vehicle->model,
            $booking->vehicle->license_plate,
            $booking->driver->name,
            $booking->destination,
            $booking->purpose,
            $booking->passengers,
            $booking->approverL1->name,
            $booking->approverL2->name,
            $booking->getStatusLabel(),
            $booking->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A5F']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Pemesanan';
    }
}
