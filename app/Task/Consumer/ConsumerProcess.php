<?php

declare(strict_types=1);

namespace App\Task\Consumer;

use Hyperf\AsyncQueue\Driver\DriverInterface;
use Hyperf\Contract\StdoutLoggerInterface;

class ConsumerProcess extends \Hyperf\AsyncQueue\Process\ConsumerProcess
{
    protected array $handlers = [];

    public function handle(): void
    {
        if (!$this->driver instanceof DriverInterface) {
            $logger = $this->container->get(StdoutLoggerInterface::class);
            $logger->critical(sprintf('[CRITICAL] process %s is not work as expected, please check the config in [%s]', \Hyperf\AsyncQueue\Process\ConsumerProcess::class, 'config/autoload/queue.php'));
            return;
        }

        if (method_exists($this->driver, 'setProcess')) {
            $this->driver->setProcess($this);
        }

        $this->driver->consume();
    }

    public function job($params)
    {
        $type = $params['type'];
        $data = $params['data'];

        if (array_key_exists($type, $this->handlers)) {
            $handler = make($this->handlers[$type]);
            $handler($data);
        }
    }
}
