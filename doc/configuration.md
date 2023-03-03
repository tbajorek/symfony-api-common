# Configuration
This package can be configured by file placed in `config/packages/api_common.yaml`. Its structure looks like:
```yaml
api_common:
  app_prefix: 'App' #application main prefix; only needed if it's different than default 'App'
  installer:
    sort_mode: 'dependencies' # Sorting approach used in the application; possible values: 'dependencies' or 'order'
```