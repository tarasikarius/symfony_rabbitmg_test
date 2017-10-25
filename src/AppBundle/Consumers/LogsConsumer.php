<?php

namespace AppBundle\Consumers;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class WorkConsumer
 * @package AppBundle\Consumers
 *
 * Consumer callback for the 3rd part of a rabbitMQ tutorial(https://www.rabbitmq.com/tutorials/tutorial-three-php.html)
 */
class LogsConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        echo ' [x] ', $msg->body, "\n";
    }
}