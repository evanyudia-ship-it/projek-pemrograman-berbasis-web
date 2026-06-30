<?php

namespace App\Jobs;

use App\Helpers\NotificationHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private array $userIds,
        private string $judul,
        private string $pesan,
        private string $tipe = 'info',
        private ?string $entitasTerkait = null,
        private ?string $entitasId = null
    ) {}

    public function handle(): void
    {
        NotificationHelper::sendToMany(
            $this->userIds,
            $this->judul,
            $this->pesan,
            $this->tipe,
            $this->entitasTerkait,
            $this->entitasId
        );
    }
}
