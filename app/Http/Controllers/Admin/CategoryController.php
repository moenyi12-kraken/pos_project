<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    // page declaration
    public function list()
    {
        $categories = Category::orderBy('created_at', 'desc')->paginate('5');
        return view('admin.category.list', compact('categories'));
    }

    // Create category
    public function create(Request $request)
    {
        $this->checkValidation($request);
        Category::create([
            'name' => $request->categoryName,
        ]);

        Alert::success('Success Title', 'Create Successfully');

        return back();
    }

    // Delete Process
    public function delete($id)
    {
        Category::where('id', $id)->delete();
        return back();
    }

    // Category Edit
    public function edit($id)
    {
        $categories = Category::where('id', $id)->first();
        return view('admin.category.edit', compact('categories'));
    }

    // Category Update
    public function update($id, Request $request)
    {
        $request['id'] = $id;
        $this->checkValidation($request);
        Category::where('id', $id)->update([
            'name' => $request->categoryName,
        ]);

        Alert::success('Success Title', 'Update Successfully');

        return to_route('admin#Category');
    }

    // Category validation
    public function checkValidation($request)
    {
        $request->validate([
            'categoryName' => 'required|max:20|unique:categories,name,' . $request->id,
        ], [
            'categoryName.required' => 'Give a name for me',
            'categoryName.max'      => 'This name is too long',
            'categoryName.unique'   => 'This name is taken :(',
        ]);
    }
}
