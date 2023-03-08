<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Loader;

use ApiCommon\Exception\Installer\LoadDataException;
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
            throw new LoadDataException(sprintf('Unable to parse the YAML string: %s', $exception->getMessage()));
        }
    }

    public static function getType(): string
    {
        return 'yaml';
    }
}