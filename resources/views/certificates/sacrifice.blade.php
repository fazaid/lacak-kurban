<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Kurban {{ $sacrifice->reference_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background: #fff;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
        }
        .page {
            width: 100%;
            height: 210mm;
            background: #fff;
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .border-outer {
            position: absolute;
            inset: 8mm;
            border: 3px solid #e7202a;
        }
        .border-inner {
            position: absolute;
            inset: 12mm;
            border: 1px solid #e7202a;
        }
        .content {
            position: absolute;
            inset: 16mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .logo-circle {
            height: 36px;
            margin: 0 auto 6px;
        }
        .logo-circle img {
            height: 100%;
            width: auto;
            object-fit: contain;
        }
        .org-name {
            font-size: 14px;
            font-weight: bold;
            color: #e7202a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .divider {
            width: 100%;
            height: 1px;
            background: linear-gradient(to right, transparent, #e7202a, transparent);
            margin: 8px 0;
        }
        .cert-title {
            font-size: 26px;
            font-weight: bold;
            color: #1a1a1a;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 8px 0;
        }
        .cert-subtitle {
            font-size: 11px;
            color: #666;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .ref-badge {
            background: #e7202a;
            color: white;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 11px;
            font-family: monospace;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 14px;
        }
        .main-text {
            font-size: 10px;
            color: #444;
            margin-bottom: 6px;
        }
        .donor-name {
            font-size: 20px;
            font-weight: bold;
            color: #1a1a1a;
            margin: 4px 0 12px;
            border-bottom: 2px solid #e7202a;
            padding-bottom: 6px;
            min-width: 200px;
        }
        .details-grid {
            display: flex;
            gap: 20px;
            margin: 10px 0;
            width: 100%;
            justify-content: center;
        }
        .detail-box {
            background: #f8fdfb;
            border: 1px solid #e7202a;
            border-radius: 6px;
            padding: 8px 14px;
            min-width: 120px;
        }
        .detail-label {
            font-size: 8px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .detail-value {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
        }
        .bene-section {
            margin: 8px 0;
            font-size: 10px;
            color: #555;
        }
        .signature-row {
            position: absolute;
            bottom: 16mm;
            left: 18mm;
            right: 18mm;
        }
    </style>
</head>
@php
    $logoPath    = public_path('images/logos/logo-npc-kurban.png');
    $logoBase64  = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    $sigLeft     = \App\Http\Controllers\AdminSettingsController::signatureBase64('left');
    $sigRight    = \App\Http\Controllers\AdminSettingsController::signatureBase64('right');
@endphp
<body>
<div class="page">
    <div class="border-outer"></div>
    <div class="border-inner"></div>

    <div class="content">
        <div class="header">
            <div class="logo-circle"><img src="{{ $logoBase64 }}" alt="Logo NPC"></div>
            <div class="org-name">Kurban Laznas NPC</div>
        </div>


        <div class="divider"></div>

        <div class="cert-title">Sertifikat Kurban</div>
        <div class="cert-subtitle">Certificate of Sacrifice — {{ date('Y') }}</div>

        <div class="ref-badge">{{ $sacrifice->reference_code }}</div>

        <div class="main-text">Dengan ini menyatakan bahwa</div>
        <div class="donor-name">{{ $sacrifice->donor_name }}</div>

        <div class="main-text">
            Telah melaksanakan ibadah kurban melalui Laznas NPC dengan detail sebagai berikut:
        </div>

        <div class="details-grid">
            <div class="detail-box">
                <div class="detail-label">Jenis Hewan</div>
                <div class="detail-value">{{ $sacrifice->sharing_type === 'full' ? '1' : $sacrifice->getShareLabel() }} {{ $sacrifice->getAnimalTypeLabel() }} - {{ $sacrifice->getSacrificeTypeLabel() }}</div>
            </div>
            @if($sacrifice->purchase_date)
            <div class="detail-box">
                <div class="detail-label">Tanggal Pelaksanaan</div>
                <div class="detail-value">{{ $sacrifice->purchase_date->format('d M Y') }}</div>
            </div>
            @endif
            @if($sacrifice->slaughter_location)
            <div class="detail-box">
                <div class="detail-label">Lokasi</div>
                <div class="detail-value">{{ Str::limit($sacrifice->slaughter_location, 50) }}</div>
            </div>
            @endif
        </div>

        @if($sacrifice->beneficiary_name)
        <div class="bene-section">
            Daging kurban didistribusikan kepada: <strong>{{ $sacrifice->beneficiary_name }}</strong>
            @if($sacrifice->beneficiary_type) ({{ $sacrifice->beneficiary_type }})@endif
        </div>
        @endif

    </div>

    <div class="signature-row">
        <table style="width:100%;border-collapse:collapse;">
            <tr>
                <td style="width:40%;text-align:center;vertical-align:bottom;padding:0 14px 6px;">
                    <div style="height:50px;text-align:center;margin-bottom:4px;">
                        @if($sigLeft)
                        <img src="{{ $sigLeft }}" style="max-height:45px;max-width:120px;" alt="ttd">
                        @endif
                    </div>
                    <div style="font-size:11px;font-weight:bold;color:#1a1a1a;border-top:1px solid #333;padding-top:4px;">Muhammad Rivaldy Ramadhan</div>
                    <div style="font-size:9px;color:#666;">Ketua Pelaksana Kurban NPC</div>
                </td>
                <td style="width:20%;text-align:center;vertical-align:bottom;font-size:9px;color:#555;padding:0 8px 6px;">
                    Diterbitkan:<br>{{ $sacrifice->certificate_generated_at->format('d F Y') }}
                </td>
                <td style="width:40%;text-align:center;vertical-align:bottom;padding:0 14px 6px;">
                    <div style="height:50px;text-align:center;margin-bottom:4px;">
                        @if($sigRight)
                        <img src="{{ $sigRight }}" style="max-height:45px;max-width:120px;" alt="ttd">
                        @endif
                    </div>
                    <div style="font-size:11px;font-weight:bold;color:#1a1a1a;border-top:1px solid #333;padding-top:4px;">Masri Udin</div>
                    <div style="font-size:9px;color:#666;">Direktur Eksekutif</div>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:center;font-size:8px;color:#aaa;padding-top:4px;">
                    Dokumen ini diterbitkan secara digital oleh NPC | kurban.npc.id
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
