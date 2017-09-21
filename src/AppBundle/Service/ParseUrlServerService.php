<?php

namespace AppBundle\Service;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
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

        $content = $this->getContentFromUrl($url);
        $msg = new AMQPMessage($content, [
            'correlation_id' => $message->get('correlation_id')
        ]);

        $route = $message->get('reply_to');
        $channel = $message->delivery_info['channel'];
        $channel->basic_publish($msg, '', $route);
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