(function ($) {
  Drupal.behaviors.farm_api_development_authorized_callback = {
    attach: function () {

      // Get the full redirect URL with URL Fragments.
      var redirect_url = String(window.location);

      // Display the full redirect URL.
      $("#redirect_url").val(redirect_url);

      // Swap the # for ? to make the URL look like it has query parameters, not fragments.
      var redirect_url_altered = redirect_url.replace("#", "?");

      // Parse altered url for parameters.
      var url = new URL(redirect_url_altered);

      // Update the input fields with values from query parameters.
      var input_fields = ["access_token", "expires_in", "token_type", "scope", "state"];
      $.each(input_fields, function( field) {
        $("#" + field).val(url.searchParams.get(field));
      });
    }
  };
}(jQuery));
