<div style="text-align: center; padding: 1.5rem;">
    <h1 style="font-size: 1.5rem; font-weight: bold;">{{ $record->name }}</h1>
    <p style="font-size: 1.125rem; color: #6b7280;">BIB: {{ $record->bib_number }}</p>

    @php
        // Asumsi method generateQrCode() ada di model Participant dan mengembalikan path relatif
        $qrPath = $record->generateQrCode();
        $qrUrl = asset('storage/' . $qrPath);
    @endphp

    {{-- Tampilkan QR Code --}}
    <div style="margin-top: 1.5rem;">
        <img src="{{ $qrUrl }}" alt="QR Code for {{ $record->name }}" style="max-width: 200px; margin: auto;">
    </div>

    {{-- Pemisah --}}
    <!-- <hr style="margin: 2rem auto; width: 50%;"> -->

    @php
        // Asumsi method generateBarcode() juga ada di model Participant
        $barcodePath = $record->generateBarcode();
        $barcodeUrl = asset('storage/' . $barcodePath);
    @endphp

    {{-- Tampilkan Barcode --}}
    <div style="margin-top: 1.5rem;">
        <img src="{{ $barcodeUrl }}" alt="Barcode for {{ $record->bib_number }}" style="max-width: 300px; height: auto; margin: auto;">
    </div>
</div>