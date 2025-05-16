<?php

namespace App\Http\Controllers\Admin;


use App\Models\Sanphams;
use App\Models\ProductImages;
use App\Http\Requests\ProductImagesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductImagesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductImagesCrudController extends CrudController
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
        CRUD::setModel(\App\Models\ProductImages::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product-images');
        CRUD::setEntityNameStrings('product images', 'product images');
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
         CRUD::addColumn([
            'name' => 'name',
            'label' => 'Product',
            'type' => 'select',
            'entity' => 'product', // tên phương thức relationship trong model Sanpham
            'attribute' => 'name', // cột muốn hiển thị từ bảng liên kết
        ]);
        CRUD::addColumn([
            'name' => 'image_url',     // Tên cột chứa đường dẫn ảnh
            'label' => 'Ảnh',
            'type' => 'image',
            'height' => '150px',
            'width' => '200px',
            'prefix' => '/storage/',
        ]);
        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductImagesRequest::class);

        CRUD::field([
            'name' => 'product_id',
            'label' => 'Product',
            'type' => 'select',
            'entity' => 'product', // Đã sửa tên relationship cho đúng (nếu có)
            'model' => \App\Models\Sanphams::class,
            'attribute' => 'name',
            'options' => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }),
        ]);

        CRUD::field([
            'name' => 'image_url',
            'label' => 'Ảnh sản phẩm',
            'type' => 'upload_multiple',
            'withFiles' => true,
        ]);
    }

   public function store()
{
    $request = $this->crud->getRequest();
    $productId = $request->input('product_id');
    $files = $request->file('image_url');

    if ($files) {
        $imagesData = [];
        foreach ($files as $file) {
            $path = $file->store('public/product_images');
            // Lưu đường dẫn tương đối từ storage/app/public
            $relativePath = str_replace('public/product_images/', 'product_images/', $path);
            $imagesData[] = [
                'product_id' => $productId,
                'image_url' => $relativePath,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        ProductImages::insert($imagesData);
    }

    return redirect()->to($this->crud->route);
}

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
       CRUD::setValidation(ProductImagesRequest::class);

        CRUD::field([
            'name' => 'product_id',
            'label' => 'Product',
            'type' => 'select',
            'entity' => 'product', // Đã sửa tên relationship cho đúng (nếu có)
            'model' => \App\Models\Sanphams::class,
            'attribute' => 'name',
            'options' => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }),
        ]);

        CRUD::field([
            'name' => 'image_url',
            'label' => 'Ảnh sản phẩm',
            'type' => 'upload_multiple',
            'withFiles' => true,
        ]);
    }

   public function update()
{
    $request = $this->crud->getRequest();
    $productId = $request->input('product_id');
    $files = $request->file('image_url');

    if ($files) {
        $imagesData = [];
        foreach ($files as $file) {
            $path = $file->store('public/product_images');
            // Lưu đường dẫn tương đối từ storage/app/public
            $relativePath = str_replace('public/product_images/', 'product_images/', $path);
            $imagesData[] = [
                'product_id' => $productId,
                'image_url' => $relativePath,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        ProductImages::insert($imagesData);
    }

    return redirect()->to($this->crud->route);
}

}
