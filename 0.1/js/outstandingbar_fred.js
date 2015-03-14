<<<<<<< .mine
//jQuery(document).ready(function($){
//  
//  $("body").addClass("has-outstanding");
//  
//  setTimeout(function(){
////    $(".outstanding-bar").removeClass("ob-preload");
//  }, 1000);
//
//  // COOKIE STUFF
//
//  /**
//   * Create cookie with javascript
//   *
//   * @param {string} name cookie name
//   * @param {string} value cookie value
//   * @param {int} days2expire
//   * @param {string} path
//   */
//  function create_cookie(name, value, days2expire, path) {
//    var date = new Date();
//    date.setTime(date.getTime() + (days2expire * 24 * 60 * 60 * 1000));
//    var expires = date.toUTCString();
//    document.cookie = name + '=' + value + ';' +
//                     'expires=' + expires + ';' +
//                     'path=' + path + ';';
//  }
//  
//  /**
//  * Retrieve cookie with javascript
//  *
//  * @param {string} name cookie name
//  */
//  function retrieve_cookie(name) {
//    var cookie_value = "",
//      current_cookie = "",
//      name_expr = name + "=",
//      all_cookies = document.cookie.split(';'),
//      n = all_cookies.length;
//
//    for(var i = 0; i < n; i++) {
//      current_cookie = all_cookies[i].trim();
//      if(current_cookie.indexOf(name_expr) == 0) {
//        cookie_value = current_cookie.substring(name_expr.length, current_cookie.length);
//        break;
//      }
//    }
//    return cookie_value;
//  }
//  
//  /**
//  * Delete cookie with javascript
//  *
//  * @param {string} name cookie name
//  */
//  function delete_cookie(name) {
//    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
//  }
//  
//  
//  
//  // set up the cookie variables
//  var cookie_name = 'hide_outstanding_bar';
//  var cookie_value = 'true';
//  var res = retrieve_cookie(cookie_name);
//    
//  // if we have a result for the cookie, collapse the signup bar
//  if(res) {
//    $(".outstanding-bar").addClass("ob-collapsed");
//  }
//  
//  // on collapse signup bar click, add a class to the signup bar & set cookie
//  $("body").on("click", ".collapse-email-subscribe-yo", function() {
//    $(this).closest(".outstanding-bar").addClass("ob-collapsed");
//    // set cookie to expire auto expire in 30 days (and show newsletter bar again)
//    create_cookie(cookie_name, cookie_value, 30, "/");
=======
jQuery(document).ready(function($){
    
    $('#ob-mc-signup').on('click', function(){
        console.log('123');
        if(!_validateForm()){
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
            function(response){
                console.log(response);
            }
        );
    });
    
    function _validateForm(){
        return true;
    }
    
//  setTimeout(function(){
//    $(".outstanding-bar").removeClass("ob-preload");
//  }, 1000);

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

    for(var i = 0; i < n; i++) {
      current_cookie = all_cookies[i].trim();
      if(current_cookie.indexOf(name_expr) == 0) {
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
  if(res) {
    $(".outstanding-bar").addClass("ob-collapsed");
  }
  
  // on collapse signup bar click, add a class to the signup bar & set cookie
  $("body").on("click", ".collapse-email-subscribe-yo", function() {
    $(this).closest(".outstanding-bar").addClass("ob-collapsed");
    // set cookie to expire auto expire in 30 days (and show newsletter bar again)
    create_cookie(cookie_name, cookie_value, 30, "/");
  });
  
  $("body").on("click", ".ob-collapsed", function() {
    $(this).removeClass("ob-collapsed");
    delete_cookie(cookie_name);
  });
  
  $(".outstanding-bar").on("click", "#mc-embedded-subscribe", function() {
    
    setTimeout(function(){
      $(".outstanding-bar .mc-field-group").remove();
      $(".outstanding-bar .submit-cont").remove();
      $(".outstanding-bar .main-message").text("Thanks! Click the link in your email to confirm :)")
      $(".outstanding-bar .collapse-email-subscribe-yo").text("hide me \u00bb")
    }, 20);
  });
  
  // scroll down hide, scroll up show
//  $(function(){
//      //Keep track of last scroll
//      var lastScroll = 0;
//      $(window).scroll(function(event){
//          //Sets the current scroll position
//          var st = $(this).scrollTop();
//          //Determines up-or-down scrolling
//          if (st > lastScroll){//down
//             $(".outstanding-bar").addClass("ob-offscreen");
//          }
//          else {//up
//             $(".outstanding-bar").removeClass("ob-offscreen");
//          }
//          //Updates scroll position
//          lastScroll = st;
//      });
>>>>>>> .r275
//  });
//  
//  $("body").on("click", ".ob-collapsed", function() {
//    $(this).removeClass("ob-collapsed");
//    delete_cookie(cookie_name);
//  });
//  
//  $(".outstanding-bar").on("click", "#mc-embedded-subscribe", function() {
//    
//    setTimeout(function(){
//      $(".outstanding-bar .mc-field-group").remove();
//      $(".outstanding-bar .submit-cont").remove();
//      $(".outstanding-bar .main-message").text("Thanks! Click the link in your email to confirm :)")
//      $(".outstanding-bar .collapse-email-subscribe-yo").text("hide me \u00bb")
//    }, 20);
//  });
//  
//  // scroll down hide, scroll up show
////  $(function(){
////      //Keep track of last scroll
////      var lastScroll = 0;
////      $(window).scroll(function(event){
////          //Sets the current scroll position
////          var st = $(this).scrollTop();
////          //Determines up-or-down scrolling
////          if (st > lastScroll){//down
////             $(".outstanding-bar").addClass("ob-offscreen");
////          }
////          else {//up
////             $(".outstanding-bar").removeClass("ob-offscreen");
////          }
////          //Updates scroll position
////          lastScroll = st;
////      });
////  });
//  
////  // if past certain point scroll
////  $(window).scroll(function(event){
////      var scrollPercentage = 100 * ($(this).scrollTop() / ($('html').height() - $(this).height()));
////      var scrollPoint = 110;
////
////      if (scrollPercentage > scrollPoint){
////         $(".outstanding-bar").removeClass("ob-offscreen");
////      }
////      else {
////         $(".outstanding-bar").addClass("ob-offscreen");
////      }
////  });
//  
//});