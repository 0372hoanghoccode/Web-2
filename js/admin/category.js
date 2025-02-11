var filter_form = document.querySelector(".admin__content--body__filter");
function getFilterFromURL() {
  filter_form.querySelector("#categoryName").value =
    urlParams["name"] != null ? urlParams["name"] : "";
  filter_form.querySelector("#categoryId").value =
    urlParams["id"] != null ? urlParams["id"] : "";
  filter_form.querySelector("#statusSelect").value =
    urlParams["status"] != null ? urlParams["status"] : "active";
}
function pushFilterToURL() {
  var filter = getFilterFromForm();
  var url_key = {
    category_name: "name",
    category_id: "id",
    category_status: "status",
  };
  var url = "";
  Object.keys(filter).forEach((key) => {
    url +=
      filter[key] != null && filter[key] != ""
        ? `&${url_key[key]}=${filter[key]}`
        : "";
  });
  return url;
}
function getFilterFromForm() {
  return {
    category_name: filter_form.querySelector("#categoryName").value,
    category_id: filter_form.querySelector("#categoryId").value,
    category_status: filter_form.querySelector("#statusSelect").value,
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
var orderby = urlParams['orderby'];
var order_type = urlParams['order_type'];
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
      await new Promise((resolve) => setTimeout(resolve, 20));
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
  document.querySelectorAll(".pag").forEach((btn) =>
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
  $(".body__filter--action__filter").click((e) => {
    e.preventDefault();
    var categoryId = filter_form.querySelector("#categoryId").value.trim();
    var message = filter_form.querySelector("#message");
    var check = true;
    var regex = /^\d+$/;
    if (!categoryId.match(regex) && categoryId !== "") {
      message.innerHTML = "*Mã thể loại phải là kí tự số";
      filter_form.querySelector("#categoryId").focus();
      check = false;
    }
    if (check == true) {
      message.innerHTML = "";
      current_page = 1;
      loadItem();
    }
  });
  $(".body__filter--action__reset").click((e) => {
    message.innerHTML = "";
    check = true;
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
          category_status: status_value,
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
      loadItem();
      pagnationBtn();
      js();
    });
  });
}

var js = function () {
  if (orderby != "" && order_type != "") document.querySelector("[data-order=" + "'" + orderby + "']").innerHTML+=(order_type=="ASC")?' <i class="fas fa-sort-up">':' <i class="fas fa-sort-down">';
  else document.querySelector("[data-order]").innerHTML+=(order_type=="ASC")?' <i class="fas fa-sort-up">':' <i class="fas fa-sort-down">';
  document.querySelector(".result").querySelectorAll("th").forEach((th) => {
      if (th.hasAttribute("data-order")) th.addEventListener("click", () => {
          if (orderby == "") orderby = document.querySelector("[data-order]").getAttribute("data-order");
          if (orderby == th.getAttribute("data-order") && order_type == "ASC") {
              order_type = "DESC";
          }
          else {
              order_type = "ASC"
          }
          orderby = th.getAttribute("data-order");
          loadItem();
      })
  });
//   const create_html = `<div class="modal-edit-product-container show" id="modal-edit-container">
// <div class="modal-edit-product">
//     <div class="modal-header">
//         <h3>Thêm thể loại</h3>
//         <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
//     </div>
//     <div class="modal-body">
//         <form action="">

//             <div class="modal-body-2">
//                 <div class="flex">
//                     <label for="nameCategory">Tên thể loại</label>
//                     <input id="nameCategory" type="text" add-index="2" placeholder="Tên thể loại">                   
//                 </div>
//                 <p id ="message"></p>
                
//             </div>
//             <div>
//             </div>
//             <input type="reset" value="Hủy" class="button-cancel">
//             <input type="submit" value="Xác nhận" class="button-confirm" add-index="9">
//         </form>
//     </div>
// </div>
// </div>`;

//   document
//     .querySelector(".body__filter--action__add")
//     .addEventListener("click", (e) => {
//       e.preventDefault();
//       modal.innerHTML = create_html;
//       const modal_create_container = document.querySelector(
//         "#modal-edit-container"
//       );
//       modal
//         .querySelector(".button-confirm")
//         .addEventListener("click", function (e) {
//           e.preventDefault();
//           const message = modal_create_container.querySelector("#message");
//           const name =
//             modal_create_container.querySelector("#nameCategory").value;
//           var check = true;
//           if (name == "") {
//             message.innerHTML = "*Vui lòng điền tên thể loại";
//             modal_create_container.querySelector("#nameCategory").focus();
//             check = false;
//           }
//           if (check == true) {
//             message.innerHTML = "";
//             $.ajax({
//               url: "../controller/admin/category.controller.php",
//               type: "post",
//               dataType: "html",
//               data: {
//                 function: "create",
//                 field: {
//                   name: modal.querySelector("#nameCategory").value,
//                 },
//               },
//             }).done(function (result) {
//               loadItem();
//               $("#sqlresult").html(result);
//             });
//             modal_create_container.classList.add("hidden");
//           }
//         });

//       document.querySelector("#btnClose").addEventListener("click", () => {
//         modal_create_container.classList.add("hidden");
//       });
//       document.querySelector(".button-cancel").addEventListener("click", () => {
//         modal_create_container.classList.add("hidden");
//       });
//     });


const create_html = `
<div class="modal-edit-product-container show" id="modal-edit-container">
  <div class="modal-edit-product">
    <div class="modal-header">
      <h3>Thêm thể loại</h3>
      <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <form action="">
        <div class="modal-body-2">
          <div class="flex">
            <label for="nameCategory">Tên thể loại</label>
            <input id="nameCategory" type="text" add-index="2" placeholder="Tên thể loại">
          </div>
          <p id="message"></p>
        </div>
        <input type="reset" value="Hủy" class="button-cancel">
        <input type="submit" value="Xác nhận" class="button-confirm" add-index="9">
      </form>
    </div>
  </div>
</div>`;

document.querySelector(".body__filter--action__add").addEventListener("click", (e) => {
  e.preventDefault();
  modal.innerHTML = create_html;
  const modal_create_container = document.querySelector("#modal-edit-container");

  modal_create_container.querySelector(".button-confirm").addEventListener("click", function (e) {
    e.preventDefault();
    const message = modal_create_container.querySelector("#message");
    let name = modal_create_container.querySelector("#nameCategory").value;
    let check = true;

    if (name === "") {
      message.innerHTML = "*Vui lòng điền tên thể loại";
      modal_create_container.querySelector("#nameCategory").focus();
      check = false;
    }

    if (check) {
      message.innerHTML = "";
      name = name.toUpperCase();
    }
      // Kiểm tra xem tên thể loại đã tồn tại hay chưa
      $.ajax({
        url: '../controller/admin/category.controller.php',
        type: 'post',
        dataType: 'json',
        data: {
          function: 'checkNameExists', // Gọi đến hàm kiểm tra thể loại tồn tại
          name: name,
        },
        
      }).done(function (result) {
        console.log("Checking name:", name);

        if (result.exists) {
          message.innerHTML = "*Tên thể loại đã tồn tại!";
          modal_create_container.querySelector("#nameCategory").focus();
        } else {
          // Nếu thể loại chưa tồn tại, thực hiện thêm thể loại mới
          $.ajax({
            url: '../controller/admin/category.controller.php',
            type: 'post',
            dataType: 'html',
            data: {
              function: 'create',
              field: {
                name: name,
              },
            },
          }).done(function (result) {
            loadItem(); // Tải lại danh sách sau khi thêm thành công
            $("#sqlresult").html(result);
              setTimeout(() => {
                $("#sqlresult").html(""); // Xóa nội dung sau 3 giây
              }, 3000); // Hiển thị thông báo thành công
          });
          modal_create_container.classList.add("hidden");
        }
      }).fail(function () {
        message.innerHTML = "Đã xảy ra lỗi khi kiểm tra tên thể loại!";
      });
    
  });

  document.querySelector("#btnClose").addEventListener("click", () => {
    modal_create_container.classList.add("hidden");
  });

  document.querySelector(".button-cancel").addEventListener("click", () => {
    modal_create_container.classList.add("hidden");
  });
});


      


  const edit_html = `<div class="modal-edit-product-container show" id="modal-edit-container">
<div class="modal-edit-product">
    <div class="modal-header">
        <h3>Sửa thể loại</h3>
        <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
        <form action="">

            <div class="modal-body-2">
                <div class="flex">
                    <label for="name">Tên thể loại</label>
                    <input id="name" type="text" add-index="2" placeholder="Tên thể loại">                   
                </div>
                <p id ="message"></p>
                
            </div>
            <div>
            </div>
            <input type="reset" value="Hủy" class="button-cancel">
            <input type="submit" value="Xác nhận" class="button-confirm" add-index="9">
        </form>
    </div>
</div>
</div>`;

  // var edit_btns = document.getElementsByClassName("actions--edit");
  // for (var i = 0; i < edit_btns.length; i++) {
  //   edit_btns[i].addEventListener("click", function (e) {
  //     modal.innerHTML = edit_html;
  //     const modal_edit_container = document.querySelector(
  //       "#modal-edit-container"
  //     );
  //     modal.querySelector("#btnClose").addEventListener("click", () => {
  //       modal_edit_container.classList.remove("show");
  //     });
  //     modal.querySelector(".button-cancel").addEventListener("click", () => {
  //       modal_edit_container.classList.remove("show");
  //     });
  //     var id = this.parentNode.parentNode.querySelector(".id").innerHTML;
  //     modal.querySelector("#name").value =
  //       this.parentNode.parentNode.querySelector(".name").innerHTML;
  //     // modal.querySelector('#status').value = this.parentNode.parentNode.querySelector(".status").innerHTML;
  //     modal
  //       .querySelector(".button-confirm")
  //       .addEventListener("click", function (e) {
  //         e.preventDefault();
  //         const message = modal_edit_container.querySelector("#message");
  //         const name = modal_edit_container.querySelector("#name").value;
  //         var check = true;

  //         if (name == "") {
  //           message.innerHTML = "*Vui lòng điền tên thể loại";
  //           modal_edit_container.querySelector("#name").focus();
  //           check = false;
  //         } else if(name.length < 3) {
  //           message.innerHTML = "*Tên thể loại quá ngắn";
  //           modal_edit_container.querySelector("#name").focus();
  //           check = false;
  //         }
  //         if (check == true) {
  //           message.innerHTML = "";
  //           $.ajax({
  //             url: "../controller/admin/category.controller.php",
  //             type: "post",
  //             dataType: "html",
  //             data: {
  //               function: "edit",
  //               field: {
  //                 id: id,
  //                 name: modal.querySelector("#name").value,
  //               },
  //             },
  //           }).done(function (result) {
  //             loadItem();
  //             $("#sqlresult").html(result);
  //           });
  //           modal_edit_container.classList.remove("show");
  //         }
  //       });
  //   });
  // }

  var edit_btns = document.getElementsByClassName("actions--edit");
for (var i = 0; i < edit_btns.length; i++) {
    edit_btns[i].addEventListener("click", function (e) {
        modal.innerHTML = edit_html;
        const modal_edit_container = document.querySelector("#modal-edit-container");
        modal.querySelector("#btnClose").addEventListener("click", () => {
            modal_edit_container.classList.remove("show");
        });
        modal.querySelector(".button-cancel").addEventListener("click", () => {
            modal_edit_container.classList.remove("show");
        });
        var id = this.parentNode.parentNode.querySelector(".id").innerHTML;
        modal.querySelector("#name").value = this.parentNode.parentNode.querySelector(".name").innerHTML;

        modal.querySelector(".button-confirm").addEventListener("click", function (e) {
            e.preventDefault();
            const message = modal_edit_container.querySelector("#message");
            let name = modal_edit_container.querySelector("#name").value;
            var check = true;

            // Kiểm tra tên thể loại
            if (name === "") {
                message.innerHTML = "*Vui lòng điền tên thể loại";
                modal_edit_container.querySelector("#name").focus();
                check = false;
            } else if (name.length < 3) {
                message.innerHTML = "*Tên thể loại quá ngắn";
                modal_edit_container.querySelector("#name").focus();
                check = false;
            }

            // Nếu các kiểm tra trước đã qua, thực hiện kiểm tra tên
            if (check) {
              name = name.toUpperCase();
                $.ajax({
                    url: "../controller/admin/category.controller.php",
                    type: "post",
                    dataType: "json",
                    data: {
                        function: "checkCategoryExists",
                        name: name,
                        id: id // Gửi id để kiểm tra nếu tên đã tồn tại
                    },
                }).done(function (result) {
                    if (result.exists) {
                        message.innerHTML = "*Tên thể loại đã tồn tại trong hệ thống";
                        modal_edit_container.querySelector("#name").focus();
                    } else {
                        // Nếu tên không tồn tại, thực hiện yêu cầu sửa
                        message.innerHTML = "";
                        $.ajax({
                            url: "../controller/admin/category.controller.php",
                            type: "post",
                            dataType: "html",
                            data: {
                                function: "edit",
                                field: {
                                    id: id,
                                    name: name,
                                },
                            },
                        }).done(function (result) {
                            loadItem();
                            $("#sqlresult").html(result);
                              setTimeout(() => {
                              $("#sqlresult").html(""); // Xóa nội dung sau 3 giây
                            }, 3000);
                        });
                        modal_edit_container.classList.remove("show");
                    }
                });
            }
        });
    });
}

  // delete

  const del_btns = document.getElementsByClassName("actions--delete");

  for (var i = 0; i < del_btns.length; i++) {
    del_btns[i].addEventListener("click", function () {
      let selected_content = this.parentNode.parentNode;
      let category_id = selected_content.querySelector(".id").innerHTML;
      let category_name = selected_content.querySelector(".name").innerHTML;

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
                        <div><span style="font-weight: bold;">Mã thể loại :</span> <span id="category-delete-id">${category_id}</span> </div>
                        <div><span style="font-weight: bold;">Tên thể loại :</span> <span>${category_name}</span> </div>
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
          url: "../controller/admin/category.controller.php",
          type: "post",
          dataType: "html",
          data: {
            function: "delete",
            id: $id,
          },
        }).done(function (result) {
          loadItem();
          $("#sqlresult").html(result);
              setTimeout(() => {
                $("#sqlresult").html(""); // Xóa nội dung sau 3 giây
              }, 3000);
          modal_edit_container.classList.remove("show");
        });
      });

      // Button close
      const modal_edit_container = document.querySelector(
        "#modal-edit-container"
      );

      const btnClose = document.querySelector("#btnClose");
      // console.log(btnClose)
      btnClose.addEventListener("click", () => {
        // console.log(modal_edit_container)
        modal_edit_container.classList.remove("show");
      });
      // Button cancel
      const btnCancel = document.querySelector(".del-cancel");
      // console.log(btnClose)
      btnCancel.addEventListener("click", () => {
        // console.log(modal_edit_container)
        modal_edit_container.classList.remove("show");
      });
    });
  }
};
