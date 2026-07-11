# Settings Extension Guide

Settings are the simplest way to add configurable behavior to BMS Enterprise modules.

## Setting Values
Use the `SettingsService` to persist values. Always provide the relevant context.

```php
\$settings->set('preferred_view', 'grid', ['user_id' => \$user->id]);
```

## Retrieving Values
The `SettingsService` automatically handles hierarchical resolution.

```php
// If not set for user, will check Team -> Branch -> Company -> Workspace -> Platform
\$view = \$settings->get('preferred_view', 'list');
```

## Supported Types
- `string`
- `integer`
- `boolean`
- `float`
- `json` / `array`

The resolver automatically casts values back to their original type during retrieval.
