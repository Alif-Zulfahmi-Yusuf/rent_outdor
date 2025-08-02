<?php

namespace App\Exports;

use App\Models\Rent;
use App\Models\Renting;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RentingsExport implements FromView, WithEvents, WithStyles
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        $query = Rent::with(['rentItems.book', 'user']);

        if ($this->from != null || $this->to != null) {
            $query->where('rent_date', '>=', $this->from)->where('rent_date', '<=', $this->to);
        }

        $query = $query->latest()->get();

        return view('backend.renting.excel', [
            'data' => $query,
            'from' => $this->from ? Carbon::parse($this->from) : null,
            'to' => $this->to ? Carbon::parse($this->to) : null
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        if ($this->from != null || $this->to != null) {
            $sheet->getStyle('A4:I5')->applyFromArray([
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center'
                ]
            ]);
        } else {
            $sheet->getStyle('A1:I2')->applyFromArray([
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center'
                ]
            ]);
        }

        return $sheet;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                foreach (range('A', 'I') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                if ($this->from != null || $this->to != null) {
                    $cellRange = 'A4:I' . $sheet->getHighestRow();
                } else {
                    $cellRange = 'A1:I' . $sheet->getHighestRow();
                }

                $sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
            }
        ];
    }
}
