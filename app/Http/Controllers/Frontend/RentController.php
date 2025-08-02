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
        })->pluck('book_id')->toArray();

        $booksOnBag = $bags->pluck('book_id')->toArray();

        if (count(array_intersect($books, $booksOnBag)) > 0) {
            return redirect()->route('bags.index')->with('error', 'Anda belum mengembalikan buku yang dipinjam sebelumnya. Silakan kembalikan terlebih dahulu.');
        }

        if (Auth::user()->total_book_rented >= $settings['max_book_per_rent']) {
            return redirect()->route('bags.index')->with('error', 'Anda sedang meminjam ' . Auth::user()->total_book_rented . ' buku dan belum di kembalikan. Silakan kembalikan terlebih dahulu.');
        }

        DB::transaction(function () use ($bags, $settings) {
            $rent = Rent::create([
                'user_id' => Auth::user()->id,
                'rent_date' => Carbon::now(),
                'return_date' => Carbon::now()->addDays((int)$settings['max_rent_day']),
            ]);
            
            foreach ($bags as $bag) {
                if ($bag->book->current_stock <= 0) {
                    throw new \Exception("Buku '{$bag->book->title}' sedang habis dipinjam. Peminjaman dibatalkan.");
                }

                $rent->rentItems()->create([
                    'book_id' => $bag->book->id,
                ]);
            }

            $bags->each->delete();
        });

        return redirect()->route('account.index')->with('success', 'Peminjaman berhasil ditambahkan');
    }
}
