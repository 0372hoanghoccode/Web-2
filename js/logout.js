$(document).ready(function () {
  $(".btnDangXuat").click(function () {
    $.ajax({
      type: "post",
      url: "controller/logout.controller.php",
      dataType: "html",
      data: {
        logoutRequest: true,
      },
    }).done(function (result) {
      localStorage.removeItem("keyword");
      window.location.href = "index.php?page=signup";
      alert("Bạn đã đăng xuất thành công!");
    });
  });
});
