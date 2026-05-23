<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Verification — Barangay San Jose</title>
    @vite(['resources/css/app.css'])
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">

    <div class="bg-primary py-4 px-6">
        <div class="max-w-2xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-sm">Barangay San Jose</p>
                    <p class="text-blue-300 text-xs">Document Verification Portal</p>
                </div>
            </div>
            <a href="{{ route('login') }}" class="text-blue-200 hover:text-white text-xs">Staff Login →</a>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-10">

        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Verify Barangay Document</h1>
            <p class="text-gray-500 mt-2 text-sm max-w-md mx-auto">
                Scan the QR code printed on the document to verify its authenticity.
            </p>
        </div>

        {{-- ===== QR SCANNER ===== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-5">

            <div id="scanner-idle" class="text-center py-6">
                <div class="w-20 h-20 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 8V6a2 2 0 012-2h2M4 16v2a2 2 0 002 2h2m8-16h2a2 2 0 012 2v2m-4 12h2a2 2 0 002-2v-2M8 8h.01M8 16h.01M16 8h.01M16 16h.01M12 12h.01"/>
                    </svg>
                </div>
                <button type="button" id="startScanBtn"
                        class="bg-primary hover:bg-blue-900 text-white px-6 py-3 rounded-lg text-sm font-semibold transition-colors">
                    📷  Start QR Scanner
                </button>
                <p class="text-xs text-gray-400 mt-3">Allow camera access when prompted</p>
            </div>

            <div id="scanner-active" class="hidden">
                <div id="qr-reader" class="mx-auto" style="max-width: 400px;"></div>
                <div class="flex justify-center mt-4">
                    <button type="button" id="stopScanBtn"
                            class="text-sm text-gray-600 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50">
                        ✕ Stop Scanner
                    </button>
                </div>
                <p class="text-center text-xs text-gray-400 mt-3">
                    Point your camera at the QR code on the document
                </p>
            </div>

            {{-- Status messages --}}
            <div id="scanner-error" class="hidden mt-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                <strong>Camera error:</strong> <span id="scanner-error-msg"></span>
                <p class="text-xs mt-1">You can still verify by clicking "Use hash code instead" below.</p>
            </div>

            {{-- Fallback: collapsible hash entry --}}
            <details class="mt-5 pt-5 border-t border-gray-100">
                <summary class="text-xs text-gray-500 cursor-pointer hover:text-gray-700 select-none flex items-center gap-1">
                    <span>Can't scan? Use hash code instead</span>
                </summary>
                <form method="POST" action="{{ route('verify.check') }}" class="mt-4">
                    @csrf
                    <textarea
                        name="hash_code"
                        rows="3"
                        placeholder="Paste the 64-character SHA-256 hash here..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none @error('hash_code') border-red-400 @enderror"
                    >{{ old('hash_code', request('hash')) }}</textarea>
                    @error('hash_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <button type="submit"
                            class="w-full mt-3 bg-gray-700 hover:bg-gray-800 text-white py-2 rounded-lg text-sm font-medium transition-colors">
                        Verify Hash Code
                    </button>
                </form>
            </details>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            © {{ date('Y') }} Barangay San Jose · Document Verification Portal
        </p>
    </div>

    <script>
        const startBtn = document.getElementById('startScanBtn');
        const stopBtn  = document.getElementById('stopScanBtn');
        const idle     = document.getElementById('scanner-idle');
        const active   = document.getElementById('scanner-active');
        const errBox   = document.getElementById('scanner-error');
        const errMsg   = document.getElementById('scanner-error-msg');

        let html5QrCode = null;

        function showError(msg) {
            errBox.classList.remove('hidden');
            errMsg.textContent = msg;
            idle.classList.remove('hidden');
            active.classList.add('hidden');
        }

        function extractHash(text) {
            // Format 1: full URL  → http://…/verify?hash=<hash>
            try {
                const url = new URL(text);
                const h = url.searchParams.get('hash');
                if (h && h.length === 64) return h;
            } catch {}

            // Format 2: multi-line QR payload — find "Verify: …?hash=<hash>" line
            const verifyLine = text.split('\n').find(l => l.startsWith('Verify:'));
            if (verifyLine) {
                try {
                    const url = new URL(verifyLine.replace('Verify:', '').trim());
                    const h = url.searchParams.get('hash');
                    if (h && h.length === 64) return h;
                } catch {}
            }

            // Format 3: "Hash: <first32>..." line — partial hash, cannot verify
            // Format 4: raw 64-char hex string
            const trimmed = text.trim();
            if (/^[a-f0-9]{64}$/i.test(trimmed)) return trimmed;

            return text;
        }

        async function startScanner() {
            errBox.classList.add('hidden');
            idle.classList.add('hidden');
            active.classList.remove('hidden');

            html5QrCode = new Html5Qrcode("qr-reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            try {
                await html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    async (decodedText) => {
                        // Successful scan
                        await stopScanner();
                        const hash = extractHash(decodedText).trim();

                        // Auto-submit verification
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = "{{ route('verify.check') }}";
                        form.innerHTML = `
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="hash_code" value="${hash}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    },
                    (err) => { /* per-frame scan errors are normal, ignore */ }
                );
            } catch (err) {
                console.error(err);
                let msg = 'Could not access camera.';
                if (err?.message?.includes('Permission')) msg = 'Camera permission denied. Please allow camera access in your browser.';
                else if (err?.message?.includes('NotFound')) msg = 'No camera found on this device.';
                else if (err?.message) msg = err.message;
                showError(msg);
            }
        }

        async function stopScanner() {
            if (html5QrCode) {
                try { await html5QrCode.stop(); } catch (e) {}
                try { html5QrCode.clear(); } catch (e) {}
                html5QrCode = null;
            }
            idle.classList.remove('hidden');
            active.classList.add('hidden');
        }

        startBtn?.addEventListener('click', startScanner);
        stopBtn?.addEventListener('click', stopScanner);

        // If page is reloaded with ?hash=XXX (e.g. from QR scan in another tab), auto-verify
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('hash')) {
            const hash = urlParams.get('hash');
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('verify.check') }}";
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="hash_code" value="${hash}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
