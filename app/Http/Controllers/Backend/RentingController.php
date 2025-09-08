<?php

namespace App\Http\Controllers\Backend;

use App\Mail\PengingatPengembalianMail;
use Illuminate\Support\Facades\Mail;
use App\Exports\RentingsExport;
use App\Http\Controllers\Controller;
use App\Models\Rent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;


class RentingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $return = $request->query('return');

        $data = Rent::query();

        if ($search) {
            $data->where(function ($query) use ($search) {
                $query->where('code', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($return == 'true') {
            $data->whereNull('actual_return_date');
        } else if ($return == 'false') {
            $data->whereNotNull('actual_return_date');
        }

        $data = $data->latest()->paginate(10);

        // 🔹 Hitung rata-rata keterlambatan per user
        $predictions = [];
        foreach ($data as $item) {
            $userId = $item->user_id;

            if (!isset($predictions[$userId])) {
                $delays = Rent::where('user_id', $userId)
                    ->whereNotNull('actual_return_date')
                    ->get()
                    ->map(function ($rent) {
                        return $rent->late_barangs; // pakai accessor
                    })
                    ->filter(function ($delay) {
                        return $delay > 0; // hanya ambil yang benar-benar terlambat
                    })
                    ->toArray();

                if (!empty($delays)) {
                    $weights = range(1, count($delays));
                    $numerator = 0;
                    $denominator = array_sum($weights);

                    foreach ($delays as $i => $delay) {
                        $numerator += $delay * $weights[$i];
                    }

                    $predictions[$userId] = round($numerator / $denominator, 2);
                } else {
                    $predictions[$userId] = null;
                }
            }
        }

        return view('backend.renting.index', [
            'data' => $data,
            'predictions' => $predictions
        ]);
    }

    public function download(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $format = $request->format;

        if ($format == 'pdf') {
            return redirect()->route('panel.rentings.pdf', ['from' => $from, 'to' => $to]);
        } else if ($format == 'excel') {
            return $this->excel($from, $to);
        }
    }

    public function pdf(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $data = Rent::query();

        if ($from != null || $to != null) {
            $data->where('rent_date', '>=', $from)->where('rent_date', '<=', $to)->get();
        }

        $data = $data->latest()->get();

        return view('backend.renting.pdf', [
            'from' => $from ? Carbon::parse($from) : null,
            'to' => $to ? Carbon::parse($to) : null,
            'data' => $data
        ]);
    }

    public function excel($from = null, $to = null)
    {
        return Excel::download(new RentingsExport($from, $to), 'laporan Peminjaman.xlsx');
    }

    public function show(Rent $renting)
    {
        return view('backend.renting.show', [
            'renting' => $renting
        ]);
    }

    public function destroy(Rent $renting)
    {
        $renting->delete();

        return redirect()->back()->with('success', 'Peminjaman ' . $renting->code . ' berhasil dihapus');
    }

    public function kirimPengingat()
    {
        // Ambil semua peminjaman yang belum dikembalikan
        // dan sudah melewati return_date dibandingkan dengan hari ini
        $rentings = Rent::with('user')
            ->whereNull('actual_return_date') // belum dikembalikan
            ->whereDate('return_date', '<=', now()) // sudah jatuh tempo atau lewat
            ->get();

        if ($rentings->isEmpty()) {
            Log::info("Tidak ada data peminjaman yang lewat tenggat pada " . now()->toDateString());
        }

        $sent = 0;
        foreach ($rentings as $rent) {
            if ($rent->user && $rent->user->email) {
                try {
                    Mail::to($rent->user->email)->send(new PengingatPengembalianMail($rent));

                    // log pakai return_date masing-masing biar lebih jelas
                    Log::info("✅ Email pengingat dikirim ke {$rent->user->email} untuk kode sewa {$rent->code}, tenggat: {$rent->return_date}");
                    $sent++;
                } catch (\Exception $e) {
                    Log::error("❌ Gagal mengirim email ke {$rent->user->email} untuk kode sewa {$rent->code}: " . $e->getMessage());
                }
            } else {
                Log::warning("⚠️ Rent ID {$rent->id} tidak memiliki user/email");
            }
        }

        return redirect()->back()->with('success', "Pengiriman selesai. Total terkirim: {$sent}");
    }
}
