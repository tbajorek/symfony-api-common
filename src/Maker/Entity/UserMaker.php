<?php declare(strict_types=1);

namespace ApiCommon\Maker\Entity;

use ApiCommon\Entity\EntityInterface;
use ApiCommon\Entity\ScopedEntityInterface;
use ApiCommon\Model\Maker\Entity\EntityField;
use Doctrine\DBAL\Types\Types;
use Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserMaker extends AbstractEntityMaker
{
    public static function getCommandName(): string
    {
        return 'make:entities:user';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->addOption('overwrite', null, InputOption::VALUE_NONE, 'Overwrite any existing getter/setter methods');
    }

    public static function getCommandDescription(): string
    {
        return 'Create application user entity';
    }

    public static function getUniqueConstraintFields(): array
    {
        return [
            'email'
        ];
    }

    public static function getTableName(): ?string
    {
        return 'users';
    }

    public function getInterfaces(): array
    {
        return [
            EntityInterface::class,
            UserInterface::class,
            ScopedEntityInterface::class,
            PasswordAuthenticatedUserInterface::class
        ];
    }

    public function getFields(): Generator
    {
        yield new EntityField('email', Types::STRING, false, ['length' => 64]);
        yield new EntityField('password', Types::STRING, false, ['length' => 255]);
        yield new EntityField('name', Types::STRING, false, ['length' => 128]);
        yield new EntityField('surname', Types::STRING, false, ['length' => 128]);
        yield new EntityField('roles', Types::JSON);
    }

    public static function getEntityClassName(): string
    {
        return 'User';
    }
}