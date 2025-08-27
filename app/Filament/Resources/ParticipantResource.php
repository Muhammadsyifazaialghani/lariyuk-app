<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParticipantResource\Pages;
use App\Models\Participant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Set;
use Filament\Navigation\NavigationItem; 
use App\Events\ParticipantCheckedIn; 

class ParticipantResource extends Resource
{
    protected static ?string $model = Participant::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    // Baris ini membuat grup menu dropdown di sidebar
    protected static ?string $navigationGroup = 'Manajemen Peserta';

  public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('bib_number')
                ->label('bib_number')
                ->required()
                ->unique(ignoreRecord: true)
                ->readonly()
                ->default(function () {
                    // 1. Cari peserta terakhir yang dibuat
                    $latestParticipant = Participant::latest('id')->first();

                    // 2. Jika belum ada peserta sama sekali
                    if (! $latestParticipant) {
                        return 'EV0001';
                    }

                    // 3. Jika sudah ada, ambil nomor terakhir
                    $lastNumber = (int) substr($latestParticipant->bib_number, 2);

                    // 4. Tambah 1 ke nomor terakhir
                    $newNumber = $lastNumber + 1;

                    // 5. Format ulang dengan 4 digit (misal: 2 jadi '0002', 10 jadi '0010')
                    $formattedNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

                    return 'EV' . $formattedNumber;
                }),
            // Forms\Components\DateTimePicker::make('checked_in_at'),
        ]);
}


    public static function table(Table $table): Table
    {
        // Kode table() Anda yang sudah ada bisa ditaruh di sini
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('bib_number')->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        // Bagian ini mendaftarkan semua halaman terkait resource ini
        return [
            'index' => Pages\ListParticipants::route('/'),
            'create' => Pages\CreateParticipant::route('/create'),
            // 'scan' => Pages\Scanner::route('/scanner'), // Daftarkan halaman scanner
            'edit' => Pages\EditParticipant::route('/{record}/edit'),
        ];
    }
}