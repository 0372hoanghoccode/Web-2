const emailRegex =
  /^(([A-Za-z0-9]+((\.|\-|\_|\+)?[A-Za-z0-9]?)*[A-Za-z0-9]+)|[A-Za-z0-9]+){6,50}@(([A-Za-z0-9]+)+((\.|\-|\_)?([A-Za-z0-9]+)+)*)+\.([A-Za-z]{2,})+$/;

var filter_form = document.querySelector(".admin__content--body__filter");
function getFilterFromURL() {
  filter_form.querySelector("#publisherName").value =
    urlParams["name"] != null ? urlParams["name"] : "";
  filter_form.querySelector("#publisherId").value =
    urlParams["id"] != null ? urlParams["id"] : "";
  filter_form.querySelector("#publisherEmail").value =
    urlParams["email"] != null ? urlParams["email"] : "";
  filter_form.querySelector("#statusSelect").value =
    urlParams["status"] != null ? urlParams["status"] : "active";
}
function pushFilterToURL() {
  var filter = getFilterFromForm();
  var url_key = {
    publisher_name: "name",
    publisher_id: "id",
    publisher_email: "email",
    publisher_status: "status",
  };
  var url = "";
  Object.keys(filter).forEach(key => {
    url +=
      filter[key] != null && filter[key] != ""
        ? `&${url_key[key]}=${filter[key]}`
        : "";
  });
  return url;
}

function getFilterFromForm() {
  return {
    publisher_name: filter_form.querySelector("#publisherName").value,
    publisher_id: filter_form.querySelector("#publisherId").value,
    publisher_email: filter_form.querySelector("#publisherEmail").value,
    publisher_status: filter_form.querySelector("#statusSelect").value,
  };
}
// Load the jquery
var script = document.createElement("SCRIPT");
script.src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js";
script.type = "text/javascript";
document.getElementsByTagName("head")[0].appendChild(script);
var search = location.search.substring(1);
urlParams = JSON.parse(
  '{"' + search.replace(/&/g, '","').replace(/=/g, '":"') + '"}',
  function (key, value) {
    return key === "" ? value : decodeURIComponent(value);
  }
);
var number_of_item = urlParams["item"];
var current_page = urlParams["pag"];
var orderby = urlParams["orderby"];
var order_type = urlParams["order_type"];
if (current_page == null) {
  current_page = 1;
}
if (number_of_item == null) {
  number_of_item = 5;
}
if (orderby == null) {
  orderby = "";
}
if (order_type != "ASC" && order_type != "DESC") {
  order_type = "ASC";
}
function checkReady() {
  return new Promise(async function (resolve) {
    while (!window.jQuery) {
      await new Promise(resolve => setTimeout(resolve, 20));
    }
    resolve();
  });
}
async function loadForFirstTime() {
  await checkReady();
  getFilterFromURL();
  loadItem();
}
function pagnationBtn() {
  // pagnation
  document.querySelectorAll(".pag").forEach(btn =>
    btn.addEventListener("click", function () {
      current_page = btn.innerHTML;
      loadItem();
    })
  );
  if (document.getElementsByClassName("pag-pre").length > 0)
    document.querySelector(".pag-pre").addEventListener("click", function () {
      current_page =
        Number(document.querySelector("span.active").innerHTML) - 1;
      loadItem(number_of_item, current_page);
    });
  if (document.getElementsByClassName("pag-con").length > 0)
    document.querySelector(".pag-con").addEventListener("click", function () {
      current_page =
        Number(document.querySelector("span.active").innerHTML) + 1;

      loadItem();
    });
}
function loadItem() {
  var filter = getFilterFromForm();
  $.ajax({
    url: "../controller/admin/pagnation.controller.php",
    type: "post",
    dataType: "html",
    data: {
      number_of_item: number_of_item,
      current_page: current_page,
      function: "getRecords",
      filter: filter,
    },
  }).done(function (result) {
    if (current_page > parseInt(result)) current_page = parseInt(result);
    if (current_page < 1) current_page = 1;
    $.ajax({
      url: "../controller/admin/pagnation.controller.php",
      type: "post",
      dataType: "html",
      data: {
        number_of_item: number_of_item,
        current_page: current_page,
        function: "render",
        orderby: orderby,
        order_type: order_type,
        filter: filter,
      },
    }).done(function (result) {
      var newurl =
        window.location.protocol +
        "//" +
        window.location.host +
        window.location.pathname +
        "?page=" +
        urlParams["page"] +
        "&item=" +
        number_of_item +
        "&current_page=" +
        current_page;
      newurl += pushFilterToURL();
      window.history.pushState({ path: newurl }, "", newurl);
      $(".result").html(result);
      pagnationBtn();
      filterBtn();
      js();
    });
  });
}
document.addEventListener("DOMContentLoaded", () => {
  loadForFirstTime();
});

function filterBtn() {
  $(".body__filter--action__filter").click(e => {
    current_page = 1;
    e.preventDefault();
    loadItem();
  });
  $(".body__filter--action__reset").click(e => {
    current_page = 1;
    $.ajax({
      url: "../controller/admin/pagnation.controller.php",
      type: "post",
      dataType: "html",
      data: {
        number_of_item: number_of_item,
        current_page: current_page,
        function: "render",
      },
    }).done(function (result) {
      var newurl =
        window.location.protocol +
        "//" +
        window.location.host +
        window.location.pathname +
        "?page=" +
        urlParams["page"] +
        "&item=" +
        number_of_item +
        "&current_page=" +
        current_page;
      window.history.pushState({ path: newurl }, "", newurl);
      $(".result").html(result);
      pagnationBtn();
      js();
    });
  });
}

function hideNotifications() {
  const notifications = document.querySelectorAll(".success, .failed");
  notifications.forEach(notification => {
    setTimeout(() => {
      notification.style.display = "none";
    }, 2000);
  });
}

var js = function () {
  if (orderby != "" && order_type != "")
    document.querySelector("[data-order=" + "'" + orderby + "']").innerHTML +=
      order_type == "ASC"
        ? ' <i class="fas fa-sort-up">'
        : ' <i class="fas fa-sort-down">';
  else
    document.querySelector("[data-order]").innerHTML +=
      order_type == "ASC"
        ? ' <i class="fas fa-sort-up">'
        : ' <i class="fas fa-sort-down">';
  document
    .querySelector(".result")
    .querySelectorAll("th")
    .forEach(th => {
      if (th.hasAttribute("data-order"))
        th.addEventListener("click", () => {
          if (orderby == "")
            orderby = document
              .querySelector("[data-order]")
              .getAttribute("data-order");
          if (orderby == th.getAttribute("data-order") && order_type == "ASC") {
            order_type = "DESC";
          } else {
            order_type = "ASC";
          }
          orderby = th.getAttribute("data-order");
          loadItem();
        });
    });
  const create_html = `<div class="modal-edit-product-container show" id="modal-edit-container">
<div class="modal-edit-product">
    <div class="modal-header">
        <h3>Thêm nhà xuất bản</h3>
        <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
        <form action="">
            <div class="modal-body-2">
                <div class="flex">
                    <label for="name">Tên nhà xuất bản</label>
                    <input id="namePublisher" type="text" add-index="1" placeholder="Tên nhà xuất bản">
                    
                </div>
                <div class="flex">
                <label for="email">Email nhà xuất bản</label>
                    <input id="emailPublisher" type="text" add-index="2" placeholder="Email nhà xuất bản">
                </div>
            </div>
            <div>
            </div>
            <input type="reset" value="Hủy" class="create-cancel">
            <input type="submit" value="Xác nhận" class="create-confirm" add-index="9">
        </form>
    </div>
</div>
</div>`;
  const modal = document.querySelector("#modal");
  document
    .querySelector(".body__filter--action__add")
    .addEventListener("click", e => {
      e.preventDefault();
      modal.innerHTML = create_html;
      const modal_create_container = document.querySelector(
        ".modal-edit-product-container"
      );

      modal
        .querySelector(".create-confirm")
        .addEventListener("click", function (e) {
          e.preventDefault();
          let name = modal.querySelector("#namePublisher");
          const email = modal.querySelector("#emailPublisher");

          // Biểu thức chính quy để kiểm tra email phải có định dạng @gmai.com

          // Kiểm tra dữ liệu đầu vào
          if (name.value.trim() === "") {
            alert("Tên không được để trống hoặc chỉ chứa khoảng trắng!");
            name.focus();
            return;
          }

          if (email.value.trim() === "") {
            alert("Email không được để trống hoặc chỉ chứa khoảng trắng!");
            email.focus();
            return;
          }

          // if(email.value.length )

          if (!emailRegex.test(email.value)) {
            alert("Email phải có định dạng ex: phatlenguyen@mail.com ...!");
            email.focus();
            return;
          }

          name = name.value.toUpperCase();
          console.log(name);
          // Kiểm tra email đã tồn tại trong hệ thống hay chưa
          $.ajax({
            url: "../controller/admin/publisher.controller.php",
            type: "post",
            dataType: "json",
            data: {
              function: "checkEmailAndNameExists", // Gọi đến hàm kiểm tra email tồn tại
              email: email.value.trim(),
              name: name.trim(),
            },
            success: function (response) {
              if (response.emailExists) {
                alert("Email nhà xuất bản đã tồn tại!");
                email.focus();
              } else if (response.nameExists) {
                alert("Tên nhà xuất bản đã tồn tại!");
                name.focus();
              } else {
                // Nếu email chưa tồn tại, thực hiện thêm nhà xuất bản mới
                $.ajax({
                  url: "../controller/admin/publisher.controller.php",
                  type: "post",
                  dataType: "html",
                  data: {
                    function: "create",
                    field: {
                      name: name.trim(),
                      email: email.value.trim(),
                    },
                  },
                }).done(function (result) {
                  loadItem();
                  $("#sqlresult").html(result);
                  hideNotifications();
                });
                modal_create_container.classList.remove("show");
              }
            },
            error: function () {
              alert("Đã có lỗi xảy ra khi kiểm tra email!");
            },
          });
        });

      // Đóng modal khi nhấn nút Close hoặc Cancel
      document.querySelector("#btnClose").addEventListener("click", () => {
        modal_create_container.classList.remove("show");
      });
      document.querySelector(".create-cancel").addEventListener("click", () => {
        modal_create_container.classList.remove("show");
      });
    });

  // edit
  const edit_html = `<div class="modal-edit-product-container show" id="modal-edit-container">
<div class="modal-edit-product">
    <div class="modal-header">
        <h3>Sửa nhà xuất bản</h3>
        <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
        <form action="">
            <div class="modal-body-2">
                <div class="flex">
                    <label for="name">Tên nhà xuất bản </label>
                    <input id="name" type="text" add-index="1" placeholder="Tên nhà xuất bản"  disabled>
                    
                </div>
                <div class="flex">
                <label for="email">Email nhà xuất bản</label>
                    <input id="email" type="text" add-index="2" placeholder="Email nhà xuất bản">
                </div>
            </div>
            <div>
            </div>
            <input type="reset" value="Hủy" class="create-cancel">
            <input type="submit" value="Xác nhận" class="create-confirm" add-index="9">
        </form>
    </div>
</div>
</div>`;

  var edit_btns = document.getElementsByClassName("actions--edit");
  for (var i = 0; i < edit_btns.length; i++) {
    edit_btns[i].addEventListener("click", function (e) {
      modal.innerHTML = edit_html;
      const modal_edit_container = document.querySelector(
        "#modal-edit-container"
      );

      // Đóng modal khi nhấn nút Close hoặc Cancel
      modal.querySelector("#btnClose").addEventListener("click", () => {
        modal_edit_container.classList.remove("show");
      });
      modal.querySelector(".create-cancel").addEventListener("click", () => {
        modal_edit_container.classList.remove("show");
      });

      var id = this.parentNode.parentNode.querySelector(".id").innerHTML;
      var currentEmail =
        this.parentNode.parentNode.querySelector(".email").innerHTML; // Lưu email hiện tại
      console.log(currentEmail);

      modal.querySelector("#name").value =
        this.parentNode.parentNode.querySelector(".name").innerHTML;
      modal.querySelector("#email").value = currentEmail; // Đặt giá trị email hiện tại

      modal
        .querySelector(".create-confirm")
        .addEventListener("click", function (e) {
          e.preventDefault();
          const nameEdit = modal.querySelector("#name");
          const emailEdit = modal.querySelector("#email");

          // Kiểm tra dữ liệu đầu vào
          if (nameEdit.value.trim() === "") {
            alert("Tên không được để trống hoặc chỉ chứa khoảng trắng!");
            nameEdit.focus();
            return;
          }

          if (emailEdit.value.trim() === "") {
            alert("Email không được để trống hoặc chỉ chứa khoảng trắng!");
            emailEdit.focus();
            return;
          }

          if (!emailRegex.test(emailEdit.value)) {
            alert(
              "Email phải có độ dài từ 6 với 50 ký tự và có định dạng ex: phatlenguyen@mail.com ...!"
            );
            emailEdit.focus();
            return;
          }

          // Kiểm tra xem email mới có trùng với các nhà xuất bản khác không
          $.ajax({
            url: "../controller/admin/publisher.controller.php",
            type: "post",
            dataType: "json", // Đặt định dạng trả về là JSON
            data: {
              function: "checkEmailExists", // Gọi đến hàm kiểm tra email tồn tại
              email: emailEdit.value.trim(),
            },
            success: function (response) {
              // Nếu email mới đã tồn tại và khác với email hiện tại
              if (response.exists && emailEdit.value.trim() !== currentEmail) {
                alert("Email này đã tồn tại trong hệ thống!");
                emailEdit.focus();
                return;
              } else {
                // Nếu email không tồn tại hoặc trùng với email hiện tại, thực hiện chỉnh sửa
                $.ajax({
                  url: "../controller/admin/publisher.controller.php",
                  type: "post",
                  dataType: "html",
                  data: {
                    function: "edit",
                    field: {
                      id: id,
                      name: nameEdit.value.trim(),
                      email: emailEdit.value.trim(),
                    },
                  },
                }).done(function (result) {
                  loadItem();
                  $("#sqlresult").html(result);
                  hideNotifications();
                });
                modal_edit_container.classList.remove("show");
              }
            },
            error: function () {
              alert("Đã có lỗi xảy ra khi kiểm tra email!");
            },
          });
        });
      hideNotifications();
    });
  }

  // delete

  const del_btns = document.getElementsByClassName("actions--delete");

  for (var i = 0; i < del_btns.length; i++) {
    del_btns[i].addEventListener("click", function () {
      let selected_content = this.parentNode.parentNode;
      let publisher_id = selected_content.querySelector(".id").innerHTML;
      let publisher_name = selected_content.querySelector(".name").innerHTML;
      let publisher_email = selected_content.querySelector(".email").innerHTML;

      var del_html = `
        <div class="modal-edit-product-container show" id="modal-edit-container">
        <div class="modal-edit-product">
            <div class="modal-header">
                <h3>Xác nhận xóa sản phẩm</h3>
                <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="del-body">
                    
                    <div class="thongtin">
                        <div><span style="font-weight: bold;">Mã NXB :</span> <span id="category-delete-id">${publisher_id}</span> </div>
                        <div><span style="font-weight: bold;">Tên NXB :</span> <span>${publisher_name}</span> </div>
                        <div><span style="font-weight: bold;">Email NXB :</span> <span>${publisher_email}</span> </div>
                    </div>
                </div>
                <div class="del-btn-container">
                    <input type="button" value="Hủy" class="del-cancel">
                    <input type="button" value="Xác nhận" class="del-confirm">
                </div>
            </div>
        </div>
    </div>
        `;

      modal.innerHTML = del_html;
      $(".del-confirm").click(function (e) {
        e.preventDefault();
        var $id = $("#category-delete-id").html();
        $.ajax({
          url: "../controller/admin/publisher.controller.php",
          type: "post",
          dataType: "html",
          data: {
            function: "delete",
            id: $id,
          },
        }).done(function (result) {
          loadItem();
          $("#sqlresult").html(result);
          hideNotifications();
          modal_edit_container.classList.remove("show");
        });
      });

      // Button close
      const modal_edit_container = document.querySelector(
        "#modal-edit-container"
      );
      const btnClose = document.querySelector("#btnClose");
      btnClose.addEventListener("click", () => {
        modal_edit_container.classList.remove("show");
      });
      // Button cancel
      const btnCancel = document.querySelector(".del-cancel");
      btnCancel.addEventListener("click", () => {
        modal_edit_container.classList.remove("show");
      });
    });
  }
};
