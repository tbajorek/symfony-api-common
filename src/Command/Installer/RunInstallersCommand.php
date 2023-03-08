<?php declare(strict_types=1);

namespace ApiCommon\Command\Installer;

use ApiCommon\Model\Installer\InstallerRunner;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RunInstallersCommand extends Command
{
    public const COMMAND_NAME = 'app:installers:run';

    public function __construct(private readonly InstallerRunner $installerRunner, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Run all installers in the application');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);
        try {
            $executed = $this->installerRunner->install($ui);
            $ui->success(sprintf('%d installers were executed', $executed));
            return Command::SUCCESS;
        } catch (Exception $exception) {
            $ui->error($exception->getMessage());
            return Command::FAILURE;
        }
    }
}