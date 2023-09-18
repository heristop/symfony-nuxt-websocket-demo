<?php

declare(strict_types=1);

namespace App\WebSocket\Command;

use App\WebSocket\Manager\MessageManager;
use App\WebSocket\Server\CustomIoServer;
use App\WebSocket\Server\WebSocketServer;
use Psr\Log\LoggerInterface;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'websocket:server:run',
)]
class ServerRunCommand extends Command
{
    private const COMMAND_DESCRIPTION = 'Run WebSocket Server';

    public function __construct(
        private readonly WebSocketServer $webSocketServer,
        private readonly MessageManager $messageManager,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription(self::COMMAND_DESCRIPTION)
            ->addOption(
                'work',
                null,
                InputOption::VALUE_NONE,
                'Option to process only one message'
            )
            ->addOption(
                'port',
                'p',
                InputOption::VALUE_OPTIONAL,
                'The port to server sockets on (default: 4000)',
                4000
            )
            ->addOption(
                'address',
                'a',
                InputOption::VALUE_OPTIONAL,
                'The address to receive sockets on (0.0.0.0 means receive connections from any',
                '0.0.0.0'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $app = new HttpServer(
                new WsServer(
                    $this->webSocketServer
                )
            );

            /** @var CustomIoServer $server */
            $server = CustomIoServer::factory(
                $app,
                $input->getOption('port'),
                $input->getOption('address')
            );
            $server->attach(
                $this->messageManager,
                $this->logger
            );

            $output->writeLn(sprintf(
                '🚀 <info>WebSocket server ws://%s:%d is running... press ctrl-c to stop.</info>',
                $input->getOption('address'),
                $input->getOption('port')
            ));

            if ($input->getOption('work')) {
                $server->listenOnce($this->webSocketServer);
                $output->writeLn('⏳ <comment>Checking for new queued messages...</comment>');
                $server->run();

                return Command::SUCCESS;
            }

            $server->listenPeriodically($this->webSocketServer);
            $output->writeLn('⏳ <comment>Checking for new queued messages...</comment>');
            $output->writeLn('⏳ <comment>Listening new client connections...</comment>');
            $server->run();

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->logger->error('Failed to run the server', ['exception' => $e]);
            $output->writeln(sprintf('<error>Failed to run the server: %s</error>', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
