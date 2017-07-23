DataColumn
==========

The purpose of this component is no other than simply provide extra functionality to other columns on this library and 
that the enhancements could be included on your regular `yii\grid\DataColumn` columns.

### Usage 

```php 
'columns' => [
    [
        'class' => '\dosamigos\grid\columns\DataColumn',
        'attribute' => 'id',
        'rowSpanNoFilterHeaders' => true, // this will rowSpan the 
        'filter' => false,
        'headerOptions' => [
            // this should be on a CSS file as class instead of a inline style attribute...
            'style' => 'text-align: center !important;vertical-align: middle !important'
        ]
    ],
    // ... other columns 
],
```


© [2amigos](http://www.2amigos.us/) 2013-2017
