<?php


namespace App\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ReflectionClass;
use ReflectionException;
use ReflectionExceptŁion;

abstract class BaseEvent implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @return string
     * @throws ReflectionException
     */
    public function broadcastAs()
    {
        return (new ReflectionClass($this))->getShortName();
    }

    public function broadcastOn()
    {
        return new PrivateChannel($this->channel());
    }

    public abstract function channel(): string;
}