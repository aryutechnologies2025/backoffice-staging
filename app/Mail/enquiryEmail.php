<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class enquiryEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $details;

    public function __construct(array $details)
    {
        $this->details = $details;
    }

    // public function build()
    // {
    //     return $this->subject('Enquiry Notification')
    //         ->view('emails.ClientNotification')
    //         ->with('details', $this->details);
    // }

    public function build()
{
    $email = $this->subject('Enquiry Notification')
        ->view('emails.ClientNotification')
         ->with([
                'body' => $this->details['body'] ?? '',
                'details' => $this->details, // Pass details to access subject in template
            ]);

    // Check if program_pdf exists and attach it
    if (!empty($this->details['program_pdf'])) {
        $filePath = public_path('uploads/program_pdfs/' . $this->details['program_pdf'] ?? null);
        if (file_exists($filePath)) {
            $email->attach($filePath);
        }
    }

    return $email;
}


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Form Submission Confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}