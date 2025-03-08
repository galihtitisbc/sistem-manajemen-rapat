<?php
namespace Modules\Rapat\Http\Service;

interface ZoomServiceInterface
{
    public function authentication();
    public function createMeeting($data);
}
