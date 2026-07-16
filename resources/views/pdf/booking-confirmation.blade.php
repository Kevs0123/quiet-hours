<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - {{ $booking->booking_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #1b2a4a;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #1b2a4a;
            font-size: 28px;
            margin-bottom: 5px;
            font-family: 'Playfair Display', serif;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .booking-id {
            background: #1b2a4a;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            display: inline-block;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            font-family: 'Courier New', monospace;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section h2 {
            color: #1b2a4a;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e0b669;
            font-family: 'Playfair Display', serif;
        }

        .field {
            margin-bottom: 12px;
        }

        .field-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .field-value {
            font-size: 16px;
            color: #1b2a4a;
            font-weight: 500;
        }

        .price-highlight {
            background: #fef3e6;
            border-left: 4px solid #e0b669;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .price-highlight .field-label {
            color: #d4a574;
        }

        .price-highlight .field-value {
            font-size: 24px;
            color: #d4a574;
            font-weight: bold;
        }

        .total-section {
            background: #1b2a4a;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin-top: 30px;
        }

        .total-section .field-label {
            color: #bbb;
        }

        .total-section .field-value {
            color: white;
            font-size: 32px;
            margin: 10px 0;
        }

        .footer {
            border-top: 1px solid #eee;
            margin-top: 40px;
            padding-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }

        .confirmation-badge {
            display: inline-block;
            background: #4caf50;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        @media print {
            body {
                background: white;
            }
            .container {
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="confirmation-badge">✓ BOOKING CONFIRMED</div>
            <h1>Your Booking Confirmation</h1>
            <p>Thank you for your reservation</p>
            <div class="booking-id">ID: {{ $booking->booking_id }}</div>
        </div>

        <div class="grid">
            <!-- Left Column -->
            <div>
                <div class="section">
                    <h2>Guest Information</h2>
                    <div class="field">
                        <div class="field-label">Guest Name</div>
                        <div class="field-value">{{ $booking->customer_name }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Number of Guests</div>
                        <div class="field-value">{{ $booking->number_of_persons }}</div>
                    </div>
                </div>

                <div class="section">
                    <h2>Check-In & Check-Out</h2>
                    <div class="field">
                        <div class="field-label">Check-In Date</div>
                        <div class="field-value">{{ $booking->check_in_date->format('M d, Y') }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Check-Out Date</div>
                        <div class="field-value">{{ $booking->check_out_date->format('M d, Y') }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Duration</div>
                        <div class="field-value">{{ $booking->nights }} {{ $booking->nights === 1 ? 'night' : 'nights' }}</div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <div class="section">
                    <h2>Room Details</h2>
                    <div class="field">
                        <div class="field-label">Room Name</div>
                        <div class="field-value">{{ $booking->room->name }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Category</div>
                        <div class="field-value">{{ $booking->room->category->name }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Event/Occasion</div>
                        <div class="field-value">{{ $booking->event_name }}</div>
                    </div>
                </div>

                <div class="section">
                    <h2>Pricing</h2>
                    <div class="field">
                        <div class="field-label">Rate per Night</div>
                        <div class="field-value">₱{{ number_format($booking->room->price_per_night, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="price-highlight">
            <div class="field">
                <div class="field-label">Total Amount</div>
                <div class="field-value">₱{{ number_format($booking->room->price_per_night * $booking->nights, 2) }}</div>
            </div>
        </div>

        <div class="total-section">
            <div class="field-label">BOOKING REFERENCE</div>
            <div class="field-value">{{ $booking->booking_id }}</div>
            <p style="margin-top: 15px; font-size: 11px;">Please keep this reference for your records</p>
        </div>

        <div class="footer">
            <p>This is an automated confirmation. Please do not reply to this email.</p>
            <p>For any inquiries, please contact our support team.</p>
            <p style="margin-top: 10px;">© {{ date('Y') }} Quiet Hours. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
