jQuery(document).ready(function ($) {
  // Initialize the Bootstrap Switch
  $("#quote_menu_enabled").bootstrapSwitch({
    size: "small",
    onText: "ON",
    offText: "OFF",
    onColor: "success",
    offColor: "danger",
    labelText: " ",
    handleWidth: 40,
    onSwitchChange: function (event, state) {
      // Submit the form when the switch is toggled
      $(this).closest("form").submit();
    },
  });
});
