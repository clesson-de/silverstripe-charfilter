# Silverstripe Charfilter

The Charfilter is another component for the Silverstripe Gridfield. The purpose of this component is to display a series of letters, numbers or characters as buttons in order to filter a value in the data list according to these characters.

## Installation

Add this to composer.json in your project
```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/clesson-de/silverstripe-charfilter.git"
        }
    ],
```
and then execute in command line:
```sh
composer require clesson-de/silverstripe-charfilter
```

## Documentation

Simply add the component to a Gridfield configuration. Define a field that is to be sorted. And optionally specify the characters to be filtered by.

```php
$config = $gridfield->getConfig();
// add the component and let it filter the "Name" property
$config->addComponent(new GridField_CharFilter('before', 'Name', ['a','b','c']));
```
