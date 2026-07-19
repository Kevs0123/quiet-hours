<?php
 
namespace App\Mail;
 
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
 
class BookingSummaryMail extends Mailable
{
    use Queueable, SerializesModels;
 
    public Booking $booking;
 
    /**
     * @param  Booking  $booking  Must already have 'room.category' eager loaded.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }
 
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Received — Booking ' . $this->booking->booking_id . ' Pending Confirmation',
        );
    }
 
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-summary',
            with: [
                'booking' => $this->booking,
                'nights'  => $this->booking->nights,
                'total'   => $this->booking->room
                    ? $this->booking->room->price_per_night * max($this->booking->nights, 1)
                    : null,
            ],
        );
    }
 
    public function attachments(): array
    {
        $attachments = [];
 
        if ($this->booking->confirmation_file_path
            && Storage::disk('public')->exists($this->booking->confirmation_file_path)
        ) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromStorageDisk(
                'public',
                $this->booking->confirmation_file_path
            )->as('confirmation.' . $this->booking->confirmation_file_type)
             ->withMime($this->guessMime($this->booking->confirmation_file_type));
        }
 
        return $attachments;
    }
 
    protected function guessMime(?string $ext): string
    {
        return match (strtolower((string) $ext)) {
            'pdf'          => 'application/pdf',
            'jpg', 'jpeg'  => 'image/jpeg',
            'png'          => 'image/png',
            default        => 'application/octet-stream',
        };
    }
}