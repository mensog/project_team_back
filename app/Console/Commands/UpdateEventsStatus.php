<?php

namespace App\Console\Commands;

use App\Services\Interfaces\EventServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateEventsStatus extends Command
{
    protected $signature = 'events:update-status';
    protected $description = 'Обновление статуса просроченных мероприятий до завершенных и уведомление участников';

    protected $eventService;

    public function __construct(EventServiceInterface $eventService)
    {
        parent::__construct();
        $this->eventService = $eventService;
    }

    public function handle()
    {
        try {
            $this->eventService->updateExpiredEventsStatus();
            $this->info('Статус Events с истекшим сроком действия успешно обновлен.');
            Log::info('Команда UpdateEventsStatus выполнена успешно.');
        } catch (\Exception $e) {
            $this->error('Ошибка при обновлении статуса Event: ' . $e->getMessage());
            Log::error('Команда UpdateEventsStatus не выполнена: ' . $e->getMessage());
        }
    }
}
