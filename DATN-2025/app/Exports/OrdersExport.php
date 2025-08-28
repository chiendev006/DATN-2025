<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Order::with('orderDetails');

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['pay_status'])) {
            $query->where('pay_status', $this->filters['pay_status']);
        }
        if (!empty($this->filters['order_type'])) {
            if ($this->filters['order_type'] == 'staff') {
                $query->where(function($q) {
                    $q->whereHas('user', function($u) {
                        $u->whereIn('role', [1, 21, 22]);
                    })
                    ->orWhere(function($q2) {
                        $q2->whereNull('user_id')
                            ->where(function($q3) {
                                $q3->where('phone', 'N/A')
                                    ->orWhere('phone', 'Nhân viên thu ngân')
                                    ->orWhere('phone', 'Không có')
                                    ->orWhere('name', 'like', '%Khách lẻ%')
                                    ->orWhere('name', 'like', '%Khách Vãng Lai%');
                            });
                    });
                });
            } elseif ($this->filters['order_type'] == 'web') {
                $query->where(function($q) {
                    $q->whereHas('user', function($u) {
                        $u->where('role', 0);
                    })
                    ->orWhere(function($q2) {
                        $q2->whereNull('user_id')
                            ->where('phone', '!=', 'N/A')
                            ->where('phone', '!=', 'Nhân viên thu ngân')
                            ->where('phone', '!=', 'Không có')
                            ->where('name', 'not like', '%Khách lẻ%')
                            ->where('name', 'not like', '%Khách Vãng Lai%');
                    });
                });
            }
        }
        if (!empty($this->filters['transaction_id'])) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->filters['transaction_id'] . '%')
                  ->orWhere('phone', 'like', '%' . $this->filters['transaction_id'] . '%');
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tên khách',
            'SĐT',
            'Địa chỉ',
            'Trạng thái',
            'Trạng thái thanh toán',
            'Tổng tiền',
            'Ngày tạo',
            'Tên sản phẩm',
            'Số lượng',
            'Ghi chú'
        ];
    }

    public function map($order): array
    {
        $productNames = $order->orderDetails->map(function ($detail) {
            return $detail->product_name;
        })->implode(', ');

        $quantities = $order->orderDetails->map(function ($detail) {
            return $detail->quantity;
        })->implode(', ');

        $statusMap = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đã xác nhận',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];
        $payStatusMap = [
            '0' => 'Chờ thanh toán',
            '1' => 'Đã thanh toán',
            '2' => 'Đã hủy',
            '3' => 'Hoàn tiền'
        ];

        return [
            $order->id,
            $order->name,
            $order->phone,
            $order->address_detail . ($order->district_name ? ', ' . $order->district_name : ''),
            $statusMap[$order->status] ?? $order->status,
            $payStatusMap[$order->pay_status] ?? $order->pay_status,
            $order->total,
            $order->created_at->format('H:i d/m/Y'),
            $productNames,
            $quantities,
            $order->note
        ];
    }

    // Giao diện style cơ bản
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // Dòng tiêu đề
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ]
        ];
    }

    // Sự kiện tùy biến nâng cao

    public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet;

            // Thêm dòng tiêu đề "HÓA ĐƠN" ở đầu file
            $sheet->insertNewRowBefore(1, 1); // chèn 1 dòng trên cùng

            // Ghi chữ "HÓA ĐƠN" vào ô A1
            $sheet->setCellValue('A1', 'HÓA ĐƠN');

            // Merge từ A1 đến K1 (tùy số cột, ở đây là 11 cột = cột K)
            $sheet->mergeCells('A1:K1');

            // Căn giữa, in đậm, tăng font dòng HÓA ĐƠN
            $sheet->getStyle('A1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'name' => 'Times New Roman',
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ]
            ]);

            // Định dạng viền bảng dữ liệu
            $dataRange = 'A2:K' . $sheet->getHighestRow();
            $sheet->getStyle($dataRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
                'alignment' => [
                    'vertical' => 'center',
                ]
            ]);

            // Format số tiền và căn phải
            $sheet->getStyle('G3:G' . $sheet->getHighestRow())
                ->getNumberFormat()
                ->setFormatCode('#,##0');

            $sheet->getStyle('G:G')->getAlignment()->setHorizontal('right');
            $sheet->getStyle('H:H')->getAlignment()->setHorizontal('center');
        },
    ];
}

}
