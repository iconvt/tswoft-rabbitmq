<?php declare(strict_types=1);

namespace Ticonv\Swoft\RabbitMq;

use Swoft\Connection\Pool\AbstractPool;
use Swoft\Connection\Pool\Contract\ConnectionInterface;
use Ticonv\Swoft\RabbitMq\Connection\Connection;
use Ticonv\Swoft\RabbitMq\Connection\ConnectionManage;
use Swoft\Connection\Pool\Exception\ConnectionPoolException;
use Ticonv\Swoft\RabbitMq\Exception\AMQPPoolException;

/**
 * Class Pool
 * @package Ticonv\Swoft\RabbitMq
 */
class Pool extends AbstractPool{

    /**
     * @var MqClient
     */
    public $client;

    public $i=0;
    /**
     * Create connection
     *
     * @return ConnectionInterface
     */
    public function createConnection(): ConnectionInterface
    {
        return $this->client->createConnect($this);
    }

    /**
     * @return Connection
     * @throws ConnectionPoolException
     */
    public function connect():Connection{
        /**
         * @var ConnectionManage $connectManage
         *//**
         * @var Connection $connection
         */
        try{
            $connectManage = bean(ConnectionManage::class);
            $connection = $this->getConnection();
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
