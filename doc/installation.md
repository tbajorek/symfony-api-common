# Installation

To install your application please follow these steps:
* check configuration in ENV files, eg. to confirm database connection configs;
* change API-Common configuration (`src/config/packages/api_common.yml`) if you need
* make all entities you want, eg. configuration, user, etc.
* prepare migrations by command
```shell
bin/console make:migration
```
* run migrations:
```shell
bin/console doctrine:migrations:migrate
```
* run installers:
```shell
bin/console app:install
```

Then your application is ready to further work.