# keven/climate-live-table

Dynamically add and remove rows from a table in console.

## Install

```shell
$ composer require keven/climate-live-table
```

## Usage

```php
<?php

use League\CLImate\CLImate;
use Keven\CLImate\LiveTable;

$climate = new CLImate;
$climate->extend(LiveTable::class, 'liveTable');
$table = $climate->liveTable(
    // Initial rows
    [
        ['<light_red>✗</light_red>', 'PHP', 'https://php.net'],
    ], 
    
    // Headers
    ['Status', 'Name', 'URL']
);

// Add some rows
$league = $table->add(['<light_green>✓</light_green>', 'PHP League', 'https://thephpleague.com']);

// Result in console:
// ---------------------------------------------
// | ! | Name       | URL                      |
// =============================================
// | ✗ | PHP        | https://php.net          |
// ---------------------------------------------
// | ✓ | PHP League | https://thephpleague.com |
// ---------------------------------------------

// You can also choose the added row index:
$table->add(['<light_green>✓</light_green>', 'Packagist', 'https://packagist.org'], 'packagist');

// Result in console:
// ---------------------------------------------
// | ! | Name       | URL                      |
// =============================================
// | ✗ | PHP        | https://php.net          |
// ---------------------------------------------
// | ✓ | PHP League | https://thephpleague.com |
// ---------------------------------------------
// | ✓ | Packagist  | https://packagist.org    |
// ---------------------------------------------

// Remove some rows
$table->remove($league);
$table->remove('packagist');

// Result in console:
// ---------------------------------------------
// | ! | Name       | URL                      |
// =============================================
// | ✗ | PHP        | https://php.net          |
// ---------------------------------------------

// Clear all rows
$table->clear();

// Result in console:
// ---------------------------------------------
// | ! | Name       | URL                      |
// =============================================
// |   |            |                          |
// ---------------------------------------------

// Finally delete totally the table of the CLI
$table->delete();
```
