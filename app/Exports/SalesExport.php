<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromArray; // Added for data handling
use Maatwebsite\Excel\Events\AfterSheet;

class SalesExport implements WithHeadings, WithColumnWidths, WithStyles, WithEvents, FromArray
{
    protected $salesData;

    public function __construct(array $data)
    {
        $this->salesData = $data;
    }

    // Providing the stock data for the Excel export
    public function array(): array
    {
        return $this->salesData;
    }

    // Defining the headings for the Excel columns
    public function headings(): array
    {
        return [
            'Order Id',
            'Customer Id',
            'Invoice Number',
            'Customer Name',
            'Customer Email',
            'Product Name',
            'Product SKU',
            'Qunatity Sold',
            'Product Sale Price',
            'Total Sale Amount',
            'Order Date',
            'Payment Method',
            'Shipping Address',
            'Order Status',
            'Payment Status',
            'Discount Applied',
            'Coupon Applied',
            'Shipping Cost',
            'Total Order Value',
        ];
    }

    // Setting column widths
    public function columnWidths(): array
    {
        return [

            'A'=>10,
            'B'=>25,
            'C' => 20,
            'D' => 30,
            'E' => 45,
            'F' => 60,
            'G' => 25,
            'H' => 15,
            'I' => 30,
            'J' => 25,
            'K' => 25,
            'L' => 35,
            'M' => 50,
            'N' => 15,
            'O' => 20,
            'P' => 20,
            'Q' => 20,
            'R' => 20,
            'S' => 20
        ];
    }

    // Styling the worksheet (header row and alignment)
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:S1')->applyFromArray([
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
        $sheet->getStyle('A1:S1')->applyFromArray([
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
                foreach ($this->salesData as $data) {
                    $sheet->getRowDimension($rowIndex)->setRowHeight(20);
                    $rowIndex++;
                }
            },
        ];
    }
}
