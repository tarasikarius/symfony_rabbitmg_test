<?php

namespace AppBundle\Consumers;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class DirectLogsConsumer
 * @package AppBundle\Consumers
 *
 * Consumer callback for the 4th part of a rabbitMQ tutorial(https://www.rabbitmq.com/tutorials/tutorial-four-php.html)
 */
class DirectLogsConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        $key = $msg->delivery_info['routing_key'];

        printf("[%s] %s\n", $key, $msg->body);
    }
}