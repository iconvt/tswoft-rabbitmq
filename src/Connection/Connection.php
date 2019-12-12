<?php declare(strict_types=1);

namespace Ticonv\Swoft\RabbitMq\Connection;

use PhpAmqpLib\Connection\AMQPConnection;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Connection\Pool\AbstractConnection;
use Swoft\Log\Helper\CLog;
use Ticonv\Swoft\RabbitMq\Contract\ConnectionInterface;
use Ticonv\Swoft\RabbitMq\MqClient;
use Ticonv\Swoft\RabbitMq\Pool;

/**
 * Class Connection
 * @package Ticonv\Swoft\RabbitMq\Connection
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Connection extends AbstractConnection implements ConnectionInterface {

    /**
     * @var MqClient
     */
    public $mqClient;
    /**
     * @var MqClient
     */
    /**
     * @var AMQPConnection
     */
    public $client;
    /**
     * @var Pool
     */
    public $pool;

    /**
     * @param Pool $pool
     * @param MqClient $client
     */
    public function initialize(Pool $pool,MqClient $client){
        $this->updateLastTime();
        $this->pool = $pool;
        $this->mqClient = $client;
        $this->id = $this->pool->getConnectionId();
    }

    /**
     * Create connection
     */
    public function create(): void
    {
        $this->client = $this->mqClient->connect();
    }

    /**
     * Reconnect connection
     */
    public function reconnect(): bool
    {
        try {
            $this->create();
        } catch (Throwable $e) {
            Log::error('RabbitMq reconnect error(%s)', $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Close connection
     */
    public function close(): void
    {
        $this->client->close();
    }

    /**
     * @return AMQPConnection
     */
    public function getClient():AMQPConnection{
        return $this->client;
    }

    /**
     * 为了防止被关闭的连接返回连接池,在下次取出连接验证过期时间时,新增验证连接是否有效
     * @return int
     */
    public function getLastTime(): int
    {
        if ($this->client->isConnected()){
            return parent::getLastTime();
        }
        if ($this->reconnect()){
            return parent::getLastTime();
        }
        return 0;
    }

    public function __destruct()
    {
        if ($this->client){
            $this->client->close();
//            CLog::info('rabbit连接失效关闭连接:%d',$this->getId());
        }
    }
}
