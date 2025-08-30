<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bag;
use App\Models\Rent;
use App\Models\RentItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class RentController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'return_date' => 'required|date|after:today|before_or_equal:' . Carbon::now()->addDays(30)->toDateString(),
        ]);

        $bags = Bag::where('user_id', Auth::user()->id)->get();

        $settings = View::shared('settings');

        if (!Auth::user()->phone || !Auth::user()->address) {
            return redirect()->route('bags.index')->with('error', 'Harap lengkapi profil terlebih dahulu.');
        }

        if ($bags->isEmpty()) {
            return redirect()->route('bags.index')->with('error', 'Yah.. Kantong mu kosong. Coba isi yaa..');
        }

        if ($bags->count() > $settings['max_book_per_rent'] || (Auth::user()->total_book_rented + $bags->count()) > $settings['max_book_per_rent']) {
            return redirect()->route('bags.index')->with('error', 'Maksimal jumlah peminjaman buku adalah ' . $settings['max_book_per_rent'] . ' buku per orang.');
        }

        $books = RentItem::whereHas('rent', function ($query) {
            $query->where('user_id', Auth::user()->id)->whereNull('actual_return_date');
        })->pluck('barang_id')->toArray();

        $booksOnBag = $bags->pluck('barang_id')->toArray();

        if (count(array_intersect($books, $booksOnBag)) > 0) {
            return redirect()->route('bags.index')->with('error', 'Anda belum mengembalikan barang yang dipinjam sebelumnya. Silakan kembalikan terlebih dahulu.');
        }

        if (Auth::user()->total_book_rented >= $settings['max_book_per_rent']) {
            return redirect()->route('bags.index')->with('error', 'Anda sedang meminjam ' . Auth::user()->total_book_rented . ' barang dan belum di kembalikan. Silakan kembalikan terlebih dahulu.');
        }

        DB::transaction(function () use ($bags, $request) {
            $rent = Rent::create([
                'user_id'     => Auth::user()->id,
                'rent_date'   => Carbon::now(),
                'return_date' => Carbon::parse($request->return_date),
            ]);

            foreach ($bags as $bag) {
                if ($bag->barang->current_stock <= 0) {
                    throw new \Exception("Barang '{$bag->barang->title}' sedang habis dipinjam. Peminjaman dibatalkan.");
                }

                $rent->rentItems()->create([
                    'barang_id' => $bag->barang->id,
                ]);
            }

            $bags->each->delete();
        });



        return redirect()->route('account.index')->with('success', 'Peminjaman berhasil ditambahkan');
    }
}
