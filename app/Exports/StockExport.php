<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromArray; // Added for data handling
use Maatwebsite\Excel\Events\AfterSheet;

class StockExport implements WithHeadings, WithColumnWidths, WithStyles, WithEvents, FromArray
{
    protected $stockData;

    public function __construct(array $data)
    {
        $this->stockData = $data;
    }

    // Providing the stock data for the Excel export
    public function array(): array
    {
        return $this->stockData;
    }

    // Defining the headings for the Excel columns
    public function headings(): array
    {
        return [
            'ID',
            'Product Name',
            'SKU',
            'Stock',
            'Sold',
            'Category',
            'Sale Price'
        ];
    }

    // Setting column widths
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 50,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 25,
            'G' => 15,
        ];
    }

    // Styling the worksheet (header row and alignment)
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Example: Center align all columns
        $sheet->getStyle('A:G')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        return $sheet;
    }

    // Registering events for after the sheet is created
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set row height for the first row (headings)
                $sheet->getRowDimension(1)->setRowHeight(25);

                // Set height for each data row
                $rowIndex = 2;
                foreach ($this->stockData as $data) {
                    $sheet->getRowDimension($rowIndex)->setRowHeight(20);    
                    $rowIndex++;
                }
            },
        ];
    }
}
