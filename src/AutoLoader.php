<?php declare(strict_types=1);



namespace  Ticonv\Swoft\RabbitMq;

use Swoft\SwoftComponent;
use Ticonv\Swoft\RabbitMq\MqClient;
use Ticonv\Swoft\RabbitMq\Pool;
use function bean;
/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{
    /**
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * @return array
     */
    public function metadata(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function beans(): array
    {
        return [
            'rabbitMq'      => [
                'class'  => MqClient::class,
            ],
            'rabbitMq.pool' => [
                'class'   => Pool::class,
                'client' => bean(MqClient::class)
            ]
        ];
    }
}
