{{-- resources/views/filament/pages/bib-display.blade.php --}}

{{-- Gunakan layout kosong agar tidak ada header/footer admin --}}
<x-filament-panels::page.simple>

    {{-- Tambahkan ini di <head> untuk auto-refresh halaman sebagai cadangan --}}
    @push('scripts')
        <meta http-equiv="refresh" content="300">
    @endpush
    
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold tracking-tight text-gray-950 dark:text-white">
            Status Check-in
        </h1>
        <p class="mt-2 text-lg text-gray-500 dark:text-gray-400">
            Detail Status Check-in Peserta
        </p>
    </div>

    {{-- KUNCI REAL-TIME: wire:poll akan memanggil method loadLastBibSearch setiap 5 detik --}}
    <div wire:poll.5s="loadLastBibSearch" class="fi-section mt-6 rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        @if ($participantResult && $participantResult->status !== 'not_found')
            <table class="w-full text-start divide-y divide-gray-200 dark:divide-white/5">
                <thead class="bg-gray-50 dark:bg-white/5">
                    <tr>
                        <th class="p-4 text-xl font-semibold text-left text-gray-950 dark:text-white">Nama Peserta</th>
                        <th class="p-4 text-xl font-semibold text-left text-gray-950 dark:text-white">Nomor BIB</th>
                        <th class="p-4 text-xl font-semibold text-right text-gray-950 dark:text-white">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                    <tr>
                        <td class="p-4 text-2xl font-bold">{{ $participantResult->name }}</td>
                        <td class="p-4 text-2xl font-mono">{{ $participantResult->bib_number }}</td>
                        <td class="p-4 text-2xl text-right">
                            @if ($participantResult->status === 'checked_in')
                                <span class="text-success-600 dark:text-success-400">✅ SUDAH CHECK-IN</span>
                            @else
                                <span class="text-gray-500">BELUM CHECK-IN</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        @else
            <div class="p-6 text-center text-xl text-gray-500 dark:text-gray-400">
                Silakan cek status BIB Anda di halaman Bib Check
            </div>
        @endif
    </div>

    {{-- Display Bib Check Result --}}
    {{-- Dihilangkan sesuai permintaan --}}
</x-filament-panels::page.simple>
