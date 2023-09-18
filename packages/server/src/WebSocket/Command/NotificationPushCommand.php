<?php

declare(strict_types=1);

namespace App\WebSocket\Command;

use App\WebSocket\Manager\MessageManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'websocket:notification:push',
)]
class NotificationPushCommand extends Command
{
    private const COMMAND_DESCRIPTION = 'Push a notification to WebSocket clients.';

    public function __construct(
        private readonly MessageManager $messageManager,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::COMMAND_DESCRIPTION)
            ->addArgument('notification', InputArgument::REQUIRED, 'The notification to push.')
            ->addOption('resource-id', null, InputOption::VALUE_OPTIONAL, 'Resource Id.')
            ->addOption('delay', null, InputOption::VALUE_OPTIONAL, 'The delay in seconds before pushing the notification', '0');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->messageManager->createNotificationMessage(
                $input->getArgument('notification'),
                $input->getOption('resource-id'),
                (int) $input->getOption('delay'),
            );
        } catch (\RuntimeException $e) {
            $this->logger->error('Failed to push the notification', ['exception' => $e]);
            $output->writeln('<error>Failed to send the notification</error>');

            return Command::FAILURE;
        }

        $output->writeln('(📨) <info>Notification ready to be pushed</info>');

        return Command::SUCCESS;
    }
}
