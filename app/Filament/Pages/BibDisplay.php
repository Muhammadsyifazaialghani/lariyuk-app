<?php

namespace App\Filament\Pages;

use App\Models\LastBibSearch;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;

class BibDisplay extends Page
{
    protected static string $view = 'filament.pages.bib-display';
    protected static bool $shouldRegisterNavigation = false;

    // Properti ini sekarang akan menyimpan BANYAK hasil, bukan cuma satu
    public Collection $participantResults;

    public function mount(): void
    {
        // Panggil method untuk memuat SEMUA hasil pencarian terakhir
        $this->loadLastBibSearchResults();
    }

    // Nama method diubah agar lebih jelas
    public function loadLastBibSearchResults(): void
    {
        // Mengambil SEMUA data dari tabel LastBibSearch, bukan cuma 'first()'
        $this->participantResults = LastBibSearch::all();
    }
    
    // Anda bisa menambahkan listener untuk refresh otomatis jika BibCheck dan BibDisplay ada di satu halaman
    protected $listeners = ['bibsChecked' => 'loadLastBibSearchResults'];

    public function hasLogo(): bool
    {
        return true;
    }
}