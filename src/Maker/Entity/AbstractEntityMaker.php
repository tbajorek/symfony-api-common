<?php declare(strict_types=1);

namespace ApiCommon\Maker\Entity;

use ApiCommon\Entity\EntityInterface;
use ApiCommon\Maker\Util\ClassSourceManipulator;
use ApiCommon\Model\Maker\Entity\ClassNameResolver;
use ApiCommon\Model\Maker\Entity\DependencyManager;
use ApiCommon\Model\Maker\Entity\EntityClassGenerator;
use ApiCommon\Model\Maker\Entity\EntityField;
use ApiCommon\Model\Maker\Entity\EntityRelation;
use Exception;
use Generator;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\FileManager;
use Symfony\Bundle\MakerBundle\Generator as MakerGenerator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

abstract class AbstractEntityMaker extends AbstractMaker
{
    private readonly DependencyManager $dependencyManager;

    public function __construct(
        private readonly FileManager $fileManager,
        private readonly EntityClassGenerator $entityClassGenerator,
        protected readonly ClassNameResolver $classNameResolver
    ) {
        $this->dependencyManager = new DependencyManager(static::class, $this->classNameResolver);
    }

    /**
     * @inheritdoc
     */
    public static function getCommandName(): string
    {
    }

    /**
     * @inheritdoc
     */
    public static function getCommandDescription(): string
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
    }

    /**
     * @inheritdoc
     */
    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }

    public function __call(string $name, array $arguments)
    {
    }

    /**
     * @throws Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, MakerGenerator $generator): void
    {
        $entityClass = $this->classNameResolver->resolve(static::getEntityClassName());
        $overwrite = $input->getOption('overwrite');

        $entityClassDetails = $this->getEntityClassDetails($entityClass);
        $entityPath = $this->getEntityPath($entityClass, $generator);

        $manipulator = $this->createClassManipulator($entityPath, $io, $overwrite, true);

        /** @var EntityRelation|EntityField $field */
        foreach ($this->getFields() as $field) {
            $io->comment($field instanceof EntityRelation ? $field->getOwningProperty() : $field->getName());

            $fileManagerOperations = [];
            $fileManagerOperations[$entityPath] = $manipulator;

            if ($field instanceof EntityRelation) {
                // both overridden below for OneToMany
                if ($field->isSelfReferencing()) {
                    $otherManipulator = $manipulator;
                    $otherManipulatorFilename = $entityPath;
                } else {
                    $otherManipulatorFilename = $this->getEntityPath($field->getInverseClass(), $generator);
                    $otherManipulator = $this->createClassManipulator(
                        $otherManipulatorFilename,
                        $io,
                        $overwrite,
                        false
                    );
                }

                switch ($field->getType()) {
                    case EntityRelation::MANY_TO_ONE:
                        if ($field->getOwningClass() === $entityClassDetails->getFullName()) {
                            // THIS class will receive the ManyToOne
                            $manipulator->addManyToOneRelation($field->getOwningRelation());

                            if ($field->getMapInverseRelation()) {
                                $otherManipulator->addOneToManyRelation($field->getInverseRelation());
                            }
                        } else {
                            // the new field being added to THIS entity is the inverse
                            $otherManipulatorFilename = $this->getEntityPath($field->getOwningClass(), $generator);
                            $otherManipulator = $this->createClassManipulator(
                                $otherManipulatorFilename,
                                $io,
                                $overwrite,
                                false
                            );

                            // The *other* class will receive the ManyToOne
                            $otherManipulator->addManyToOneRelation($field->getOwningRelation());
                            if (!$field->getMapInverseRelation()) {
                                throw new RuntimeException(
                                    'Somehow a OneToMany relationship is being created, but the inverse side will not be mapped?'
                                );
                            }
                            $manipulator->addOneToManyRelation($field->getInverseRelation());
                        }
                        break;
                    case EntityRelation::MANY_TO_MANY:
                        $manipulator->addManyToManyRelation($field->getOwningRelation());
                        if ($field->getMapInverseRelation()) {
                            $otherManipulator->addManyToManyRelation($field->getInverseRelation());
                        }
                        break;
                    case EntityRelation::ONE_TO_ONE:
                        $manipulator->addOneToOneRelation($field->getOwningRelation());
                        if ($field->getMapInverseRelation()) {
                            $otherManipulator->addOneToOneRelation($field->getInverseRelation());
                        }
                        break;
                    default:
                        throw new RuntimeException('Invalid relation type');
                }

                // save the inverse side if it's being mapped
                if ($field->getMapInverseRelation()) {
                    $fileManagerOperations[$otherManipulatorFilename] = $otherManipulator;
                }
            } else {
                $annotationOptions = $field->getAllData();
                unset($annotationOptions['fieldName']);
                $manipulator->addEntityField($field->getName(), $annotationOptions);
            }

            foreach ($fileManagerOperations as $path => $manipulatorOrMessage) {
                $this->fileManager->dumpFile($path, $manipulatorOrMessage->getSourceCode());
            }
        }

        $this->writeSuccessMessage($io);
    }

    private function getEntityClassDetails(string $entityClass): ClassNameDetails
    {
        return new ClassNameDetails(
            $entityClass,
            substr($entityClass, 0, strrpos($entityClass, '\\') + 1)
        );
    }

    private function getEntityPath(string $entityClass, MakerGenerator $generator): string
    {
        $entityClassDetails = new ClassNameDetails(
            $entityClass,
            substr($entityClass, 0, strrpos($entityClass, '\\') + 1)
        );

        $classExists = class_exists($entityClassDetails->getFullName());
        if (!$classExists) {
            $entityPath = $this->entityClassGenerator->generateEntityClass(
                $entityClassDetails,
                $this->dependencyManager->getDependencyForEntity($entityClass,
                    $this->getDependencies())::getTableName(),
                $this->dependencyManager->getDependencyForEntity($entityClass,
                    $this->getDependencies())::isApiResource(),
                false,
                $this->dependencyManager->getDependencyForEntity($entityClass,
                    $this->getDependencies())::isRepositoryGenerated(),
                $this->dependencyManager->getDependencyForEntity($entityClass,
                    $this->getDependencies())::getUniqueConstraintFields()
            );
            $generator->writeChanges();
            require_once($entityPath);
        } else {
            $entityPath = $this->fileManager->getRelativePathForFutureClass($entityClass);
        }
        return $entityPath;
    }

    private function createClassManipulator(
        string $path,
        ConsoleStyle $io,
        bool $overwrite,
        bool $currentManipulator
    ): ClassSourceManipulator {
        $manipulator = new ClassSourceManipulator(
            $this->fileManager->getFileContents($path),
            $overwrite,
        );

        if ($currentManipulator) {
            foreach ($this->getTraits() as $trait) {
                $manipulator->addTrait($trait);
            }

            foreach ($this->getInterfaces() as $interface) {
                $manipulator->addInterface($interface);
            }
        }

        $manipulator->setIo($io);

        return $manipulator;
    }

    public static function getTableName(): ?string
    {
        return null;
    }

    public static function isApiResource(): bool
    {
        return false;
    }

    public static function isRepositoryGenerated(): bool
    {
        return false;
    }

    public static function getUniqueConstraintFields(): array
    {
        return [];
    }

    public function getTraits(): array
    {
        return [];
    }

    public function getInterfaces(): array
    {
        return [
            EntityInterface::class
        ];
    }

    public function getDependencies(): array
    {
        return [];
    }

    /**
     * @return Generator
     * @throws Exception
     */
    abstract public function getFields(): Generator;

    abstract public static function getEntityClassName(): string;
}