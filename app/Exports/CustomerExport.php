<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromArray; // Added for data handling
use Maatwebsite\Excel\Events\AfterSheet;

class CustomerExport implements WithHeadings, WithColumnWidths, WithStyles, WithEvents, FromArray
{
    protected $customerData;

    public function __construct(array $data)
    {
        $this->customerData = $data;
    }

    // Providing the stock data for the Excel export
    public function array(): array
    {
        return $this->customerData;
    }

    // Defining the headings for the Excel columns
    public function headings(): array
    {
        return [
            'Customer ID',
            'First Name',
            'Last Name',
            'Full Name',
            'Email Address',
            'Phone Number',
            'Registration Date',
            'Total Orders',
            'Total Spent',
            'Last Purchase Date',
            'Shipping Address'
        ];
    }

    // Setting column widths
    public function columnWidths(): array
    {
        return [

            'A' => 20,
            'B' => 25,
            'C' => 20,
            'D' => 30,
            'E' => 45,
            'F' => 30,
            'G' => 25,
            'H' => 15,
            'I' => 20,
            'J' => 25,
            'K' => 35,
        ];
    }

    // Styling the worksheet (header row and alignment)
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->applyFromArray([
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
        $sheet->getStyle('A1:K1')->applyFromArray([
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
                foreach ($this->customerData as $data) {
                    $sheet->getRowDimension($rowIndex)->setRowHeight(20);
                    $rowIndex++;
                }
            },
        ];
    }
}
