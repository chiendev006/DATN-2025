<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Danhmuc;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\sanpham;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BartenderController extends Controller
{
    public function index()
    {
        // Check for flash messages
        $donhangs = Order::with('details')
        ->whereDate('created_at', Carbon::today())
        ->orderBy('created_at', 'desc')
        ->get();



    return view('staff.bartender', compact('donhangs'));

}

    public function create()
    {
        return view('staff.bartender');
    }

                        public function getOrderDetails($id)
    {
        try {
            // Get order details for the given order
            $orderDetails = Orderdetail::where('order_id', $id)->get();

            foreach ($orderDetails as $detail) {
                // Handle size
                if ($detail->size_id) {
                    $size = \App\Models\Size::find($detail->size_id);
                    $detail->size_name = $size ? $size->size : 'Size ' . $detail->size_id;
                } else {
                    $detail->size_name = 'Không có size';
                }

                // Handle topping - directly parse the topping_id field
                if ($detail->topping_id) {
                    // If topping_id contains comma-separated values
                    $toppingIds = explode(',', $detail->topping_id);
                    $toppingNames = [];

                    foreach ($toppingIds as $toppingId) {
                        $toppingId = trim($toppingId);
                        if (!empty($toppingId)) {
                            // Look up in product_topping table
                            $productTopping = \App\Models\Product_topping::find($toppingId);
                            if ($productTopping) {
                                $toppingNames[] = $productTopping->topping;
                            } else {
                                $toppingNames[] = 'Topping ' . $toppingId;
                            }
                        }
                    }

                    $detail->topping_name = !empty($toppingNames) ? implode(', ', $toppingNames) : 'Không có topping';
                } else {
                    $detail->topping_name = 'Không có topping';
                }
            }

            return response()->json($orderDetails);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Đã xảy ra lỗi khi lấy chi tiết đơn hàng: ' . $e->getMessage()], 500);
        }
    }

    public function updateOrderDetailStatus(Request $request, $id)
    {
        $orderDetail = Orderdetail::findOrFail($id);
        $orderDetail->status = $request->status;
        $orderDetail->updated_at = Carbon::now();
        $orderDetail->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->status = $request->status;
            $order->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái đơn hàng thành công']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }
}
?>
