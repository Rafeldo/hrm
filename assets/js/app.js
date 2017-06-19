/*!
 *
 * Angle - Bootstrap Admin App + jQuery
 *
 * Version: 3.2.0
 * Author: @themicon_co
 * Website: http://themicon.co
 * License: https://wrapbootstrap.com/help/licenses
 *
 */



// Start Bootstrap JS
// -----------------------------------

(function (window, document, $, undefined) {

    $(function () {

        // POPOVER
        // -----------------------------------

        $('[data-toggle="popover"]').popover();
        $('.offsidebar.hide').removeClass('hide');

        // TOOLTIP
        // -----------------------------------

        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });

        // DROPDOWN INPUTS
        // -----------------------------------
        $('.dropdown input').on('click focus', function (event) {
            event.stopPropagation();
        });

    });

})(window, document, window.jQuery);

/**=========================================================
 * Module: clear-storage.js
 * Removes a key from the browser storage via element click
 =========================================================*/

(function ($, window, document) {
    'use strict';

    var Selector = '[data-reset-key]';

    $(document).on('click', Selector, function (e) {
        e.preventDefault();
        var key = $(this).data('resetKey');

        if (key) {
            $.localStorage.remove(key);
            // reload the page
            window.location.reload();
        }
        else {
            $.error('No storage key specified for reset.');
        }
    });

}(jQuery, window, document));

// GLOBAL CONSTANTS
// -----------------------------------


(function (window, document, $, undefined) {

    window.APP_COLORS = {
        'primary': '#5d9cec',
        'success': '#27c24c',
        'info': '#23b7e5',
        'warning': '#ff902b',
        'danger': '#f05050',
        'inverse': '#131e26',
        'green': '#37bc9b',
        'pink': '#f532e5',
        'purple': '#7266ba',
        'dark': '#3a3f51',
        'yellow': '#fad732',
        'gray-darker': '#232735',
        'gray-dark': '#3a3f51',
        'gray': '#dde6e9',
        'gray-light': '#e4eaec',
        'gray-lighter': '#edf1f2'
    };

    window.APP_MEDIAQUERY = {
        'desktopLG': 1200,
        'desktop': 992,
        'tablet': 768,
        'mobile': 480
    };

})(window, document, window.jQuery);

// SIDEBAR
// -----------------------------------


(function (window, document, $, undefined) {

    var $win;
    var $html;
    var $body;
    var $sidebar;
    var mq;

    $(function () {

        $win = $(window);
        $html = $('html');
        $body = $('body');
        $sidebar = $('.sidebar');
        mq = APP_MEDIAQUERY;

        // AUTOCOLLAPSE ITEMS
        // -----------------------------------

        var sidebarCollapse = $sidebar.find('.collapse');
        sidebarCollapse.on('show.bs.collapse', function (event) {

            event.stopPropagation();
            if ($(this).parents('.collapse').length === 0)
                sidebarCollapse.filter('.in').collapse('hide');

        });

        // SIDEBAR ACTIVE STATE
        // -----------------------------------

        // Find current active item
        var currentItem = $('.sidebar .active').parents('li');

        // hover mode don't try to expand active collapse
        if (!useAsideHover())
            currentItem
                .addClass('active')     // activate the parent
                .children('.collapse')  // find the collapse
                .collapse('show');      // and show it

        // remove this if you use only collapsible sidebar items
        $sidebar.find('li > a + ul').on('show.bs.collapse', function (e) {
            if (useAsideHover()) e.preventDefault();
        });

        // SIDEBAR COLLAPSED ITEM HANDLER
        // -----------------------------------


        var eventName = isTouch() ? 'click' : 'mouseenter';
        var subNav = $();
        $sidebar.on(eventName, '.nav > li', function () {

            if (isSidebarCollapsed() || useAsideHover()) {

                subNav.trigger('mouseleave');
                subNav = toggleMenuItem($(this));

                // Used to detect click and touch events outside the sidebar
                sidebarAddBackdrop();
            }

        });

        var sidebarAnyclickClose = $sidebar.data('sidebarAnyclickClose');

        // Allows to close
        if (typeof sidebarAnyclickClose !== 'undefined') {

            $('.wrapper').on('click.sidebar', function (e) {
                // don't check if sidebar not visible
                if (!$body.hasClass('aside-toggled')) return;

                var $target = $(e.target);
                if (!$target.parents('.aside').length && // if not child of sidebar
                    !$target.is('#user-block-toggle') && // user block toggle anchor
                    !$target.parent().is('#user-block-toggle') // user block toggle icon
                ) {
                    $body.removeClass('aside-toggled');
                }

            });
        }

    });

    function sidebarAddBackdrop() {
        var $backdrop = $('<div/>', {'class': 'dropdown-backdrop'});
        $backdrop.insertAfter('.aside').on("click mouseenter", function () {
            removeFloatingNav();
        });
    }

    // Open the collapse sidebar submenu items when on touch devices
    // - desktop only opens on hover
    function toggleTouchItem($element) {
        $element
            .siblings('li')
            .removeClass('open')
            .end()
            .toggleClass('open');
    }

    // Handles hover to open items under collapsed menu
    // -----------------------------------
    function toggleMenuItem($listItem) {

        removeFloatingNav();

        var ul = $listItem.children('ul');

        if (!ul.length) return $();
        if ($listItem.hasClass('open')) {
            toggleTouchItem($listItem);
            return $();
        }

        var $aside = $('.aside');
        var $asideInner = $('.aside-inner'); // for top offset calculation
        // float aside uses extra padding on aside
        var mar = parseInt($asideInner.css('padding-top'), 0) + parseInt($aside.css('padding-top'), 0);

        var subNav = ul.clone().appendTo($aside);

        toggleTouchItem($listItem);

        var itemTop = ($listItem.position().top + mar) - $sidebar.scrollTop();
        var vwHeight = $win.height();

        subNav
            .addClass('nav-floating')
            .css({
                position: isFixed() ? 'fixed' : 'absolute',
                top: itemTop,
                bottom: (subNav.outerHeight(true) + itemTop > vwHeight) ? 0 : 'auto'
            });

        subNav.on('mouseleave', function () {
            toggleTouchItem($listItem);
            subNav.remove();
        });

        return subNav;
    }

    function removeFloatingNav() {
        $('.sidebar-subnav.nav-floating').remove();
        $('.dropdown-backdrop').remove();
        $('.sidebar li.open').removeClass('open');
    }

    function isTouch() {
        return $html.hasClass('touch');
    }

    function isSidebarCollapsed() {
        return $body.hasClass('aside-collapsed');
    }

    function isSidebarToggled() {
        return $body.hasClass('aside-toggled');
    }

    function isMobile() {
        return $win.width() < mq.tablet;
    }

    function isFixed() {
        return $body.hasClass('layout-fixed');
    }

    function useAsideHover() {
        return $body.hasClass('aside-hover');
    }

})(window, document, window.jQuery);

// TOGGLE STATE
// -----------------------------------

(function (window, document, $, undefined) {

    $(function () {

        var $body = $('body');
        toggle = new StateToggler();

        $('[data-toggle-state]')
            .on('click', function (e) {
                // e.preventDefault();
                e.stopPropagation();
                var element = $(this),
                    classname = element.data('toggleState'),
                    target = element.data('target'),
                    noPersist = (element.attr('data-no-persist') !== undefined);

                // Specify a target selector to toggle classname
                // use body by default
                var $target = target ? $(target) : $body;

                if (classname) {
                    if ($target.hasClass(classname)) {
                        $target.removeClass(classname);
                        if (!noPersist)
                            toggle.removeState(classname);
                    }
                    else {
                        $target.addClass(classname);
                        if (!noPersist)
                            toggle.addState(classname);
                    }

                }
                // some elements may need this when toggled class change the content size
                // e.g. sidebar collapsed mode and jqGrid
                $(window).resize();

            });

    });

    // Handle states to/from localstorage
    window.StateToggler = function () {

        var storageKeyName = 'jq-toggleState';

        // Helper object to check for words in a phrase //
        var WordChecker = {
            hasWord: function (phrase, word) {
                return new RegExp('(^|\\s)' + word + '(\\s|$)').test(phrase);
            },
            addWord: function (phrase, word) {
                if (!this.hasWord(phrase, word)) {
                    return (phrase + (phrase ? ' ' : '') + word);
                }
            },
            removeWord: function (phrase, word) {
                if (this.hasWord(phrase, word)) {
                    return phrase.replace(new RegExp('(^|\\s)*' + word + '(\\s|$)*', 'g'), '');
                }
            }
        };

        // Return service public methods
        return {
            // Add a state to the browser storage to be restored later
            addState: function (classname) {
                var data = $.localStorage.get(storageKeyName);

                if (!data) {
                    data = classname;
                }
                else {
                    data = WordChecker.addWord(data, classname);
                }

                $.localStorage.set(storageKeyName, data);
            },

            // Remove a state from the browser storage
            removeState: function (classname) {
                var data = $.localStorage.get(storageKeyName);
                // nothing to remove
                if (!data) return;

                data = WordChecker.removeWord(data, classname);

                $.localStorage.set(storageKeyName, data);
            },

            // Load the state string and restore the classlist
            restoreState: function ($elem) {
                var data = $.localStorage.get(storageKeyName);

                // nothing to restore
                if (!data) return;
                $elem.addClass(data);
            }

        };
    };

})(window, document, window.jQuery);

/**=========================================================
 * Module: utils.js
 * jQuery Utility functions library
 * adapted from the core of UIKit
 =========================================================*/

(function ($, window, doc) {
    'use strict';

    var $html = $("html"), $win = $(window);

    $.support.transition = (function () {

        var transitionEnd = (function () {

            var element = doc.body || doc.documentElement,
                transEndEventNames = {
                    WebkitTransition: 'webkitTransitionEnd',
                    MozTransition: 'transitionend',
                    OTransition: 'oTransitionEnd otransitionend',
                    transition: 'transitionend'
                }, name;

            for (name in transEndEventNames) {
                if (element.style[name] !== undefined) return transEndEventNames[name];
            }
        }());

        return transitionEnd && {end: transitionEnd};
    })();

    $.support.animation = (function () {

        var animationEnd = (function () {

            var element = doc.body || doc.documentElement,
                animEndEventNames = {
                    WebkitAnimation: 'webkitAnimationEnd',
                    MozAnimation: 'animationend',
                    OAnimation: 'oAnimationEnd oanimationend',
                    animation: 'animationend'
                }, name;

            for (name in animEndEventNames) {
                if (element.style[name] !== undefined) return animEndEventNames[name];
            }
        }());

        return animationEnd && {end: animationEnd};
    })();

    $.support.requestAnimationFrame = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.msRequestAnimationFrame || window.oRequestAnimationFrame || function (callback) {
            window.setTimeout(callback, 1000 / 60);
        };
    $.support.touch = (
        ('ontouchstart' in window && navigator.userAgent.toLowerCase().match(/mobile|tablet/)) ||
        (window.DocumentTouch && document instanceof window.DocumentTouch) ||
        (window.navigator['msPointerEnabled'] && window.navigator['msMaxTouchPoints'] > 0) || //IE 10
        (window.navigator['pointerEnabled'] && window.navigator['maxTouchPoints'] > 0) || //IE >=11
        false
    );
    $.support.mutationobserver = (window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver || null);

    $.Utils = {};

    $.Utils.debounce = function (func, wait, immediate) {
        var timeout;
        return function () {
            var context = this, args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };

    $.Utils.removeCssRules = function (selectorRegEx) {
        var idx, idxs, stylesheet, _i, _j, _k, _len, _len1, _len2, _ref;

        if (!selectorRegEx) return;

        setTimeout(function () {
            try {
                _ref = document.styleSheets;
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    stylesheet = _ref[_i];
                    idxs = [];
                    stylesheet.cssRules = stylesheet.cssRules;
                    for (idx = _j = 0, _len1 = stylesheet.cssRules.length; _j < _len1; idx = ++_j) {
                        if (stylesheet.cssRules[idx].type === CSSRule.STYLE_RULE && selectorRegEx.test(stylesheet.cssRules[idx].selectorText)) {
                            idxs.unshift(idx);
                        }
                    }
                    for (_k = 0, _len2 = idxs.length; _k < _len2; _k++) {
                        stylesheet.deleteRule(idxs[_k]);
                    }
                }
            } catch (_error) {
            }
        }, 0);
    };

    $.Utils.isInView = function (element, options) {

        var $element = $(element);

        if (!$element.is(':visible')) {
            return false;
        }

        var window_left = $win.scrollLeft(),
            window_top = $win.scrollTop(),
            offset = $element.offset(),
            left = offset.left,
            top = offset.top;

        options = $.extend({topoffset: 0, leftoffset: 0}, options);

        if (top + $element.height() >= window_top && top - options.topoffset <= window_top + $win.height() &&
            left + $element.width() >= window_left && left - options.leftoffset <= window_left + $win.width()) {
            return true;
        } else {
            return false;
        }
    };

    $.Utils.options = function (string) {

        if ($.isPlainObject(string)) return string;

        var start = (string ? string.indexOf("{") : -1), options = {};

        if (start != -1) {
            try {
                options = (new Function("", "var json = " + string.substr(start) + "; return JSON.parse(JSON.stringify(json));"))();
            } catch (e) {
            }
        }

        return options;
    };

    $.Utils.events = {};
    $.Utils.events.click = $.support.touch ? 'tap' : 'click';

    $.langdirection = $html.attr("dir") == "rtl" ? "right" : "left";

    $(function () {

        // Check for dom modifications
        if (!$.support.mutationobserver) return;

        // Install an observer for custom needs of dom changes
        var observer = new $.support.mutationobserver($.Utils.debounce(function (mutations) {
            $(doc).trigger("domready");
        }, 300));

        // pass in the target node, as well as the observer options
        observer.observe(document.body, {childList: true, subtree: true});

    });

    // add touch identifier class
    $html.addClass($.support.touch ? "touch" : "no-touch");

}(jQuery, window, document));

// FULLSCREEN
// -----------------------------------

(function (window, document, $, undefined) {

    if (typeof screenfull === 'undefined') return;

    $(function () {

        var $doc = $(document);
        var $fsToggler = $('[data-toggle-fullscreen]');

        // Not supported under IE
        var ua = window.navigator.userAgent;
        if (ua.indexOf("MSIE ") > 0 || !!ua.match(/Trident.*rv\:11\./)) {
            $fsToggler.addClass('hide');
        }

        if (!$fsToggler.is(':visible')) // hidden on mobiles or IE
            return;

        $fsToggler.on('click', function (e) {
            e.preventDefault();

            if (screenfull.enabled) {

                screenfull.toggle();

                // Switch icon indicator
                toggleFSIcon($fsToggler);

            } else {
                console.log('Fullscreen not enabled');
            }
        });

        if (screenfull.raw && screenfull.raw.fullscreenchange)
            $doc.on(screenfull.raw.fullscreenchange, function () {
                toggleFSIcon($fsToggler);
            });

        function toggleFSIcon($element) {
            if (screenfull.isFullscreen)
                $element.children('em').removeClass('fa-expand').addClass('fa-compress');
            else
                $element.children('em').removeClass('fa-compress').addClass('fa-expand');
        }

    });

})(window, document, window.jQuery);
// Select2
// -----------------------------------


$(function () {
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayBtn: "linked",
    });

    $('.monthyear').datepicker({
        autoclose: true,
        startView: 1,
        format: 'yyyy-mm',
        minViewMode: 1,
    });

    $('.years').datepicker({
        startView: 2,
        format: 'yyyy',
        minViewMode: 2,
        autoclose: true,
    });

    $('.textarea').summernote({
        codemirror: {// codemirror options
            theme: 'monokai'
        }
    });
    $('.note-toolbar .note-fontsize,.note-toolbar .note-help,.note-toolbar .note-fontname,.note-toolbar .note-height,.note-toolbar .note-table').remove();


    $('.timepicker').timepicker();

    $('.timepicker2').timepicker({
        minuteStep: 1,
        showSeconds: false,
        showMeridian: false,
        defaultTime: false
    });
});
// CLASSYLOADER
// -----------------------------------

(function (window, document, $, undefined) {

    $(function () {

        var $scroller = $(window),
            inViewFlagClass = 'js-is-in-view'; // a classname to detect when a chart has been triggered after scroll

        $('[data-classyloader]').each(initClassyLoader);

        function initClassyLoader() {

            var $element = $(this),
                options = $element.data();

            // At lease we need a data-percentage attribute
            if (options) {
                if (options.triggerInView) {

                    $scroller.scroll(function () {
                        checkLoaderInVIew($element, options);
                    });
                    // if the element starts already in view
                    checkLoaderInVIew($element, options);
                }
                else
                    startLoader($element, options);
            }
        }

        function checkLoaderInVIew(element, options) {
            var offset = -20;
            if (!element.hasClass(inViewFlagClass) &&
                $.Utils.isInView(element, {topoffset: offset})) {
                startLoader(element, options);
            }
        }

        function startLoader(element, options) {
            element.ClassyLoader(options).addClass(inViewFlagClass);
        }

    });

})(window, document, window.jQuery);

// Easypie chart
// -----------------------------------

(function (window, document, $, undefined) {

    $(function () {

        if (!$.fn.easyPieChart) return;

        var pieOptions1 = {
            animate: {
                duration: 800,
                enabled: true
            },
            barColor: APP_COLORS['success'],
            trackColor: false,
            scaleColor: false,
            lineWidth: 10,
            lineCap: 'circle'
        };
        $('.easypiechart').easyPieChart(pieOptions1);

        var pieOptions2 = {
            animate: {
                duration: 800,
                enabled: true
            },
            barColor: APP_COLORS['warning'],
            trackColor: false,
            scaleColor: false,
            lineWidth: 4,
            lineCap: 'circle'
        };
        $('#easypie2').easyPieChart(pieOptions2);

        var pieOptions3 = {
            animate: {
                duration: 800,
                enabled: true
            },
            barColor: APP_COLORS['danger'],
            trackColor: APP_COLORS['gray-light'],
            scaleColor: APP_COLORS['gray'],
            size: 200,
            lineWidth: 15,
            lineCap: 'circle'
        };
        $('#easypie3').easyPieChart(pieOptions3);

        var pieOptions4 = {
            animate: {
                duration: 800,
                enabled: true
            },
            barColor: APP_COLORS['danger'],
            trackColor: APP_COLORS['yellow'],
            scaleColor: APP_COLORS['gray-dark'],
            lineWidth: 10,
            lineCap: 'circle'
        };
        $('#easypie4').easyPieChart(pieOptions4);

    });

})(window, document, window.jQuery);

(function (window, document, $, undefined) {

    $(function () {
        var maincss = $('#maincss');
        var bscss = $('#bscss');
        $('#chk-rtl').on('change', function () {

            // app rtl check
            maincss.attr('href', this.checked ? '../../assets/css/app-rtl.css' : "../../assets/css/app.css");
            // bootstrap rtl check
            bscss.attr('href', this.checked ? '../../assets/css/bootstrap-rtl.css' : '../../assets/css/bootstrap.css');

        });

    });

})(window, document, window.jQuery);
// LOAD CUSTOM CSS
// -----------------------------------

(function (window, document, $, undefined) {

    $(function () {

        $('[data-load-css]').on('click', function (e) {

            var element = $(this);

            if (element.is('a'))
                e.preventDefault();

            var uri = element.data('loadCss'),
                link;

            if (uri) {
                link = createLink(uri);
                if (!link) {
                    $.error('Error creating stylesheet link element.');
                }
            }
            else {
                $.error('No stylesheet location defined.');
            }

        });
    });

    function createLink(uri) {
        var linkId = 'autoloaded-stylesheet',
            oldLink = $('#' + linkId).attr('id', linkId + '-old');

        $('head').append($('<link/>').attr({
            'id': linkId,
            'rel': 'stylesheet',
            'href': uri
        }));

        if (oldLink.length) {
            oldLink.remove();
        }

        return $('#' + linkId);
    }


})(window, document, window.jQuery);

(function (window, document, $, undefined) {

    $(function () {

        $('[data-check-all]').on('change', function () {
            var $this = $(this),
                index = $this.index() + 1,
                checkbox = $this.find('input[type="checkbox"]'),
                table = $this.parents('table');
            // Make sure to affect only the correct checkbox column
            table.find('tbody > tr > td:nth-child(' + index + ') input[type="checkbox"]')
                .prop('checked', checkbox[0].checked);

        });


    });
    /*
     * Select All select
     */
    $(function () {
        $('#parent_present').on('change', function () {
            $('.child_present').prop('checked', $(this).prop('checked'));
        });
        $('.child_present').on('change', function () {
            $('.child_present').prop($('.child_present:checked').length ? true : false);
        });
        $('#parent_absent').on('change', function () {
            $('.child_absent').prop('checked', $(this).prop('checked'));
        });
        $('.child_absent').on('change', function () {
            $('.child_absent').prop($('.child_absent:checked').length ? true : false);
        });
    });
    $('#check_related').change(function () {
        $('.company').hide()
        $(".company").attr('disabled', 'disabled');
    });

    var client_stusus = $('#client_stusus').val();
    if (client_stusus == '2') {
        $(".company").removeAttr('disabled');
    } else {
        $(".company").attr('disabled', 'disabled');
        $('.company').hide()
    }
    $('#client_stusus').change(function () {
        if ($('#client_stusus').val() == '1') {
            $('.person').show();
            $('.company').hide();
            $(".company").attr('disabled', 'disabled');
            $(".person").removeAttr('disabled');
        } else {
            $('.person').hide();
            $('.company').show();
            $(".person").attr('disabled', 'disabled');
            $(".company").removeAttr('disabled');
        }
    });
    $('.company_vat').hide();
    $('#get_company').change(function () {
        if ($('#get_company').val() == '2') {
            $('.company_vat').show();
            $(".company_vat").removeAttr('disabled');

        } else {
            $('.company_vat').hide();
            $(".company_vat").attr('disabled', 'disabled');
        }
    });
    // ************* CRM ********************
    $(function () {
        $('input[id="use_postmark"]').click(function () {
            if (this.checked) {
                $('div#postmark_config').show();
            } else {
                $('div#postmark_config').hide();
            }
        });
        $('input[id="for_leads"]').click(function () {
            if (this.checked) {
                $('div#imap_search_for_leads').show();
            } else {
                $('div#imap_search_for_leads').hide();
            }
        });
        $('input[id="for_tickets"]').click(function () {
            if (this.checked) {
                $('div#imap_search_for_tickets').show();
            } else {
                $('div#imap_search_for_tickets').hide();
            }
        });
        $('#protocol').change(function () {
            if ($('#protocol').val() == 'smtp') {
                $('#smtp_config').show();
            } else {
                $('#smtp_config').hide();
            }
        });
        $('#new_departments').change(function () {
            if ($('#new_departments').val() != '') {
                $('.new_departments').hide();
                $(".new_departments").attr('disabled', 'disabled');
            } else {
                $('.new_departments').show();
                $(".new_departments").removeAttr('disabled');
            }
        });
        $('#goal_type_id').change(function () {
            if ($('#goal_type_id').val() == '2' || $('#goal_type_id').val() == '4') {
                $('#account').show();
                document.getElementById("account_id").disabled = false;
            } else {
                $('#account').hide();
                document.getElementById("account_id").disabled = true;
            }
        });
        $('input.select_one').on('change', function () {
            $('input.select_one').not(this).prop('checked', false);
        });

        $('input[id="same_time"]').click(function () {
            if (this.checked) {
                $('.same_time').show();
                $('.different_time').hide();
                $(".different_time_input").attr('disabled', 'disabled');
                $(".disabled").attr('disabled', 'disabled');
                $(".same_time").removeAttr('disabled');
            } else {
                $('.same_time').hide();
                $(".same_time").attr('disabled', 'disabled');
            }
        });

        $('input[id="different_time"]').click(function () {
            if (this.checked) {
                $('.same_time').hide();
                $('.different_time').show();
                $(".different_time_input").removeAttr('disabled');
                $(".same_time").attr('disabled', 'disabled');
            } else {
                $('.same_time').hide();
                $(".different_time_input").attr('disabled', 'disabled');
                $(".same_time").attr('disabled', 'disabled');
            }
        });
        $(".disabled").attr('disabled', 'disabled');
        $(".different_time_input").click(function () {
            var id = $(this).val();
            if (this.checked) {
                $('#different_time_' + id).show();
                $(".different_time_hours_" + id).removeAttr('disabled');
                $(".different_time_hours_" + id).removeClass('disabled');

            } else {
                $('#different_time_' + id).hide();
                $(".different_time_hours_" + id).attr('disabled', 'disabled');

            }
        });
        $('input[id="fixed_rate"]').click(function () {
            if (this.checked) {
                $('div.fixed_price').show();
                $('div.hourly_rate').hide();
                $("div.hourly_rate").removeClass('hidden');
            } else {
                $('div.fixed_price').hide();
                $('div.hourly_rate').show();
                $("div.fixed_price").removeClass('hidden');
                $("div.hourly_rate").removeClass('hidden');
            }
        });
    });

})(window, document, window.jQuery);


// SPARKLINE
// -----------------------------------

(function (window, document, $, undefined) {

    $(function () {

        $('[data-sparkline]').each(initSparkLine);

        function initSparkLine() {
            var $element = $(this),
                options = $element.data(),
                values = options.values && options.values.split(',');

            options.type = options.type || 'bar'; // default chart is bar
            options.disableHiddenCheck = true;

            $element.sparkline(values, options);

            if (options.resize) {
                $(window).resize(function () {
                    $element.sparkline(values, options);
                });
            }
        }
    });

})(window, document, window.jQuery);
