<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class TransactionExport implements FromCollection, WithHeadings, WithTitle, WithEvents

{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($filter)
    {
        $this->filter_transaction = $filter;
    }

    public function collection()
    {
         $query = DB::table('v_main_transactions')
            ->where('transaction_type', '=', 'SALE')
            ->orderBy('transaction_date', 'DESC');

        if($this->filter_transaction == 'today'){
            $query->whereDate('transaction_date', Carbon::today());
        }elseif($this->filter_transaction == 'week'){
             $query->whereBetween('transaction_date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
        }else{
            $query->whereMonth('transaction_date', Carbon::now()->month)
            ->whereYear('transaction_date', Carbon::now()->year);
        }

       return $query->get();
    }


    public function headings(): array
    {
        return [
            'ID',
            'Invoice',
            'Banyak',
            'Total Amount',
            'Payment Changes',
            'Grand Total',
            'ID Pelanggan',
            'Pelanggan',
            'Kasir',
            'Jenis Transaksi',
            'Tanggal',
            'Created_at',
            'Updated_at'
        ];
    }

    public function title(): string
    {
        return 'Data Transaksi'; // Nama atau judul sheet
    }

    public function registerEvents(): array
    {
        return [
            // Event before sheet is created, you can set titles, etc.
            BeforeSheet::class => function (BeforeSheet $event) {
                // Set title for the sheet (optional)
                $event->sheet->setCellValue('A1', 'Data Transaksi');
                $event->sheet->mergeCells('A1:L1'); // Merge cells for the title
                $event->sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true); // Optional styling for title
            },
            // You can also customize formatting for other parts of the sheet (e.g., bold headers)
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A2:AF2')->getFont()->setBold(true);

                // CODE UNTUK AUTO-SIZE COLUMN
                $sheet = $event->sheet->getDelegate();
                // Auto-size all used columns
                foreach (range('A', 'Z') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Jika Anda tahu ada kolom lebih dari Z, Anda bisa extend dengan:
                foreach (range('A', 'Z') as $first) {
                    $sheet->getColumnDimension($first)->setAutoSize(true);
                }
                foreach (range('A', 'F') as $second) { // Untuk kolom AA hingga AF
                    $col = 'A' . $second;
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
