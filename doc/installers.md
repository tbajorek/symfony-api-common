# Installers
Installers are classes similar to fixtures, but they install data not only in database. You can put into installer any operation which prepares your application to be able to work before first use.

To be consistent, you should locate all installers in `App\Installer` directory. All must implement at least `\ApiCommon\Model\Installer\InstallerInterface` interface, however you can choose one of its children to determine sorting mode you want to have.

## Simple installer

## Install data
It's a bad practice to keep install data in an installer class. You can keep it in 
## Sorting mode
Installers can be sorted using two approaches. It should be defined in `api_common.yml` configuration file.

```yaml
api_common:
  installer:
    sort_mode: 'dependencies' # possible values: 'dependencies' or 'order'
```
There are two possible values of `sort_option` configuration:
* `dependencies` - all installers should implement `\ApiCommon\Model\Installer\DependentInstallerInterface` interface;
* `order` - all installers should implement `\ApiCommon\Model\Installer\OrderedInstallerInterface` interface.

**IMPORTANT!** As current sort mode is configured, having installers implementing both interfaces may lead into some issues.

### Dependencies mode
In this case a single installer depends on a list of other installers. Dependencies are returned by `getDependencies` method.
In this mode all installers not implementing `DependentInstallerInterface` are considered as not having any dependency and may be executed in a random time.

### Order mode
In this case a single installer is executed in an order determined by order value returned by its `getOrder` method in a relation to order of other installers.
In this mode all installers not implementing `OrderedInstallerInterface` are considered as having `0` order and may be executed in a wrong time.