<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SanphamsRequest;
use App\Models\Danhmucs;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SanphamsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SanphamsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Sanphams::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sanphams');
        CRUD::setEntityNameStrings('sanphams', 'sanphams');
          $this->crud->with('danhmuc');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
     protected function setupListOperation()
    {


        CRUD::column('id');
        CRUD::column('name');
      CRUD::addColumn([
    'name' => 'id_danhmuc',
    'label' => 'Danh mục',
    'type' => 'select',
    'entity' => 'danhmuc', // tên phương thức relationship trong model Sanpham
    'attribute' => 'name', // cột muốn hiển thị từ bảng liên kết
]);

        CRUD::column('price')->suffix(' VND');
         CRUD::addColumn([
            'name' => 'image',     // Tên cột chứa đường dẫn ảnh
            'label' => 'Ảnh',
            'type' => 'image',
            'height' => '150px',
            'width' => '200px',
            'prefix' => '/storage',
        ]);
    }


    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SanphamsRequest::class);
        CRUD::field('name')->type('text')->label('Tên sản phẩm');
       CRUD::field([
    'name'      => 'image',
    'label'     => 'Ảnh sản phẩm',
    'type'      => 'upload',
    'upload'    => true,
    'withFiles' => true,
    'prefix'    => '/product_images/', // không có dấu /
    'disk'      => 'public',   // đảm bảo bạn đang dùng disk đúng
]);
        // ...
        CRUD::field('mota')->type('textarea')->label('Mô tả');
        CRUD::field('price')->type('number')->label('Giá');
        CRUD::field([
            'name' => 'id_danhmuc',
            'label' => 'Danh mục',
            'type' => 'select',
            'entity' => 'danhmuc', // Tên relationship
            'model' => Danhmucs::class, // Đường dẫn đến model Danh mục
            'attribute' => 'name', // Cột hiển thị
            'options' => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }),
        ]);


    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
       CRUD::setValidation(SanphamsRequest::class);
        CRUD::field('name')->type('text')->label('Tên sản phẩm');

   CRUD::field([
    'name'      => 'image',
    'label'     => 'Ảnh sản phẩm',
    'type'      => 'upload',
    'upload'    => true,
    'withFiles' => true,
    'prefix'    => '/product_images/', // không có dấu /
    'disk'      => 'public',   // đảm bảo bạn đang dùng disk đúng
]);

        CRUD::field('mota')->type('textarea')->label('Mô tả');
        CRUD::field('price')->type('number')->label('Giá');
        CRUD::field([
            'name' => 'id_danhmuc',
            'label' => 'Danh mục',
            'type' => 'select',
            'entity' => 'danhmuc', // Tên relationship
            'model' => Danhmucs::class, // Đường dẫn đến model Danh mục
            'attribute' => 'name', // Cột hiển thị
            'options' => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }),
        ]);
    }

     protected function setupShowOperation()
    {
        // $this->crud->set('show.setFromDb', false); // Nếu bạn muốn tự định nghĩa cột

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'amount', 'type' => 'number']);
         */

        // Ví dụ hiển thị tên sản phẩm
        CRUD::column('name')->label('Tên sản phẩm');

        // Ví dụ hiển thị mô tả
        CRUD::column('mota')->label('Mô tả');

        // Ví dụ hiển thị giá
        CRUD::column('price')->type('number')->label('Giá');

        // Hiển thị tên danh mục (tương tự như trang list)
       CRUD::addColumn([
    'name' => 'id_danhmuc',
    'label' => 'Danh mục',
    'type' => 'select',
    'entity' => 'danhmuc', // tên phương thức relationship trong model Sanpham
    'attribute' => 'name', // cột muốn hiển thị từ bảng liên kết
]);

        // Hiển thị ảnh (tương tự như trang list, nhưng có thể cần điều chỉnh kích thước)
        CRUD::column([
            'name' => 'image',
            'label' => 'Ảnh sản phẩm',
            'type' => 'image',
            'prefix' => '/storage/',
            'height' => '100px',
            'width'  => 'auto',
        ]);

        CRUD::addColumn([
    'name' => 'product_images',
    'label' => 'Ảnh Sản Phẩm',
    'type' => 'closure',
    'escaped' => false,  // Quan trọng: cho phép render HTML
    'function' => function ($entry) {
        $html = '';
        foreach ($entry->product_img as $image) {
            $url = asset('storage/' . $image->image_url);
            $html .= '<img src="' . $url . '" style="width:200px; height:100px; margin-top:10px; margin-right:10px">';
        }
        return $html ?: '-';
    },
]);


CRUD::addColumn([
    'name' => 'created_at',
    'label' => 'Ngày tạo',
    'type' => 'datetime',
]);
CRUD::addColumn([
    'name' => 'updated_at',
    'label' => 'Ngày cập nhật',
    'type' => 'datetime',
]);
    }
}
