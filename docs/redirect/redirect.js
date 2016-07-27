/**
 * redirect.js
 */
(function () {

  "use strict";

  // Function for matching redirects.
  // To be used with map().
  // If the source path matches the current URL, the destination URL
  // is returned. False otherwise.
  function matchRedirects(redirect) {
    var currentPath = window.location.pathname.replace(/\/$/, "");
    if (currentPath == redirect.src) {
      return redirect.dst;
    }
    else {
      return false;
    }
  }

  // Match redirects.
  var matches = redirects.map(matchRedirects);

  // Redirect to the first match.
  for (var i = 0, len = matches.length; i < len; i++) {
    if (matches[i]) {
      window.location.href = matches[i];
      break;
    }
  }
})();

