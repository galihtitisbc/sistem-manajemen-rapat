<?php

namespace Modules\Rapat\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Rapat\Entities\RapatAgenda;

trait AgendaRapatDatatables
{
    public function getAgendaRapatDatatables()
    {
        Carbon::setLocale('id');
        $rapats  = RapatAgenda::userIsPesertaOrCreator(Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $statusRapat = [
            'CANCELED' => ['danger', 'Di Batalkan'],
            'SCHEDULED' => ['warning', 'Di Jadwalkan'],
            'COMPLETED' => ['success', 'Selesai'],
            'STARTED' => ['primary', 'Sedang Berlangsung'],
        ];
        $statusKeaktifan = [
            'SCHEDULED' => ['fa-calendar-times', '#ff0000'],
            'CANCELED' => ['fa-undo', '#5cb85c'],
            'COMPLETED' => ['fas fa-check-circle', '#28a745'],
            'STARTED' => ['fas fa-play-circle', '#0275d8'],
        ];
        foreach ($rapats as $index => $rapat) {
            $startTime = Carbon::parse($rapat->waktu_mulai)->translatedFormat('l, d F Y H:i');
            $statusBadge = '<span class="badge bg-' . $statusRapat[$rapat->status][0] . '">' . $statusRapat[$rapat->status][1] . '</span>';

            $aksi = '<a href="' . url('rapat/agenda-rapat/' . $rapat->slug . '/detail') . '">
                    <i class="fas fa-eye fa-lg" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail Rapat"></i>
                </a>';

            if (Auth::user()->id === $rapat->user_id || Auth::user()->id === $rapat->pimpinan_id) {
                $aksi .= '<a href="' . url('rapat/agenda-rapat/' . $rapat->slug . '/edit') . '" class="mx-2 my-2">
                        <i class="fas fa-edit fa-lg" style="color: #FFD43B;" title="Edit Rapat"></i>
                      </a>';

                if (in_array($rapat->status, ['CANCELED', 'SCHEDULED'])) {
                    $aksi .= '<a href="' . url('rapat/agenda-rapat/' . $rapat->slug . '/batal') . '" onclick="return batalkanRapat(event,this.href,\'' . $rapat->status . '\')">
                            <i class="fas ' . $statusKeaktifan[$rapat->status][0] . ' fa-lg"
                               style="color: ' . $statusKeaktifan[$rapat->status][1] . ';"
                               title="Batalkan / Jadwal Ulang"></i>
                          </a>';
                }
            }

            if (Auth::user()->id === $rapat->notulis_id && $rapat->status !== 'CANCELED') {
                $aksi .= '<a href="#" class="btn btn-success btn-sm">Isi Notulen</a>';
            }
            $tugas = '';
            if (
                in_array(Auth::user()->id, [$rapat->notulis_id, $rapat->user_id, $rapat->pimpinan_id]) &&
                in_array($rapat->status, ['COMPLETED', 'STARTED'])
            ) {
                $tugas = '<a href="' . url('rapat/agenda-rapat/' . $rapat->slug . '/tugas') . '">
                        <span class="badge bg-primary p-2">Input Tugas</span>
                      </a>';
            }

            $data[] = [
                '<div class="text-center">' . ($index + 1) . '</div>',
                $rapat->agenda_rapat,
                '<div class="text-center">' . $startTime . '</div>',
                '<div class="text-center">' . $statusBadge . '</div>',
                '<div class="text-center">' . $aksi . '</div>',
                '<div class="text-center">' . $tugas . '</div>',
            ];
        }

        $heads = [
            ['label' => 'No', 'width' => 5, 'class' => 'text-center'],
            ['label' => 'Topik Rapat', 'width' => 25],
            ['label' => 'Waktu Mulai', 'width' => 25, 'class' => 'text-center'],
            ['label' => 'Status', 'width' => 10, 'class' => 'text-center'],
            ['label' => 'Aksi', 'width' => 20, 'class' => 'text-center'],
            ['label' => 'Tugas', 'width' => 15, 'class' => 'text-center'],
        ];

        $config = [
            'data' => $data,
            'order' => [[2, 'asc']],
            'columns' => [
                ['className' => 'text-center'],
                null,
                ['className' => 'text-center'],
                ['className' => 'text-center'],
                ['className' => 'text-center', 'orderable' => false],
                ['className' => 'text-center', 'orderable' => false],
            ]
        ];
        return ['heads' => $heads, 'config' => $config];
    }
}
