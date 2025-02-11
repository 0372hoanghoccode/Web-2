var filter_form = document.querySelector(".admin__content--body__filter");
function getFilterFromURL() {
  filter_form.querySelector("#discountName").value =
    urlParams["name"] != null ? urlParams["name"] : "";
  filter_form.querySelector("#statusSelect").value =
    urlParams["status"] != null ? urlParams["status"] : "active";
}
function pushFilterToURL() {
  var filter = getFilterFromForm();
  var url_key = {
    discount_name: "name",
    discount_status: "status",
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
    discount_name: filter_form.querySelector("#discountName").value,
    discount_status: filter_form.querySelector("#statusSelect").value,
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
  $(".body__filter--action__filter")
    .off("click")
    .on("click", e => {
      current_page = 1;
      e.preventDefault();
      loadItem();
    });
  $(".body__filter--action__reset")
    .off("click")
    .on("click", e => {
      current_page = 1;
      status_value = "active";
      $.ajax({
        url: "../controller/admin/pagnation.controller.php",
        type: "post",
        dataType: "html",
        data: {
          number_of_item: number_of_item,
          current_page: current_page,
          function: "getTotalRecords",
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
        <h3>Thêm mã giảm giá</h3>
        <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
        <form action="">

            <div class="modal-body-2">
                <div class="flex">
                    <label for="nameDiscount">Tên mã giảm giá</label>
                    <input id="nameDiscount" type="text" add-index="2" placeholder="Tên mã giảm giá">   
                    <p id="message_name" class = "message"></p>      
                </div>
                <div class="flex">
                    <label for="type_discount">Loại mã giảm giá giá</label>
                    <select  id="type_discount">
                    <option value="">Chọn loại mã giảm giá</option>
                    <option value="AR" >Giảm theo giá tiền </option>
                    <option value="PR">Giảm theo phần trăm</option>                 
                </select>
                <p id="message_type" class = "message"></p> 
                </div>
                <div class="flex">
                    <label for="value_discount">Giá trị mã giảm giá</label>
                    <input id="value_discount" type="text" add-index="2" placeholder="Giá trị mã giảm giá"> 
                    <p id="message_value" class = "message"></p>        
                </div>
                <div class="flex">
                    <label for="start_date">Ngày bắt đầu</label>
                    <input id="start_date" type="date" add-index="2" p>  
                    <p id="message_start" class = "message"></p>       
                </div>
                <div class="flex">
                    <label for="end_date">Ngày kết thúc</label>
                    <input id="end_date"" type="date" add-index="2" p> 
                    <p id="message_end" class = "message"></p>       
                </div>
                
            </div>
            <div>
            </div>
            <input type="button" value="Hủy" class="button-cancel">
            <input type="submit" value="Xác nhận" class="button-confirm" add-index="9">
        </form>
    </div>
</div>
</div>`;

  document
    .querySelector(".body__filter--action__add")
    .addEventListener("click", e => {
      e.preventDefault();
      modal.innerHTML = create_html;
      const modal_create_container = document.querySelector(
        "#modal-edit-container"
      );
      modal
        .querySelector(".button-confirm")
        .addEventListener("click", function (e) {
          e.preventDefault();
          const message_name =
            modal_create_container.querySelector("#message_name");
          const message_type =
            modal_create_container.querySelector("#message_type");
          const message_value =
            modal_create_container.querySelector("#message_value");
          const message_start =
            modal_create_container.querySelector("#message_start");
          const message_end =
            modal_create_container.querySelector("#message_end");
          const start_date_str =
            modal_create_container.querySelector("#start_date").value;
          const end_date_str =
            modal_create_container.querySelector("#end_date").value;
          const discount_code = modal.querySelector("#nameDiscount").value;
          const type = modal.querySelector("#type_discount").value;
          const discount_value = modal.querySelector("#value_discount").value;

          const start_date = new Date(start_date_str);
          const end_date = new Date(end_date_str);
          var check = true;

          if (discount_code == "") {
            message_name.innerHTML = "*Vui lòng nhập tên mã khuyến mãi";
            modal.querySelector("#nameDiscount").focus();
            check = false;
          } else {
            message_name.innerHTML = "";
          }

          if (type == "") {
            message_type.innerHTML = "*Vui lòng chọn loại mã khuyến mãi";
            modal.querySelector("#type_discount").focus();
            check = false;
          } else {
            message_type.innerHTML = "";
          }

          if (discount_value == "") {
            message_value.innerHTML = "*Vui lòng nhập giá trị mã khuyến mãi";
            modal.querySelector("#value_discount").focus();
            check = false;
          } else if (isNaN(discount_value)) {
            message_value.innerHTML =
              "*Vui lòng nhập giá trị mã khuyến mãi phải là số";
            check = false;
          } else if (discount_value <= 0) {
            message_value.innerHTML = "*Giá trị phải lớn hơn 0";
            check = false;
          } else if (type == "PR") {
            if (discount_value > 100) {
              message_value.innerHTML = "*Giá trị không được lớn hơn 100";
              check = false;
            } else if (discount_value <= 0) {
              message_value.innerHTML = "*Giá trị phải lớn hơn 0";
              check = false;
            } else {
              message_value.innerHTML = "";
            }
          } else if (type == "AR") {
            if (discount_value <= 0) {
              message_value.innerHTML = "*Giá trị phải lớn hơn 0";
              check = false;
            } else if (discount_value > 1000001) {
              message_value.innerHTML =
                "*Giá trị phải nhỏ hơn hoặc bằng 1000000";
              check = false;
            } else {
              message_value.innerHTML = "";
            }
          } else {
            message_value.innerHTML = "";
          }

          if (!start_date_str) {
            message_start.innerHTML = "*Vui lòng chọn ngày bắt đầu";
            modal_create_container.querySelector("#start_date").focus();
            check = false;
          } else if (start_date > end_date) {
            message_start.innerHTML =
              "*Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc.";
          } else {
            message_start.innerHTML = "";
          }

          if (!end_date_str) {
            message_end.innerHTML = "*Vui lòng chọn ngày kết thúc";
            modal_create_container.querySelector("#end_date").focus();
            check = false;
          } else if (start_date > end_date) {
            message_end.innerHTML =
              "*Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.";
            check = false;
          } else {
            message_end.innerHTML = "";
          }

          if (check == true) {
            message_end.innerHTML = "";
            message_name.innerHTML = "";
            message_start.innerHTML = "";
            message_type.innerHTML = "";
            message_value.innerHTML = "";
            $.ajax({
              url: "../controller/admin/discount.controller.php",
              type: "post",
              dataType: "html",
              data: {
                function: "create",
                field: {
                  discount_code: discount_code,
                  type: type,
                  discount_value: discount_value,
                  start_date: start_date_str,
                  end_date: end_date_str,
                },
              },
            }).done(function (result) {
              loadItem();
              $("#sqlresult").html(result);
              setTimeout(() => {
                $("#sqlresult").html(""); // Xóa nội dung sau 3 giây
              }, 3000);
            });
            modal_create_container.classList.add("hidden");
          }
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
        <h3>Sửa mã giảm giá</h3>
        <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
        <form action="">

            <div class="modal-body-2">
                <div class="flex">
                    <label for="name">Tên mã giảm giá</label>
                    <input id="name" type="text" add-index="2" placeholder="Tên mã giảm giá" disabled>   
                </div>
                <div class="flex">
                    <label for="type_discount">Loại mã giảm giá giá</label>
                    <select id="type_discount">
                    <option value="">Chọn loại mã giảm giá</option>
                    <option value="AR" >Giảm theo giá tiền </option>
                    <option value="PR">Giảm theo phần trăm</option>                 
                </select>
                <p id="message_type" class ="message"></p>
                </div>
                <div class="flex">
                    <label for="value_discount">Giá trị mã giảm giá</label>
                    <input id="value_discount" type="text" add-index="2" placeholder="Giá trị mã giảm giá">   
                    <p id="message_value" class ="message"></p>      
                </div>
                <div class="flex">
                    <label for="start_date">Ngày bắt đầu</label>
                    <input id="start_date" type="date" add-index="2" p>    
                    <p id="message_start" class ="message"></p>     
                </div>
                <div class="flex">
                    <label for="end_date">Ngày kết thúc</label>
                    <input id="end_date"" type="date" add-index="2" p>      
                    <p id="message_end" class ="message"></p>   
                </div>
            </div>
            <div>
            </div>
            <input type="button" value="Hủy" class="button-cancel">
            <input type="submit" value="Xác nhận" class="button-confirm" add-index="9">
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
      modal.querySelector("#btnClose").addEventListener("click", () => {
        modal_edit_container.classList.remove("show");
      });
      modal.querySelector(".button-cancel").addEventListener("click", () => {
        modal_edit_container.classList.remove("show");
      });
      modal.querySelector("#name").value =
        this.parentNode.parentNode.querySelector(".discount_code").innerHTML;
      const type_discount_value =
        this.parentNode.parentNode.querySelector(".type").innerText;
      const type_discount = modal.querySelector("#type_discount");
      for (let i = 0; i < type_discount.options.length; i++) {
        if (type_discount.options[i].value === type_discount_value) {
          type_discount.options[i].selected = true;
          break;
        }
      }
      const innerHTMLDiscountValue =
        this.parentNode.parentNode.querySelector(".discount_value").innerHTML;
      const discountValue = innerHTMLDiscountValue.replace(/\D/g, "");

      modal.querySelector("#value_discount").value = discountValue;
      modal.querySelector("#start_date").value =
        this.parentNode.parentNode.querySelector(".start_date").innerHTML;
      modal.querySelector("#end_date").value =
        this.parentNode.parentNode.querySelector(".end_date").innerHTML;

      var discount_code =
        this.parentNode.parentNode.querySelector(".discount_code").innerHTML;

      modal
        .querySelector(".button-confirm")
        .addEventListener("click", function (e) {
          e.preventDefault();
          const message_type =
            modal_edit_container.querySelector("#message_type");
          const message_value =
            modal_edit_container.querySelector("#message_value");
          const message_start =
            modal_edit_container.querySelector("#message_start");
          const message_end =
            modal_edit_container.querySelector("#message_end");
          const start_date_str =
            modal_edit_container.querySelector("#start_date").value;
          const end_date_str =
            modal_edit_container.querySelector("#end_date").value;
          const type =
            modal_edit_container.querySelector("#type_discount ").value;
          const discount_value =
            modal_edit_container.querySelector("#value_discount").value;
          const start_date = new Date(start_date_str);
          const end_date = new Date(end_date_str);

          var check = true;

          if (type == "") {
            message_type.innerHTML = "*Vui lòng chọn loại mã khuyến mãi";
            modal.querySelector("#type_discount").focus();
            check = false;
          } else {
            message_type.innerHTML = "";
          }

          if (discount_value == "") {
            message_value.innerHTML = "*Vui lòng nhập giá trị mã khuyến mãi";
            modal_edit_container.querySelector("#value_discount").focus();
            check = false;
          } else if (isNaN(discount_value)) {
            message_value.innerHTML =
              "*Vui lòng nhập giá trị mã khuyến mãi phải là số";
            modal_edit_container.querySelector("#value_discount").focus();
            check = false;
          } else if (discount_value <= 0) {
            message_value.innerHTML = "*Giá trị phải lớn hơn 0";
            check = false;
          } else if (type == "PR") {
            if (discount_value > 100) {
              message_value.innerHTML = "*Giá trị không được lớn hơn 100";
              check = false;
            } else if (discount_value <= 0) {
              message_value.innerHTML = "*Giá trị phải lớn hơn 0";
              check = false;
            } else {
              message_value.innerHTML = "";
            }
          } else if (type == "AR") {
            if (discount_value <= 0) {
              message_value.innerHTML = "*Giá trị phải lớn hơn 0";
              check = false;
            } else if (discount_value > 1000000) {
              message_value.innerHTML = "*Giá trị phải nhỏ hơn 1000000";
              check = false;
            } else {
              message_value.innerHTML = "";
            }
          } else {
            message_value.innerHTML = "";
          }

          if (!start_date_str) {
            message_start.innerHTML = "*Vui lòng chọn ngày bắt đầu";
            check = false;
          } else if (start_date > end_date) {
            message_start.innerHTML =
              "*Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc.";
          } else {
            message_start.innerHTML = "";
          }

          if (!end_date_str) {
            message_end.innerHTML = "*Vui lòng chọn ngày kết thúc";
            modal_create_container.querySelector("#end_date").focus();
            check = false;
          } else if (start_date > end_date) {
            message_end.innerHTML =
              "*Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.";
            check = false;
          } else {
            message_end.innerHTML = "";
          }
          if (check === true) {
            message_end.innerHTML = "";
            message_start.innerHTML = "";
            message_type.innerHTML = "";
            message_value.innerHTML = "";
            $.ajax({
              url: "../controller/admin/discount.controller.php",
              type: "post",
              dataType: "html",
              data: {
                function: "edit",
                field: {
                  discount_code: discount_code,
                  type: type,
                  value_discount: discount_value,
                  start_date: start_date_str,
                  end_date: end_date_str,
                },
              },
            }).done(function (result) {
              loadItem();
              $("#sqlresult").html(result);
            });
            modal_edit_container.classList.remove("show");
          }
        });
    });
  }

  // delete

  const del_btns = document.getElementsByClassName("actions--delete");

  for (var i = 0; i < del_btns.length; i++) {
    del_btns[i].addEventListener("click", function () {
      let selected_content = this.parentNode.parentNode;
      let discount_code =
        selected_content.querySelector(".discount_code").innerHTML;

      var del_html = `
        <div class="modal-edit-product-container show" id="modal-edit-container">
        <div class="modal-edit-product">
            <div class="modal-header">
                <h3>Xác nhận xóa mã giảm giá</h3>
                <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="del-body">
                    
                    <div class="thongtin">
                       
                        <div><span style="font-weight: bold;">Tên mã giảm giá :</span> <span id="discount_code_delete">${discount_code}</span> </div>
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
        var $discount_code = $("#discount_code_delete").html();
        $.ajax({
          url: "../controller/admin/discount.controller.php",
          type: "post",
          dataType: "html",
          data: {
            function: "delete",
            discount_code: $discount_code,
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
