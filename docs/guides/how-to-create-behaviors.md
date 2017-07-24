How to create grid behaviors
============================

First of all, lets recall what is a `Behavior` on Yii:

> Behaviors are instances of yii\base\Behavior, or of a child class. Behaviors, also known as mixins, allow you to 
> enhance the functionality of an existing component class without needing to change the class's inheritance. Attaching 
> a behavior to a component "injects" the behavior's methods and properties into the component, making those methods and 
> properties accessible as if they were defined in the component class itself. Moreover, a behavior can respond to the 
> events triggered by the component, which allows behaviors to also customize the normal code execution of the 
> component.

Why is so important to mention its definition? Because the whole idea of expanding the grid was actually taken from 
reading that piece of code. That description, made me understand that is not necessary to create a huge piece of 
code in order to create something really powerful. Behaviors work as some sort of plugins that increase and/or improve 
the current functionality of a component. 

By using behaviors we can also encapsulate complex logic that do not need to deal with GridView's logic, making even 
easier to manage our code. 

For example, there is a pretty famous grid view library for Yii2 whose developer has made a huge effort to include many 
enhancements into one single GridView, is a "GridView on roids". The result is amazing but the way its coded brings 
many issues:

- Is hard to maintain (has more than 1800 lines of code vs 95 lines of our GridView).
- To add a new enhancement you have to modify its code and go throughout a PR and acceptance process to its repository.
- It has huge amount of javascript files and dependencies that you cannot get rid of. It forces you to add them to your 
  project no matter what. 

Those are the main ones but there are many more. Our approach solves those issues with:

- Code enhancements are encapsulated within behaviors so the GridView widget stays very small (less than 100 lines).
- If a developer wishes to add new enhancement, it can simply create a new behavior himself and use it immediately or 
  publish it to packagist for others to use, without waiting for others to accept its vision of things with the GridView
- Javascript Dependencies belong to the behaviors and if I do not register a new package, those assets won't be 
  registered either.
- Sky is the limit on its enhancements. We can add as many behaviors as enhancements we want as long as they do not 
  conflict with eachother. 
  
###Creating a Behavior

Rules for the behavior class: 

- It **MUST** extend from `yii\base\Behavior`
- If requires to register javascript code or assets **MUST** implement 
  `dosamigos\grid\contracts\RegistersClientScriptInterface` 
- If modifies the `GridView::$layout` template, it **MUST** contain a method with the name of the token and it has to be 
  prefixed with `render`. For example, for a token `{panelHeader}` it will contain a `renderPanelHeader()` method. 

**Sample Behavior**

For the sake of the example, we are going to keep things simple. We highly recommend that you look into the code of the 
behaviors provided on this package to give you a more in depth look of the possibilities to enhance the GridView 
widget. We are going to create a `HelloWorldBehavior`, a small behavior that will render that famous sentence on the 
grid layout. 

Here is the behavior class: 

```php 
use app\behaviors;

class HelloWorldBehavior extends yii\base\Behavior 
{
    public function renderHello() {
        return 'Hello World!';
    }
}

```

Now, lets use that brand new behavior in our GridView: 

```php
use dosamigos\grid\GridView;

$dataProvider = ...;

echo GridView::widget(
[
    'dataProvider' => $provider,
    'behaviors' => [
        // here your behavior, as we do to configure them statically on components
        '\app\behaviors\HelloWorldBehavior', 
    ],
    'columns' => [
        // ... 
    ],
    // set the token {hello}, that means the Grid will check for `renderHello()` method on its behaviors
    'layout' => "{hello}\n{summary}\n{items}\n{pager}"
]
);

```

Simple right? What are you waiting to build yours? Let us know.

© [2amigos](http://www.2amigos.us/) 2013-2017
