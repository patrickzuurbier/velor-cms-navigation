# Velor CMS Navigation

A Navigation content-management package for Velor that adds resources for managing
website menus, translated navigation items, URLs, active states, and scoped row
ordering.

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Features](#features)
- [Package Registration](#package-registration)
- [Package Contents](#package-contents)
- [Data Model](#data-model)
- [Repository API](#repository-api)
- [URL Validation](#url-validation)
- [Authorization](#authorization)
- [Published Files](#published-files)
- [Testing](#testing)
- [Uninstalling](#uninstalling)
- [License](#license)

## Requirements

- PHP `^8.4.1`
- Velor `^1.26`

## Installation

Require the package in a Velor application:

```bash
composer require patrickzuurbier/velor-cms-navigation:^1.4
```

Publish the migrations when the application should own them:

```bash
php artisan vendor:publish --tag=velor-navigation-migrations
```

Run migrations after installing or publishing migrations:

```bash
php artisan migrate
```

Seed sample navigation data when useful for local development:

```bash
php artisan db:seed --class='Velor\Navigation\Database\Seeders\NavigationsTableSeeder'
php artisan db:seed --class='Velor\Navigation\Database\Seeders\NavigationItemsTableSeeder'
```

The seeders do not need to be published before running them. Publish seeders
only when the host application should customize their data; after publishing,
adjust their namespace to the host application's seeder namespace.

## Features

- Adds a `Navigation` resource for website menu containers.
- Adds a nested `Navigation Item` resource for ordered menu links.
- Supports translated navigation item text and validated URL values, including
  HTTP(S) addresses, internal paths, anchors, email links, and telephone links.
- Lets editors use one navigation URL for every language or enter a different
  URL per language.
- Supports active/inactive navigation items.
- Supports scoped row ordering inside a navigation.
- Authorizes standard Navigation and Navigation Item resource actions through
  their registered policies and Velor's shared resource middleware.
- Registers package resources, routes, policies, translations, migrations, and
  Velor menu item through Velor extension points.

## Package Registration

Laravel package discovery loads `NavigationServiceProvider`. The provider:

- registers the Navigation and Navigation Item resources;
- registers both model policies;
- registers Navigation in the Velor menu;
- loads authenticated package routes through Velor's route registrar;
- loads package migrations and the `velor-navigation` translation namespace;
- binds `NavigationRepositoryInterface` and
  `NavigationItemRepositoryInterface` as singletons.

The authenticated resource routes use the application-configured Velor prefix.
Their route names are `navigations.*` and the nested
`navigations.navigation-items.*` names.

## Package Contents

```text
src/Models/Navigation.php
src/Models/NavigationItem.php
src/Resources/NavigationResource.php
src/Resources/NavigationItemResource.php
src/Http/Controllers
src/Http/Requests
src/Policies
src/Repositories
src/Rules/NavigationUrl.php
database/factories
database/migrations
database/seeders
lang/en
lang/nl
routes/velor.php
tests
```

## Data Model

`Navigation` represents a named menu container. It uses UUID route keys and
exposes a `navigationItems()` relationship.

`NavigationItem` belongs to a Navigation and stores:

- `is_active` as a boolean;
- translated `text` and `url` values as JSON;
- `sort_order` as an unsigned integer scoped to its Navigation.

Deleting a Navigation cascades to its Navigation Items. Row ordering never
moves items between Navigation records.

The package manages navigation data but does not prescribe public website
markup. A consuming application can query and render the models in its own
frontend, for example:

```php
use Velor\Navigation\Models\Navigation;

$navigation = Navigation::query()
    ->where('name', 'Main')
    ->with(['navigationItems' => fn ($query) => $query
        ->where('is_active', true)
        ->orderBy('sort_order')])
    ->firstOrFail();
```

The translated model casts resolve `text` and `url` for Laravel's active
locale. Applications that need a specific translation can use the translation
methods provided by `spatie/laravel-translatable`.

## Repository API

The package binds these contracts for application and package services:

```php
use Velor\Navigation\Repositories\Contracts\NavigationItemRepositoryInterface;
use Velor\Navigation\Repositories\Contracts\NavigationRepositoryInterface;
```

`NavigationRepositoryInterface` provides `create()`, `update()`, and
`delete()` for Navigation models. `NavigationItemRepositoryInterface` provides
`createForNavigation()`, `update()`, and `delete()` for Navigation Items.
Creating an item through `createForNavigation()` assigns it to the supplied
Navigation relationship.

## URL Validation

`Velor\Navigation\Rules\NavigationUrl` accepts:

- absolute `http` and `https` URLs;
- application-relative paths beginning with a single `/`;
- anchors beginning with `#`;
- valid `mailto:` email links;
- `tel:` links containing telephone characters.

Protocol-relative URLs, unsupported schemes, empty values, control characters,
spaces, and unsafe URL characters are rejected.

## Authorization

`NavigationPolicy` and `NavigationItemPolicy` resolve Velor role privileges for
standard resource abilities. Navigation Items additionally expose the
`reorder` ability. Velor's resource middleware authorizes standard controller
actions before they run; hiding resource links is not the security boundary.

Applications grant or revoke these privileges through the normal Velor role
configuration. The package does not compare concrete role names.

## Published Files

Publishing is optional because Laravel loads the package files directly.

| Tag | Destination | Purpose |
| --- | --- | --- |
| `velor-navigation-migrations` | `database/migrations` | Customize package database migrations before running them. |
| `velor-navigation-seeders` | `database/seeders` | Customize the provided sample-data seeders. |
| `velor-navigation-lang` | `lang/vendor/velor-navigation` | Override package translations. |

The package has no configuration file. Resource registration, policies,
repositories, routes, and menu integration are provided by the service
provider.

## Testing

Package tests live in `tests`. They cover URL validation, repository
persistence, resource authorization, package routes, and integration with a
consuming Velor test application.

## Uninstalling

Remove the package with Composer:

```bash
composer remove patrickzuurbier/velor-cms-navigation
```

Velor continues to boot without the package. Package-owned routes, resources,
policies, menu registration, translations, and loaded migrations disappear.
Existing database tables and published files remain project data and are not
removed automatically.

## License

This package is open-sourced software licensed under the MIT license.
