GridView Widget
===============

This grid is not complex at all, its magic is created by simply override three of GridView's methods and allow 
to set behaviors dynamically on configuration. 

On `renderSection()` the behaviors methods take priority over the GridView's thus providing a runtime override 
mechanism. 

At the end of `run()` method, it checks if any of its behaviors implement `RegistersClientScriptInterface` so 
to register any javascript required and if they implements `RunnableBehaviorInterface` to run its internal code.

There is really not much more to say about this widget and thats the beauty of it. You should check 
[how to create behaviors](../guides/how-to-create-behaviors.md) guide to find out how to extend this great piece of 
code.

© [2amigos](http://www.2amigos.us/) 2013-2017
