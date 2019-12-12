<?php declare(strict_types=1);

namespace Ticonv\Swoft\RabbitMq\Listener;

use Swoft\Event\Annotation\Mapping\Subscriber;
use Swoft\Event\EventInterface;
use Swoft\Bean\BeanFactory;
use Swoft\Event\EventSubscriberInterface;
use Ticonv\Swoft\RabbitMq\Pool;
use Swoft\Server\SwooleEvent;
use Swoft\SwoftEvent;
use Swoft\Log\Helper\CLog;

/**
 * Class WorkerStopAndErrorListener
 * @package Ticonv\Swoft\RabbitMq\Listener
 * @Subscriber()
 */
class WorkerStopAndErrorListener implements EventSubscriberInterface {

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SwooleEvent::WORKER_STOP    => 'handle',
            SwoftEvent::WORKER_SHUTDOWN => 'handle',
        ];
    }
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        $pools = BeanFactory::getBeans(Pool::class);

        /* @var Pool $pool */
        foreach ($pools as $pool) {
            $count = $pool->close();
            CLog::info('Close %d rabbitmq connection on %s!', $count, $event->getName());
        }
    }
}
