<?php declare(strict_types=1);

namespace Ticonv\Swoft\RabbitMq\Listener;

use Swoft\Bean\BeanFactory;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Ticonv\Swoft\RabbitMq\Connection\ConnectionManage;
use Swoft\SwoftEvent;

/**
 * Class CoroutineDeferListener
 * @package Ticonv\Swoft\RabbitMq\Listener
 * @Listener(event=SwoftEvent::COROUTINE_DEFER)
 */
class CoroutineDeferListener implements EventHandlerInterface{

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /* @var ConnectionManage $conManager */
        $conManager = BeanFactory::getBean(ConnectionManage::class);
        CLog::info('CoroutineDefder');
        $conManager->release();
    }
}
