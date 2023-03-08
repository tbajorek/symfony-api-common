<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer;

use ApiCommon\Model\DependencyResolver\SorterInterface;
use Exception;
use Countable;

class InstallersCollection implements Countable
{
    /** @var InstallerInterface[]|null */
    private ?array $sortedInstallers = null;

    public function __construct(private readonly SorterInterface $sorter, private array $installers = [])
    {
    }

    /**
     * @throws Exception
     * @return InstallerInterface[]
     */
    public function getInstallers(): array
    {
        if (!$this->sortedInstallers) {
            $this->sortedInstallers = $this->sorter->sort($this->installers);
        }
        return $this->sortedInstallers;
    }

    public function addInstaller(InstallerInterface $installer): self
    {
        $this->sortedInstallers = null;
        $this->installers[] = $installer;
        return $this;
    }

    public function count(): int
    {
        return count($this->installers);
    }
}