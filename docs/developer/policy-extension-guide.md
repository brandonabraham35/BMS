# Policy Extension Guide

This guide explains how to add new policies to BMS Enterprise using the generic Policy Engine.

## 1. Register Policy Definition
In your module's `ServiceProvider`, use the `PolicyRegistry` to register your policy.

```php
public function boot()
{
    \$registry = \$this->app->make(PolicyRegistry::class);

    \$registry->register(new PolicyDefinition(
        key: 'inventory_thresholds',
        category: 'Inventory',
        displayName: 'Stock Thresholds',
        description: 'Global inventory reorder points.',
        dataType: 'JSON',
        defaultValue: ['min' => 10, 'max' => 100],
        inheritanceMode: 'Merge'
    ));
}
```

## 2. Define Custom Validation (Optional)
If your policy requires complex validation, create a class implementing `PolicyValidatorInterface` and register it in the definition metadata.

## 3. Resolve Policy
Use the `PolicyResolver` to get the effective policy for the current tenant.

```php
\$policy = \$resolver->resolve('inventory_thresholds');
```
