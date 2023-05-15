<?php

namespace App\Events;

use App\Models\Messages;
use App\Services\FCMService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessagePushNotification
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    private $user;
    private Messages $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Messages $message, $user)
    {
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PrivateChannel|array
     */
    public function broadcastOn(): Channel|PrivateChannel|array
    {

        $token = $this->user->fcm_token;

        $notification = [
            'title' => $this->message->sender()->first()->name,
            'body' => $this->message->text
        ];

        try {
            FCMService::send($token, $notification);
            Log::info('Message sent to user with id - ' . $this->user->id);
        } catch (\Exception $e) {
            Log::error('Error while sending message to user with id - ' . $this->user->id);
            Log::error($e->getMessage());
        }
        return new PrivateChannel('message.'.$this->message->chat_id);
    }
}
