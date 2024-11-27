<?php

namespace App\Exports;

use App\Models\Monitoring;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

class MonitoringsExport implements FromQuery, WithMapping, WithStrictNullComparison, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;

    // private $fileName = 'Monitoring.xlsx';

    // private $writerType = Excel::XLSX;

    private int $year;
    private int $month;

    function filterStudent($monitoring, $ket)
    {
        $filtered = $monitoring->students->filter(function ($student) use ($ket) {
            return $student->keterangan == $ket;
        })->map(function ($student) {
            // return json_decode($student->student->name, true);
            return $student->student->name;
        })->join(", ");

        return $filtered ?? null;
    }

    public function forYear(int $year)
    {
        $this->year = $year;

        return $this;
    }

    public function forMonth(int $month)
    {
        $this->month = $month;

        return $this;
    }

    public function query()
    {
        return Monitoring::query()->whereYear('date', $this->year);
    }

    public function map($monitoring): array
    {
        static $no = 1;

        return [
            $no++,
            $monitoring->title,
            $monitoring->description,
            $monitoring->date,
            $monitoring->start_time,
            $monitoring->end_time,
            $this->filterStudent($monitoring, 'Izin'),
            $this->filterStudent($monitoring, 'Sakit'),
            $this->filterStudent($monitoring, 'Alfa')
        ];
    }

    public function headings(): array
    {
        return [
            ['No', 'Judul', 'Deskripsi', 'Tanggal', 'Jam Mulai', 'Jam Berakhir', 'Siswa Tidak Masuk',],
            ['Set', 'Set', 'Set', 'Set', 'Set', 'Set', 'Izin', 'Sakit', 'Alfa']
        ];
    }

    public function registerEvents(): array
    {
        return [
            Sheet::listen(AfterSheet::class, function (AfterSheet $event) {
                $sheet = $event->sheet;

                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();

                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');
                $sheet->mergeCells('F1:F2');

                $sheet->mergeCells('G1:I1');

                $sheet->getStyle('A1:' . $highestColumn . '2')
                    ->getFont()
                    ->setBold(true);

                $sheet->getStyle('A1:' . $highestColumn . '2')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('FABE64');

                $sheet->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getAlignment()
                    ->setHorizontal('center');

                $sheet->getStyle('A1:' . $highestColumn . '2')
                    ->getAlignment()
                    ->setVertical('center');

                $sheet->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $sheet->getPageSetup()
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4) // Ukuran A4
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE); // Orientasi landscape

                foreach (range('A', 'F') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $sheet->getPageSetup()
                    ->setFitToWidth(1);

                $sheet->getPageSetup()
                    ->setFitToHeight(0);
            })
        ];
    }
}
