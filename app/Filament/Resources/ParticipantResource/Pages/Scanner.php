<?php

// app/Filament/Pages/Scanner.php
namespace App\Filament\Resources\ParticipantResource\Pages;

use App\Filament\Resources\ParticipantResource;
use App\Models\Participant;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page; 
use Illuminate\Support\Collection;
use App\Events\ParticipantCheckedIn;

class Scanner extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ParticipantResource::class;
    protected static string $view = 'filament.resources.participant-resource.pages.scanner';
    protected static ?string $title = 'BIB Scanner';
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    // Properti untuk menyimpan data
    public ?array $data = [];
    public ?Participant $lastScannedParticipant = null;
    public Collection $checkedInLog;

    public function mount(): void
    {
        $this->form->fill();
        $this->loadCheckedInLog();
    }

    // Fungsi untuk memuat log peserta yang sudah check-in
    protected function loadCheckedInLog(): void
    {
        $this->checkedInLog = Participant::where('status', 'checked_in')
            ->latest('checked_in_at')
            ->take(20) // Ambil 20 data terakhir
            ->get();
    }

    // Mendefinisikan form input
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('bib_number')
                    ->label('Scan BIB Number')
                    ->autofocus()
                    ->placeholder('Arahkan scanner ke QR/Barcode...')
                    ->extraInputAttributes(['autocomplete' => 'off']),
            ])
            ->statePath('data');
    }

    // Fungsi utama yang dieksekusi saat scan
    public function scan(): void
    {
        $formData = $this->form->getState();
        $bibNumber = $formData['bib_number'];

        if (empty($bibNumber)) {
            return;
        }

        $participant = Participant::where('bib_number', $bibNumber)->first();

        if (!$participant) {
            Notification::make()->title('Gagal!')->body("Peserta dengan BIB Number '{$bibNumber}' tidak ditemukan.")->danger()->send();
            $this->lastScannedParticipant = null; // Kosongkan data
        } else if ($participant->status === 'checked_in') {
            Notification::make()->title('Info')->body("{$participant->name} sudah melakukan check-in sebelumnya.")->warning()->send();
            $this->lastScannedParticipant = $participant; // Tetap tampilkan datanya
            
            // Set form ke ev002 secara otomatis
            $this->form->fill(['bib_number' => 'ev002']);
        } else {
            $participant->update([
                'status' => 'checked_in',
                'checked_in_at' => now(),
            ]);
            $this->lastScannedParticipant = $participant;
            Notification::make()->title('Berhasil!')->body("Selamat datang, {$participant->name}!")->success()->send();
            
            // Pass the last scanned participant to the view
            $this->lastScannedParticipant = $participant; // Ensure this is set
            $this->form->fill();
        }

        // Muat ulang log
        $this->loadCheckedInLog();
    }
}