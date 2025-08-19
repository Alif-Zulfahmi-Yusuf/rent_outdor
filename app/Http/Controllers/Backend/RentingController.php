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

        return view('backend.renting.index', [
            'data' => $data
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
        return Excel::download(new RentingsExport($from, $to), 'Log Peminjaman.xlsx');
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

        return redirect()->back()->with('success', 'Peminjaman <b>' . $renting->code . '</b> berhasil dihapus');
    }

    public function kirimPengingat()
    {
        $today = Carbon::now()->toDateString();

        $rentings = Rent::with('user')
            ->whereNull('actual_return_date') // belum dikembalikan
            ->whereDate('return_date', '<=', $today) // sudah jatuh tempo atau lewat
            ->get();

        if ($rentings->isEmpty()) {
            Log::info("Tidak ada data peminjaman yang lewat tenggat pada {$today}");
        }

        $sent = 0;
        foreach ($rentings as $rent) {
            if ($rent->user && $rent->user->email) {
                try {
                    Mail::to($rent->user->email)->send(new PengingatPengembalianMail($rent));
                    Log::info("✅ Email pengingat dikirim ke {$rent->user->email} untuk kode sewa {$rent->code}");
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
