<?php

namespace AppBundle\Consumers;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class WorkConsumer
 * @package AppBundle\Consumers
 *
 * Consumer callback for the 4th part of a rabbitMQ tutorial(https://www.rabbitmq.com/tutorials/tutorial-four-php.html)
 */
class DirectLogsConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        $message = json_decode($msg->body, true);
        printf("[%s] %s\n", $message['severity'], $message['body']);
    }
}