<?php

// app/Models/Participant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class Participant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'bib_number',
    ];

    /**
     * Generate QR code for the participant
     *
     * @return string
     */
    public function generateQrCode()
    {
        // Data yang akan diencode ke QR code dengan detail lengkap
        $qrData = json_encode([
            'id' => $this->id,
            'name' => $this->name,
            'bib_number' => $this->bib_number,
            'created_at' => $this->created_at->toISOString(),
        ]);

        // Generate QR code menggunakan cara yang lebih sederhana
        $qrCode = new QrCode($qrData);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Simpan QR code ke storage
        $qrCodePath = 'qrcodes/participant_' . $this->id . '.png';
        $result->saveToFile(storage_path('app/public/' . $qrCodePath));

        return $qrCodePath;
    }

    /**
     * Get QR code URL
     *
     * @return string
     */
    public function getQrCodeUrl()
    {
        $qrCodePath = 'qrcodes/participant_' . $this->id . '.png';
        $fullPath = storage_path('app/public/' . $qrCodePath);

        if (!file_exists($fullPath)) {
            $this->generateQrCode();
        }

        return asset('storage/' . $qrCodePath);
    }

    /**
     * Generate barcode for the participant
     *
     * @return string
     */
    public function generateBarcode()
    {
        $path = 'barcodes/participant_' . $this->id . '.png';
        $fullPath = storage_path('app/public/' . $path);
        $directory = dirname($fullPath);

        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Menggunakan DNS1D untuk barcode tipe Code 128
        $generator = new \Milon\Barcode\DNS1D();
        file_put_contents($fullPath, $generator->getBarcodePNG($this->bib_number, 'C128', 2, 60, [0, 0, 0], true));

        return $path;
    }
}
