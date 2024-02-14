<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerExport implements FromQuery, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;

    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return Customer::query()->filter($this->request);
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->name,
            $customer->email,
            $customer->mobile,
            $customer->gender,
            $customer->status,
            $customer->remarks ?? 'N/A',
            $customer->created_at,
            $customer->updated_at,
            $customer->createdBy->name ?? 'Self',
            $customer->updatedBy->name ?? '---'
        ];
    }

    public function headings(): array
    {
        return [
            'ID#',
            'Name',
            'Email',
            'Mobile',
            'Gender',
            'Status',
            'Remarks',
            'Created At',
            'Updated At',
            'Created By',
            'Updated By'
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
