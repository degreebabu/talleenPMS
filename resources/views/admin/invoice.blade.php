<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $booking->booking_number }} — {{ $hotel->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; color: #1e293b; background: #fff; padding: 40px; font-size: 13px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; padding-bottom: 24px; border-bottom: 2px solid #e2e8f0; }
        .logo-name { font-size: 22px; font-weight: 800; color: #0f172a; }
        .logo-tagline { font-size: 11px; color: #64748b; margin-top: 2px; }
        .invoice-meta { text-align: right; }
        .invoice-meta h1 { font-size: 28px; font-weight: 900; color: #1d4ed8; letter-spacing: -1px; }
        .invoice-meta p { font-size: 12px; color: #64748b; margin-top: 4px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-confirmed { background: #dbeafe; color: #1d4ed8; }
        .badge-checked_in { background: #dcfce7; color: #15803d; }
        .badge-checked_out { background: #f1f5f9; color: #475569; }
        .badge-cancelled { background: #fee2e2; color: #dc2626; }
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; }
        .info-box label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; }
        .info-box p { font-size: 14px; font-weight: 600; color: #0f172a; margin-top: 4px; }
        .info-box small { font-size: 11px; color: #64748b; display: block; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        table thead tr { background: #f8fafc; }
        table th { padding: 10px 14px; text-align: left; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; border-bottom: 1px solid #e2e8f0; }
        table td { padding: 12px 14px; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
        .totals { max-width: 300px; margin-left: auto; }
        .totals table td { border: none; padding: 6px 0; }
        .totals .total-row td { font-size: 16px; font-weight: 800; border-top: 2px solid #e2e8f0; padding-top: 10px; color: #0f172a; }
        .footer { margin-top: 48px; padding-top: 16px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: #94a3b8; }
        @media print { 
            body { padding: 20px; } 
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="no-print" style="margin-bottom: 16px; display: flex; gap: 10px;">
    <button onclick="window.print()" style="padding: 8px 16px; background: #1d4ed8; color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">🖨 Print</button>
    <button onclick="window.close()" style="padding: 8px 16px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">Close</button>
</div>

<div class="header">
    <div>
        <div class="logo-name">{{ $hotel->name }}</div>
        <div class="logo-tagline">{{ $hotel->address }}</div>
        <div class="logo-tagline">{{ $hotel->contact_email }} · {{ $hotel->contact_phone }}</div>
        @if($hotel->gst_number)
        <div class="logo-tagline" style="margin-top: 4px;">GST: {{ $hotel->gst_number }}</div>
        @endif
    </div>
    <div class="invoice-meta">
        <h1>INVOICE</h1>
        <p><strong>Ref:</strong> {{ $booking->booking_number }}</p>
        <p><strong>Date:</strong> {{ $booking->created_at->format('d M Y') }}</p>
        <p style="margin-top: 6px;">
            <span class="badge badge-{{ $booking->status }}">{{ str_replace('_', ' ', strtoupper($booking->status)) }}</span>
        </p>
    </div>
</div>

<div class="two-col">
    <div class="info-box">
        <label>Billed To</label>
        <p>{{ $booking->guest->name }}</p>
        <small>📞 {{ $booking->guest->phone }}</small>
        @if($booking->guest->email)
        <small>✉ {{ $booking->guest->email }}</small>
        @endif
    </div>
    <div class="info-box">
        <label>Booking Details</label>
        <p>{{ $booking->adults }} Adults, {{ $booking->children }} Children</p>
        @if($booking->checked_in_at)
        <small>Checked In: {{ $booking->checked_in_at->format('d M Y, H:i') }}</small>
        @endif
        @if($booking->checked_out_at)
        <small>Checked Out: {{ $booking->checked_out_at->format('d M Y, H:i') }}</small>
        @endif
        @if($booking->notes)
        <small style="margin-top:6px; font-style: italic;">{{ $booking->notes }}</small>
        @endif
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Description</th>
            <th>Check-In</th>
            <th>Check-Out</th>
            <th>Nights</th>
            <th style="text-align:right;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($booking->items as $item)
        @if($item->item)
        <tr>
            <td>
                <strong>Room {{ $item->item->room_number }}</strong><br>
                <span style="font-size:11px; color:#64748b;">{{ $item->item->category->name ?? '' }}</span>
            </td>
            <td>{{ $item->start_date->format('d M Y') }}</td>
            <td>{{ $item->end_date->format('d M Y') }}</td>
            <td>{{ $item->start_date->diffInDays($item->end_date) }}</td>
            <td style="text-align:right; font-weight: 700;">₹{{ number_format($item->price, 2) }}</td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>

<div class="totals">
    <table>
        <tr>
            <td style="color: #64748b;">Subtotal</td>
            <td style="text-align:right; font-weight:600;">₹{{ number_format($booking->total_amount - $booking->tax_amount, 2) }}</td>
        </tr>
        <tr>
            <td style="color: #64748b;">GST (12%)</td>
            <td style="text-align:right; font-weight:600;">₹{{ number_format($booking->tax_amount, 2) }}</td>
        </tr>
        <tr class="total-row">
            <td>Total Due</td>
            <td style="text-align:right;">₹{{ number_format($booking->total_amount, 2) }}</td>
        </tr>
    </table>
</div>

<div class="footer">
    <span>Thank you for choosing {{ $hotel->name }}. We look forward to welcoming you again.</span>
    <span>Powered by TalleenPMS</span>
</div>

</body>
</html>
