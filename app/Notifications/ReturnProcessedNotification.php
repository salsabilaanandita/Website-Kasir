<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReturnProcessedNotification extends Notification
{
    use Queueable;

    public $return;
    public $message;

    public function __construct($return, $message)
    {
        $this->return = $return;
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Return Baru Diproses',
            'pesan' => $this->message,
            'icon'  => 'fas fa-undo-alt',
            'url'   => route('returns.index')
        ];
    }
}
