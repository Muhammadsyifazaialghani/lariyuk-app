<?php

namespace App\Filament\Pages;

use App\Models\Participant;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use App\Events\ParticipantCheckedIn;
use App\Models\LastBibSearch;
use Filament\Pages\Page;
use Filament\Notifications\Notification; 

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
                    ->placeholder('masukkan id atau nomor BIB')
                    ->autocomplete('off'),
            ]);
    }

    public function checkStatus(): void
    {
        // Kosongkan hasil pencarian sebelumnya agar display bersih
        LastBibSearch::query()->delete();

        $this->searchMessage = null;

        // Ambil input dari form
        $input = $this->form->getState()['bib_number'];

        // Jika input kosong, jangan lakukan apa-apa
        if (empty($input)) {
            return;
        }

        // Pecah input menjadi array, pemisahnya adalah koma (,)
        $inputs = explode(',', $input);

        $foundCount = 0;
        $notFoundInputs = [];

        // Lakukan perulangan untuk setiap input (bisa bib number atau nama)
        foreach ($inputs as $inputItem) {
            $inputItem = trim($inputItem);
            if (empty($inputItem)) {
                continue;
            }

            // Cari berdasarkan bib_number atau nama (case-insensitive)
            $participant = Participant::where('bib_number', $inputItem)
                ->orWhereRaw('LOWER(name) = ?', [strtolower($inputItem)])
                ->first();

            if ($participant) {
                $foundCount++;
                event(new ParticipantCheckedIn($participant));
                LastBibSearch::create([
                    'bib_number' => $participant->bib_number,
                    'name' => $participant->name,
                ]);
            } else {
                $notFoundInputs[] = $inputItem;
                // Jika tidak ditemukan, kosongkan kedua field
                LastBibSearch::create([
                    'bib_number' => '-',
                    'name' => '-',
                ]);
            }
        }

        // Notifikasi hasil proses
        if ($foundCount > 0) {
            Notification::make()
                ->title("Berhasil memproses {$foundCount} data.")
                ->success()
                ->send();
        }
        if (!empty($notFoundInputs)) {
            Notification::make()
                ->title('Beberapa data tidak ditemukan')
                ->body("Input: " . implode(', ', $notFoundInputs))
                ->danger()
                ->send();
        }
    }

    public function hasLogo(): bool
    {
        return true;
    }
}