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
 * Class CoroutineDestoryListener
 * @package Ticonv\Swoft\RabbitMq\Listener
 * @Listener(event=SwoftEvent::COROUTINE_DESTROY)
 */
class CoroutineDestoryListener implements EventHandlerInterface{

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /* @var ConnectionManage $conManager */
        $conManager = BeanFactory::getBean(ConnectionManage::class);
        CLog::info('coroutineDestory');
        $conManager->release(true);
    }
}
