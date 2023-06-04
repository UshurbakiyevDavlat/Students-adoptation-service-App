<?php

namespace App\Events;

use App\Http\Resources\Messenger\WebsocketResource;
use App\Models\Messages;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use JsonException;

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

    /**
     * @throws JsonException
     */
    public function broadcastWith(): array
    {
        return json_decode(json_encode((new WebsocketResource($this->message))->toArray(request()), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PrivateChannel|array
     */
    public function broadcastOn(): Channel|PrivateChannel|array
    {
        Log::channel('websockets')->debug('Broadcasting event: MyWebSocketEvent', [
            'payload' => $this->message
        ]);

        return new Channel('chatMessage');
    }

    public function broadcastAs(): string
    {
        return 'chatMessage';
    }
}
