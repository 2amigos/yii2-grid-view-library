/**
 * @copyright Copyright (c) 2014-2017 2amigos - https://2amigos.us
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

if (typeof dosamigos == "undefined" || !dosamigos) {
    var dosamigos = {};
}
dosamigos.loadingGrid = (function ($) {
    'use strict';
    return {
        registerHandler: function (grid, type, hash) {
            if ($.pjax) {
                var $grid = $(grid),
                    loadingClass = 'da-grid-loading-' + type,
                    $pjax = $grid.parents('[data-pjax-container]').first();

                if ($pjax.length) {
                    $pjax
                        .off('pjax:beforeSend.' + hash)
                        .off('pjax:beforeReplace.' + hash)
                        .on('pjax:beforeSend.' + hash, function () {
                            $grid.addClass(loadingClass);
                        })
                        .on('pjax:beforeReplace.' + hash, function () {
                            $grid.removeClass(loadingClass);
                        });
                }
            }
        }
    };
})(jQuery);
