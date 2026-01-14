<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoiceNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
        }

        .logo-section h1 {
            font-size: 28px;
            color: #3b82f6;
            margin-bottom: 5px;
        }

        .logo-section p {
            color: #666;
            font-size: 11px;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-info h2 {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .invoice-info p {
            color: #666;
            margin-bottom: 3px;
        }

        .invoice-details {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .detail-box {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .detail-box h3 {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .detail-box p {
            margin-bottom: 3px;
        }

        .detail-box .highlight {
            font-weight: bold;
            color: #1f2937;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table.items thead {
            background-color: #3b82f6;
            color: white;
        }

        table.items th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }

        table.items th:last-child {
            text-align: right;
        }

        table.items td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }

        table.items td:last-child {
            text-align: right;
            font-weight: 500;
        }

        table.items tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .items .description {
            color: #6b7280;
            font-size: 10px;
            margin-top: 3px;
        }

        .summary {
            width: 300px;
            margin-left: auto;
            margin-bottom: 30px;
        }

        .summary-row {
            display: table;
            width: 100%;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-row .label {
            display: table-cell;
            color: #6b7280;
        }

        .summary-row .value {
            display: table-cell;
            text-align: right;
            font-weight: 500;
        }

        .summary-row.total {
            border-bottom: none;
            border-top: 2px solid #3b82f6;
            margin-top: 5px;
            padding-top: 12px;
        }

        .summary-row.total .label,
        .summary-row.total .value {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }

        .summary-row.discount .value {
            color: #10b981;
        }

        .payment-history {
            margin-bottom: 30px;
        }

        .payment-history h3 {
            font-size: 14px;
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        table.payments {
            width: 100%;
            border-collapse: collapse;
        }

        table.payments th {
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        table.payments td {
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }

        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }

        .footer p {
            margin-bottom: 3px;
        }

        .notes {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .notes h4 {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .notes p {
            color: #4b5563;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo-section">
            <h1>📋 Project Manager</h1>
            <p>Personal Project Management</p>
        </div>
        <div class="invoice-info">
            <h2>INVOICE</h2>
            <p><strong>{{ $invoiceNumber }}</strong></p>
            <p>Tanggal: {{ $invoiceDate }}</p>
        </div>
    </div>

    <div class="invoice-details">
        <div class="detail-box">
            <h3>Tagihan Kepada</h3>
            <p class="highlight">{{ $project->client->name }}</p>
            @if($project->client->phone)
                <p>📞 {{ $project->client->phone }}</p>
            @endif
        </div>
        <div class="detail-box" style="text-align: right;">
            <h3>Detail Project</h3>
            <p class="highlight">{{ $project->project_name }}</p>
            <p>Status:
                <span class="status-badge {{ $project->status === 'paid' ? 'status-paid' : 'status-pending' }}">
                    {{ $project->status_label }}
                </span>
            </p>
            @if($project->deadline)
                <p>Deadline: {{ $project->formatted_deadline }}</p>
            @endif
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th style="width: 50%;">Item / Fitur</th>
                <th>Kategori</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($project->features as $feature)
                <tr>
                    <td>
                        {{ $feature->category->name }}
                        @if($feature->description)
                            <div class="description">{{ $feature->description }}</div>
                        @endif
                    </td>
                    <td>{{ $feature->category->name }}</td>
                    <td>{{ $feature->formatted_price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span class="label">Subtotal</span>
            <span class="value">{{ $project->formatted_base_price }}</span>
        </div>
        @if($project->discount_applied > 0)
            <div class="summary-row discount">
                <span class="label">Diskon</span>
                <span class="value">- {{ $project->formatted_discount }}</span>
            </div>
        @endif
        <div class="summary-row total">
            <span class="label">Total</span>
            <span class="value">{{ $project->formatted_final_price }}</span>
        </div>
    </div>

    @if($project->payments->count() > 0)
        <div class="payment-history">
            <h3>Riwayat Pembayaran</h3>
            <table class="payments">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Metode</th>
                        <th>Catatan</th>
                        <th style="text-align: right;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('d M Y') }}</td>
                            <td>{{ ucfirst($payment->payment_method ?? '-') }}</td>
                            <td>{{ $payment->notes ?? '-' }}</td>
                            <td style="text-align: right;">{{ $payment->formatted_amount }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary" style="margin-top: 15px;">
                <div class="summary-row">
                    <span class="label">Total Dibayar</span>
                    <span class="value" style="color: #10b981;">{{ $project->formatted_total_paid }}</span>
                </div>
                <div class="summary-row">
                    <span class="label">Sisa Tagihan</span>
                    <span class="value" style="color: {{ $project->remaining_amount > 0 ? '#ef4444' : '#10b981' }};">
                        {{ $project->formatted_remaining }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    @if($project->notes)
        <div class="notes">
            <h4>Catatan</h4>
            <p>{{ $project->notes }}</p>
        </div>
    @endif

    <div class="footer">
        <p>Terima kasih atas kepercayaan Anda!</p>
        <p>Invoice ini dibuat secara otomatis oleh sistem.</p>
        <p style="margin-top: 10px; color: #9ca3af;">
            Dicetak pada: {{ now()->format('d F Y H:i') }}
        </p>
    </div>
</body>

</html>