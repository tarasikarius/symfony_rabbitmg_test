<?php

namespace AppBundle\Command;

use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UrlParserClientCommand
 * @package AppBundle\Command
 */
class UrlParserClientCommand extends Command
{
    /**
     * @var Producer
     */
    private $producer;

    /**
     * @var mixed
     */
    private $channel;

    /**
     * @var string
     */
    private $correlationId;

    /**
     * @var string
     */
    private $callbackQueue;

    /**
     * @var string
     */
    private $response;

    /**
     * UrlParserClientCommand constructor.
     * @param Producer $producer
     * @param string $name
     */
    public function __construct(Producer $producer, string $name = null){
        $this->producer = $producer;
        $channel = $this->producer->getChannel();

        list($this->callbackQueue, ,) = $channel->queue_declare('', false, false, true, false);
        $channel->basic_consume($this->callbackQueue, '', false, false, false, false, [$this, 'onResponse']);

        $this->channel = $channel;
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('rabbitmq:url-parser:emit')
            ->setDescription('Parses url')
            ->addArgument('url', InputArgument::REQUIRED, 'The url to parse.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->correlationId = uniqid();

        $message = json_encode(['url' => $input->getArgument('url')]);

        $this->producer->publish(serialize($message), '', [
            'correlation_id' => $this->correlationId,
            'reply_to' => $this->callbackQueue
        ]);

        while (!$this->response) {
            $this->channel->wait();
        }

        $output->writeln($this->response);

    }

    /**
     * @param $response
     */
    public function onResponse($response)
    {
        if ($response->get('correlation_id') === $this->correlationId) {
            $this->response = $response->body;
        }
    }
}