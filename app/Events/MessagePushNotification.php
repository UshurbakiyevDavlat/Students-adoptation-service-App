<?php

namespace App\Events;

use App\Http\Resources\Messenger\WebsocketResource;
use App\Models\Messages;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
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

    /**
     * @throws \JsonException
     */
    public function broadcastWith(): array
    {
        $data = (new WebsocketResource($this->message))->toArray(request());

        $encodedData = json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        return [
            'channel' => 'chatMessage',
            'event' => 'chatMessage',
            'data' => $encodedData,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn(): Channel|array
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
