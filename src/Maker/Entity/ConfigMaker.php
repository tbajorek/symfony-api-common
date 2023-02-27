<?php declare(strict_types=1);

namespace ApiCommon\Maker\Entity;

use ApiCommon\Maker\Entity\Config\ConfigGroupMaker;
use ApiCommon\Maker\Entity\Config\DefinitionMaker;
use ApiCommon\Maker\Entity\Config\ValueMaker;
use Exception;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class ConfigMaker extends AbstractMaker
{
    public function __construct(
        private readonly ValueMaker $valueMaker,
        private readonly DefinitionMaker $definitionMaker,
        private readonly ConfigGroupMaker $configGroupMaker
    ) {
    }

    public static function getCommandName(): string
    {
        return 'make:entities:config';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->addOption('overwrite', null, InputOption::VALUE_NONE, 'Overwrite any existing getter/setter methods')
            ->addOption('regenerate', null, InputOption::VALUE_NONE,
                'Instead of adding new fields, simply generate the methods (e.g. getter/setter) for existing fields');
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }

    /**
     * @throws Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $this->valueMaker->generate($input, $io, $generator);
        $this->definitionMaker->generate($input, $io, $generator);
        $this->configGroupMaker->generate($input, $io, $generator);
    }

    public function __call(string $name, array $arguments)
    {
    }

    public static function getCommandDescription(): string
    {
        return 'Creates application configuration entities';
    }
}