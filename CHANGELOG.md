# Changelog

All notable changes to the Velor CMS Navigation package will be documented in
this file.

This package follows semantic versioning: `MAJOR.MINOR.PATCH`.

Release entries are grouped by change type:

- `Added` for new features.
- `Changed` for changes to existing behaviour.
- `Fixed` for bug fixes.

## [Unreleased]

## [1.1.2] - 2026-08-22

### Fixed

- Documented shell-safe quoted seeder command class names.

## [1.1.1] - 2026-08-20

### Changed

- Removed the enabled option from config, package registration is now always active.

### Fixed

- Changed navigation item row order input to the dedicated Velor CMS Order field.
- Removed random navigation item sort order values from the factory so row
  ordering can fill missing positions.

## [1.1.0] - 2026-08-13

### Changed

- Updated the package to use Velor CMS `^1.8` CMS menu registration contracts.

## [1.0.0] - 2026-08-05

### Added

- Added the initial Navigation package with navigation and navigation item
  resources, migrations, factories, seeders, routes, policies, translations,
  and scoped row ordering.

[Unreleased]: https://github.com/patrickzuurbier/velor-cms-navigation/compare/1.1.2...HEAD
[1.1.2]: https://github.com/patrickzuurbier/velor-cms-navigation/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/patrickzuurbier/velor-cms-navigation/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/patrickzuurbier/velor-cms-navigation/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/patrickzuurbier/velor-cms-navigation/releases/tag/1.0.0
