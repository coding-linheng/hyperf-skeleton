<?php

declare(strict_types=1);

namespace App\Task\Producer;

use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\Driver\DriverInterface;
use App\Task\Consumer\ConsumerJob;
use Psr\Container\ContainerInterface;

class BaseProducer
{
    /**
     * 队列名称
     * @var string
     */
    protected $queueName = 'default';

    /**
     * @var DriverFactory
     */
    protected $driverFactory;

    /**
     * @var DriverInterface
     */
    protected $queueDriver;

    public function __construct(ContainerInterface $container)
    {
        $this->driverFactory = $container->get(DriverFactory::class);
        $this->queueDriver = $this->driverFactory->get($this->queueName);
    }

    /**
     * 加入队列
     *
     * @param array $data
     * @param int $delay
     *
     * @return bool
     */
    protected function push(array $data, int $delay = 0): bool
    {
        $job = new ConsumerJob($data);
        return $this->queueDriver->push($job, $delay);
    }
}