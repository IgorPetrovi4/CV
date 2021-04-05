<?php

namespace App\Command;

use App\Service\ScreenshotGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateScreenshotsCommand extends Command
{

    private $screenshotGenerator;

    public function __construct(ScreenshotGenerator $screenshotGenerator)
    {
        $this->screenshotGenerator = $screenshotGenerator;

        parent::__construct();
    }

    protected static $defaultName = 'app:update-screenshots';

    protected function configure()
    {
        $this
            ->setDescription('Create a screenshot command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->screenshotGenerator->generate();
    }
}
