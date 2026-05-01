<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    //to Product Page
    public function productPage()
    {
        $categoryData = Category::select('id', 'name')->get();
        return view('admin.product.productPage', compact('categoryData'));
    }

    //Create Product
    public function create(Request $request)
    {
        $this->checkValidation($request, 'create');
        $data = $this->getData($request);

        if ($request->hasFile('image')) {
            $fileName = uniqid() . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path() . '/productImage/', $fileName);
            $data['image'] = $fileName;
        }

        Product::create($data);

        Alert::success('Success Title', 'Create Successfully');

        return back();
    }

    //Get Data Process
    public function getData($request)
    {
        return [
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'stock'       => $request->stock,
            'category_id' => $request->categoryId,
        ];
    }

    //Check Validation
    public function checkValidation($request, $action)
    {
        $rules = [
            'name'        => 'required|max:20|unique:products,name,' . $request->productId,
            'categoryId'  => 'required',
            'price'       => 'required',
            'stock'       => 'required|max:99',
            'description' => 'required|max:10000',
        ];
        $rules['image'] = $action != 'update' ? 'required|file' : 'file';
        $message        = [

        ];

        $request->validate($rules, $message);
    }

    //Product List
    public function list($action = 'default')
    {
        $productItem = Product::select('products.id', 'products.name', 'products.image', 'products.price', 'products.stock', 'products.description', 'products.category_id', 'categories.name as category_name')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->when($action == 'lowAmt', function ($query) {
                $query->where('products.stock', '<=', 3);
            })
            ->when(request('searchKey'), function ($query) {
                $query->whereAny(['products.name', 'categories.name', 'products.price'], 'like', '%' . request('searchKey') . '%');
            })
            ->orderBy('products.created_at', 'desc')
            ->get();
        return view('admin.product.productList', compact('productItem'));
    }

    //Delete Product
    public function delete($id)
    {
        $img = Product::where('id', $id)->value('image');
        if (file_exists(public_path('productImage/' . $img))) {
            unlink(public_path('productImage/' . $img));
        }
        Product::where('id', $id)->delete();
        return back();
    }

    //Edit Product
    public function edit($id)
    {
        $category = Category::select('id', 'name')->get();
        $products = Product::where('id', $id)->first();
        return view('admin.product.productEdit', compact(['category', 'products']));
    }

    //Update Product
    public function update(Request $request)
    {
        $this->checkValidation($request, 'update');
        $data = $this->getData($request);
        if ($request->hasFile('image')) {
            if (file_exists(public_path('productImage/' . $request->oldPhoto))) {
                unlink(public_path('productImage/' . $request->oldPhoto));
            }
            $fileName = uniqid() . $request->file("image")->getClientOriginalName();
            $request->file('image')->move(public_path() . '/productImage/', $fileName);
            $data['image'] = $fileName;
        } else {
            $data['image'] = $request->oldPhoto;
        }
        Alert::success('Success Title', 'Update Successfully');
        Product::where('id', $request->productId)->update($data);
        return to_route('product#List');
    }

    //Product Detail
    public function detail($id)
    {
        $products = Product::select('products.name', 'products.id', 'products.image', 'products.price', 'products.stock', 'products.description', 'products.category_id', 'categories.name as category_name')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->where('products.id', $id)
            ->first();
        return view('admin.product.productDetail', compact('products'));
    }
}
