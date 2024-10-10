<?php

namespace App\Exports;

use App\Models\Model\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class PostsExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithCustomStartCell, WithEvents
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = Carbon::parse($startDate);
        $this->endDate = Carbon::parse($endDate);
    }

    public function headings(): array
    {
        return [
            ['Тайлан ' . $this->startDate->format('Y-m-d') . ' аас ' . $this->endDate->format('Y-m-d')],
            ['Нэр', 'Дугаар', 'Тайлбар', 'Төрөл', 'Админ-хариу', 'Статус', 'Ирсэн Огноо', 'Шийдвэрлэсэн Огноо'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => '4CAF50']],
                'alignment' => ['horizontal' => 'center'],
            ], // Title row styling
            2 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => '2196F3']],
                'alignment' => ['horizontal' => 'center'],
            ], // Header row styling
        ];
    }

    public function title(): string
    {
        return 'Report from ' . $this->startDate->format('Y-m-d') . ' to ' . $this->endDate->format('Y-m-d');
    }

    public function startCell(): string
    {
        return 'A1';
    }

    private function statusName($status)
    {
        $statusNames = [
            1 => 'Шинээр ирсэн',
            2 => 'Хүлээн авсан',
            3 => 'Шийдвэрлэсэн',
            4 => 'Татгалзсан',
        ];

        return $statusNames[$status] ?? 'Тодорхойгүй';
    }

    private function typeName($type)
    {
        $typeName = [
            1 => 'Бусад',
            2 => 'Хог хягдал',
            3 => 'Эвдрэл доройтол',
            4 => 'Бохир',
        ];

        return $typeName[$type] ?? 'Тодорхойгүй';
    }

    public function collection()
    {
        $report = new Report($this->startDate, $this->endDate);
        $details = $report->reportExport(Session::get('admin_is'));

        // Map the details to include the status name
        return $details->map(function ($item) {
            return [
                $item->name,
                $item->number,
                $item->comment,
                $this->typeName($item->type),
                $item->admin_comment,
                $this->statusName($item->status),
                Carbon::parse($item->created_at)->format('Y-m-d H:i:s'),
                Carbon::parse($item->updated_at)->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merge cells for the title
                $sheet->mergeCells('A1:H1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

                // Set the width of the columns
                $sheet->getColumnDimension('A')->setWidth(21); // name
                $sheet->getColumnDimension('B')->setWidth(18); // number
                $sheet->getColumnDimension('C')->setWidth(33); // comment
                $sheet->getColumnDimension('D')->setWidth(18); // type
                $sheet->getColumnDimension('E')->setWidth(33); // admin_comment
                $sheet->getColumnDimension('F')->setWidth(20); // status
                $sheet->getColumnDimension('G')->setWidth(25); // created_at
                $sheet->getColumnDimension('H')->setWidth(25); // updated_at

                // Add borders to all cells
                $sheet->getStyle('A2:H' . $sheet->getHighestRow())
                    ->getBorders()->getAllBorders()->setBorderStyle('thin');

                // Calculate status counts
                $report = new Report($this->startDate, $this->endDate);
                $counts = $report->getStatusCounts(Session::get('admin_is'), $this->startDate, $this->endDate);

                // Insert the status counts table below the data
                $startRow = $sheet->getHighestRow() + 2;
                $sheet->setCellValue('A' . $startRow, 'Статусын тайлан');
                $sheet->setCellValue('A' . ($startRow + 1), 'Статус');
                $sheet->setCellValue('B' . ($startRow + 1), 'Тоо');

                $sheet->setCellValue('A' . ($startRow + 2), 'Шинээр ирсэн');
                $sheet->setCellValue('B' . ($startRow + 2), $counts['new']);

                $sheet->setCellValue('A' . ($startRow + 3), 'Хүлээн авсан');
                $sheet->setCellValue('B' . ($startRow + 3), $counts['received']);

                $sheet->setCellValue('A' . ($startRow + 4), 'Шийдвэрлэсэн');
                $sheet->setCellValue('B' . ($startRow + 4), $counts['resolved']);

                $sheet->setCellValue('A' . ($startRow + 5), 'Татгалзсан');
                $sheet->setCellValue('B' . ($startRow + 5), $counts['rejected']);

                // Style the status and type tables
                $sheet->getStyle('A' . $startRow . ':B' . ($startRow + 5))
                    ->getBorders()->getAllBorders()->setBorderStyle('thin');

                $sheet->getStyle('A' . ($startRow - 7) . ':B' . ($startRow + 5))
                    ->getAlignment()->setHorizontal('center');

                $sheet->getStyle('A' . ($startRow - 7) . ':B' . ($startRow - 6))->getFont()->setBold(true);
                $sheet->getStyle('A' . $startRow . ':B' . ($startRow + 1))->getFont()->setBold(true);
            },
        ];
    }
}
