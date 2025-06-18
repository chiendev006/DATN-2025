<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $address = Address::all();
        $address = Address::paginate(10);
        return view('admin.address.index', compact('address'));
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'shipping_fee' => 'required|numeric|min:0',
        ]);
        $address = new Address();
        $address->name = $request->name;
        $address->shipping_fee = $request->shipping_fee;
        $address->save();
        return redirect()->route('address.index')->with('success', 'Thêm Khu vực thành công');
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'shipping_fee' => 'required|numeric|min:0',
        ]);
        $address = Address::find($id);
        $address->name = $request->name;
        $address->shipping_fee = $request->shipping_fee;
        $address->save();
        return redirect()->route('address.index')->with('success', 'Cập nhật Khu vực thành công');
    }

    public function delete($id)
    {
        $address = Address::find($id);
        $address->delete();
        return redirect()->route('address.index')->with('success', 'Xóa Khu vực thành công');
    }
    public function search(Request $request)
    {
        $query = Address::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('price_min')) {
            $query->where('shipping_fee', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('shipping_fee', '<=', $request->price_max);
        }
        $address = $query->paginate(10)->appends($request->query());

        return view('admin.address.index', compact('address'));
    }

}
?>