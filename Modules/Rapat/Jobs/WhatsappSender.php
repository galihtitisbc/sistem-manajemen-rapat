<?php
namespace Modules\Rapat\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Http\Service\Implementation\WhatsappService;

class WhatsappSender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $agendaRapat;
    private $type;
    private $status;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RapatAgenda $agendaRapat, $type, $status)
    {
        $this->agendaRapat = $agendaRapat;
        $this->type        = $type;
        $this->status      = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(WhatsappService $whatsappService)
    {
        if ($this->type === 'rapat') {
            $whatsappService->sendMessageRapat($this->agendaRapat, $this->status);
        } else if ($this->type === 'penugasan') {
            $whatsappService->sendMessagePenugasan($this->agendaRapat);
        }
    }
}
