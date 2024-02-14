<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    use Exportable;

    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return Order::query()->filter($this->request);
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->invoice_no,
            $order->customer->name,
            $order->promotion->name,
            $order->promotionObjective->name,
            $order->promotion_period,
            $order->amount,
            $order->divisions ?? $order->location,
            $order->gender,
            $order->min_age,
            $order->max_age,
            $order->status,
            $order->remarks ?? 'N/A',
            $order->created_at,
            $order->updated_at,
            $order->createdBy->name ?? 'Self',
            $order->updatedBy->name ?? '---'
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Invoice No',
            'Customer Name',
            'Promotion',
            'Promotion Objective',
            'Promotion Period',
            'Amount',
            'Divisions',
            'Gender',
            'Min Age',
            'Max Age',
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
