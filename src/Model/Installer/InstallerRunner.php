<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer;

use ApiCommon\Model\Installer\Entity\EntityInstaller;
use ApiCommon\Model\Installer\Loader\DataLocationLoader;
use ApiCommon\Model\Installer\Loader\LoaderProvider;
use ApiCommon\Model\Installer\Operations\InstallerOperationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Style\SymfonyStyle;
use ApiCommon\Model\Installer\Entity\EntityHydrator;
use Traversable;

class InstallerRunner
{
    /** @var InstallerOperationInterface[] */
    private array $operations;
    
    public function __construct(
        private readonly InstallersCollection $installersCollection,
        private readonly EntityManagerInterface $entityManager,
        iterable $operations
    ) {
        $this->operations = $operations instanceof Traversable ? iterator_to_array($operations) : $operations;
    }

    /**
     * @throws Exception
     */
    public function install(SymfonyStyle $ui): int
    {
        $executedInstallers = 0;
        $ui->info('Installation has been started');
        $this->entityManager->getConnection()->beginTransaction();
        try {
            foreach ($this->installersCollection->getInstallers() as $installer) {
                foreach ($this->operations as $operation) {
                    $operation->execute($installer);
                }
                $ui->writeln(sprintf('Installing %s', $installer::class));
                $installer->install();
                $ui->writeln('Successfully installed');
                $executedInstallers++;
            }
            $this->entityManager->getConnection()->commit();
            $ui->info('Installation has been finished');
        } catch (\Throwable $e) {
            $ui->error('Installation has been broken');
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
        return $executedInstallers;
    }
}