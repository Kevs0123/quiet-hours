<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Confirmation</title>
</head>
<body style="margin:0;padding:0;background:#f4f1ea;font-family:Arial,Helvetica,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f1ea;padding:32px 16px;">
<tr><td align="center">
<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:10px;overflow:hidden;max-width:600px;width:100%;">
 
    {{-- Header --}}
    <tr>
        <td style="background:#1b2a4a;padding:28px 32px;text-align:center;">
            <span style="font-family:Georgia,serif;color:#ffffff;font-size:22px;letter-spacing:1px;">QUIET <span style="color:#c9a15a;">HOURS</span></span>
        </td>
    </tr>
 
    {{-- Status banner --}}
    <tr>
        <td style="padding:24px 32px 0;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eff9f3;border:1px solid #cde8d6;border-radius:8px;">
                <tr>
                    <td style="padding:14px 18px;font-size:14px;color:#1f5f3c;">
                        ✅ &nbsp;<strong>Booking Confirmed!</strong> Your payment is verified and your reservation is now complete. A PDF confirmation is attached for your records.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
 
    {{-- Booking details --}}
    <tr>
        <td style="padding:24px 32px 0;">
            <h2 style="font-family:Georgia,serif;color:#1b2a4a;font-size:19px;margin:0 0 14px;">Booking Details</h2>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;color:#1b2a4a;">
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #eee5d3;color:#7a7568;width:150px;">Customer Name</td>
                    <td style="padding:10px 0;border-bottom:1px solid #eee5d3;font-weight:bold;">{{ $booking->customer_name }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #eee5d3;color:#7a7568;">Booking ID</td>
                    <td style="padding:10px 0;border-bottom:1px solid #eee5d3;">
                        <span style="background:#1b2a4a;color:#fff;font-family:monospace;font-size:13px;font-weight:bold;letter-spacing:2px;padding:3px 10px;border-radius:6px;">{{ $booking->booking_id }}</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #eee5d3;color:#7a7568;">Check In</td>
                    <td style="padding:10px 0;border-bottom:1px solid #eee5d3;font-weight:bold;">{{ $booking->check_in_date->format('F j, Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #eee5d3;color:#7a7568;">Check Out</td>
                    <td style="padding:10px 0;border-bottom:1px solid #eee5d3;font-weight:bold;">{{ $booking->check_out_date->format('F j, Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #eee5d3;color:#7a7568;">Nights</td>
                    <td style="padding:10px 0;border-bottom:1px solid #eee5d3;font-weight:bold;">
                        {{ $booking->nights }} {{ $booking->nights === 1 ? 'night' : 'nights' }}
                        @if($booking->room)
                            <span style="background:#f4f1ea;color:#a6833e;font-size:12px;font-weight:bold;padding:2px 9px;border-radius:20px;border:1px solid #e8c97a;margin-left:6px;">₱{{ number_format($booking->room->price_per_night * $booking->nights, 2) }} total</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #eee5d3;color:#7a7568;">Room</td>
                    <td style="padding:10px 0;border-bottom:1px solid #eee5d3;font-weight:bold;">
                        {{ $booking->room->name ?? $booking->event_name }}
                        @if($booking->room?->category)
                            <div style="font-size:12px;color:#7a7568;font-weight:normal;">{{ $booking->room->category->name }}</div>
                        @endif
                    </td>
                </tr>
                @if($booking->room)
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #eee5d3;color:#7a7568;">Rate</td>
                    <td style="padding:10px 0;border-bottom:1px solid #eee5d3;font-weight:bold;">₱{{ number_format($booking->room->price_per_night, 2) }} / night</td>
                </tr>
                @endif
                <tr>
                    <td style="padding:10px 0;color:#7a7568;">Guests</td>
                    <td style="padding:10px 0;font-weight:bold;">{{ $booking->number_of_persons }} {{ $booking->number_of_persons === 1 ? 'guest' : 'guests' }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#7a7568;">Payment Method</td>
                    <td style="padding:10px 0;font-weight:bold;">{{ $booking->paymentMethodLabel() }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#7a7568;">Reference No.</td>
                    <td style="padding:10px 0;font-weight:bold;font-family:monospace;">{{ $booking->payment_reference ?? '—' }}</td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="padding:20px 32px 0;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f1ea;border-radius:8px;">
                <tr>
                    <td style="padding:12px 16px;font-size:13px;color:#1b2a4a;">
                        📄 &nbsp;A detailed PDF confirmation is attached. Please keep it for your records, and contact us if you need any changes.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
 
    <tr>
        <td style="padding:32px;text-align:center;">
            <p style="font-size:12px;color:#9c9587;margin:0;">Thank you for choosing Quiet Hours Hotel.<br>This is an automated message — please do not reply directly to this email.</p>
        </td>
    </tr>
 
</table>
</td></tr>
</table>
</body>
</html>
