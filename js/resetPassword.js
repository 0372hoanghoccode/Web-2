var searchInput = document.getElementById("searchInput");
var notification = document.querySelector(".notification");

// Add focus event to search input
searchInput.addEventListener("focus", function () {
  notification.style.display = "flex";

  // Close notification when clicking outside after a delay
  setTimeout(function () {
    document.addEventListener("click", clickOutsideHandler);
  }, 0);
});

// Function to handle click outside
function clickOutsideHandler(event) {
  var isClickInside =
    notification.contains(event.target) || searchInput.contains(event.target);
  if (!isClickInside && event.target !== searchInput) {
    // Kiểm tra xem không phải click vào trường tìm kiếm
    notification.style.display = "none";
    document.removeEventListener("click", clickOutsideHandler);
  }
}

// Hàm để hiện phần thông tin của tôi
function showNotification() {
  document.getElementById("overlay").style.display = "block";
  document.getElementById("notificationBox").style.display = "block";
}

function hideNotification() {
  document.getElementById("overlay").style.display = "none";
  document.getElementById("notificationBox").style.display = "none";
}
function saveInfo() {
  var newName = document.getElementById("newName").value;
  var newPhone = document.getElementById("newPhone").value;
  var newAddress = document.getElementById("newAddress").value;

  document.getElementById("name").innerText = newName;
  document.getElementById("phone").innerText = newPhone;
  document.getElementById("address").innerText = newAddress;

  hideNotification();
}


var urlParams = new URLSearchParams(window.location.search);


// Kiểm tra form hợp lệ
const btnDangNhap = document.querySelector(".btnDangNhap");
const btnDangKy = document.querySelector(".btnDangKy");

const loginPassword = document.querySelector("#loginPassword");
const errMessagePassword = document.querySelector(".errMessagePassword");

const registerEmail = document.querySelector("#email");
const registerPassword = document.querySelector("#registerPassword");
const registerConfirmPassword = document.querySelector(
  "#registerConfirmPassword"
);
const registerTinhThanhPho = document.querySelector("#tinhthanhpho");
const registerQuanHuyen = document.querySelector("#quanhuyen");
const registerPhuongXa = document.querySelector("#phuongxa");


const errMessageEmailRegister = document.querySelector(
  ".errMessageEmail"
);

const errMessagePasswordRegister = document.querySelector(
  ".errMessagePasswordRegister"
);
const errMessageConfirmPasswordRegister = document.querySelector(
  ".errMessageConfirmPasswordRegister"
);


const validationFormEmail = () => {

  let isNotEmptyEmail = false;


  const regexEmail = /^(([A-Za-z0-9]+((\.|\-|\_|\+)?[A-Za-z0-9]?)*[A-Za-z0-9]+)|[A-Za-z0-9]+)@(([A-Za-z0-9]+)+((\.|\-|\_)?([A-Za-z0-9]+)+)*)+\.([A-Za-z]{2,})+$/;



  if (registerEmail.value.trim() == "") {
    errMessageEmailRegister.innerText = "Vui lòng điền email";
    isNotEmptyEmail = false;
  } else if (!regexEmail.test(registerEmail.value.trim())) {
    errMessageEmailRegister.innerText =
      "Nhập email đúng định dạng (ví dụ: minhne04@gmail.com)";
    isNotEmptyEmail = false;
  } else {
    errMessageEmailRegister.innerText = "";
    isNotEmptyEmail = true;
  }


  return (
    isNotEmptyEmail
  );
};

const validationFormPassword = () => {

  let isNotEmptyPassword = false;
  let isNotEmptyConfirmPassword = false;


  if (registerPassword.value.trim() == "") {
    errMessagePasswordRegister.innerText = "Vui lòng điền mật khẩu";
    isNotEmptyPassword = false;
  } else if (registerPassword.value.trim().length < 8) {
    errMessagePasswordRegister.innerText =
      "Vui lòng điền mật khẩu tối thiểu 8 kí tự";
    isNotEmptyPassword = false;
  } else {
    errMessagePasswordRegister.innerText = "";
    isNotEmptyPassword = true;
  }

  if (registerConfirmPassword.value.trim() == "") {
    errMessageConfirmPasswordRegister.innerText =
      "Vui lòng điền nhập lại mật khẩu";
    isNotEmptyConfirmPassword = false;
  } else if (
    registerConfirmPassword.value.trim() != registerPassword.value.trim() &&
    registerPassword.value.trim() != ""
  ) {
    errMessageConfirmPasswordRegister.innerText =
      "Vui lòng điền nhập lại mật khẩu khớp với mật khẩu";
    isNotEmptyConfirmPassword = false;
  } else {
    errMessageConfirmPasswordRegister.innerText = "";
    isNotEmptyConfirmPassword = true;
  }

  return (
    isNotEmptyPassword &&
    isNotEmptyConfirmPassword
  );
};

$(document).ready(function () {

  $(".btnSendCode").click(function (e) {
    var date = new Date();
    var currentMonth = date.getMonth() + 1;
    if (currentMonth < 10) { currentMonth = '0' + currentMonth; }
    var currentHour = date.getHours();
    if (currentHour < 10) { currentHour = '0' + currentHour; }
    var time = date.getFullYear() + "-" + currentMonth + "-" + date.getDate() + " " + currentHour + ":" + date.getMinutes() + ":" + date.getSeconds();
    e.preventDefault();
    if ($("#verify_code").val().length < 6) $("#verify_code").focus();
    else {
      document.querySelector(".reload").classList.remove("hidden");
      $.ajax({

        url: "controller/signup.controller.php",
        type: "post",
        dataType: "html",
        data: {
          function: "checkCode",
          verify_code: $("#verify_code").val(),
          verify_time: time,
          emailRegister: $("#email").val(),
        },
      }).done(function (result) {
        const data = JSON.parse(result);
        document.querySelector(".reload").classList.add("hidden");
        if (validationFormEmail()) {
          if (data.success) {
            document.querySelector("#verifyCodeInput").classList.remove("active");
            document.querySelector("#passwordInput").classList.add("active");
          }
        } else {
          $("#verify_code").val("");
          $("#verify_code").focus();
          $(".verify_code_msg").html(data.message);

        }
      })
    }
  })
  $(".new_verify_code").click(function (e) {
    var date = new Date();
    var currentMonth = date.getMonth() + 1;
    if (currentMonth < 10) { currentMonth = '0' + currentMonth; }
    var currentHour = date.getHours();
    if (currentHour < 10) { currentHour = '0' + currentHour; }
    var time = date.getFullYear() + "-" + currentMonth + "-" + date.getDate() + " " + currentHour + ":" + date.getMinutes() + ":" + date.getSeconds();
    e.preventDefault();
    document.querySelector(".reload").classList.remove("hidden");
    $.ajax({
      url: "controller/signup.controller.php",
      type: "post",
      dataType: "html",
      data: {
        // function: "sendNewCode",
        verify_time: time,
        email: $("#email").val(),
      },
    }).done(function (result) {
      document.querySelector(".reload").classList.add("hidden");
      $(".verify_code_msg").html(result);
    })
  })

  $(".btnEmail").click(function (e) {
    e.preventDefault();
    if (validationFormEmail()) {
      // var username = $("#registerUsername").val();
      document.querySelector(".reload").classList.remove("hidden");
      $.ajax({
        url: "controller/signup.controller.php",
        type: "post",
        dataType: "html",
        data: {
          function: "checkEmailExist",
          // usernameRegister: username,
          email: $("#email").val(),
        },
      }).done(function (result) {
        // console.log(result);
        const data = JSON.parse(result);
        document.querySelector(".reload").classList.add("hidden");
        if (data.success) {
          document.querySelector("#emailInput").classList.remove("active");
          document.querySelector("#verifyCodeInput").classList.add("active");
          $(".verify_code_msg").html(data.message);
        } else {
          if (!data.existEmail) {
            errMessageEmailRegister.innerText = "Email chưa được đăng ký";
            registerEmail.focus();
          }
        }
      });
    }
  });
  $(".btnPassword").click(function (e) {
    e.preventDefault();
    if (validationFormPassword()) {
      // var username = $("#registerUsername").val();
      var email = $("#email").val();
      var password = $("#registerPassword").val();
      var confirmPassword = $("#registerConfirmPassword").val();
      document.querySelector(".reload").classList.remove("hidden");
      $.ajax({
        url: "controller/signup.controller.php",
        type: "post",
        dataType: "html",
        data: {
          function: "resetPassword",
          email: email,
          password: password,
          confirmPassword: confirmPassword,
        },
      }).done(function () {
        document.querySelector(".reload").classList.add("hidden");
        window.location.href = "index.php?page=signup";
        alert("Bạn đã đặt lại mật khẩu thành công!");
      })
      // $.ajax({
      //   url: "controller/signup.controller.php",
      //   type: "post",
      //   dataType: "html",
      //   data: {
      //     function: "resetPassword",
      //     // usernameRegister: username,
      //     email: $("#email").val(),
      //   },
      // }).done(function (result) {
      //   // console.log(result);
      //   const data = JSON.parse(result);
      //   document.querySelector(".reload").classList.add("hidden");
      //   if (data.success) {
      //     document.querySelector("#emailInput").classList.remove("active");
      //     document.querySelector("#verifyCodeInput").classList.add("active");
      //     $(".verify_code_msg").html(data.message);
      //   } else {
      //     if (!data.existEmail) {
      //       errMessageEmailRegister.innerText = "Email chưa được đăng ký";
      //       registerEmail.focus();
      //     }
      //   }
      // });
    }
  });
});



// Xử lý xem/ẩn mật khẩu input

const registerPasswordNoViewIcon = document.querySelector(
  ".registerPasswordView .noView-registerPassword"
);
const registerPasswordViewIcon = document.querySelector(
  ".registerPasswordView .view-registerPassword"
);
const registerConfirmPasswordNoViewIcon = document.querySelector(
  ".registerConfirmPasswordView .noView-registerConfirmPassword"
);
const registerConfirmPasswordViewIcon = document.querySelector(
  ".registerConfirmPasswordView .view-registerConfirmPassword"
);

registerPasswordNoViewIcon.addEventListener("click", (e) => {
  e.preventDefault();
  registerPassword.setAttribute("type", "text");
  registerPasswordViewIcon.classList.toggle("hide");
  registerPasswordNoViewIcon.classList.toggle("hide");
});

registerPasswordViewIcon.addEventListener("click", (e) => {
  e.preventDefault();
  registerPassword.setAttribute("type", "password");
  registerPasswordViewIcon.classList.toggle("hide");
  registerPasswordNoViewIcon.classList.toggle("hide");
});

registerConfirmPasswordNoViewIcon.addEventListener("click", (e) => {
  e.preventDefault();
  registerConfirmPassword.setAttribute("type", "text");
  registerConfirmPasswordViewIcon.classList.toggle("hide");
  registerConfirmPasswordNoViewIcon.classList.toggle("hide");
});

registerConfirmPasswordViewIcon.addEventListener("click", (e) => {
  e.preventDefault();
  registerConfirmPassword.setAttribute("type", "password");
  registerConfirmPasswordViewIcon.classList.toggle("hide");
  registerConfirmPasswordNoViewIcon.classList.toggle("hide");
});

function setInputFilter(textbox, inputFilter, errMsg) {
  ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop", "focusout"].forEach(function (event) {
    textbox.addEventListener(event, function (e) {
      if (inputFilter(this.value)) {
        // Accepted value.
        // if (["keydown", "mousedown", "focusout"].indexOf(e.type) >= 0) {
        //     this.classList.remove("input-error");
        //     this.setCustomValidity("");
        // }

        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        // Rejected value: restore the previous one.
        // this.classList.add("input-error");
        // this.setCustomValidity(errMsg);
        this.reportValidity();
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        // Rejected value: nothing to restore.
        this.value = "";
      }
    });
  });
}
setInputFilter(document.getElementById("verify_code"), function (value) {
  return /^\d*?\d*$/.test(value); // Allow digits and '.' only, using a RegExp.
}, "Only digits and '.' are allowed");