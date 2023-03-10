<?= "<?php declare(strict_types=1);\n" ?>

namespace <?= $namespace ?>;

<?= $use_statements; ?>

#[ORM\Entity<?php if ($repository_class_name): ?>(repositoryClass: <?= $repository_class_name ?>::class)<?php endif ?>]
<?php if ($table_name): ?>
<?php if ($should_escape_table_name): ?>#[ORM\Table(name: '`<?= $table_name ?>`')]
<?php else: ?>#[ORM\Table(name: '<?= $table_name ?>')]
<?php endif ?>
<?php endif ?>
<?php if ($api_resource): ?>
#[ApiResource]
<?php endif ?>
<?php if ($unique_constraint_fields): ?>
#[ORM\UniqueConstraint(name: 'unique_fields_index', columns: ['<?= $unique_constraint_fields ?>'])]
<?php endif ?>
class <?= $class_name."\n" ?>
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->getId() ? $this->getId()->toRfc4122() : '';
    }

    public function getParentScopeId(): ?Uuid
    {
        return null;
    }

    public function eraseCredentials()
    {
    }
}
