<?php

namespace AppBundle\Service;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use OldSound\RabbitMqBundle\RabbitMq\RpcServer;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class ParseUrlServerService
 * @package AppBundle\Service
 */
class ParseUrlServerService implements ConsumerInterface
{
    /**
     * @param AMQPMessage $message
     */
    public function execute(AMQPMessage $message)
    {
        $data = json_decode(unserialize($message->body), true);
        $url = $data['url'];

        echo sprintf ("received url: '%s'\n", $url);

        return $this->getContentFromUrl($url);
    }

    /**
     * @param string $url
     * @return string
     */
    public function getContentFromUrl(string $url): string
    {
        $content = simplexml_load_string(file_get_contents($url));
        $items = $content->channel->item;

        $content = '';
        foreach ($items as $item) {
            $date = $item->pubDate;
            $title = $item->title;
            $link = $item->link;

            $content .= $date . "\n" . $title . "\n" . $link . "\n\n\n";
        }

        return $content;
    }
}