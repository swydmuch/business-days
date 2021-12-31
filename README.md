# Business Days
PHP library to work with single day and range of days in order to check business days

## Installation
```sh
composer require swydmuch/business-days
```

## Localization
At the moment, the library operates only on the public holidays in **Poland** (pl) and **Netherlands** (nl)    
I encourage you to contribute in order to add other locations

## Basic Usage
### Creating Day from string
```php
$day = Day::createFromString('2021-12-13', 'pl');
```

### Checking if day is business
```php
$day->isBusiness();
//true
```
### Creating range between two days
```php
$range = Range::createFromString('2021-12-13', '2021-12-14', 'pl');
```

### Counting business days in range
```php
$range->countBusinessDays();
//2
```

### Getting array of business days from range
```php
$range->businessDays();
//[Day,Day]
```