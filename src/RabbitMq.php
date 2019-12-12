<?php declare(strict_types=1);

namespace Ticonv\Swoft\RabbitMq;

use Ticonv\Swoft\RabbitMq\Connection\Connection;
use Ticonv\Swoft\RabbitMq\Exception\AMQPPoolException;

/**
 * Class RabbitMq
 * @package Ticonv\Swoft\RabbitMq
 */
class RabbitMq{
    public function connect():Connection{
        /**
         * @var ConnectionManage $connectManage
         */
        /**
         * @var Connection $connection
         */
        /**
         * @var Pool $pool
         */
        try{
            $connectManage = bean(ConnectionManage::class);
            $pool = bean(Pool::class);
            $connection = $pool->getConnection();
            $connection->setRelease(true);
            $connectManage->setConnection($connection);
        }catch (ConnectionPoolException $e){
            throw new AMQPPoolException(
                sprintf('Pool error is %s file=%s line=%d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }
        return $connection;
    }
}
