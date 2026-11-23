# Velor CMS Navigation

A Navigation content-management package for Velor that adds resources for managing
website menus, translated navigation items, URLs, active states, and scoped row
ordering.

## Requirements

- PHP `^8.4.1`
- Velor `^1.26`

## Installation

Require the package in a Velor application:

```bash
composer require patrickzuurbier/velor-cms-navigation:^1.4
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
- Authorizes standard Navigation and Navigation Item resource actions through
  their registered policies and Velor's shared resource middleware.
- Registers package resources, routes, policies, translations, migrations, and
  Velor menu item through Velor extension points.
