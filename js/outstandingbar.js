jQuery(document).ready(function ($) {

    if(outstandingBar_isActive){
        $('#ob-mc-signup').on('click', function () {
            if (!_validateForm()) {
                return false;
            }

            var data = {
                action: 'outstandingbar_signup',
                security: outstandingBar_nonce,
                email: $('#ob-mc-email').val()
            };
            $.post(
                    outstandingBar_ajaxUrl,
                    data,
                    function (response) {
                        $('#ob-mc-email').val('');
                        $('#ob-mainText').hide();
                        $('#ob-successText').css('display', 'inline-block');
                    }
            );
        });

        function _validateForm() {
            return true;
        }

        // COOKIE STUFF

        /**
         * Create cookie with javascript
         *
         * @param {string} name cookie name
         * @param {string} value cookie value
         * @param {int} days2expire
         * @param {string} path
         */
        function create_cookie(name, value, days2expire, path) {
            var date = new Date();
            date.setTime(date.getTime() + (days2expire * 24 * 60 * 60 * 1000));
            var expires = date.toUTCString();
            document.cookie = name + '=' + value + ';' +
                    'expires=' + expires + ';' +
                    'path=' + path + ';';
        }

        /**
         * Retrieve cookie with javascript
         *
         * @param {string} name cookie name
         */
        function retrieve_cookie(name) {
            var cookie_value = "",
                    current_cookie = "",
                    name_expr = name + "=",
                    all_cookies = document.cookie.split(';'),
                    n = all_cookies.length;

            for (var i = 0; i < n; i++) {
                current_cookie = all_cookies[i].trim();
                if (current_cookie.indexOf(name_expr) == 0) {
                    cookie_value = current_cookie.substring(name_expr.length, current_cookie.length);
                    break;
                }
            }
            return cookie_value;
        }

        /**
         * Delete cookie with javascript
         *
         * @param {string} name cookie name
         */
        function delete_cookie(name) {
            document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
        }

        // set up the cookie variables
        var cookie_name = 'hide_outstanding_bar';
        var cookie_value = 'true';
        var res = retrieve_cookie(cookie_name);

        // if we have a result for the cookie, collapse the signup bar
        if (res) {
            $("body").addClass("ob-collapsed");
        }

        // on collapse signup bar click, add a class to the signup bar & set cookie
        $("body").on("click", "[class^=ob-hide-]", function (event) {
            event.stopPropagation();
            $("body").addClass("ob-collapsed");
            // set cookie to expire auto expire in 30 days (and show outstanding bar again)
            create_cookie(cookie_name, cookie_value, 30, "/");
        });

        $("html").on("click", ".ob-collapsed .outstanding-bar", function () {
            $("body").removeClass("ob-collapsed");
            delete_cookie(cookie_name);
        });

        $(".outstanding-bar").on("click", "#mc-embedded-subscribe", function () {

            setTimeout(function () {
                $(".outstanding-bar .mc-field-group").remove();
                $(".outstanding-bar .submit-cont").remove();
                $(".outstanding-bar p").text("Thanks! Click the link in your email to confirm :)")
                $(".outstanding-bar .ob-hide").text("hide me \u00bb")
            }, 20);
        });

        function initOnScrollUp() {
            $(function () {
                //Keep track of last scroll
                var lastScroll = 0;
                $(window).scroll(function (event) {
                    //Sets the current scroll position
                    var st = $(this).scrollTop();
                    //Determines up-or-down scrolling
                    if (st > lastScroll) {//down
                        $(".outstanding-bar").addClass("ob-offscreen");
                    }
                    else {//up
                        $(".outstanding-bar").removeClass("ob-offscreen");
                    }
                    //Updates scroll position
                    lastScroll = st;
                });
            });
        }

        function initAlways() {
            setTimeout(function () {
                $(".outstanding-bar").removeClass("ob-offscreen");
            }, 400);
        }

        function init50Percent() {
            $(window).scroll(function (event) {
                var scrollPercentage = ($(this).scrollTop() / (document.body.scrollHeight - window.innerHeight)) * 100;
                var scrollPoint = 50;

                if (scrollPercentage > scrollPoint) {
                    $(".outstanding-bar").removeClass("ob-offscreen");
                } else {
                    $(".outstanding-bar").addClass("ob-offscreen");
                }
            });
        }
    
        switch (outstandingBar_displayStyle) {
            case 'Always':
                initAlways();
                break;
            case 'OnScrollUp':
                initOnScrollUp();
                break;
            case '50Percent':
                init50Percent();
                break;
        }
    }

});