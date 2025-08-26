<?php

namespace App\Events;

use App\Models\Participant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // <-- Penting!
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ParticipantCheckedIn implements ShouldBroadcast // <-- Implementasi interface ini
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // Data yang ingin kita kirimkan
    public Participant $participant;

    public function __construct(Participant $participant)
    {
        $this->participant = $participant;
    }

    // Tentukan nama "channel" atau "saluran" broadcast
    public function broadcastOn(): array
    {
        // Kita pakai channel publik bernama 'check-ins'
        return [
            new Channel('check-ins'),
        ];
    }
}