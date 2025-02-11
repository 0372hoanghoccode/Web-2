var filter_form = document.querySelector(".admin__content--body__filter");
function getFilterFromURL() {
  filter_form.querySelector("#authorName").value =
    urlParams["name"] != null ? urlParams["name"] : "";
  filter_form.querySelector("#authorId").value =
    urlParams["id"] != null ? urlParams["id"] : "";
  filter_form.querySelector("#authorEmail").value =
    urlParams["email"] != null ? urlParams["email"] : "";
  filter_form.querySelector("#statusSelect").value =
    urlParams["status"] != null ? urlParams["status"] : "active";
}
function pushFilterToURL() {
  var filter = getAUFilterFromForm();
  var url_key = {
    author_name: "name",
    author_id: "id",
    author_email: "email",
    author_status: "status",
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
function getAUFilterFromForm() {
  return {
    author_name: filter_form.querySelector("#authorName").value,
    author_id: filter_form.querySelector("#authorId").value,
    author_email: filter_form.querySelector("#authorEmail").value,
    author_status: filter_form.querySelector("#statusSelect").value,
  };
}

function hideNotifications() {
  const notifications = document.querySelectorAll(".success, .failed");
  notifications.forEach(notification => {
    setTimeout(() => {
      notification.style.display = "none";
    }, 3000);
  });
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
  var filter = getAUFilterFromForm();
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
    e.preventDefault();
    const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const name_regex = /^[A-Za-zÀ-ÿ\s]+$/;
    const name = filter_form.querySelector("#authorName").value.trim();
    const id = filter_form.querySelector("#authorId").value.trim();
    const email = filter_form.querySelector("#authorEmail").value.trim();
    var message = filter_form.querySelector("#message");
    var check = true;

    if ((isNaN(id) || id <= 0) && id !== "") {
      message.innerHTML = "*Mã tác giả không hợp lệ";
      filter_form.querySelector("#authorId").focus();
      check = false;
    } else if (!email.match(regexEmail) && email !== "") {
      message.innerHTML = "*Email không hợp lệ";
      filter_form.querySelector("#authorEmail").focus();
      check = false;
    }

    if (check == true) {
      message.innerHTML = "";
      current_page = 1;
      loadItem();
    }
  });
  $(".body__filter--action__reset").click(e => {
    current_page = 1;
    status_value = "active";
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
        filter: {
          author_status: status_value,
        },
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

const js = function () {
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
  const addHtml = `
  <div class="form">
    <h2>Thêm Tác Giả</h2>
    <div class="input-field">
      <label for="addAuthorName">Tên tác giả</label>
      <input type="text" id="addAuthorName">
    </div>
    <div class="input-field">
      <label for="addAuthorEmail">Email</label>
      <input type="email" id="addAuthorEmail">
    </div>
  </div>
`;

  // Lấy các phần tử cần thiết từ DOM
  const addAuthorModal = document.getElementById("addAuthorModal");
  const addModalContent = document.querySelector(".addModal-content .form");
  const openModalBtn = document.querySelector(".body__filter--action__add");
  const addButton = document.getElementById("addButton");
  const closeAddIcon = document.querySelector(".addModal-content .close i");

  openModalBtn.addEventListener("click", function () {
    addModalContent.innerHTML = addHtml;
    addAuthorModal.style.display = "block";

    // Đảm bảo xóa sự kiện click cũ trước khi gắn sự kiện mới
    $("#addButton")
      .off("click")
      .on("click", function (e) {
        e.preventDefault();

        const name = document.getElementById("addAuthorName").value.trim();
        const email = document.getElementById("addAuthorEmail").value.trim();
        const regexName = /^[A-Za-zÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚ\s]+$/;
        const regexEmail =
          /^(([A-Za-z0-9]+((\.|\-|\_|\+)?[A-Za-z0-9]?)*[A-Za-z0-9]+)|[A-Za-z0-9]+)@(([A-Za-z0-9]+)+((\.|\-|\_)?([A-Za-z0-9]+)+)*)+\.([A-Za-z]{2,})+$/;

        // Kiểm tra tên và email không rỗng và tên không chứa ký tự đặc biệt
        if (name === "") {
          alert("Vui lòng điền tên!");
          document.getElementById("addAuthorName").focus();
          return;
        }
        if (!regexName.test(name)) {
          alert("Tên không được chứa ký tự đặc biệt.");
          document.getElementById("addAuthorName").focus();
          return;
        }

        if (email === "") {
          alert("Vui lòng điền email!");
          document.getElementById("addAuthorEmail").focus();
          return;
        }

        if (!regexEmail.test(email)) {
          alert("Email không đúng định dạng.");
          document.getElementById("addAuthorEmail").focus();
          return;
        }

        // Khóa nút sau khi gửi yêu cầu
        addButton.disabled = true;

        // AJAX kiểm tra email đã tồn tại
        $.ajax({
          url: "../controller/admin/author.controller.php",
          type: "post",
          dataType: "json",
          data: {
            function: "checkEmailExists", // Gọi đến hàm kiểm tra email tồn tại
            email: email,
          },
          success: function (response) {
            if (response.exists) {
              alert("Email tác giả đã tồn tại!");
              document.getElementById("addAuthorEmail").focus();
              addButton.disabled = false; // Mở khóa nút nếu email tồn tại
            } else {
              // Nếu email chưa tồn tại, thực hiện thêm nhà xuất bản mới
              $.ajax({
                url: "../controller/admin/author.controller.php",
                type: "post",
                dataType: "html",
                data: {
                  function: "create",
                  field: {
                    name: name,
                    email: email,
                  },
                },
              })
                .done(function (result) {
                  if (result.includes("success")) {
                    loadItem(); // Chỉ load lại danh sách khi thành công
                    $("#sqlresult").html(result); // Hiển thị thông báo thành công
                    hideNotifications();
                    addAuthorModal.style.display = "none"; // Ẩn modal sau khi thành công
                  } else {
                    $("#sqlresult").html(result);
                    setTimeout(() => {
                      $("#sqlresult").html(""); // Xóa nội dung sau 3 giây
                    }, 3000); // Hiển thị thông báo thất bại
                  }
                })
                .fail(function () {
                  alert("Có lỗi xảy ra trong quá trình thêm tác giả.");
                })
                .always(function () {
                  // Mở khóa nút sau khi xử lý xong
                  addButton.disabled = false;
                });
            }
          },
          error: function () {
            alert("Đã có lỗi xảy ra khi kiểm tra email!");
            addButton.disabled = false;
          },
        });
      });
  });

  closeAddIcon.addEventListener("click", function () {
    addAuthorModal.style.display = "none";
  });

  const editModal = document.getElementById("editModal");
  const editModalContent = document.querySelector(".editModal-content .form");
  const editFunctionButton = document.querySelector(".editFunctionButton");
  const editAuthorButton = document.querySelector(".editAuthorButton");
  const closeEditIcon = document.querySelector(".editModal-content .close i");
  const saveButton = document.getElementById("saveButton");

  const deleteModal = document.getElementById("deleteModal");
  const deleteModalContent = document.querySelector(
    ".deleteModal-content .form"
  );
  const closeDeleteIcon = document.querySelector(
    ".deleteModal-content .close i"
  );

  //  const editHtml = `
  //     <div class="form">
  //       <h2>Chỉnh sửa thông tin tác giả</h2>
  //       <form id="form">
  //         <div class="input-field">
  //           <label for="editAuthorId">Mã tác giả</label>
  //           <input type="text" id="editAuthorId" readonly>
  //         </div>
  //         <div class="input-field">
  //           <label for="editAuthorName">Tên tác giả</label>
  //           <input type="text" id="editAuthorName" readonly>
  //         </div>
  //         <div class="input-field">
  //           <label for="editAuthorEmail">Email</label>
  //           <input type="email" id="editAuthorEmail">
  //         </div>
  //       </form>
  //     </div>`;

  //  const deleteHtml = `
  //     <h2>Xác nhận xóa thông tin tác giả</h2>
  //     <form id="form">
  //       <label for="editAuthorId">Mã tác giả</label>
  //       <div id="author-delete-id"></div>
  //       <label for="editAuthorName">Tên tác giả</label>
  //       <div id="author-delete-name"></div>
  //       <label for="editAuthorEmail">Email</label>
  //       <div id="author-delete-email"></div>

  //     </form>`;

  //  editAuthorButton.addEventListener("click", () => {
  //   editModalContent.innerHTML = editHtml;
  //   editModal.style.display = "block";
  //   editFunctionButton.classList.remove("d-none");
  //   editAuthorButton.classList.add("d-none");
  //  });

  //  closeEditIcon.addEventListener("click", () => {
  //   editModal.style.display = "none";
  //  });

  //  window.addEventListener("click", (event) => {
  //   if (event.target === editModal) {
  //    editModal.style.display = "none";
  //   }
  //  });

  //  var edit_btns = document.getElementsByClassName("actions--edit");
  //  for (var i = 0; i < edit_btns.length; i++) {
  //   edit_btns[i].addEventListener("click", function () {
  //    editModalContent.innerHTML = editHtml;
  //    editModal.style.display = "block";
  //    var authorId = this.parentNode.parentNode.querySelector(".id").innerHTML;
  //    var authorName = this.parentNode.parentNode.querySelector(".name").innerHTML;
  //    var authorEmail =
  //     this.parentNode.parentNode.querySelector(".email").innerHTML;
  //    document.getElementById("editAuthorId").value = authorId;
  //    document.getElementById("editAuthorName").value = authorName;
  //    document.getElementById("editAuthorEmail").value = authorEmail;

  //    // Hàm kiểm tra email hợp lệ
  //    function isValidEmail(email) {
  //     var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  //     return emailPattern.test(email);
  //    }

  //    saveButton.addEventListener("click", function (e) {
  //     e.preventDefault();

  //     // Lấy giá trị email sau khi chỉnh sửa
  //     var editedEmail = editModal.querySelector("#editAuthorEmail");
  //     // Kiểm tra xem email có hợp lệ không
  //     if (editedEmail.value === "") {
  //      alert("Hãy nhập email.");
  //      editedEmail.focus();
  //      return;
  //     }

  //     if (!isValidEmail(editedEmail.value)) {
  //      alert("Email không hợp lệ! Vui lòng nhập lại.");
  //      editedEmail.focus();
  //      return;
  //     }

  //     // Nếu email hợp lệ, tiếp tục gửi AJAX
  //     $.ajax({
  //      url: "../controller/admin/author.controller.php",
  //      type: "post",
  //      dataType: "html",
  //      data: {
  //       function: "edit",
  //       field: {
  //        id: authorId,
  //        name: editModal.querySelector("#editAuthorName").value,
  //        email: editedEmail,
  //       },
  //      },
  //     }).done(function (result) {
  //      loadItem();
  //      $("#sqlresult").html(result);
  //      hideNotifications()
  //     });
  //     editModal.style.display = "none";
  //    });
  //   });
  //  }

  const editHtml = `
    <div class="form">
      <h2>Chỉnh sửa thông tin tác giả</h2>
      <form id="form">
        <div class="input-field">
          <label for="editAuthorId">Mã tác giả</label>
          <input type="text" id="editAuthorId" readonly>
        </div>
        <div class="input-field">
          <label for="editAuthorName">Tên tác giả</label>
          <input type="text" id="editAuthorName" readonly>
        </div>
        <div class="input-field">
          <label for="editAuthorEmail">Email</label>
          <input type="email" id="editAuthorEmail">
        </div>
      </form>
    </div>`;

  const deleteHtml = `
    <h2>Xác nhận xóa thông tin tác giả</h2>
    <form id="form">
      <label for="editAuthorId">Mã tác giả</label>
      <div id="author-delete-id"></div>
      <label for="editAuthorName">Tên tác giả</label>
      <div id="author-delete-name"></div>
      <label for="editAuthorEmail">Email</label>
      <div id="author-delete-email"></div>
    </form>`;

  editAuthorButton.addEventListener("click", () => {
    editModalContent.innerHTML = editHtml;
    editModal.style.display = "block";
    editFunctionButton.classList.remove("d-none");
    editAuthorButton.classList.add("d-none");
  });

  closeEditIcon.addEventListener("click", () => {
    editModal.style.display = "none";
  });

  window.addEventListener("click", event => {
    if (event.target === editModal) {
      editModal.style.display = "none";
    }
  });

  var edit_btns = document.getElementsByClassName("actions--edit");
  for (var i = 0; i < edit_btns.length; i++) {
    edit_btns[i].addEventListener("click", function () {
      editModalContent.innerHTML = editHtml;
      editModal.style.display = "block";
      var authorId = this.parentNode.parentNode.querySelector(".id").innerHTML;
      var authorName =
        this.parentNode.parentNode.querySelector(".name").innerHTML;
      var authorEmail =
        this.parentNode.parentNode.querySelector(".email").innerHTML;
      document.getElementById("editAuthorId").value = authorId;
      document.getElementById("editAuthorName").value = authorName;
      document.getElementById("editAuthorEmail").value = authorEmail;

      // Hàm kiểm tra email hợp lệ
      function isValidEmail(email) {
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
      }

      // Xóa các sự kiện cũ (nếu có)
      // saveButton.onclick = null;

      $("#saveButton")
        .off("click")
        .on("click", e => {
          e.preventDefault();

          // Lấy giá trị email sau khi chỉnh sửa
          var editedEmail = editModal.querySelector("#editAuthorEmail");
          // Kiểm tra xem email có hợp lệ không
          if (editedEmail.value === "") {
            alert("Hãy nhập email.");
            editedEmail.focus();
            return;
          }

          if (!isValidEmail(editedEmail.value)) {
            alert("Email không hợp lệ! Vui lòng nhập lại.");
            editedEmail.focus();
            return;
          }

          // Nếu email hợp lệ, tiếp tục gửi AJAX
          $.ajax({
            url: "../controller/admin/author.controller.php",
            type: "post",
            dataType: "html",
            data: {
              function: "edit",
              field: {
                id: authorId,
                name: editModal.querySelector("#editAuthorName").value,
                email: editedEmail.value, // Đảm bảo lấy giá trị
              },
            },
          }).done(function (result) {
            loadItem();
            $("#sqlresult").html(result);
            hideNotifications();
            editModal.style.display = "none"; // Di chuyển vào đây
          });
        });
      // Thêm sự kiện click mới cho saveButton
      // saveButton.addEventListener("click", function (e) {
      //   e.preventDefault();

      //   // Lấy giá trị email sau khi chỉnh sửa
      //   var editedEmail = editModal.querySelector("#editAuthorEmail");
      //   // Kiểm tra xem email có hợp lệ không
      //   if (editedEmail.value === "") {
      //     alert("Hãy nhập email.");
      //     editedEmail.focus();
      //     return;
      //   }

      //   if (!isValidEmail(editedEmail.value)) {
      //     alert("Email không hợp lệ! Vui lòng nhập lại.");
      //     editedEmail.focus();
      //     return;
      //   }

      //   // Nếu email hợp lệ, tiếp tục gửi AJAX
      //   $.ajax({
      //     url: "../controller/admin/author.controller.php",
      //     type: "post",
      //     dataType: "html",
      //     data: {
      //       function: "edit",
      //       field: {
      //         id: authorId,
      //         name: editModal.querySelector("#editAuthorName").value,
      //         email: editedEmail.value, // Đảm bảo lấy giá trị
      //       },
      //     },
      //   }).done(function (result) {
      //     loadItem();
      //     $("#sqlresult").html(result);
      //     hideNotifications();
      //     editModal.style.display = "none"; // Di chuyển vào đây
      //   });
      // });
    });
  }

  const del_btns = document.getElementsByClassName("actions--delete");
  for (let i = 0; i < del_btns.length; i++) {
    del_btns[i].addEventListener("click", () => {
      deleteModalContent.innerHTML = deleteHtml;
      deleteModal.style.display = "block";
      let selected_content = del_btns[i].parentNode.parentNode;
      document.querySelector("#author-delete-id").textContent =
        selected_content.querySelector(".id").innerHTML;
      document.querySelector("#author-delete-name").textContent =
        selected_content.querySelector(".name").innerHTML;
      document.querySelector("#author-delete-email").textContent =
        selected_content.querySelector(".email").innerHTML;

      const btnConfirmDelete = document.querySelector("#del-confirm");
      btnConfirmDelete.addEventListener("click", e => {
        e.preventDefault();
        var $id = $("#author-delete-id").html();
        $.ajax({
          url: "../controller/admin/author.controller.php",
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
          deleteModal.style.display = "none";
          deleteModal.classList.remove("show");
        });
      });
    });

    closeEditIcon.addEventListener("click", () => {
      editModal.style.display = "none";
    });
    closeDeleteIcon.addEventListener("click", () => {
      deleteModal.style.display = "none";
    });
    window.addEventListener("click", event => {
      if (event.target === editModal) {
        editModal.style.display = "none";
      }
    });
  }

  closeDeleteIcon.addEventListener("click", () => {
    deleteModal.style.display = "none";
  });
  const btnCancelDelete = document.querySelector("#deleteModal .del-cancel");
  btnCancelDelete.addEventListener("click", () => {
    deleteModal.style.display = "none";
  });
};
