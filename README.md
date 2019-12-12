# tswoft-rabbitmq
rabbitmq-pool  swoft 仿redis-pool

## 配置
bean.php

```php
return [
  'rabbitMq' =>[
        'class' => \Ticonv\Swoft\RabbitMq\MqClient::class,
        'host' =>   '127.0.0.1',
        'port' =>   5672,
        'userName'  =>  'admin',
        'passWord'  =>  '******',
        'setting'   =>  [
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
        ]
    ],
    'rabbitMq.pool'    =>  [
        'class' =>  \Ticonv\Swoft\RabbitMq\Pool::class,
        'client'=>  bean(\Ticonv\Swoft\RabbitMq\MqClient::class)
    ]
]
```
## 使用
```php
$connection = bean(\Ticonv\Swoft\RabbitMq\Pool::class);
$client = $connection->getClient();
$channel = $client->channel();
$channel->queue_declare('hello', false, false, false, false);

$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, '', 'hello');
$channel->close();
```
