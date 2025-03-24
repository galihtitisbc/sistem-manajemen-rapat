<?php
namespace Modules\Rapat\Http\Helper;

enum StatusAgendaRapat: string {
    case STARTED   = 'STARTED';
    case CANCELLED = 'CANCELED';
    case SCHEDULED = 'SCHEDULED';
    case COMPLETED = 'COMPLETED';
}
