<?php declare(strict_types=1);

namespace Ticonv\Swoft\RabbitMq;

use PhpAmqpLib\Connection\AMQPConnection;
use phpDocumentor\Reflection\Types\Boolean;
use Ticonv\Swoft\RabbitMq\Connection\Connection;
use Ticonv\Swoft\RabbitMq\Exception\AMQPException;

/**
 * Class MqClient
 * @package Ticonv\Swoft\RabbitMq
 */
class MqClient{
    /**
     * @var string
     */
    public $host = '127.0.0.1';
    /**
     * @var int
     */
    public $port = 5672;
    /**
     * @var string
     */
    public $userName = 'admin';
    /**
     * @var string
     */
    public $passWord = 'mqadmin';
    /**
     * @var array
     */
    public $setting = [
        'vhost'  =>  '/',
        'insist'  => false,
        'login_method'=>'AMQPLAIN',
        'login_response'=>null,
        'locale'=>'en_US',
        'connection_timeout'=>3.0,
        'read_write_timeout'=>3.0,
        'context'=>null,
        'keepalive'=>false,
        'heartbeat'=>0
    ];

    /**
     * @param Pool $pool
     * @return Connection
     */
    public function createConnect(Pool $pool):Connection{
        /**
         * @var Connection $connection;
         */
        $connection = bean(Connection::class);
        $connection->initialize($pool,$this);
        $connection->create();
        return $connection;
    }

    /**
     * @return AMQPConnection
     * @throws AMQPException
     */
    public function connect():AMQPConnection{
        $config = [
            'host'=>$this->getHost(),
            'port'=>$this->getPort(),
            'user'=>$this->getUser(),
            'password'=>$this->getPassWord(),
            'vhost'=>$this->getVhost(),
            'insist'=>$this->getInsist(),
            'login_method'=>$this->getLoginMethod(),
            'login_response'=>$this->getLoginResponse(),
            'locale'=>$this->getLocale(),
            'connection_timeout'=>$this->getConnectionTimeout(),
            'read_write_timeout'=>$this->getReadWriteTimeout(),
            'context'=>$this->getContext(),
            'keepalive'=>$this->getKeepalive(),
            'heartbeat'=>$this->getHeartbeat()
        ];
        $connection = new AMQPConnection(...array_values($config));
        if (!$connection->isConnected()) {
            throw new AMQPException(
                sprintf('Connect failed host=%s port=%d', $this->getHost(), $this->getPort())
            );
        }
        return $connection;
    }

    /**
     * @return int
     */
    public function getHeartbeat():int {
        return $this->setting['heartbeat']??0;
    }

    /**
     * @return bool|mixed
     */
    public function getKeepalive() {
        return $this->setting['keepalive']??false;
    }

    /**
     * @return mixed|null
     */
    public function getContext() {
        return $this->setting['context']??null;
    }

    /**
     * @return float
     */
    public function getConnectionTimeout():float {
        return (float)$this->setting['connection_timeout']??3.0;
    }

    /**
     * @return float
     */
    public function getReadWriteTimeout():float {
        return (float)$this->setting['read_write_timeout']??3.0;
    }

    /**
     * @return string
     */
    public function getLocale():string {
        return $this->setting['locale']??'en_US';
    }

    /**
     * @return string
     */
    public function getLoginMethod():string {
        return $this->setting['login_method']??'AMQPLAIN';
    }

    /**
     * @return bool
     */
    public function getLoginResponse():bool {
        return $this->setting['login_response']??false;
    }

    /**
     * @return bool
     */
    public function getInsist():bool {
        return $this->setting['insist']??false;
    }

    /**
     * @return string
     */
    public function getVhost():string {
        return $this->setting['vhost']??'/';
    }

    /**
     * @return string
     */
    public function getHost():string {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort():int {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getUser():string {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getPassWord():string {
        return $this->passWord;
    }
}
