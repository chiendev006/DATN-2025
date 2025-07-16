<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use App\Models\Address;
use App\Models\historylog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $content1='';
        $content2='';
        $title='<strong>Thêm khu vực ship:</strong> <br>';
            $content1=" *<span style='color: red;'>Tên:</span> <b>$address->name</b> <br>";
            $content2=" *<span style='color: red;'>Giá:</span> <b>$address->shipping_fee</b>  <br>";


        historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' =>$title.$content1.$content2,
        ]);
        return redirect()->route('address.index')->with('success', 'Thêm Khu vực thành công');
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'shipping_fee' => 'required|numeric|min:0',
        ]);
        $address = Address::find($id);
        $addressshopping_fee=$address->shipping_fee;
        $addressname= $address->name;
        $address->name = $request->name;
        $address->shipping_fee = $request->shipping_fee;
        $address->save();
        $content1='';
        $content2='';
        $title="<strong>Cập nhật khu vực: $address->name </strong><br>";
        if($request->name!=$addressname){
            $content1=" *<span style='color: red;'>Tên:</span> <b>$addressname</b> <span style='color: blue;'> thành </span> <b>$address->name</b> <br>";
        }
        if($request->shipping_fee!=$addressshopping_fee){
            $content2=" *<span style='color: red;'>Giá:</span> <b>$addressshopping_fee</b> VND <span style='color: blue;'> thành </span> <b>$address->shipping_fee</b> VND <br>";
        }
       if($content1!=''||$content2!=''){
        historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' =>$title.$content1.$content2,
        ]);
       }
        return redirect()->route('address.index')->with('success', 'Cập nhật Khu vực thành công');
    }

    public function delete($id)
    {
        $address = Address::find($id);
      
          $addressshopping_fee=$address->shipping_fee;
        $addressname= $address->name;
        $address->save();
        $content1='';
        $content2='';
        $title='<strong>Xóa khu vực ship:</strong> <br>';
        $content1=" *<span style='color: red;'>Tên:</span> <b>$addressname</b> <br>";
        $content2=" *<span style='color: red;'>Giá:</span> <b>$address->shipping_fee</b> VND <br>";


        historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' =>$title.$content1.$content2,
        ]);
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
