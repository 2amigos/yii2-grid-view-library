/**
 * @copyright Copyright (c) 2014-2017 2amigos - https://2amigos.us
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
if (typeof dosamigos == "undefined" || !dosamigos) {
    var dosamigos = {};
}
dosamigos.editableColumn = (function ($) {
    'use strict';
    var pub = {
        handlers: [],
        registerHandler: function (grid, selector, hash) {
            if ($.pjax) {
                var eGrid = window.btoa(grid);
                if (!pub.handlers[eGrid]) {
                    pub.handlers[eGrid] = [];
                }
                if ($.inArray(selector, this.handlers[eGrid]) == -1) {
                    pub.handlers[eGrid].push(selector);
                }
                $(grid)
                    .parents('[data-pjax-container]')
                    .first()
                    .off('pjax:complete.' + hash)
                    .on('pjax:complete.' + hash, function () {
                        for (var k in pub.handlers) {
                            for (var j = 0; j < pub.handlers[k].length; j++) {
                                $(pub.handlers[k][j]).editable();
                            }
                        }
                    });
            }
        }
    };
    return pub;
})(jQuery);
