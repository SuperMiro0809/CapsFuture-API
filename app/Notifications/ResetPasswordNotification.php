<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                ->greeting("Здравейте,")
                ->subject('Възстановяване на парола')
                ->line('Получавате този имейл, зашото сте изпратили заявка за възстановяване на вшата парола. Посетете линка за въвеждане на нова парола.')
                ->action('Смяна на парола', $this->url)
                ->line('Линкът за смяна на паролата ще бъде активен следващите 60 минути.')
                ->line('Ако не сте заявили смяна на парола, игнорирайте този имейл.')
                ->salutation("Капачки за бъдеще");
    }
}