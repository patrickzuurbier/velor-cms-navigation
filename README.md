# Velor CMS Navigation

A Navigation plugin package for Velor CMS that adds CMS resources for managing
website menus, translated navigation items, URLs, active states, and scoped row
ordering.

## Requirements

- PHP `^8.2`
- Velor CMS `^1.12`

## Installation

Require the package in a Velor CMS application:

```bash
composer require patrickzuurbier/velor-cms-navigation:^1.2
```

Publish the package assets you want to customize:

```bash
php artisan vendor:publish --tag=velor-navigation-migrations
php artisan vendor:publish --tag=velor-navigation-seeders
php artisan vendor:publish --tag=velor-navigation-lang
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

## Features

- Adds a `Navigation` resource for website menu containers.
- Adds a nested `Navigation Item` resource for ordered menu links.
- Supports translated navigation item text and validated URL values, including
  HTTP(S) addresses, internal paths, anchors, email links, and telephone links.
- Lets editors use one navigation URL for every language or enter a different
  URL per language.
- Supports active/inactive navigation items.
- Supports scoped row ordering inside a navigation.
- Registers package resources, routes, policies, translations, migrations, and
  CMS menu item through Velor CMS extension points.
