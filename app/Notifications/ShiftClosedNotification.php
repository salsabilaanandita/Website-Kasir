<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ShiftClosedNotification extends Notification
{
    use Queueable;

    public $shift;
    public $message;

    public function __construct($shift, $message)
    {
        $this->shift = $shift;
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Shift Ditutup',
            'pesan' => $this->message,
            'icon'  => 'fas fa-cash-register',
            'url'   => route('shifts.index')
        ];
    }
}
