<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\BarangRequest;
use App\Http\Services\FileService;
use App\Models\Barang;
use App\Models\BarangCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function __construct(
        private FileService $fileService
    ) {}

    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $data = Barang::query();

        if ($search) {
            $data->where('title', 'like', '%' . $search . '%');
        }

        if ($category) {
            $data->whereHas('barangCategories', function ($query) use ($category) {
                $query->whereHas('category', function ($query) use ($category) {
                    $query->where('slug', $category);
                });
            });
        }

        $data = $data->latest()->paginate(10);

        return view('backend.barang.index', [
            'data' => $data,
            'categories' => Category::all()
        ]);
    }

    public function create()
    {
        return view('backend.barang.create', [
            'categories' => Category::all()
        ]);
    }

    public function store(BarangRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->fileService->upload($request->file('image'), 'images');
        }

        $barang = Barang::create($data);

        foreach ($request->category as $category) {
            BarangCategory::create([
                'barang_id' => $barang->id,
                'category_id' => $category
            ]);
        }


        return redirect()->route('panel.barangs.index')->with('success', 'barang ' . $request->get('title') . ' berhasil ditambahkan');
    }

    public function show(Barang $barang)
    {
        return view('backend.barang.show', [
            'barang' => $barang
        ]);
    }

    public function edit(Barang $barang)
    {
        return view('backend.barang.edit', [
            'barang' => $barang,
            'categories' => Category::all()
        ]);
    }

    public function update(BarangRequest $request, Barang $barang)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($barang->image) {
                $this->fileService->delete(public_path('storage/' . $barang->image));
            }
            $data['image'] = $this->fileService->upload($request->file('image'), 'images');
        }

        $barang->update($data);

        BarangCategory::where('barang_id', $barang->id)->delete();

        foreach ($request->category as $category) {
            BarangCategory::create([
                'barang_id' => $barang->id,
                'category_id' => $category
            ]);
        }

        return redirect()->route('panel.barangs.index')->with('success', 'barang ' . $request->get('title') . ' berhasil diupdate');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->cover) {
            $this->fileService->delete(public_path('storage/' . $barang->cover));
        }

        $barang->delete();

        return redirect()->back()->with('success', 'barang ' . $barang->title . ' berhasil dihapus');
    }
}
