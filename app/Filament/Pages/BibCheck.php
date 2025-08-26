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

class BibCheck extends Page implements HasForms
{
    use InteractsWithForms;

    // Arahkan ke view yang sesuai
    protected static string $view = 'filament.pages.bib-check';
    
    // Jangan tampilkan di navigasi admin
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];
    
    // Properti untuk menyimpan hasil pencarian
    public ?Participant $participantResult = null;
    public ?string $searchMessage = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                TextInput::make('bib_number')
                    ->label('Masukkan Nomor BIB Anda')
                    ->required()
                    ->placeholder('Contoh: 12345'),
            ]);
    }

    public function checkStatus(): void
    {
        $this->participantResult = null;
        $this->searchMessage = null;

        $bibNumber = $this->form->getState()['bib_number'];
        $participant = Participant::where('bib_number', $bibNumber)->first();

        if ($participant) {
            $this->participantResult = $participant;
            // Update the checked-in log
            event(new ParticipantCheckedIn($participant));
            $this->searchMessage = null;

            // Simpan hasil pencarian ke tabel last_bib_searches
            LastBibSearch::create([
                'bib_number' => $participant->bib_number,
                'name' => $participant->name,
                'status' => $participant->status,
                'checked_in_at' => $participant->checked_in_at,
            ]);
        } else {
            $this->searchMessage = "Nomor BIB '{$bibNumber}' tidak ditemukan. Mohon periksa kembali.";
            // Simpan pencarian gagal juga (opsional)
            LastBibSearch::create([
                'bib_number' => $bibNumber,
                'name' => null,
                'status' => 'not_found',
                'checked_in_at' => null,
            ]);
        }
        
        // Jangan reset form agar peserta bisa lihat nomor yang mereka masukkan
        // $this->form->fill(); 
    }

    public function hasLogo(): bool
    {
        // Logic to determine if a logo should be displayed
        return true; // Change this logic as needed
    }
}
