<?php

declare(strict_types=1);

namespace App\WebSocket\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WebSocket\Client;

#[AsCommand(
    name: 'websocket:server:test',
)]
class ServerTestCommand extends Command
{
    private const COMMAND_DESCRIPTION = 'WebSocket Connection Test';

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::COMMAND_DESCRIPTION)
            ->addArgument('uri', InputArgument::OPTIONAL, 'A ws/wss-URI', 'ws://0.0.0.0:3000');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $client = new Client($input->getArgument('uri'));

            $client->receive();

            if (false === $client->isConnected()) {
                $output->writeln('Connection <error>[KO]</error>');
            }

            if ($client->isConnected()) {
                $output->writeln('Connection <info>[OK]</info>');
            }

            $client->close();

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->logger->error('Failed to test the server', ['exception' => $e]);
            $output->writeln('<error>Failed to test the server</error>');

            return Command::FAILURE;
        }
    }
}
