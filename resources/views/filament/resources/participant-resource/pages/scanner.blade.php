{{-- resources/views/filament/resources/participant-resource/pages/scanner.blade.php --}}
<x-filament-panels::page>
    {{-- Form untuk melakukan scan --}}
    <form wire:submit.prevent="scan">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-4">
            Proses Check-in
        </x-filament::button>
    </form>

    {{-- Layout 2 Panel --}}
    <div class="fi-section mt-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- PANEL KIRI: HASIL SCAN TERAKHIR --}}
            <div class="fi-section-content">
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Hasil Scan Terakhir</h3>
                    @if ($lastScannedParticipant)
                        <dl>
                            <div class="py-2 grid grid-cols-3 gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2 font-bold">{{ $lastScannedParticipant->name }}</dd>
                            </div>
                            <div class="py-2 grid grid-cols-3 gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">BIB Number</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2">{{ $lastScannedParticipant->bib_number }}</dd>
                            </div>
                            <div class="py-2 grid grid-cols-3 gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="text-sm col-span-2">
                                    @if ($lastScannedParticipant->status === 'checked_in')
                                        <span class="fi-badge inline-flex items-center justify-center gap-x-1 rounded-md text-xs font-medium px-2 py-1 bg-success-100 text-success-700 dark:bg-success-500/20 dark:text-success-400">
                                            SUDAH CHECK-IN
                                        </span>
                                    @else
                                         <span class="fi-badge inline-flex items-center justify-center gap-x-1 rounded-md text-xs font-medium px-2 py-1 bg-gray-100 text-gray-700 dark:bg-gray-500/20 dark:text-gray-400">
                                            TERDAFTAR
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div class="py-2 grid grid-cols-3 gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Waktu Check-in</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2">{{ $lastScannedParticipant->checked_in_at ? $lastScannedParticipant->checked_in_at->format('d M Y, H:i:s') : '-' }}</dd>
                            </div>
                        </dl>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Belum ada data yang di-scan.</p>
                    @endif
                </div>
            </div>

            {{-- PANEL KANAN: LOG CHECK-IN --}}
            <div class="fi-section-content">
                 <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Log Check-in (20 Terbaru)</h3>
                     <div class="overflow-y-auto" style="max-height: 300px;">
                         <table class="fi-table w-full text-start divide-y divide-gray-200 dark:divide-white/5">
                             <thead class="bg-gray-50 dark:bg-white/5">
                                 <tr>
                                     <th class="fi-table-header-cell p-2 text-sm font-medium text-left text-gray-950 dark:text-white">Nama</th>
                                     <th class="fi-table-header-cell p-2 text-sm font-medium text-left text-gray-950 dark:text-white">BIB</th>
                                     <th class="fi-table-header-cell p-2 text-sm font-medium text-left text-gray-950 dark:text-white">Waktu</th>
                                 </tr>
                             </thead>
                             <tbody class="divide-y divide-gray-200 dark:divide-white/5 whitespace-nowrap">
                                 @forelse ($checkedInLog as $log)
                                     <tr>
                                         <td class="fi-table-cell p-2">{{ $log->name }}</td>
                                         <td class="fi-table-cell p-2">{{ $log->bib_number }}</td>
                                         <td class="fi-table-cell p-2 text-sm text-gray-500 dark:text-gray-400">{{ $log->checked_in_at->format('H:i:s') }}</td>
                                     </tr>
                                 @empty
                                     <tr>
                                         <td colspan="3" class="fi-table-cell p-4 text-center text-gray-500 dark:text-gray-400">Belum ada peserta yang check-in.</td>
                                     </tr>
                                 @endforelse
                             </tbody>
                         </table>
                     </div>
                 </div>
            </div>

        </div>
    </div>
</x-filament-panels::page>