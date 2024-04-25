//****************************
  /* This is for sidebartoggler*/
  //****************************
  function cambiaside() {
    $("#main-wrapper").toggleClass("mini-sidebar");
    if ($("#main-wrapper").hasClass("mini-sidebar")) {
      $(".sidebartoggler").prop("checked", !0);
      $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
    } else {
      $(".sidebartoggler").prop("checked", !1);
      $("#main-wrapper").attr("data-sidebartype", "full");
    }
  };