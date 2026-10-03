<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class SupportReplyNotification extends Notification
{
    use Queueable;

    public ContactMessage $inquiry;
    public string $replyMessage;

    /**
     * Create a new notification instance.
     */
    public function __construct(ContactMessage $inquiry, string $replyMessage)
    {
        $this->inquiry = $inquiry;
        $this->replyMessage = $replyMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'inquiry_id' => $this->inquiry->id,
            'title'      => 'Official Reply: ' . ($this->inquiry->subject ?: 'Support Inquiry'),
            'message'    => Str::limit($this->replyMessage, 180),
            'reply'      => $this->replyMessage,
            'subject'    => $this->inquiry->subject,
            'type'       => 'support',
            'url'        => route('dashboard'),
            'created_at' => now(),
        ];
    }

    /**
     * Database notification channel payload.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}
