Yii 2 GridView Library
----------------------

[![Latest Version](https://img.shields.io/github/tag/2amigos/yii2-grid-view-library.svg?style=flat-square&label=release)](https://github.com/2amigos/yii2-grid-view-library/tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/2amigos/yii2-grid-view-library/master.svg?style=flat-square)](https://travis-ci.org/2amigos/yii2-grid-view-library)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/2amigos/yii2-grid-view-library.svg?style=flat-square)](https://scrutinizer-ci.com/g/2amigos/yii2-grid-view-library/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/2amigos/yii2-grid-view-library.svg?style=flat-square)](https://scrutinizer-ci.com/g/2amigos/yii2-grid-view-library)
[![Total Downloads](https://img.shields.io/packagist/dt/2amigos/yii2-grid-view-library.svg?style=flat-square)](https://packagist.org/packages/2amigos/yii2-grid-view-library)

This library enhances the official grid library for Yii2 providing you with the required tools to develop and display 
beautiful interfaces for your reports. 


Why another grid view?
====================== 

We know that there are some extensions out there that look awesome, and they have been the result of a huge amount of 
work from the developers (from here, a big thank you), but they fall into the same mistakes we had in the past: 
their library is a frankenstein version of the official Yii2 grid, bloated with huge amount of code that overrides 
the class they extend from, making it very hard for other developers to enhance and/or to provide their very own 
functionality on other ways but to forcely override themselves the library. 

We wanted to avoid that, Yii2 is highly flexible and this library had to stick with that convention. So, we asked 
ourselves: **how to create a highly configurable widget that developers could extend with their very own vision 
and/requirements?**

Yii already answered that, it is through its behaviors mechanism. We simply allowed that mechanism to be configurable 
on widget creation and not hardcoded with them. Thanks to that, our grid allows developers to `inject` its custom 
behaviors, whether they are rendering methods or initialization of javascript plugins. Thus, the behavior system works 
like a `plugin` system and complies with the rules we wanted to implement: 

- Grid's code **MUST** be as clean as possible 
- Grid's code **MUST NOT** have hardcoded enhancements that developers are force to override if they wish to enhance
- Developers **SHOULD** be able to enhance or even replace entirely the resulting grid through behaviors. Yes! is 
  possible without overriding a single line of code! Check [GroupColumnsBehavior](behaviors/group-columns-behavior.md)

By following those rules, developers wouldn't have to wait for their pull requests to be accepted, they can simply 
create their own behaviors and that's it, they will have their very own "**GRID ON ROIDS**".

And all that was done with less than 100 lines of code :)


Installation
============ 

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


Library Components
==================

### GridView Widget

Check its code. You are going to be highly surprised how simple was to provide such power to the grid. That's the 
beauty of Yii.

- [GridView Widget](grids/enhanced-grid-view.md)

### Actions 

- [ToggleAction](actions/toggle-action.md)

### Behaviors 

A set of behaviors included into the library to show you how easy is to enhance our [GridView](grids/enhanced-grid-view.md) 
component. [Build your own!](guides/how-to-create-behaviors.md)

- [FloatHeaderBehavior](behaviors/float-header-behavior.md)
- [GroupColumnsBehavior](behaviors/group-columns-behavior.md)
- [LoadingBehavior](behaviors/loading-behavior.md)
- [ResizableColumnsBehavior](behaviors/resizable-columns-behavior.md)
- [ToolbarBehavior](behaviors/toolbar-behavior.md)

### Columns 

These columns can be used on any grid, you don't have to use them with our enhanced one. They all extend from our 
[DataColumn](columns/data-column.md) as we provide a small enhancements mechanisms that are included within the 
[ColumnTrait](columns/column-trait.md) Trait class.

- [BooleanColumn](columns/boolean-column.md)  
- [DataColumn](columns/data-column.md)
- [EditableColumn](columns/editable-column.md)
- [ImageColumn](columns/image-column.md) 
- [LabelColumn](columns/label-column.md)
- [ToggleColumn](columns/toggle-column.md)

Contributing
------------

- [How to Contribute](contributing/how-to.md)
- [Clean Code](contributing/clean-code.md)

© [2amigos](http://www.2amigos.us/) 2013-2017
