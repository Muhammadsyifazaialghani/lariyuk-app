<x-filament-panels::page.simple>
    
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold tracking-tight text-gray-950 dark:text-white">
            Status Check-in
        </h1>
        <p class="mt-2 text-lg text-gray-500 dark:text-gray-400">
            Detail Status Check-in Peserta
        </p>
    </div>

    <div wire:poll.5s="loadLastBibSearchResults" class="fi-section mt-6 rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <table class="w-full text-start divide-y divide-gray-200 dark:divide-white/5">
            <thead class="bg-gray-50 dark:bg-white/5">
                <tr>
                    <th class="p-4 text-xl font-semibold text-left text-gray-950 dark:text-white">Nama Peserta</th>
                    <th class="p-4 text-xl font-semibold text-left text-gray-950 dark:text-white">Nomor BIB</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                @forelse ($participantResults as $result)
                    <tr wire:key="{{ $result->id }}">
                        <td class="p-4 text-2xl font-bold">{{ $result->name }}</td>
                        <td class="p-4 text-2xl font-mono">{{ $result->bib_number }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="p-6 text-center text-xl text-gray-500 dark:text-gray-400">
                            Silakan cek status BIB Anda di halaman Bib Check
                        </td>
                    </tr>
                @endforelse
                
            </tbody>
        </table>
    </div>

</x-filament-panels::page.simple>