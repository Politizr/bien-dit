(function ($) {
// 	fix timelineHeader for css
    $.fn.stickyTimelineHeader = function (options) {

        var settings = $.extend({
            stickyClass: 'header',
            padding: 60
        }, options);

        return $(this).each(function () {

            var container = $(this);
            var header = $('.' + settings.stickyClass, container);

            var originalCss = {
                position: header.css('position'),
                top: header.css('top'),
                width: header.css('width')
            };

            var placeholder = undefined;
            var originalWidth = header.outerWidth();

            $(window).scroll(function () {
                var containerTop = container.offset().top;
                var headerOrigin = header.offset().top;
                var headerHeight = header.outerHeight();
                var containerHeight = container.outerHeight();
                var containerTop = container.offset().top;
                var containerSize = container.outerHeight();
                var pageOffset = $(window).scrollTop() + settings.padding;
                var containerBottom = containerHeight + containerTop;

                if (pageOffset < containerTop && placeholder != undefined) {
                    if (placeholder != undefined) {
                        placeholder.remove();
                        placeholder = undefined;
                        header.css(originalCss);
                    }
                }
                else if (pageOffset > containerTop && pageOffset < (containerBottom - headerHeight)) {
                    if (placeholder == undefined) {
                        placeholder = $('<div/>')
                        .css('height', header.outerHeight() + 'px')
                        .css('width', header.width() + 'px');
                        header.before(placeholder);
                        header.css('position', 'fixed');
                        header.css('width', originalWidth + 'px');
                    }
                    header.css('top', settings.padding + 'px');
                }
                else if (pageOffset > (containerBottom - headerHeight)) {
                    header.css('top', (containerBottom - headerHeight) - pageOffset + settings.padding + 'px');
                }
            });
        });
    }
	
// 	fix day for css
    $.fn.stickyDayWithTimelineHeader = function (options) {

        var settings = $.extend({
            stickyClass: 'header',
            padding: 110
        }, options);

        return $(this).each(function () {

            var container = $(this);
            var header = $('.' + settings.stickyClass, container);

            var originalCss = {
                position: header.css('position'),
                top: header.css('top'),
                width: header.css('width')
            };

            var placeholder = undefined;
            var originalWidth = header.outerWidth();

            $(window).scroll(function () {
                var containerTop = container.offset().top;
                var headerOrigin = header.offset().top;
                var headerHeight = header.outerHeight();
                var containerHeight = container.outerHeight();
                var containerTop = container.offset().top;
                var containerSize = container.outerHeight();
                var pageOffset = $(window).scrollTop() + settings.padding;
                var containerBottom = containerHeight + containerTop;

                if (pageOffset < containerTop && placeholder != undefined) {
                    if (placeholder != undefined) {
                        placeholder.remove();
                        placeholder = undefined;
                        header.css(originalCss);
                    }
                }
                else if (pageOffset > containerTop && pageOffset < (containerBottom - headerHeight)) {
                    if (placeholder == undefined) {
                        placeholder = $('<div/>')
                        .css('height', header.outerHeight() + 'px')
                        .css('width', header.width() + 'px');
                        header.before(placeholder);
                        header.css('position', 'fixed');
                        header.css('width', originalWidth + 'px');
                    }
                    header.css('top', settings.padding + 'px');
                }
                else if (pageOffset > (containerBottom - headerHeight)) {
                    header.css('top', (containerBottom - headerHeight) - pageOffset + settings.padding + 'px');
                }
            });
        });
    }
    
    $.fn.stickyDayWithoutTimelineHeader = function (options) {

        var settings = $.extend({
            stickyClass: 'header',
            padding: 50
        }, options);

        return $(this).each(function () {

            var container = $(this);
            var header = $('.' + settings.stickyClass, container);

            var originalCss = {
                position: header.css('position'),
                top: header.css('top'),
                width: header.css('width')
            };

            var placeholder = undefined;
            var originalWidth = header.outerWidth();

            $(window).scroll(function () {
                var containerTop = container.offset().top;
                var headerOrigin = header.offset().top;
                var headerHeight = header.outerHeight();
                var containerHeight = container.outerHeight();
                var containerTop = container.offset().top;
                var containerSize = container.outerHeight();
                var pageOffset = $(window).scrollTop() + settings.padding;
                var containerBottom = containerHeight + containerTop;

                if (pageOffset < containerTop && placeholder != undefined) {
                    if (placeholder != undefined) {
                        placeholder.remove();
                        placeholder = undefined;
                        header.css(originalCss);
                    }
                }
                else if (pageOffset > containerTop && pageOffset < (containerBottom - headerHeight)) {
                    if (placeholder == undefined) {
                        placeholder = $('<div/>')
                        .css('height', header.outerHeight() + 'px')
                        .css('width', header.width() + 'px');
                        header.before(placeholder);
                        header.css('position', 'fixed');
                        header.css('width', originalWidth + 'px');
                    }
                    header.css('top', settings.padding + 'px');
                }
                else if (pageOffset > (containerBottom - headerHeight)) {
                    header.css('top', (containerBottom - headerHeight) - pageOffset + settings.padding + 'px');
                }
            });
        });
    }
   
// 	fix day for css1000
    $.fn.stickyDay1000 = function (options) {

        var settings = $.extend({
            stickyClass: 'header',
            padding: 60
        }, options);

        return $(this).each(function () {

            var container = $(this);
            var header = $('.' + settings.stickyClass, container);

            var originalCss = {
                position: header.css('position'),
                top: header.css('top'),
                width: header.css('width')
            };

            var placeholder = undefined;
            var originalWidth = header.outerWidth();

            $(window).scroll(function () {
                var containerTop = container.offset().top;
                var headerOrigin = header.offset().top;
                var headerHeight = header.outerHeight();
                var containerHeight = container.outerHeight();
                var containerTop = container.offset().top;
                var containerSize = container.outerHeight();
                var pageOffset = $(window).scrollTop() + settings.padding;
                var containerBottom = containerHeight + containerTop;

                if (pageOffset < containerTop && placeholder != undefined) {
                    if (placeholder != undefined) {
                        placeholder.remove();
                        placeholder = undefined;
                        header.css(originalCss);
                    }
                }
                else if (pageOffset > containerTop && pageOffset < (containerBottom - headerHeight)) {
                    if (placeholder == undefined) {
                        placeholder = $('<div/>')
                        .css('height', header.outerHeight() + 'px')
                        .css('width', header.width() + 'px');
                        header.before(placeholder);
                        header.css('position', 'fixed');
                        header.css('width', originalWidth + 'px');
                    }
                    header.css('top', settings.padding + 'px');
                }
                else if (pageOffset > (containerBottom - headerHeight)) {
                    header.css('top', (containerBottom - headerHeight) - pageOffset + settings.padding + 'px');
                }
            });
        });
    }
    
// 	fix day for css760    
    $.fn.stickyDay760 = function (options) {

        var settings = $.extend({
            stickyClass: 'header',
            padding: 0
        }, options);

        return $(this).each(function () {

            var container = $(this);
            var header = $('.' + settings.stickyClass, container);

            var originalCss = {
                position: header.css('position'),
                top: header.css('top'),
                width: header.css('width')
            };

            var placeholder = undefined;
            var originalWidth = header.outerWidth();

            $(window).scroll(function () {
                var containerTop = container.offset().top;
                var headerOrigin = header.offset().top;
                var headerHeight = header.outerHeight();
                var containerHeight = container.outerHeight();
                var containerTop = container.offset().top;
                var containerSize = container.outerHeight();
                var pageOffset = $(window).scrollTop() + settings.padding;
                var containerBottom = containerHeight + containerTop;

                if (pageOffset < containerTop && placeholder != undefined) {
                    if (placeholder != undefined) {
                        placeholder.remove();
                        placeholder = undefined;
                        header.css(originalCss);
                    }
                }
                else if (pageOffset > containerTop && pageOffset < (containerBottom - headerHeight)) {
                    if (placeholder == undefined) {
                        placeholder = $('<div/>')
                        .css('height', header.outerHeight() + 'px')
                        .css('width', header.width() + 'px');
                        header.before(placeholder);
                        header.css('position', 'fixed');
                        header.css('width', originalWidth + 'px');
                    }
                    header.css('top', settings.padding + 'px');
                }
                else if (pageOffset > (containerBottom - headerHeight)) {
                    header.css('top', (containerBottom - headerHeight) - pageOffset + settings.padding + 'px');
                }
            });
        });
    }
})(jQuery);

