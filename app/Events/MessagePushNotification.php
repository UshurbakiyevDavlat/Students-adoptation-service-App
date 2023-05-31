<?php

namespace App\Events;

use App\Models\Messages;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessagePushNotification implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $user;
    public Messages $message;

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

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message->text,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PrivateChannel|array
     */
    public function broadcastOn(): Channel|PrivateChannel|array
    {
        $channel = 'chat.' . $this->message->chat_id;

        Log::channel('websockets')->debug('Broadcasting event: MyWebSocketEvent', [
            'payload' => $this->message,
            'channel' => $channel
        ]);
        return new Channel($channel);
    }
}
