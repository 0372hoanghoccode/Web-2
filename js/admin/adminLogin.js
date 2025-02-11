const loginPasswordNoViewIcon = document.querySelector(
  ".loginPasswordView .noView-loginPassword"
);
const loginPasswordViewIcon = document.querySelector(
  ".loginPasswordView .view-loginPassword"
);

const loginPassword = document.querySelector(".password");

loginPasswordNoViewIcon.addEventListener("click", (e) => {
  e.preventDefault();
  loginPassword.setAttribute("type", "text");
  loginPasswordViewIcon.classList.toggle("hide");
  loginPasswordNoViewIcon.classList.toggle("hide");
});

loginPasswordViewIcon.addEventListener("click", (e) => {
  e.preventDefault();
  loginPassword.setAttribute("type", "password");
  loginPasswordViewIcon.classList.toggle("hide");
  loginPasswordNoViewIcon.classList.toggle("hide");
});
$(document).ready(function () {
  $(".btnDangNhap").click(function () {
    const username = document.querySelector(".username").value;
    const password = document.querySelector(".password").value;

    if (username == "" || password == "") {
      alert("Vui lòng nhập đầy đủ username và password!");
      return;
    }

    $.ajax({
      type: "post",
      url: "../controller/admin/loginAdmin.controller.php",
      dataType: "html",
      data: {
        username,
        password,
        isLogin: true,
      },
    }).done(function (result) {
      const data = JSON.parse(result);
      if (data.success) {
        window.location.href = "../admin/index.php";
        alert(data.message);
      } else {
        alert(data.message);
      }
    });
  });
});
