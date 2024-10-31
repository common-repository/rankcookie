'use strict'; 

var SETTINGS = rankcookie_settings;   // Set in rankcookie.php

window.Rankcookie = function(){

  var core = {

    // Create a cookie
    setCookie: function(name, value, days, dmn, p) {
      var expires = '';
      var domain = '';
      if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = '; expires=' + date.toGMTString();
      }
      if (dmn) {
        domain = '; domain=' + dmn;
      }
      document.cookie = name + '=' + value + expires + domain + '; path=' + (p || '/');
    },

    // Read previously set cookie
    readCookie: function(name) {
      var nameEQ = name + '=';
      var cookies = decodeURIComponent(document.cookie).split(';');
      for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var idx = cookie.indexOf(nameEQ);
        if (idx >= 0) {
          return cookie.substring(idx + nameEQ.length);
        }
      }
    },

    // Get the value of a single url parameter
    getUrlParameter: function(name) {
      var params = window.location.search.replace('?', '').split('&');
      var value;
      for (var i = 0; i < params.length; i++) {
        if (params[i].split('=')[0] === name) {
          value = params[i].split('=')[1];
        }
      }
      return value;
    },
  }

  var permissionCallbacks = {

    // Callback for permission 'statistics'
    // Executed only once when the user initially answers the cookie banner 
    // On any later page call the tag manager integration takes place within the functions.php
    statistics: function(settings) {    
      var gtmId = settings.gtmId;
      // Initialize Google Tag Manager
      if (gtmId) {
        // Start original Google Tag Manger snippet 
        (function(w, d, s, l, i) {
          w[l] = w[l] || [];
          w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
          });
          var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
          j.async = true;
          j.src =
            'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
          f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', gtmId);
        // End original Google Tag Manger snippet 
      }
    }, 
    // Callback for permission 'essential'
    // Executed only once when the user initially answers the cookie banner 
    essential: function(settings) {
      // Define custom callback here
    },
    // Callback for custom permission 
    // Executed only once when the user initially answers the cookie banner 
    custom: function(settings) {
      // Define custom callback here
    }
  }

  // Evaluate the user input. Store all given permissions as cookie and optionally execute a callback functions for each permission. 
  var handleUserInput = function(checkboxes, acceptAll) {
    var permissions = [];
    for (var i = 0; i < checkboxes.length; i++) {
      if (checkboxes[i].checked || acceptAll) {
        var permission = checkboxes[i].value; 
        permissions.push(permission);
        // Try to execute a callbackback for each permission
        if (permissionCallbacks[permission]) {
          try {
            permissionCallbacks[permission].call(core, SETTINGS); // Give access to core and settings
          } catch (e) {
            console.log('Permission callback failed');   
          }
        }
      };
    }
    // Store all permissions within a single cookie
    if (permissions.join()) {
      core.setCookie(SETTINGS.cookieName, permissions.join(), SETTINGS.cookieLifetime); 
    } 
  }

  // Handlers for the cookie banner buttons
  var handlers = (function(handleUserInput){

    // Helper 
    var getCheckboxes = function(cookieBanner) {
      return cookieBanner.querySelectorAll('input[type="checkbox"]');
    }

    // Remove the cookie banner
    var closeCookieBannerOl =  function(cookieBannerOl) {
      cookieBannerOl.parentNode.removeChild(cookieBannerOl);
    }

    // Open custom settings view
    var openCustomSettings = function(cookieBanner) {
      cookieBanner.classList.add('is-open');
    }

    // Close custom settings view
    var closeCustomSettings =  function(cookieBanner) {
      cookieBanner.classList.remove('is-open');
    }

    // Save custom settings
    var saveCustomSettings = function(cookieBanner, cookieBannerOl) {
      handleUserInput(getCheckboxes(cookieBanner)); 
      closeCookieBannerOl(cookieBannerOl);
    }

    // Accept all cookies
    var acceptAll = function(cookieBanner, cookieBannerOl) { 
      handleUserInput(getCheckboxes(cookieBanner), true); 
      closeCookieBannerOl(cookieBannerOl);
    }

    return {
      closeCookieBannerOl: closeCookieBannerOl,
      openCustomSettings: openCustomSettings, 
      closeCustomSettings: closeCustomSettings,
      saveCustomSettings: saveCustomSettings,
      acceptAll: acceptAll
    }

  }(handleUserInput)); 

  // Init cookie banner
  var init = function(core, handlers) {

    var cookieBannerOl = document.querySelector('.rankcookie-overlay'); 

    /**
     * Per default this js file (as well as the cookie banner HTML snippet)) wouldn't be loaded 
     * if the rankeffect cookie is detected while the page is generated via php (see rankcookie.php). 
     * But server side caching can short circuit that behaviour. Therefore we have to check again here.
     * */
    // Cookie permission already given
    if (core.readCookie(SETTINGS.cookieName)) {
      if (cookieBannerOl) {
        cookieBannerOl.parentNode.removeChild(cookieBannerOl);
      }
      return false;
    }; 

    // Cookie banner doesn't exist 
    if (!cookieBannerOl) {
      return false;
    }

    var cookieBanner = cookieBannerOl.querySelector('.rankcookie'),
      closeCookieBannerBtn = cookieBanner.querySelector('.rankcookie__link--close-banner'),
      acceptAllBtn = cookieBanner.querySelector('.rankcookie__btn--accept-all'),
      customSettingsBtn = cookieBanner.querySelector('.rankcookie__btn--custom-settings'),
      saveSettingsBtn = cookieBanner.querySelector('.rankcookie__btn--save-settings'),
      backBtn = cookieBanner.querySelector('.rankcookie__link--back');

    // Show cookie banner
    cookieBannerOl.style.display = 'block';  
    
    // Option 1: accept all cookies
    acceptAllBtn.addEventListener('click', function() {
      handlers.acceptAll(cookieBanner, cookieBannerOl);
    });

    // Option 2: open custom settings view
    customSettingsBtn.addEventListener('click', function() {
      handlers.openCustomSettings(cookieBanner);
    });

    // Option 2-1: accept all -> see Option 1

    // Option 2-2: save custom settings
    saveSettingsBtn.addEventListener('click', function() {
      handlers.saveCustomSettings(cookieBanner, cookieBannerOl);
    });

    // Option 2-3: close custom settings
    backBtn.addEventListener('click', function() {
      handlers.closeCustomSettings(cookieBanner);
    });

    // Option 3: close cookie banner
    closeCookieBannerBtn.addEventListener('click', function() {
      handlers.closeCookieBannerOl(cookieBannerOl);
    });
  }

  init(core, handlers); 
};

// Wait till DOM ready
function ready(callbackFunc) {
  // Check document state
  if (document.readyState !== 'loading') {
    // Document is already ready, call the callback directly
    callbackFunc();
  } else if (document.addEventListener) {
    // All modern browsers to register DOMContentLoaded
    document.addEventListener('DOMContentLoaded', callbackFunc);
  } else {
    // Old IE browsers
    document.attachEvent('onreadystatechange', function() {
      if (document.readyState === 'complete') {
        callbackFunc();
      }
    });
  }
}

ready(Rankcookie);