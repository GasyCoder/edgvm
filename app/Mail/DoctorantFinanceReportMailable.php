<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DoctorantFinanceReportMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(public array $data)
    {
        //
    }

    public function build(): self
    {
        return $this->subject('Votre situation financière — École Doctorale (EDGVM)')
            ->view('emails.doctorants.finance-report')
            ->with(['data' => $this->data]);
    }
}
