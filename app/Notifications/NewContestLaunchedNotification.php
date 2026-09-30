<?php

namespace App\Notifications;

use App\Models\Contest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewContestLaunchedNotification extends Notification
{
    use Queueable;

    public Contest $contest;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contest $contest)
    {
        $this->contest = $contest;
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
        $categoryName = $this->contest->categoryRelation?->name
            ?? (is_string($this->contest->category) ? $this->contest->category : ($this->contest->category?->name ?? 'Design'));

        $bounty = $this->contest->prize_bounty ?? $this->contest->prize_amount ?? 1000;

        $targetUrl = route('contests.show', $this->contest->slug ?: $this->contest->id);

        return [
            'contest_id' => $this->contest->id,
            'title'      => 'New Design Contest: ' . $this->contest->title,
            'prize'      => '৳' . number_format($bounty),
            'category'   => $categoryName,
            'url'        => $targetUrl,
            'type'       => 'contest',
            'message'    => 'Bounty: ৳' . number_format($bounty) . ($categoryName ? " • Category: {$categoryName}" : ''),
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
