/*!
 * =====================================================
 * Ratchet v2.0.2 (http://goratchet.com)
 * Copyright 2014 Connor Sears
 * Licensed under MIT (https://github.com/twbs/ratchet/blob/master/LICENSE)
 *
 * v2.0.2 designed by @connors.
 * =====================================================
 */
/* ========================================================================
 * Ratchet: modals.js v2.0.2
 * http://goratchet.com/components#modals
 * ========================================================================
 * Copyright 2014 Connor Sears
 * Licensed under MIT (https://github.com/twbs/ratchet/blob/master/LICENSE)
 * ======================================================================== */

!(function () {
    'use strict';

    var findModals = function (target) {
        var i;
        var modals = document.querySelectorAll('a');

        for (; target && target !== document; target = target.parentNode) {
            for (i = modals.length; i--;) {
                if (modals[i] === target) {
                    return target;
                }
            }
        }
    };

    var getModal = function (event) {
        var modalToggle = findModals(event.target);
        if (modalToggle && modalToggle.hash) {
            return document.querySelector(modalToggle.hash);
        }
    };

    window.addEventListener('touchend', function (event) {
        var modal = getModal(event);
        if (modal) {
            if (modal && modal.classList.contains('modal')) {
                modal.classList.toggle('active');
            }
            event.preventDefault(); // prevents rewriting url (apps can still use hash values in url)
        }
    });
}());

/* ========================================================================
 * Ratchet: popovers.js v2.0.2
 * http://goratchet.com/components#popovers
 * ========================================================================
 * Copyright 2014 Connor Sears
 * Licensed under MIT (https://github.com/twbs/ratchet/blob/master/LICENSE)
 * ======================================================================== */

!(function () {
    'use strict';

    var popover;

    var findPopovers = function (target) {
        var i;
        var popovers = document.querySelectorAll('a');

        for (; target && target !== document; target = target.parentNode) {
            for (i = popovers.length; i--;) {
                if (popovers[i] === target) {
                    return target;
                }
            }
        }
    };

    var onPopoverHidden = function () {
        popover.style.display = 'none';
        popover.removeEventListener('webkitTransitionEnd', onPopoverHidden);
    };

    var backdrop = (function () {
        var element = document.createElement('div');

        element.classList.add('backdrop');

        element.addEventListener('touchend', function () {
            popover.addEventListener('webkitTransitionEnd', onPopoverHidden);
            popover.classList.remove('visible');
            popover.parentNode.removeChild(backdrop);
        });

        return element;
    }());

    var getPopover = function (e) {
        var anchor = findPopovers(e.target);

        if (!anchor || !anchor.hash || (anchor.hash.indexOf('/') > 0)) {
            return;
        }

        try {
            popover = document.querySelector(anchor.hash);
        }
        catch (error) {
            popover = null;
        }

        if (popover === null) {
            return;
        }

        if (!popover || !popover.classList.contains('popover')) {
            return;
        }

        return popover;
    };

    var showHidePopover = function (e) {
        var popover = getPopover(e);

        if (!popover) {
            return;
        }

        popover.style.display = 'block';
        popover.offsetHeight;
        popover.classList.add('visible');

        popover.parentNode.appendChild(backdrop);
    };

    window.addEventListener('touchend', showHidePopover);

}());

