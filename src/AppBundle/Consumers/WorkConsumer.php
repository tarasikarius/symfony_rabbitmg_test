<?php

namespace AppBundle\Consumers;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class WorkConsumer
 * @package AppBundle\Consumers
 *
 * Consumer callback for the 2nd part of a rabbitMQ tutorial(https://www.rabbitmq.com/tutorials/tutorial-two-php.html)
 */
class WorkConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        echo " [x] Received ", $msg->body, "\n";

        sleep(substr_count($msg->body, '.'));

        echo " [x] Done", "\n";
    }
}