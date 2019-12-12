<?php declare(strict_types=1);

namespace Ticonv\Swoft\RabbitMq\Connection;

use phpDocumentor\Reflection\Types\Boolean;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Concern\ArrayPropertyTrait;
use Swoft\Co;
use Swoft\Log\Helper\CLog;

/**
 * Class ConnectionManage
 * @package Ticonv\Swoft\RabbitMq\Connection
 * @Bean()
 */
class ConnectionManage{
    use ArrayPropertyTrait;
    public function setConnection(Connection $connection){
        $key = sprintf('%d.%d.%d',Co::tid(),Co::id(),$connection->getId());
        $this->set($key,$connection);
    }

    public function releaseConnection($id){
        $key = sprintf('%d.%d.%d',Co::tid(),Co::id(),$id);
        $this->unset($key);
    }

    /**
     * @param Boolean $final
     */
    public function release(bool $final=false){
        $key = sprintf('%d.%d',Co::tid(),Co::id());
        $connections = $this->get($key,[]);
        /**
         * @var Connection $connection
         */
        foreach ($connections as $connection){
//            CLog::info('release rabbitMq connection %d to pool',...[$connection->getId()]);
            $connection->release();
            $this->releaseConnection($connection->getId());
        }
        if ($final) {
            $finalKey = sprintf('%d', Co::tid());
            $this->unset($finalKey);
        }
    }
}
