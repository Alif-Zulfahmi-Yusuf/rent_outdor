<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Category;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $barangs = Barang::query();

        if ($search) {
            $barangs->where('title', 'like', '%' . $search . '%');
        }

        if ($category) {
            $barangs->whereHas('barangCategories', function ($query) use ($category) {
                $query->whereHas('category', function ($query) use ($category) {
                    $query->where('slug', $category);
                });
            });
        }

        $barangs = $barangs->latest()->paginate(18);

        return view('frontend.barangs', [
            'barangs' => $barangs,
            'categories' => Category::all()
        ]);
    }

    public function show(string $barang)
    {
        $barang = Barang::where('slug', $barang)->firstOrFail();

        return view('frontend.barang-detail', [
            'barang' => $barang,
            'relatedBarangs' => Barang::where('id', '!=', $barang->id)->whereHas('barangCategories', function ($query) use ($barang) {
                $query->whereHas('category', function ($query) use ($barang) {
                    $query->whereIn('id', function ($query) use ($barang) {
                        $query->select('category_id')->from('barang_categories')->where('barang_id', $barang->id);
                    });
                });
            })->latest()->paginate(6)
        ]);
    }
}
