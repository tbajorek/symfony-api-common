<?php declare(strict_types=1);

namespace ApiCommon\Model\Maker\Entity;

use ApiCommon\Model\Configuration;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Bundle\MakerBundle\Util\UseStatementGenerator;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EntityClassGenerator
{
    public function __construct(
        private readonly Generator $generator,
        private readonly DoctrineHelper $doctrineHelper,
        private readonly Configuration $configuration
    ) {
    }

    /**
     * @throws Exception
     */
    public function generateEntityClass(
        ClassNameDetails $entityClassDetails,
        ?string $tableName,
        bool $apiResource,
        bool $withPasswordUpgrade = false,
        bool $generateRepositoryClass = true,
        array $uniqueConstraintFields = []
    ): string {
        $repoClassDetails = $this->generator->createClassNameDetails(
            str_replace($this->configuration->getAppPrefix() . '\\Entity\\', '', $entityClassDetails->getFullName()),
            'Repository\\',
            'Repository'
        );

        $potentialTableName = $this->doctrineHelper->getPotentialTableName($entityClassDetails->getFullName());
        if (!$tableName) {
            $tableName = $potentialTableName;
        }

        $useStatements = new UseStatementGenerator([
            'Symfony\Component\Uid\Uuid',
            ['Doctrine\\ORM\\Mapping' => 'ORM'],
            ['Symfony\\Component\\Validator\\Constraints' => 'Assert'],
        ]);

        if ($apiResource) {
            // @legacy Drop annotation class when annotations are no longer supported.
            $useStatements->addUseStatement(
                class_exists(ApiResource::class) ? ApiResource::class : '\ApiPlatform\Core\Annotation\ApiResource'
            );
        }

        if ($generateRepositoryClass) {
            $useStatements->addUseStatement($repoClassDetails->getFullName());
        }

        $uniqueFields = '';
        if ($uniqueConstraintFields) {
            $uniqueFields = implode('\', \'', $uniqueConstraintFields);
        }

        $entityPath = $this->generator->generateClass(
            $entityClassDetails->getFullName(),
            $this->getTemplatePath($this->getEntityTemplate()),
            [
                'use_statements' => $useStatements,
                'repository_class_name' => $generateRepositoryClass ? $repoClassDetails->getShortName() : null,
                'api_resource' => $apiResource,
                'should_escape_table_name' => $this->doctrineHelper->isKeyword($tableName),
                'table_name' => $tableName !== $potentialTableName ? $tableName : null,
                'unique_constraint_fields' => $uniqueFields,
            ]
        );

        if ($generateRepositoryClass) {
            $this->generateRepositoryClass(
                $repoClassDetails->getFullName(),
                $entityClassDetails->getFullName(),
                $withPasswordUpgrade
            );
        }

        return $entityPath;
    }

    /**
     * @throws Exception
     */
    public function generateRepositoryClass(
        string $repositoryClass,
        string $entityClass,
        bool $withPasswordUpgrade,
        bool $includeExampleComments = true
    ): void {
        $shortEntityClass = Str::getShortClassName($entityClass);
        $entityAlias = strtolower($shortEntityClass[0]);

        $passwordUserInterfaceName = UserInterface::class;

        if (interface_exists(PasswordAuthenticatedUserInterface::class)) {
            $passwordUserInterfaceName = PasswordAuthenticatedUserInterface::class;
        }

        $interfaceClassNameDetails = new ClassNameDetails(
            $passwordUserInterfaceName,
            'Symfony\Component\Security\Core\User'
        );

        $useStatements = new UseStatementGenerator([
            $entityClass,
            ManagerRegistry::class,
            ServiceEntityRepository::class,
        ]);

        if ($withPasswordUpgrade) {
            $useStatements->addUseStatement([
                $interfaceClassNameDetails->getFullName(),
                PasswordUpgraderInterface::class,
                UnsupportedUserException::class,
            ]);
        }

        $this->generator->generateClass(
            $repositoryClass,
            $this->getTemplatePath($this->getRepositoryTemplate()),
            [
                'use_statements' => $useStatements,
                'entity_class_name' => $shortEntityClass,
                'entity_alias' => $entityAlias,
                'with_password_upgrade' => $withPasswordUpgrade,
                'password_upgrade_user_interface' => $interfaceClassNameDetails,
                'include_example_comments' => $includeExampleComments,
            ]
        );
    }

    protected function getTemplatePath(string $templateName): string
    {
        return dirname(__DIR__, 3) . '/Resources/skeleton/' . $templateName;
    }

    protected function getEntityTemplate(): string
    {
        return 'doctrine/Entity.tpl.php';
    }

    protected function getRepositoryTemplate(): string
    {
        return 'doctrine/Repository.tpl.php';
    }
}
