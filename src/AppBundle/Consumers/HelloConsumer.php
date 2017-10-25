<?php

namespace AppBundle\Consumers;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class HelloConsumer
 * @package AppBundle\Consumers
 *
 * Consumer callback for the 1st part of rabbitMQ tutorial(https://www.rabbitmq.com/tutorials/tutorial-one-php.html)
 */
class HelloConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        echo $msg->body . "\n";
    }
}