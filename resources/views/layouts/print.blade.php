<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') — Barangay San Jose</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Times New Roman', serif; font-size: 12pt; color: #000; background: #fff; }
        .document-container { width: 8.5in; min-height: 11in; margin: 0 auto; padding: 1in; position: relative; }
        .header { text-align: center; border-bottom: 2px solid #1E3A5F; padding-bottom: 14px; margin-bottom: 20px; }
        .header img { width: 70px; height: 70px; }
        .header h1 { font-size: 14pt; font-weight: bold; text-transform: uppercase; color: #1E3A5F; }
        .header h2 { font-size: 12pt; color: #333; }
        .header p { font-size: 10pt; color: #555; }
        .doc-title { text-align: center; margin: 20px 0; }
        .doc-title h3 { font-size: 16pt; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; color: #1E3A5F; text-decoration: underline; }
        .doc-body { margin-top: 20px; line-height: 1.8; text-align: justify; }
        .doc-body p { margin-bottom: 12px; }
        .signature-block { margin-top: 60px; text-align: right; }
        .signature-block .signer-name { font-weight: bold; text-transform: uppercase; border-top: 1px solid #000; display: inline-block; min-width: 200px; text-align: center; padding-top: 4px; }
        .footer-info { margin-top: 30px; border-top: 1px solid #ccc; padding-top: 10px; font-size: 9pt; color: #666; display: flex; justify-content: space-between; align-items: flex-end; }
        .qr-section { text-align: center; }
        .qr-section p { font-size: 8pt; color: #666; margin-top: 4px; }
        .hash-code { font-size: 7pt; font-family: monospace; word-break: break-all; max-width: 200px; }
        .watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 80pt; color: rgba(30,58,95,0.04); font-weight: bold; text-transform: uppercase; pointer-events: none; z-index: 0; }
        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="padding: 10px; background: #1E3A5F; text-align: center;">
        <button onclick="window.print()" style="background: white; color: #1E3A5F; border: none; padding: 8px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; margin-right: 10px;">🖨 Print Document</button>
        <button onclick="window.close()" style="background: transparent; color: white; border: 1px solid white; padding: 8px 20px; border-radius: 4px; cursor: pointer;">✕ Close</button>
    </div>
    @yield('content')
</body>
</html>
