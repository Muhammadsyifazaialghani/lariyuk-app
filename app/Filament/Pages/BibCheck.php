<?php
// app/Filament/Pages/BibCheck.php

namespace App\Filament\Pages;

use App\Models\Participant;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use App\Events\ParticipantCheckedIn;
use App\Models\LastBibSearch;
use Filament\Pages\Page;
use Filament\Notifications\Notification; // Import class Notifikasi

class BibCheck extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.bib-check';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];
    public ?string $searchMessage = null;

    public function mount(): void
    {
        // Cek jika ada parameter 'bibs' dari URL
        if (request()->has('bibs')) {
            $bibsFromUrl = request()->get('bibs');
            // Isi form dengan data dari URL
            $this->form->fill(['bib_number' => $bibsFromUrl]);
            // Langsung jalankan proses check-in
            $this->checkStatus();
        } else {
            $this->form->fill();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                TextInput::make('bib_number')
                    ->label('Masukkan Nomor BIB Anda')
                    ->required()
                    ->placeholder('Contoh: EV0001 atau EV0002,EV0003,EV0004')
                    ->autocomplete('off'),
            ]);
    }

    public function checkStatus(): void
    {
        // LANGKAH 1: Kosongkan hasil pencarian sebelumnya agar display bersih
        LastBibSearch::query()->delete();

        $this->searchMessage = null;

        // LANGKAH 2: Ambil input dari form
        $bibNumbersInput = $this->form->getState()['bib_number'];

        // Jika input kosong, jangan lakukan apa-apa
        if (empty($bibNumbersInput)) {
            return;
        }

        // LANGKAH 3: Pecah input menjadi array, pemisahnya adalah koma (,)
        $bibNumbers = explode(',', $bibNumbersInput);

        $foundCount = 0;
        $notFoundNumbers = [];

        // LANGKAH 4: Lakukan perulangan untuk setiap nomor BIB
        foreach ($bibNumbers as $bibNumber) {
            // Bersihkan dari spasi yang tidak perlu di awal atau akhir
            $singleBibNumber = trim($bibNumber);

            // Lewati jika setelah dibersihkan ternyata kosong
            if (empty($singleBibNumber)) {
                continue;
            }

            $participant = Participant::where('bib_number', $singleBibNumber)->first();

            if ($participant) {
                $foundCount++;
                // Kirim event untuk update status check-in
                event(new ParticipantCheckedIn($participant));

                // Simpan data yang berhasil ditemukan ke tabel last_bib_searches
                LastBibSearch::create([
                    'bib_number' => $participant->bib_number,
                    'name' => $participant->name,
                    'status' => 'SUDAH CHECK-IN', // Langsung set status
                    'checked_in_at' => now(),
                ]);
            } else {
                // Kumpulkan nomor BIB yang tidak ditemukan
                $notFoundNumbers[] = $singleBibNumber;

                // Simpan data yang gagal ditemukan
                LastBibSearch::create([
                    'bib_number' => $singleBibNumber,
                    'name' => 'TIDAK DITEMUKAN',
                    'status' => 'not_found',
                    'checked_in_at' => null,
                ]);
            }
        }

        // LANGKAH 5: Beri notifikasi hasil proses
        if ($foundCount > 0) {
            Notification::make()
                ->title("Berhasil memproses {$foundCount} nomor BIB.")
                ->success()
                ->send();
        }
        if (!empty($notFoundNumbers)) {
            Notification::make()
                ->title('Beberapa nomor BIB tidak ditemukan')
                ->body("Nomor: " . implode(', ', $notFoundNumbers))
                ->danger()
                ->send();
        }
    }

    public function hasLogo(): bool
    {
        return true;
    }
}