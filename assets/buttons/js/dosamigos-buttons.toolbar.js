/**
 * @copyright Copyright (c) 2014-2017 2amigos - https://2amigos.us
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
if (typeof dosamigos == "undefined" || !dosamigos) {
    var dosamigos = {};
}
dosamigos.toolbarButtons = (function ($) {
    'use strict';

    return {
        reload: function (e) {
            e.preventDefault();

            if ($.pjax) {
                var pjaxId = $(this)
                    .parents('[data-pjax-container]')
                    .first()
                    .attr('id');

                if (pjaxId) {
                    $.pjax.reload({container: '#' + pjaxId});
                    return;
                }
            }
            document.location.reload();
        }
    };
})(jQuery);
