# GridView Extensions Library for Yii2

[![Latest Version](https://img.shields.io/github/tag/2amigos/yii2-grid-view-library.svg?style=flat-square&label=release)](https://github.com/2amigos/yii2-grid-view-library/tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/2amigos/yii2-grid-view-library/master.svg?style=flat-square)](https://travis-ci.org/2amigos/yii2-grid-view-library)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/2amigos/yii2-grid-view-library.svg?style=flat-square)](https://scrutinizer-ci.com/g/2amigos/yii2-grid-view-library/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/2amigos/yii2-grid-view-library.svg?style=flat-square)](https://scrutinizer-ci.com/g/2amigos/yii2-grid-view-library)
[![Total Downloads](https://img.shields.io/packagist/dt/2amigos/yii2-grid-view-library.svg?style=flat-square)](https://packagist.org/packages/2amigos/yii2-grid-view-library)

This library holds extensions specifically created to enhance Yii2's GridView Widget. We expect it to grow... :)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require 2amigos/yii2-grid-view-library:~1.0
```

or add

```
"2amigos/yii2-grid-view-library": "~1.0"
```

to the `require` section of your `composer.json` file.

## Usage

***Using ToggleColumn and ToggleAction***

```
// on your controller

public function actions()
{
    return [
        // ...
        'toggle' => [
            'class' => ToggleAction::className(),
            'modelClass' => Lang::className(),
            'onValue' => Lang::STATUS_ACTIVE,
            'offValue' => Lang::STATUS_NOT_ACTIVE
        ],
        // ...
    ];
}


// on your grid
// ... other columns above?
[
    'class' => \dosamigos\grid\ToggleColumn::className(),
    'attribute' => 'status',
    'onValue' => Status::STATUS_ACTIVE,
    'onLabel' => 'Active',
    'offLabel' => 'Not active',
    'contentOptions' => ['class' => 'text-center'],
    'afterToggle' => 'function(r, data){if(r){console.log("done", data)};}',
    'filter' => [
        Status::STATUS_ACTIVE => 'Active',
        Status::STATUS_DELETED => 'Not active'
    ]
],
// ...
```
***Using EditableColumn and EditableAction***
As you will soon realize, this column requires [2amigos/yii2-editable-widget](https://github.com/2amigos/yii2-editable-widget). So, for further information about the options you have to configure the Column please go to [the widget's repository](https://github.com/2amigos/yii2-editable-widget).

```
// on your controller

public function actions()
{
    return [
        // ...
        'editable' => [
            'class' => EditableAction::className(),
            'modelClass' => Lang::className(),
            'forceCreate' => false
        ]
        // ...
    ];


// on your grid
// ... other columns above?
[
    'class' => \dosamigos\grid\EditableColumn::className(),
    'attribute' => 'name',
    'url' => ['editable'],
    'type' => 'date',
    'editableOptions' => [
        'mode' => 'inline',
    ]
],
// ...
```

## Testing

```bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Antonio Ramirez](https://github.com/tonydspaniard)
- [All Contributors](https://github.com/2amigos/yii2-grid-view-library/graphs/contributors)

## License

The BSD License (BSD). Please see [License File](LICENSE.md) for more information.

<blockquote>
    <a href="http://www.2amigos.us"><img src="http://www.gravatar.com/avatar/55363394d72945ff7ed312556ec041e0.png"></a><br>
    <i>Custom Software | Web & Mobile Software Development</i><br>
    <a href="http://www.2amigos.us">www.2amigos.us</a>
</blockquote>
