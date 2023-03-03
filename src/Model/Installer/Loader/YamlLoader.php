<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Loader;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class YamlLoader implements LoaderInterface, DataLocationLoader
{
    use DataLocator;

    /**
     * @inheritdoc
     */
    public function load(string $filePath): mixed
    {
        try {
            return Yaml::parseFile($this->getFullFilePath($filePath));
        } catch (ParseException $exception) {
            printf('Unable to parse the YAML string: %s', $exception->getMessage());
        }
    }

    public function getType(): string
    {
        return 'yaml';
    }
}