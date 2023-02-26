<?php declare(strict_types=1);

namespace ApiCommon\Maker\Entity;

use ApiCommon\Entity\EntityInterface;
use Exception;
use Generator;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Doctrine\EntityClassGenerator;
use Symfony\Bundle\MakerBundle\Doctrine\EntityRegenerator;
use Symfony\Bundle\MakerBundle\Doctrine\EntityRelation;
use Symfony\Bundle\MakerBundle\FileManager;
use Symfony\Bundle\MakerBundle\Generator as MakerGenerator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Util\ClassDetails;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Bundle\MakerBundle\Util\ClassSourceManipulator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

abstract class AbstractEntityMaker extends AbstractMaker
{
    public function __construct(
        private readonly FileManager $fileManager,
        private readonly DoctrineHelper $doctrineHelper,
        private readonly EntityClassGenerator $entityClassGenerator
    ) {
    }

    public static function getCommandName(): string
    {
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }

    public function __call(string $name, array $arguments)
    {
    }

    abstract public static function getEntityClass(): string;

    public function isApiResource(): bool
    {
        return false;
    }

    /**
     * @return Generator
     * @throws Exception
     */
    abstract public function getFields(): Generator;

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

    /**
     * @throws Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, MakerGenerator $generator)
    {
        $entityClass = self::getEntityClass();
        $overwrite = $input->getOption('overwrite');

        if ($input->getOption('regenerate')) {
            $this->regenerateEntities($input->getArgument('name'), $overwrite, $generator);
            $this->writeSuccessMessage($io);

            return;
        }

        $overwrite = $input->getOption('overwrite');
        $entityClassDetails = new ClassNameDetails(
            $entityClass,
            substr($entityClass, 0, strrpos($entityClass, '\\') + 1)
        );

        $classExists = class_exists($entityClassDetails->getFullName());
        if (!$classExists) {
            $entityPath = $this->entityClassGenerator->generateEntityClass(
                $entityClassDetails,
                $this->isApiResource()
            );
            $generator->writeChanges();
        } else {
            $entityPath = $this->fileManager->getRelativePathForFutureClass($entityClass);
        }

        $manipulator = $this->createClassManipulator($entityPath, $io, $overwrite);

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
                    $otherManipulatorFilename = $this->getPathOfClass($field->getInverseClass());
                    $otherManipulator = $this->createClassManipulator($otherManipulatorFilename, $io, $overwrite);
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
                            $otherManipulatorFilename = $this->getPathOfClass($field->getOwningClass());
                            $otherManipulator = $this->createClassManipulator(
                                $otherManipulatorFilename,
                                $io,
                                $overwrite
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

    /**
     * @throws Exception
     */
    private function regenerateEntities(string $classOrNamespace, bool $overwrite, MakerGenerator $generator): void
    {
        $regenerator = new EntityRegenerator(
            $this->doctrineHelper,
            $this->fileManager,
            $generator,
            $this->entityClassGenerator,
            $overwrite
        );
        $regenerator->regenerateEntities($classOrNamespace);
    }

    private function createClassManipulator(
        string $path,
        ConsoleStyle $io,
        bool $overwrite
    ): ClassSourceManipulator {
        $manipulator = new ClassSourceManipulator(
            $this->fileManager->getFileContents($path),
            $overwrite,
        );

        foreach ($this->getTraits() as $trait) {
            $manipulator->addTrait($trait);
        }

        foreach ($this->getInterfaces() as $interface) {
            $manipulator->addInterface($interface);
        }

        $manipulator->setIo($io);

        return $manipulator;
    }

    private function getPathOfClass(string $class): string
    {
        return (new ClassDetails($class))->getPath();
    }
}