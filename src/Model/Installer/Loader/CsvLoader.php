<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Loader;

use League\Csv\Reader;
use League\Csv\CharsetConverter;

class CsvLoader implements LoaderInterface, DataLocationLoader
{
    use DataLocator;

    /**
     * @inheritdoc
     */
    public function load(string $filePath): mixed
    {
        try {
            $csv = Reader::createFromPath($this->getFullFilePath($filePath), 'r');
            $csv->setHeaderOffset(0);

            $inputBom = $csv->getInputBOM();

            if ($inputBom === Reader::BOM_UTF16_LE || $inputBom === Reader::BOM_UTF16_BE) {
                CharsetConverter::addTo($csv, 'utf-16', 'utf-8');
            }
            $records = [];
            foreach ($csv as $record) {
                $records[] = $record;
            }
            return $records;
        } catch (ParseException $exception) {
            printf('Unable to parse the YAML string: %s', $exception->getMessage());
        }
    }

    public static function getType(): string
    {
        return 'csv';
    }
}