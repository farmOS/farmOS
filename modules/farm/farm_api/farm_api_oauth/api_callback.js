window.onload = function() {
  // Get the full redirect URL with URL Fragments.
  var redirect_url = String(window.location);
  // Display the full redirect URL.
  document.getElementsByName("redirect_url")[0].value = redirect_url;

  // Swap the # for ? to make the URL look like it has query parameters, not fragments.
  var redirect_url_altered = redirect_url.replace("#", "?");

  // Parse altered url for parameters.
  const url = new URL(redirect_url_altered);

  // Update the input fields with values from query parameters.
  const input_fields = ["access_token", "expires_in", "token_type", "scope", "state"];
  for (field of input_fields) {
    try {
      document.getElementsByName(field)[0].value = String(url.searchParams.get(field));
    }
    catch(error) {
      console.error(error);
    }
  }

  // If this page was opened in a popup window,
  // send a message back to the window opener with
  // the url query parameters. This is used for
  // completing the OAuth Authorization Code flow.
  if (window.opener) {
    window.opener.postMessage(window.location.search, "*");
    window.close();
  }
};
