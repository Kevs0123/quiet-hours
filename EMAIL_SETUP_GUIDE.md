# 📧 Email Setup Guide for Quiet Hours

## Overview

This guide will help you configure email sending functionality for your booking confirmations. The system supports Gmail and other SMTP providers.

## Setup Instructions

### 1. Install DomPDF Package (if not already installed)

Run this command in your project directory:

```bash
composer require barryvdh/laravel-dompdf
```

### 2. Configure Gmail (Recommended)

#### Option A: Using Gmail with App Password (Recommended for Gmail)

**Step 1: Enable 2-Factor Authentication on your Google Account**

- Go to [Google Account Security](https://myaccount.google.com/security)
- Enable 2-Step Verification if not already enabled

**Step 2: Generate an App Password**

- Go to [Google Account App Passwords](https://myaccount.google.com/apppasswords)
- Select "Mail" and "Windows Computer" (or your device)
- Google will generate a 16-character password
- Copy this password

**Step 3: Update your .env file**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=xxxx xxxx xxxx xxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Quiet Hours"
```

Replace:

- `your-email@gmail.com` with your Gmail address
- `xxxx xxxx xxxx xxxx` with the 16-character App Password you generated

---

#### Option B: Using Gmail Less Secure Apps (Legacy)

**⚠️ Note:** This method is deprecated by Google but still works.

1. Allow Less Secure Apps in your Gmail account
2. Update .env:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-gmail-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Quiet Hours"
```

---

### 3. Using Other SMTP Providers

#### SendGrid

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

#### Mailgun

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-api-key
```

---

## Features Implemented

### 1. **PDF Generation**

- Automatically generates a professional PDF from booking details
- Includes all booking information: guest details, dates, room info, and pricing
- PDF is stored temporarily in memory and attached to emails

### 2. **Email Sending**

- **Send to Email**: Sends the PDF directly to the user's email
    - Route: `/booking/pdf/{bookingId}/send`
    - Button location: Booking Summary page and Booking History

- **Download PDF**: Downloads the PDF to user's computer
    - Route: `/booking/pdf/{bookingId}/download`
    - Button location: Booking Summary page and Booking History

### 3. **Mailable Class**

- File: `app/Mail/BookingConfirmationMail.php`
- Automatically generates and attaches PDF to email
- Professional email template with booking details

### 4. **PDF Template**

- File: `resources/views/pdf/booking-confirmation.blade.php`
- Professional design with company branding
- Responsive layout that works in all PDF readers

### 5. **Email Template**

- File: `resources/views/emails/booking-confirmation.blade.php`
- Uses Laravel's built-in Mailables
- Clean, professional email design

---

## Usage

### For Users

#### Send Booking Confirmation Email:

1. Go to **Booking Summary** after completing a booking
2. Click the **"📧 Send to Email"** button
3. The PDF will be sent to their registered email

#### Download Booking PDF:

1. Go to **Booking Summary** or **Booking History**
2. Click the **"📥 Download PDF"** button
3. PDF will be saved to your computer

### For Administrators

#### In Code:

```php
use App\Mail\BookingConfirmationMail;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;

// Send email with PDF
$booking = Booking::find($bookingId);
Mail::send(new BookingConfirmationMail($booking));

// Or queue it for background processing
Mail::queue(new BookingConfirmationMail($booking));
```

---

## API Routes Added

### PDF Routes (Authenticated Users Only)

```
GET /booking/pdf/{bookingId}/send        # Send PDF to user's email
GET /booking/pdf/{bookingId}/download    # Download PDF
```

---

## Troubleshooting

### Email Not Sending

1. **Check .env configuration**
    - Make sure `MAIL_MAILER` is set correctly
    - Verify all credentials are correct

2. **Check Laravel logs**

    ```bash
    tail -f storage/logs/laravel.log
    ```

3. **Test locally**
    - Use `MAIL_MAILER=log` to write emails to logs instead of sending
    - Check `storage/logs/laravel.log`

### Gmail Authentication Failed

- Make sure you're using the **App Password**, not your regular password
- App Password is 16 characters with spaces
- Ensure 2-Factor Authentication is enabled

### PDF Not Generating

- Make sure DomPDF is installed: `composer require barryvdh/laravel-dompdf`
- Check file permissions in `storage/` directory

### Email Not Found

- Users must be logged in and own the booking
- Booking ID must be valid and belong to the authenticated user

---

## Security Notes

1. **Never commit .env to version control**
2. **Use environment variables for sensitive data**
3. **Generate new App Passwords if compromised**
4. **Keep dependencies updated**: `composer update`

---

## Testing

### Send Test Email (Laravel Tinker)

```bash
php artisan tinker

// In Tinker:
$booking = App\Models\Booking::first();
Mail::send(new App\Mail\BookingConfirmationMail($booking));
```

### Test PDF Generation

```php
// In a controller or route:
$booking = Booking::first();
return \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.booking-confirmation', ['booking' => $booking])->download('test.pdf');
```

---

## Files Modified/Created

### Created:

- `app/Mail/BookingConfirmationMail.php` - Mailable class
- `resources/views/pdf/booking-confirmation.blade.php` - PDF template
- `resources/views/emails/booking-confirmation.blade.php` - Email template

### Modified:

- `app/Http/Controllers/BookingController.php` - Added PDF methods
- `routes/web.php` - Added PDF routes
- `resources/views/booking/summary.blade.php` - Added action buttons
- `resources/views/booking/history.blade.php` - Added action buttons

---

## Next Steps

1. **Install DomPDF**: `composer require barryvdh/laravel-dompdf`
2. **Configure Email**: Update your `.env` file with email credentials
3. **Test**: Create a test booking and try sending the PDF email
4. **Customize**: Edit PDF and email templates to match your branding

---

Need help? Check the [Laravel Mail Documentation](https://laravel.com/docs/mail)
