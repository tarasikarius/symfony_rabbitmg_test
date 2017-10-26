<?php

namespace AppBundle\Consumers;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class TopicLogsConsumer
 * @package AppBundle\Consumers
 *
 * Consumer callback for the 5th part of a rabbitMQ tutorial(https://www.rabbitmq.com/tutorials/tutorial-five-php.html)
 */
class TopicLogsConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        $key = $msg->delivery_info['routing_key'];

        printf("[%s] %s\n", $key, $msg->body);
    }
}