<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $data = Category::query();

        if ($search) {
            $data->where('name', 'like', '%' . $search . '%');
        }

        $data = $data->latest()->paginate(10);

        return view('backend.kategori.index', [
            'data' => $data
        ]);
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->validated();

        Category::create($data);

        return redirect()->back()->with('success', 'Kategori <b>' . $request->get('name') . '</b> berhasil ditambahkan');
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        $category->update($data);

        return redirect()->back()->with('success', 'Kategori <b>' . $category->name . '</b> berhasil diubah');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->back()->with('success', 'Kategori <b>' . $category->name . '</b> berhasil dihapus');
    }
}
