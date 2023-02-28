# symfony-api-common
Common modules for API in Symfony 

# API Entity Maker
This feature allows to generate concrete entities which are compatible with API Entity defined in this library. It's used to generate some entities already defined here, but it can be reused by you for your own entities.

## Add own entity maker command

## Most important components
### `ApiCommon\Maker\Entity\AbstractEntityMaker`
This is an abstract entity maker class. Custom makers must extend it to get benefits and be able to use additional logic.
Internal methods:
* `getCommandName` - return the command name for your maker;
* `getCommandDescription` - return the command description;
* `configureCommand` - configure the command: set description, input arguments, options, etc.;
* `configureDependencies` - configure any library dependencies (by specifying concrete classes) that your maker requires;
* `generate` - does the main logic where entity class is generated.

### `ApiCommon\Maker\Util\ClassSourceManipulator`
It is responsible for adding properties with getters and setters to the class. A lot of logic is common with the original class in Symfony however that class was permitted to be extended.
Features added to this version of class:
* define PHP property type (used in a getter/setter too) separately from Doctrine type;
* add Symfony asserts to entity fields.

### `ApiCommon\Model\Maker\Entity\EntityClassGenerator`
It is responsible for generating skeleton of entity class. Original class was overwritten for some purposes:
* specifying own entity template file;
* allowing to determine if to generate a repository for an entity;
* changing 'id' type to Uuid.