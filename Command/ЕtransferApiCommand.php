<?php

namespace App\Command;

use App\Service\DataTransferApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class ЕtransferApiCommand extends Command
{
    private $transferApi;

    public function __construct(DataTransferApi $transferApi)
    {
        $this->transferApi = $transferApi;

        parent::__construct();
    }

    protected static $defaultName = 'app:transfer-api';

    protected function configure()
    {
        $this
            ->setDescription('Transfer API');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $decode = $this->transferApi->ApiTransfer();
        $io->success($decode . ' - ' . 'API recorded');

        return 0;
    }
}
