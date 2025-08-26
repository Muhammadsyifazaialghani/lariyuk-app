<?php
// app/Filament/Pages/BibDisplay.php

namespace App\Filament\Pages;

use App\Models\Participant;
use App\Models\LastBibSearch;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;

class BibDisplay extends Page
{
    protected static string $view = 'filament.pages.bib-display';
    protected static bool $shouldRegisterNavigation = false;

    // Properti untuk menyimpan log
    public Collection $checkedInLog;
    public $participantResult = null; // Bisa LastBibSearch atau null

    public function mount(): void
    {
        $this->loadRecentCheckIns();
        $this->loadLastBibSearch();
    }

    // Method ini akan dipanggil oleh Livewire secara berkala
    public function loadRecentCheckIns(): void
    {
        $this->checkedInLog = Participant::where('status', 'checked_in')
            ->latest('checked_in_at')
            ->take(15)
            ->get();
    }

    public function loadLastBibSearch(): void
    {
        $this->participantResult = LastBibSearch::latest()->first();
    }

    public function hasLogo(): bool
    {
        return true;
    }
}
