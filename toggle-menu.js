jQuery(document).ready(function ($) {
  $("#quote_menu_enabled").bootstrapSwitch({
    size: "small",
    onText: "ON",
    offText: "OFF",
    onColor: "success",
    offColor: "danger",
    labelText: " ",
    handleWidth: 40,
    onSwitchChange: function (event, state) {
      $(this).closest("form").submit();
    },
  });
});
