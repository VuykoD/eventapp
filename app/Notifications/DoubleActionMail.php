<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Action;

class DoubleActionMail extends Notification
{
    use Queueable;

    protected $line1, $line2, $actions;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($template = null, $url = null, $reject_url = null, $end_str = null)
    {
        // TBD - need to switch to markdown templating instead of this 
        $this->line1 = 'This is notification.';
        $this->line2 = 'Thank you for using our application!';
        $action_text = 'Visit our website';
        $action_url = !empty($url) ? $url : url('/');
        $decline_url = !empty($reject_url) ? $reject_url : url('/');

        $this->actions = [
            [
                'text' => $action_text,
                'url' => $action_url,
            ],
            [
                'text' => 'Decline',
                'url' => $decline_url,
            ]
        ];

        if (empty($template)) {
            return;
        }

        $matches = [];
        if (!preg_match('/(.*)<%(.*)%>(.*)/', $template, $matches)) {
            return;
        }

        $this->line1 = $matches[1] ?? $this->line1;
        $this->line2 = $matches[3] ?? $this->line2;
        if (isset($end_str)) {
            $this->line2 = array_merge((array)$this->line2, (array)$end_str);
        }
        $action_text = (isset($matches[2]) && strstr($matches[2], '|') !== false) 
            ? substr(strstr($matches[2], '|'), 1) 
            : $this->action_text;

        $this->actions = [
            [
                'text' => $action_text,
                'url' => $action_url,
            ],
            [
                'text' => 'Decline',
                'url' => $decline_url,
            ]
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(app_name() . ' Notification')
            ->view('notifications.email-double', [
                'greeting' => '',
                'introLines' => (array)$this->line1,
                'actions' => $this->actions,
                'outroLines' => (array)$this->line2,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
