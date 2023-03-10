<?php declare(strict_types=1);

namespace ApiCommon\Model\Config\Scope;

use ApiCommon\Entity\Config\ScopeInterface;
use App\Entity\Config\Scope;
use Doctrine\ORM\EntityManagerInterface;

class ScopeProvider
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getAllScopes(): array
    {
        return $this->getScopes();
    }

    public function getScopes(?string $currentScope = null): array
    {
        $scopeRepository = $this->entityManager->getRepository(Scope::class);
        return [];
    }
}