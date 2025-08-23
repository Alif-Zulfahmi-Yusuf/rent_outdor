<?php

namespace App\Mail;

use App\Models\Rent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PengingatPengembalianMail extends Mailable
{
    use Queueable, SerializesModels;

    public $renting; // <-- otomatis tersedia di Blade

    public function __construct(Rent $renting)
    {
        $this->renting = $renting;
    }

    public function build()
    {
        return $this->subject('Pengingat Pengembalian Barang')
            ->view('emails.pengingat_pengembalian');
    }
}
