# Installers
Installers are classes similar to fixtures, but they install data not only in database. You can put into installer any operation which prepares your application to be able to work before first use.

To run registered installer you just need to execute this command:
```shell
bin/console app:installers:run
```

To be consistent, you should locate all installers in `App\Installer` directory. All what you must implement is `\ApiCommon\Model\Installer\InstallerInterface` interface, however you can choose one of its children to determine sorting mode you want to have. Sorting installers execution is described in [a separate chapter](#dependencies-between-installers).

## Simple installer
In this section you can find a description about how to write the simplest installers. If you need something more specific, dig deeper into next parts in [Installer possibilities](#installer-possibilities) chapter.

### Entity installer from YAML file
This is the simplest entity installer you can have in your application. You need just a small piece of code to save entities from YAML file in a database.

In this example some installer functionalities are used:
* class is an installer: `ApiCommon\Model\Installer\InstallerInterface`;
* installer has some dependencies: `ApiCommon\Model\Installer\DependentInstallerInterface`;
* installer expects to use data loader: `ApiCommon\Model\Installer\LoaderAwareInstaller`;
* installer installs entities into database: `ApiCommon\Model\Installer\Entity\EntityInstallerInterface`;
* installer shares entities for other next installers: `ApiCommon\Model\Installer\Repository\SharingEntitiesInstallerInterface`.

All these interfaces must be implemented here even if all are implemented by abstract parent installer.

To prepare such simple installer you just need to do three steps described in next two chapters:
1. Create installer class
2. Tag the class as `app.installer`
3. Prepare install data file

Then you can run your installer with others by running `bin/console app:installers:run` command.

#### Source code
In this case installed data is kept in YAML file in `src/Resources/install/company.yaml` directory. Installer is located in `src/Installer/CompanyInstaller.php` file and depends on `\ApiCommon\Installer\UserInstaller` installer. Entity class for installed data is `App\Entity\Company` (if `App` is configured as a main prefix of all classes in the project).
```php
<?php

namespace App\Installer;

use ApiCommon\Model\Installer\DependentInstallerInterface;
use ApiCommon\Model\Installer\Entity\EntityInstallerInterface;
use ApiCommon\Model\Installer\InstallerInterface;
use ApiCommon\Model\Installer\LoaderAwareInstaller;
use ApiCommon\Model\Installer\Repository\SharingEntitiesInstallerInterface;
use ApiCommon\Model\Installer\YamlEntityInstaller;
use ApiCommon\Installer\UserInstaller;

class CompanyInstaller extends YamlEntityInstaller implements InstallerInterface, DependentInstallerInterface, LoaderAwareInstaller, EntityInstallerInterface, SharingEntitiesInstallerInterface
{
    public function getEntityName(): string
    {
        return 'Company';
    }

    protected function getDataFilePath(): string
    {
        return 'company.yaml';
    }

    public function getDependencies(): array
    {
        return [
            UserInstaller::class,
        ];
    }
}
```
**IMPORTANT!** You must tag your installer as `app.installer`.

#### Install data
If a company entity has three fields called `name`, `foundationYear` and `owner`, YAML file could look like this:
```yaml
entityName: 'Company'
data:
  -
    id: 1
    name: 'IT startupers'
    foundationYear: 2023
    owner: '@User:1'
```
As you can see `owner` field contains relation to `User` entity with id from its data file equal to `1`.

## Installer possibilities
### Installation
Every installer must implement `\ApiCommon\Model\Installer\InstallerInterface`. All operations defining installation process are implemented in `install` method. If an exception is thrown from that method, all installation process is broken and must be repeated.

### Dependencies between installers
You may want to specify an order of installed installers. They can be sorted using two approaches. It should be defined in `api_common.yml` configuration file.

```yaml
api_common:
  installer:
    sort_mode: 'dependencies' # possible values: 'dependencies' or 'order'
```
There are two possible values of `sort_option` configuration:
* `dependencies` - all installers should implement `\ApiCommon\Model\Installer\DependentInstallerInterface` interface;
* `order` - all installers should implement `\ApiCommon\Model\Installer\OrderedInstallerInterface` interface.

**IMPORTANT!** As current sort mode is configured, having installers implementing both interfaces may lead into some issues.

#### Dependencies mode
In this case a single installer depends on a list of other installers. Dependencies are returned by `getDependencies` method.
In this mode all installers not implementing `DependentInstallerInterface` are considered as not having any dependency and may be executed in a random time.

#### Order mode
In this case a single installer is executed in an order determined by order value returned by its `getOrder` method in a relation to order of other installers.
In this mode all installers not implementing `OrderedInstallerInterface` are considered as having `0` order and may be executed in a wrong time.

### Loading install data
It's a bad practice to keep install data in the PHP code. You can use data loader in your installer which loads data from external file. You can specify your own loader or use the one which is implemented in this library.

If you want your installer to use loaders tracked by the library (already provided or implemented by you), you must implement `\ApiCommon\Model\Installer\LoaderAwareInstaller` interface and use `\ApiCommon\Model\Installer\LoadingDataInstaller` trait in your installer.
Then you must implement `getLoaderType` method to inform the library which loader type you want to use in the installer, eg.
```php
public function getLoaderType(): string
{
    return YamlLoader::getType();
}
```
Installer in the example will use native yaml loader from the library.

Then you can access loader in `install` method using there `$this->getLoader()` code.

Loaders returned by `getLoader` method implement `\ApiCommon\Model\Installer\Loader\LoaderInterface` so you can use `load` method. As a parameter you specify data file patch which is inside `src/Resources/install` where src is root directory for code containing the installer. So you can load `src/Resources/install/company.yaml` file in your installer:
```php
$loadedData = $this->getLoader()->load('company.yaml');
```

Read about it more in [next chapter](#data-directory).

#### Data directory
As it was described above, loaders managed by the library look for data in `src/Resources/install` directory relatively to location of the installer.

For example consider this project structure:
```text
.
├── ...
├── src
│   ├── ...
│   ├── Installer
│   │   └── CompanyInstaller.php           # Installer in the application
│   ├── ...
│   ├── Resources
│   │   └── install                        # Install data directory for CompanyInstaller
│   │       └── company.yaml
│   └── ...
├── vendor
│   ├── ...
│   ├── tomedio
│   │   └── symfony-api-common
│   │       └── src
│   │           ├── ...
│   │           ├── Installer
│   │           │   └── UserInstaller.php  # Installer in the library
│   │           ├── ...
│   │           ├── Resources
│   │           │   └── install            # Install data directory for UserInstaller
│   │           │       └── user.yaml
│   │           └── ...
│   └── ...
└── ...
```
In this case `src` means main directory of a project which contains installer class which wants to load data. In `vendor` directory are installed all composer modules.

In `ApiCommon\Installer\UserInstaller` loading data `$this->getLoader()->load('user.yaml')` will look for it in `vendor/tomedio/symfony-api-common/src/Resources/install/user.yaml` file because `UserInstaller` class is in `vendor/tomedio/symfony-api-common/src/Install/UserInstaller.php` file.

In `App\Installer\CompanyInstaller` loading data `$this->getLoader()->load('company.yaml')` will look for it in `src/Resources/install/company.yaml` file because `UserInstaller` class is in `src/Install/UserInstaller.php` file.

To sum up, loaders managed by the library look for the data directory relatively to an installer which is requesting for the data, using `src/Resources/install` base path.

#### Own loaders
You can implement your own loader in the way as you want, or you can do it in a similar way to other loaders which are already implemented. Second option is recommended because library adds you a lot of out-of-the-box functionality.

All loaders compatible with the library must implement `\ApiCommon\Model\Installer\Loader\LoaderInterface` interface. You need to have  methods:
* `public function load(string $filePath): mixed` - to load data from the file specified by `$filePath`;
* `public static function getType(): string` - to return type of loaded data; it's needed for the installation process to bind loaders with installers which have `getLoaderType` method;
* `public function setInstaller(InstallerInterface $installer): void` - to add installer instance to the loader.
The last one is not required always, so please read next paragraph.

All own loaders implementing `LoaderInterface` must be tagged as `app.installer.loader` to be found by loaders management system from the library.

#### Data location in own loaders
You can load your data from wherever you want. You just need to specify a full path in your loader. However again it's good to follow rules defined in the library. If you want to load data from default location: `src/Resources/install` directory, you must implement `\ApiCommon\Model\Installer\Loader\DataLocationLoader` interface and use `\ApiCommon\Model\Installer\Loader\DataLocator` trait in your loader. Then you have provided `getFullFilePath` method in the loader, and you can use it to get full path of requested data file, e.g. `$this->getFullFilePath($filePath)`. When you use `DataLocator`, you shouldn't to implement `setInstaller` method.

#### Example loader
```php
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
```
**IMPORTANT!** Just don't forget to tag your loaders as `app.installer.loader`.

### Loading entities to database
You can do in your installer whatever you want. However, a common case can be to load some initial entities to database, like configuration or test user on a dev environment.

To make your installer able to load and persist entities in an easy way, you can implement `\ApiCommon\Model\Installer\Entity\EntityInstallerInterface` interface and use `\ApiCommon\Model\Installer\Entity\EntityInstaller` in it.

In your installer you need to implement then the method:
```php
public function getEntityName(): string
{
    return 'User';
}
```
which returns entity name, without `App\Entity` prefix. For more nested entities you can use e.g. `Config\\Value` entity name. All other method of `EntityInstallerInterface` are implemented in the trait.

You can use then in your installer these methods:
* `hydrate(array $data): \ApiCommon\Model\Installer\Entity\HydratedValue` - takes data of one entity (as array, where keys are property names) and returns hydrated value;
* `persist(\ApiCommon\Entity\EntityInterface $entity): void` - takes hydrated entity and persists in a database;
* `flush(?array $entities = null): void` - applies changes in a database; if array of hydrated entities is passed, then it persists it before flushing.

**IMPORTANT!** All hydrated entities must implement `\ApiCommon\Entity\EntityInterface` interface.

### Share entities between installers
Some entities contain data which are related to other entities. Installers can be already sorted based on specified dependencies, however we need a next mechanism to share already installed entities to next which depend on them.

To make your installer able to share entities, you should implement `\ApiCommon\Model\Installer\Repository\SharingEntitiesInstallerInterface` interface and use `\ApiCommon\Model\Installer\Repository\SharingEntitiesInstaller` in it.

Then all entities installed in the installer can be shared like this:
```php
$this->shareEntity($entityFromFile, $entityObject);
```
As first argument entity identifier from file should be passed. You can't rely on generated UUID identifiers because you can predict it when you prepare install data. That's why every entry in install data should have own hard-coded identifier which will not be saved in database but can be used to specify a relation.

Shared entities can be used then in other data files. Value indicating that there is a shared entity must follow the pattern: **@**_ENTITY_NAME_**:**_ENTITY_ID_
where:
* `ENTITY_NAME` is entity name without `App\Entity` prefix (same like returned by `getEntityName` method);
* `ENTITY_ID` is identifier of related entity in its data file (not the one generated while saving in database).

Example references:
* `@Config\\Value:1`
* `@User:12`

Internal library class `\ApiCommon\Model\Installer\Repository\SharedEntityNormalizer` is responsible to interpret these references and take correct shared object.

### Share data between installers
Most needed data to be shared between installers are entities. However, you may want to share other data. You can use for it the same repository which is used internally to share entities. It means that all shared entities will be available there too.

The repository is `\ApiCommon\Model\Installer\Repository\SharedDataRepository`. You can inject it to your installer by a constructor and use two methods there:
* `add(string $id, mixed $value): void` - save data in repository under given id;
* `get(string $id): mixed` - get saved data by given id.

For your own data you can use id whatever you want. It's just required that id will be unique across all used installers (not only yours).
If you want to get access to shared entities, you can do it just by specifying a proper id following this pattern: _ENTITY_NAME_**:**_ENTITY_ID_ where:
* `ENTITY_NAME` is entity name without `App\Entity` prefix (same like returned by `getEntityName` method);
* `ENTITY_ID` is identifier of related entity in its data file (not the one generated while saving in database).

## Built-in installers:
| Installer                                        | Description                               |
|--------------------------------------------------|-------------------------------------------|
| \ApiCommon\Installer\Config\ScopeInstaller       | Installs data with default scopes         |
| \ApiCommon\Installer\Config\ConfigGroupInstaller | Installs data with default config groups  |
| \ApiCommon\Installer\Config\DefinitionInstaller  | Installs default config definitions       |
| \ApiCommon\Installer\Config\ValueInstaller       | Installs default values for configuration |