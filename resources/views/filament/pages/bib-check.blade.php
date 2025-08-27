<x-filament-panels::page.simple>
    <div class="text-center">
        <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
            Cek Status Check-in Anda
        </h2>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Masukkan nomor BIB Anda untuk melihat detail registrasi.
        </p>
    </div>

    <form wire:submit.prevent="checkStatus" class="mt-8 space-y-6">
        {{ $this->form }}

        <x-filament::button type="submit" class="w-full">
            Cek BIB Status
        </x-filament::button>
    </form>

</x-filament-panels::page.simple>