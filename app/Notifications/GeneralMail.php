<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GeneralMail extends Notification
{
    use Queueable;

    protected $line1, $line2, $url, $button_text;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($template = null, $url = null)
    {
        // TBD - need to switch to markdown templating instead of this 
        $this->line1 = 'This is notification.';
        $this->line2 = 'Thank you for using our application!';
        $this->button_text = 'Visit our website';
        $this->url = url('/');

        if (!empty($url)) {
            $this->url = $url;
        }

        if (empty($template)) {
            return;
        }

        $matches = [];
        if (!preg_match('/(.*)<%(.*)%>(.*)/', $template, $matches)) {
            return;
        }

        $this->line1 = $matches[1] ?? $this->line1;
        $this->line2 = $matches[3] ?? $this->line2;
        $this->button_text = (isset($matches[2]) && strstr($matches[2], '|') !== false) 
            ? substr(strstr($matches[2], '|'), 1) 
            : $this->button_text;
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
            ->line($this->line1)
            ->action($this->button_text, $this->url)
            ->line($this->line2)
            ;
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
