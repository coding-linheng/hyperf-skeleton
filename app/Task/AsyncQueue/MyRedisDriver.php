<?php

declare(strict_types=1);

namespace App\Task\AsyncQueue;

use Hyperf\AsyncQueue\Driver\RedisDriver;
use Hyperf\AsyncQueue\Event\AfterHandle;
use Hyperf\AsyncQueue\Event\BeforeHandle;
use Hyperf\AsyncQueue\Event\FailedHandle;
use Hyperf\AsyncQueue\Event\RetryHandle;
use Hyperf\AsyncQueue\MessageInterface;
use Hyperf\AsyncQueue\Process\ConsumerProcess;

class MyRedisDriver extends RedisDriver
{
    /**
     * @var ConsumerProcess
     */
    protected $process;

    public function setProcess($process)
    {
        $this->process = $process;
    }

    public function getCallback($data, $message): callable
    {
        return function () use ($data, $message) {
            try {
                if ($message instanceof MessageInterface) {
                    $this->event && $this->event->dispatch(new BeforeHandle($message));

                    if ($this->process instanceof ConsumerProcess && method_exists($this->process, 'job')) {
                        $params = $message->job()->handle();
                        $this->process->job($params);
                    } else {
                        $message->job()->handle();
                    }
                    $this->event && $this->event->dispatch(new AfterHandle($message));
                }

                $this->ack($data);
            } catch (\Throwable $ex) {
                if (isset($message, $data)) {
                    if ($message->attempts() && $this->remove($data)) {
                        $this->event && $this->event->dispatch(new RetryHandle($message, $ex));
                        $this->retry($message);
                    } else {
                        $this->event && $this->event->dispatch(new FailedHandle($message, $ex));
                        $this->fail($data);
                    }
                }
            }
        };
    }
}
