var filter_form = document.querySelector(".admin__content--body__filter");
function getFilterFromURL() {
  filter_form.querySelector("#productName").value =
    urlParams["name"] != null ? urlParams["name"] : "";
  filter_form.querySelector("#productId").value =
    urlParams["id"] != null ? urlParams["id"] : "";
  filter_form.querySelector("#categorySelect").value =
    urlParams["category"] != null ? urlParams["category"] : "";
  filter_form.querySelector("#cateDateSelect").value =
    urlParams["date_type"] != null ? urlParams["date_type"] : "";
  filter_form.querySelector("#date_start").value =
    urlParams["date_start"] != null ? urlParams["date_start"] : "";
  filter_form.querySelector("#date_end").value =
    urlParams["date_end"] != null ? urlParams["date_end"] : "";
  filter_form.querySelector("#price_start").value =
    urlParams["price_start"] != null ? urlParams["price_start"] : "";
  filter_form.querySelector("#price_end").value =
    urlParams["price_end"] != null ? urlParams["price_end"] : "";
  filter_form.querySelector("#status").value =
    urlParams["status"] != null ? urlParams["status"] : "1";
}
function pushFilterToURL() {
  var filter = getFilterFromForm();
  var url_key = {
    name: "name",
    id: "id",
    category: "category",
    date_type: "date_type",
    date_start: "date_start",
    date_end: "date_end",
    price_start: "price_start",
    price_end: "price_end",
    status: "status",
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
    name: filter_form.querySelector("#productName").value,
    id: filter_form.querySelector("#productId").value,
    category: filter_form.querySelector("#categorySelect").value,
    date_type: filter_form.querySelector("#cateDateSelect").value,
    date_start: filter_form.querySelector("#date_start").value,
    date_end: filter_form.querySelector("#date_end").value,
    price_start: filter_form.querySelector("#price_start").value,
    price_end: filter_form.querySelector("#price_end").value,
    status: filter_form.querySelector("#status").value,
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
var current_page = urlParams["current_page"];
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
      await new Promise((resolve) => setTimeout(resolve, 20));
    }
    resolve();
  });
}
async function loadForFirstTime() {
  await checkReady();
  getFilterFromURL();
  loadItem();
  $.ajax({
    url: "../controller/admin/product.controller.php",
    type: "post",
    dataType: "html",
    data: {
      function: "getCategories",
    },
  }).done(function (result) {
    document
      .querySelector(".admin__content--body__filter")
      .querySelector("#categorySelect").innerHTML = result;
  });
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
      loadItem();
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
        filter: filter,
        orderby: orderby,
        order_type: order_type,
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
        current_page +
        "&orderby=" +
        orderby +
        "&order_type=" +
        order_type;
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
  // Thêm lại nút Reset và Lọc vào HTML
  const actionsContainer = document.querySelector(".body__filter--actions");
  if (actionsContainer) {
    actionsContainer.querySelector("div").innerHTML = `
      <button type="reset" class="body__filter--action__reset">Reset</button>
      <button class="body__filter--action__filter">Lọc</button>`;
  }

  // Gán sự kiện khi nhấn nút "Lọc"
  $(".body__filter--action__filter").click((e) => {
    e.preventDefault();
    var message_productId = filter_form.querySelector("#message_productId");
    var productId = filter_form.querySelector("#productId").value.trim();
    var message_end = filter_form.querySelector("#message_end");
    var message_start = filter_form.querySelector("#message_begin");
    var message_price_start = filter_form.querySelector("#message_price_start");
    var message_price_end = filter_form.querySelector("#message_price_end");
    const start_price = filter_form.querySelector("#price_start");
    const end_price = filter_form.querySelector("#price_end");
    const start_date_str = filter_form.querySelector("#date_start").value;
    const end_date_str = filter_form.querySelector("#date_end").value;
    const start_date = new Date(start_date_str);
    const end_date = new Date(end_date_str);
    var check = true;
    var regex = /^\d+$/;

    // Validate productId
    if (!productId.match(regex) && productId !== "") {
      message_productId.innerHTML = "*Mã sản phẩm phải là kí tự số";
      filter_form.querySelector("#productId").focus();
      check = false;
    } else {
      message_productId.innerHTML = "";
    }

    // Validate ngày bắt đầu và kết thúc
    if (!start_date_str && end_date_str) {
      message_start.innerHTML = "*Vui lòng chọn ngày bắt đầu";
      check = false;
    } else if (start_date > end_date) {
      message_start.innerHTML =
        "*Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc.";
      check = false;
    } else {
      message_start.innerHTML = "";
    }

    if (!end_date_str && start_date_str) {
      message_end.innerHTML = "*Vui lòng chọn ngày kết thúc";
      check = false;
    } else if (start_date > end_date) {
      message_end.innerHTML =
        "*Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.";
      check = false;
    } else {
      message_end.innerHTML = "";
    }

    // Validate giá
    if (
      start_price.value !== "" &&
      (isNaN(start_price.value) || start_price.value <= 0)
    ) {
      message_price_start.innerHTML = "*Giá bắt đầu phải là số dương";
      check = false;
    } else {
      message_price_start.innerHTML = "";
    }

    if (
      end_price.value !== "" &&
      (isNaN(end_price.value) || end_price.value <= 0)
    ) {
      message_price_end.innerHTML = "*Giá kết thúc phải là số dương";
      check = false;
    } else {
      message_price_end.innerHTML = "";
    }

    // Nếu hợp lệ thì load lại
    if (check === true) {
      message_productId.innerHTML = "";
      message_start.innerHTML = "";
      message_end.innerHTML = "";
      current_page = 1;
      loadItem();
    }
  });

  // Gán sự kiện khi nhấn nút Reset
  $(".body__filter--action__reset").click((e) => {
    var message_productId = filter_form.querySelector("#message_productId");
    var message_end = filter_form.querySelector("#message_end");
    var message_start = filter_form.querySelector("#message_begin");
    message_productId.innerHTML = "";
    message_start.innerHTML = "";
    message_end.innerHTML = "";
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
      const newurl =
        window.location.protocol +
        "//" +
        window.location.host +
        window.location.pathname +
        "?page=" +
        urlParams["page"] +
        "&item=" +
        number_of_item +
        "&current_page=" +
        current_page +
        "&orderby=" +
        orderby +
        "&order_type=" +
        order_type;
      window.history.pushState({ path: newurl }, "", newurl);
      $(".result").html(result);
      pagnationBtn();
      js();
    });
  });

  // Khi nhấn nút "Thêm sản phẩm" thì cũng trigger Reset
  const addBtn = document.querySelector(".body__filter--action__add");
  const resetBtn = document.querySelector(".body__filter--action__reset");

  if (addBtn && resetBtn) {
    addBtn.addEventListener("click", function () {
      resetBtn.click(); // Gọi reset
    });
  }
}

//js
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
    .forEach((th) => {
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
  function displayImage(input) {
    const file = input.files[0]; // Lấy ra tệp được chọn từ input file
    const imagePreview = document.getElementById("imagePreview"); // Lấy thẻ img hiển thị trước ảnh

    // Kiểm tra xem đã chọn tệp hình ảnh hay chưa
    if (file) {
      const reader = new FileReader(); // Tạo một đối tượng FileReader

      // Thiết lập sự kiện khi FileReader đã đọc xong file
      reader.onload = function (event) {
        // Thiết lập thuộc tính src của thẻ img để hiển thị ảnh đã chọn
        imagePreview.src = event.target.result;
        imagePreview.style.display = "block"; // Hiển thị thẻ img
      };

      // Đọc nội dung của tệp hình ảnh dưới dạng URL
      reader.readAsDataURL(file);
    }
  }
  var multiselect_array = {
    category: [],
    author: [],
    category_hidden: [],
    author_hidden: [],
  };
  const multiselect = document.querySelector("#multiselect");
  const multiselect_html = `<div class="modal-edit-product-container show" id="modal-edit-container">
    <div class="modal-edit-product">
        <div class="modal-header">
            <h3 id="multiselect-header"></h3>
            <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <div id="multiselect-main">
                <div class="body-header">
                    <span id="modal-header">Đã chọn:</span>
                </div>
                <div class="multiselect-body" id="multiselect-selected"></div>
                <div class="body-header">
                    <span id="modal-header">Còn lại:</span>
                    <input type="text">
                </div>
                <div class="multiselect-body" id="multiselect-available"></div>
            </div>
            <input type="reset" value="Reset" class="button-cancel">
            <input type="submit" value="Xác nhận" class="button-confirm">
        </div>
    </div>
</div>`;
  var category_content = "";
  var author_content = "";
  const modal_html = `<div class="modal-edit-product-container show" id="modal-edit-container">
        <div class="modal-edit-product">
            <div class="modal-header">
                <h3 id="modal-header"></h3>
                <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="edit-image">
                        <div id="choose-img-select">
                            <input type="radio" id="retain" value="retain" name="image" checked>
                            <label for="retain">Giữ Hình</label>
                            <input type="radio" id="edit" value="edit" name="image">
                            <label for="edit">Sửa Hình</label>
                        </div>
                        <div class="choose-img hidden">
                            <label for="fileInput">Chọn hình ảnh:</label>
                            <div class="img">
                                <img id="imagePreview" src="#" alt="Ảnh xem trước" style="display: none;">
                            </div>
                            <input type="file" name="choose-img" id="fileInput" accept="image/*">
                        </div>
                        <span class="error-message fileInput hidden"></span>

                    </div>
                    <div class="modal-body-2">
                        <div class="flex">
                            <label for="name">Tên sản phẩm</label>
                            <input id="name" type="text" add-index="2" placeholder="Tên sản phẩm" >
                            <span class="error-message name hidden"></span>
                        </div>
                        <div class="flex">
                            <label for="price">Giá sản phẩm</label>
                            <input id="price" class = "priceClass" type="text" add-index="3" placeholder="Giá sản phẩm" >
                            <span class="error-message price hidden"></span>
                        </div>
                        <div class="flex">
                            <label for="publisher_id">Nhà xuất bản</label>
                            <select id="publisher_id">
                            </select>
                            <span class="error-message publisher_id hidden"></span>
                        </div>
                        <div class="flex">
                            <label for="supplier_id">Nhà cung cấp</label>
                            <select id="supplier_id">
                            </select>
                            <span class="error-message supplier_id hidden"></span>
                        </div>
                        <div class="flex">
                            <span style="display:flex;">
                                <label for="categorySelect" style="flex: 50%">Thể loại</label>
                                <button type="button" class="open-multiselect" id="category-multiselect">Thêm</button>
                            </span>
                            <span id="category-amount" style="padding:5px 0px 0px 5px;">Đã chọn 0 thể loại</span>
                        </div>
                        <div class="flex">
                        <span style="display:flex;">
                                <label for="categorySelect" style="flex: 50%">Tác giả</label>
                                <button type="button" class="open-multiselect" id="author-multiselect">Thêm</button>
                            </span>
                            <span id="author-amount" style="padding:5px 0px 0px 5px;">Đã chọn 0 tác giả</span>
                            <!-- <label for="author">Tác giả</label>
                            <select id="author">
                            </select> -->
                        </div>
                        <div class="flex">
                            <label for="status">Trạng thái</label>
                            <select id="status">
                                <option value="1" selected>Đang kinh doanh</option>
                                <option value="2" disabled>Chưa kinh doanh</option>
                                <option value="0">Ngừng kinh doanh</option>
                            </select>
                        </div>
                    </div>
                    <input type="reset" value="Hủy" class="button-cancel">
                    <input type="submit" value="Xác nhận" class="button-confirm" >
                </form>
            </div>
        </div>
    </div>`;

  // code ấn disabled các nút sửa
  // document.querySelector(".actions--edit").addEventListener("click", (e) => {

  //     modal.innerHTML = modal_html;
  //     const selectStatus = document.querySelector("#status");
  //     const priceInput = document.getElementById('price');
  //     console.log(priceInput)
  //     if(selectStatus.value == 1 || selectStatus.value == 0) {
  //          priceInput.disabled = true;
  //     }
  //  });

  // Lấy tất cả các nút sửa
  // const editButtons = document.querySelectorAll(".actions--edit");

  // Duyệt qua từng nút và gán sự kiện
  // editButtons.forEach((button) => {
  //   button.addEventListener("click", (e) => {
  //     // Thay thế nội dung của modal
  //     modal.innerHTML = modal_html;

  //     // Chờ một chút để đảm bảo nội dung HTML mới đã được thêm vào DOM
  //     setTimeout(() => {
  //       const selectStatus = document.querySelector("#status");
  //       const priceInput = document.getElementById("price");
  //       const supplier = document.getElementById("supplier_id");
  //       const publisher = document.getElementById("publisher_id");
  //       const category = document.getElementById("category-multiselect");
  //       const author = document.getElementById("author-multiselect");

  //       console.log("Price Input:", priceInput);

  //       if (selectStatus && priceInput) {
  //         console.log("Status Value:", selectStatus.value);

  //         // Áp dụng thuộc tính disabled
  //         if (selectStatus.value == 1 || selectStatus.value == 0) {
  //           priceInput.disabled = true;
  //           supplier.disabled = true;
  //           publisher.disabled = true;
  //           category.disabled = true;
  //           author.disabled = true;
  //           category.style.cursor = "default";
  //           author.style.cursor = "default";
  //         } else {
  //           priceInput.disabled = false;
  //         }
  //       } else {
  //         console.error("Select or Price Input not found");
  //       }
  //     }, 0); // Thực hiện sau khi HTML được thêm vào DOM
  //   });
  // });

  const modal = document.querySelector("#modal");

  $.ajax({
    url: "../controller/admin/product.controller.php",
    type: "post",
    dataType: "html",
    data: {
      function: "getCategories",
    },
  }).done(function (result) {
    category_content = result
      .replace("<option value=''>Chọn thể loại</option>", "")
      .replace(/option/gi, "span")
      .replace(/<span value/gi, '<span class="multiselect-content" data-value')
      .replace(
        /<\/span>/gi,
        '<i class="fa-solid fa-xmark cancel-multiselect"></i></span>'
      );
  });

  $.ajax({
    url: "../controller/admin/product.controller.php",
    type: "post",
    dataType: "html",
    data: {
      function: "getAuthors",
    },
  }).done(function (result) {
    author_content = result
      .replace("<option value=''>Chọn tác giả</option>", "")
      .replace(/option/gi, "span")
      .replace(/<span value/gi, '<span class="multiselect-content" data-value')
      .replace(
        /<\/span>/gi,
        '<i class="fa-solid fa-xmark cancel-multiselect"></i></span>'
      );
  });

  const modal_html_add = `<div class="modal-edit-product-container show" id="modal-edit-container">
        <div class="modal-edit-product">
            <div class="modal-header">
                <h3 id="modal-header"></h3>
                <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="edit-image">
                        <div id="choose-img-select">
                            <input type="radio" id="retain" value="retain" name="image" checked>
                            <label for="retain">Giữ Hình</label>
                            <input type="radio" id="edit" value="edit" name="image">
                            <label for="edit">Sửa Hình</label>
                        </div>
                        <div class="choose-img hidden">
                            <label for="fileInput">Chọn hình ảnh:</label>
                            <div class="img">
                                <img id="imagePreview" src="#" alt="Ảnh xem trước" style="display: none;">
                            </div>
                            <input type="file" name="choose-img" id="fileInput" accept="image/*">
                        </div>
                        <span class="error-message fileInput hidden"></span>
                    </div>
                    <div class="modal-body-2">
                        <div class="flex">
                            <label for="name">Tên sản phẩm</label>
                            <input id="name" type="text" add-index="2" placeholder="Tên sản phẩm">
                            <span class="error-message name hidden"></span>
                        </div>
                        <div class="flex">
                            <label for="price">Giá sản phẩm</label>
                            <input id="price" class="priceClass" type="text" add-index="3" placeholder="Giá sản phẩm">
                            <span class="error-message price hidden"></span>
                        </div>
                        <div class="flex">
                            <label for="publisher_id">Nhà xuất bản</label>
                            <select id="publisher_id">
                            </select>
                            <span class="error-message publisher_id hidden"></span>
                        </div>
                        <div class="flex">
                            <label for="supplier_id">Nhà cung cấp</label>
                            <select id="supplier_id">
                            </select>
                            <span class="error-message supplier_id hidden"></span>
                        </div>
                        <div class="flex">
                            <span style="display:flex;">
                                <label for="categorySelect" style="flex: 50%">Thể loại</label>
                                <button type="button" class="open-multiselect" id="category-multiselect">Thêm</button>
                            </span>
                            <span id="category-amount" style="padding:5px 0px 0px 5px;">Đã chọn 0 thể loại</span>
                        </div>
                        <div class="flex">
                            <span style="display:flex;">
                                <label for="categorySelect" style="flex: 50%">Tác giả</label>
                                <button type="button" class="open-multiselect" id="author-multiselect">Thêm</button>
                            </span>
                            <span id="author-amount" style="padding:5px 0px 0px 5px;">Đã chọn 0 tác giả</span>
                        </div>
                        <div class="flex">
                            <label for="status">Trạng thái</label>
                            <select id="status">
                                <option value="1" selected>Đang kinh doanh</option>
                                <option value="2" >Chưa kinh doanh</option>
                                <option value="0">Ngừng kinh doanh</option>
                            </select>
                        </div>
                    </div>
                    <input type="reset" value="Hủy" class="button-cancel">
                    <input type="submit" value="Xác nhận" class="button-confirm">
                </form>
            </div>
        </div>
    </div>`;

  document
    .querySelector(".body__filter--action__add")
    .addEventListener("click", (e) => {
      modal.innerHTML = modal_html_add;

      multiselect_array["category"] = [];
      multiselect_array["author"] = [];
      const modal_edit_container = modal.querySelector("#modal-edit-container");
      modal.querySelector("#choose-img-select").remove();
      modal.querySelector(".choose-img").classList.remove("hidden");
      // Xóa dòng ẩn ô "Trạng thái"
      // modal.querySelector("#status").parentElement.classList.add("hidden");
      modal.querySelector("#modal-header").innerHTML = "Thêm sản phẩm";

      // Đặt giá trị mặc định cho ô "Trạng thái" khi thêm sản phẩm mới
      modal.querySelector("#status").value = "1"; // "Đang kinh doanh"

      $.ajax({
        url: "../controller/admin/product.controller.php",
        type: "post",
        dataType: "html",
        data: {
          function: "getPublishers",
        },
      }).done(function (result) {
        document
          .querySelector("#modal")
          .querySelector("#publisher_id").innerHTML = result;
      });

      $.ajax({
        url: "../controller/admin/product.controller.php",
        type: "post",
        dataType: "html",
        data: {
          function: "getSuppliers",
        },
      }).done(function (result) {
        document
          .querySelector("#modal")
          .querySelector("#supplier_id").innerHTML = result;
      });

      modal
        .querySelector(".button-confirm")
        .addEventListener("click", function (e) {
          e.preventDefault();
          if (!checkInput()) return;
          $.ajax({
            url: "../controller/admin/product.controller.php",
            type: "post",
            dataType: "html",
            data: {
              function: "create",
              field: {
                id: 0,
                name: modal.querySelector("#name").value,
                publisher_id: modal.querySelector("#publisher_id").value,
                image: document.getElementById("imagePreview").src,
                price: modal.querySelector("#price").value,
                supplier_id: modal.querySelector("#supplier_id").value,
                category: multiselect_array["category"],
                author: multiselect_array["author"],
                status: modal.querySelector("#status").value,
              },
            },
          }).done(function (result) {
            loadItem();
            $("#sqlresult").html(result);
            setTimeout(() => {
              $("#sqlresult").html(""); // Xóa nội dung sau 3 giây
            }, 3000);
          });
          modal_edit_container.classList.add("hidden");
        });
      modal.querySelector("#btnClose").addEventListener("click", () => {
        modal_edit_container.classList.add("hidden");
      });
      modal
        .querySelector(".button-cancel")
        .addEventListener("click", function (e) {
          modal_edit_container.classList.add("hidden");
        });
      $("#modal").on("keydown", "input", function (event) {
        if (event.which == 13) {
          event.preventDefault();
          var $this = $(event.target);
          var index = parseFloat($this.attr("add-index"));
          $('[add-index="' + (index + 1).toString() + '"]').focus();
        }
      });
      modal.querySelectorAll("select").forEach((select) =>
        select.addEventListener("change", function (e) {
          var index = parseFloat(select.getAttribute("add-index"));
          $('[add-index="' + (index + 1).toString() + '"]').focus();
        })
      );
      const fileInput = document.getElementById("fileInput");
      fileInput.addEventListener("change", function () {
        const file = fileInput.files[0];
        const validImageTypes = ["image/jpeg", "image/png", "image/gif"];
        if (!validImageTypes.includes(file.type)) {
          alert("Chỉ được tải lên các tệp hình ảnh (.jpg, .jpeg, .png, .gif).");
          this.value = ""; // Xóa tệp đã chọn
        } else {
          displayImage(this); // Gọi hàm displayImage và truyền vào input element
        }
      });

      var multiselect_filter = (select) => {
        let search = new RegExp(
          multiselect.querySelector("input").value.toString(),
          "ui"
        );
        if (
          !search.test(
            select.innerHTML.replace(
              '<i class="fa-solid fa-xmark cancel-multiselect"></i>',
              ""
            )
          )
        ) {
          multiselect
            .querySelector("#multiselect-available")
            .querySelector(
              '[data-value="' +
                select.getAttribute("data-value").toString() +
                '"]'
            )
            .classList.add("hidden");
        } else if (
          multiselect
            .querySelector("#multiselect-selected")
            .querySelector(
              '[data-value="' +
                select.getAttribute("data-value").toString() +
                '"]'
            )
            .classList.contains("hidden")
        )
          multiselect
            .querySelector("#multiselect-available")
            .querySelector(
              '[data-value="' +
                select.getAttribute("data-value").toString() +
                '"]'
            )
            .classList.remove("hidden");
      };
      var multiselect_setup = (type, key, content) => {
        multiselect.innerHTML = multiselect_html;
        multiselect.querySelector("#btnClose").addEventListener("click", () => {
          multiselect
            .querySelector("#modal-edit-container")
            .classList.add("hidden");
        });
        multiselect.querySelector("input").addEventListener("input", () => {
          multiselect
            .querySelector("#multiselect-available")
            .querySelectorAll(".multiselect-content")
            .forEach((select) => {
              multiselect_filter(select);
            });
        });
        multiselect
          .querySelector(".button-cancel")
          .addEventListener("click", () => {
            multiselect
              .querySelector("#multiselect-selected")
              .querySelectorAll(".multiselect-content")
              .forEach(function (select) {
                multiselect
                  .querySelector("#multiselect-selected")
                  .querySelector(
                    '[data-value="' +
                      select.getAttribute("data-value").toString() +
                      '"]'
                  )
                  .classList.add("hidden");
                multiselect
                  .querySelector("#multiselect-available")
                  .querySelector(
                    '[data-value="' +
                      select.getAttribute("data-value").toString() +
                      '"]'
                  )
                  .classList.remove("hidden");
              });
            multiselect.querySelector("input").value = "";
          });
        multiselect.querySelector("#multiselect-header").innerHTML =
          "Chọn " + type;
        multiselect.querySelector("#multiselect-selected").innerHTML =
          content.replace(
            /class="multiselect-content"/gi,
            'class="multiselect-content hidden"'
          );
        multiselect.querySelector("#multiselect-available").innerHTML = content;
        multiselect
          .querySelector("#multiselect-selected")
          .querySelectorAll(".multiselect-content")
          .forEach((select) =>
            select.addEventListener("click", function () {
              this.classList.add("hidden");
              multiselect
                .querySelector("#multiselect-available")
                .querySelector(
                  '[data-value="' +
                    this.getAttribute("data-value").toString() +
                    '"]'
                )
                .classList.remove("hidden");
              multiselect_filter(this);
            })
          );
        multiselect
          .querySelector("#multiselect-available")
          .querySelectorAll(".multiselect-content")
          .forEach((select) =>
            select.addEventListener("click", function () {
              multiselect
                .querySelector("#multiselect-selected")
                .querySelector(
                  '[data-value="' +
                    this.getAttribute("data-value").toString() +
                    '"]'
                )
                .classList.remove("hidden");
              this.classList.add("hidden");
            })
          );

        multiselect_array[key].forEach(function (select) {
          multiselect
            .querySelector("#multiselect-selected")
            .querySelector('[data-value="' + select.toString() + '"]')
            .classList.remove("hidden");
          multiselect
            .querySelector("#multiselect-available")
            .querySelector('[data-value="' + select.toString() + '"]')
            .classList.add("hidden");
        });
        multiselect
          .querySelector(".button-confirm")
          .addEventListener("click", function (e) {
            e.preventDefault();
            multiselect_array[key] = [];
            multiselect
              .querySelector("#multiselect-selected")
              .querySelectorAll(".multiselect-content")
              .forEach(function (select) {
                if (!select.classList.contains("hidden"))
                  multiselect_array[key].push(
                    select.getAttribute("data-value")
                  );
              });
            multiselect
              .querySelector("#modal-edit-container")
              .classList.add("hidden");
            modal.querySelector("#" + key + "-amount").innerHTML =
              "Đã chọn " +
              multiselect_array[key].length.toString() +
              " " +
              type;
          });
      };
      modal
        .querySelector("#category-multiselect")
        .addEventListener("click", () => {
          multiselect_setup("thể loại", "category", category_content);
        });
      modal
        .querySelector("#author-multiselect")
        .addEventListener("click", () => {
          multiselect_setup("tác giả", "author", author_content);
        });
    });
  var edit_btns = document.getElementsByClassName("actions--edit");
  for (var i = 0; i < edit_btns.length; i++) {
    edit_btns[i].addEventListener("click", function () {
      // modal.innerHTML = create_html;
      // const modal_edit_container = document.querySelector("#modal-edit-container");

      var publisher_value = this.parentNode.parentNode
        .querySelector(".id")
        .getAttribute("publisher_id");
      var supplier_value = this.parentNode.parentNode
        .querySelector(".id")
        .getAttribute("supplier_id");
      var id = this.parentNode.parentNode.querySelector(".id").innerHTML;

      $.ajax({
        url: "../controller/admin/product.controller.php",
        type: "post",
        dataType: "html",
        data: {
          function: "getPublishers",
        },
      }).done(function (result) {
        modal.querySelector("#publisher_id").innerHTML = result;
        modal.querySelector("#publisher_id").value = publisher_value;
      });
      $.ajax({
        url: "../controller/admin/product.controller.php",
        type: "post",
        dataType: "html",
        data: {
          function: "getSuppliers",
        },
      }).done(function (result) {
        modal.querySelector("#supplier_id").innerHTML = result;
        modal.querySelector("#supplier_id").value = supplier_value;
      });
      if (
        this.parentNode.parentNode
          .querySelector(".type")
          .getAttribute("value") == "[]"
      )
        multiselect_array["category"] = [];
      else
        multiselect_array["category"] = this.parentNode.parentNode
          .querySelector(".type")
          .getAttribute("value")
          .replace(/[\[ \]]/gi, "")
          .split(",");
      if (
        this.parentNode.parentNode
          .querySelector(".type")
          .getAttribute("value_hidden") == "[]"
      )
        multiselect_array["category_hidden"] = [];
      else
        multiselect_array["category_hidden"] = this.parentNode.parentNode
          .querySelector(".type")
          .getAttribute("value_hidden")
          .replace(/[\[ \]]/gi, "")
          .split(",");
      if (
        this.parentNode.parentNode
          .querySelector(".author")
          .getAttribute("value") == "[]"
      )
        multiselect_array["author"] = [];
      else
        multiselect_array["author"] = this.parentNode.parentNode
          .querySelector(".author")
          .getAttribute("value")
          .replace(/[\[ \]]/gi, "")
          .split(",");
      if (
        this.parentNode.parentNode
          .querySelector(".author")
          .getAttribute("value_hidden") == "[]"
      )
        multiselect_array["author_hidden"] = [];
      else
        multiselect_array["author_hidden"] = this.parentNode.parentNode
          .querySelector(".author")
          .getAttribute("value_hidden")
          .replace(/[\[ \]]/gi, "")
          .split(",");
      modal.innerHTML = modal_html;
      modal.querySelector("#name").value =
        this.parentNode.parentNode.querySelector(".name").innerHTML;
      modal.querySelector("#price").value = this.parentNode.parentNode
        .querySelector(".price")
        .innerHTML.replace(/[₫.]+/g, "");
      modal.querySelector("#modal-header").innerHTML =
        "Sửa sản phẩm mã " +
        this.parentNode.parentNode.querySelector(".id").innerHTML;
      modal.querySelector("#category-amount").innerHTML =
        "Đã chọn " +
        multiselect_array["category"].length.toString() +
        " thể loại";
      modal.querySelector("#author-amount").innerHTML =
        "Đã chọn " + multiselect_array["author"].length.toString() + " tác giả";
      modal.querySelector("#status").value = this.parentNode.parentNode
        .querySelector(".id")
        .getAttribute("status");

      const fileInput = document.getElementById("fileInput");
      fileInput.addEventListener("change", function () {
        const file = fileInput.files[0];
        const validImageTypes = ["image/jpeg", "image/png", "image/gif"];
        if (!validImageTypes.includes(file.type)) {
          alert("Chỉ được tải lên các tệp hình ảnh (.jpg, .jpeg, .png, .gif).");
          this.value = ""; // Xóa tệp đã chọn
        } else {
          displayImage(this); // Gọi hàm displayImage và truyền vào input element
        }
      });

      // Hidden choose img
      const editRadio = modal.querySelectorAll('input[name="image"]');
      const chooseImgContainer = modal.querySelector(".choose-img");

      editRadio.forEach(function (radio) {
        radio.addEventListener("change", function () {
          if (this.value === "edit") {
            chooseImgContainer.classList.remove("hidden"); // Hiển thị phần chọn ảnh khi chọn "Sửa Hình"
          } else {
            chooseImgContainer.classList.add("hidden"); // Ẩn phần chọn ảnh khi chọn các tùy chọn khác
          }
        });
      });

      // Button close
      const modal_edit_container = modal.querySelector("#modal-edit-container");
      modal.querySelector("#btnClose").addEventListener("click", () => {
        modal_edit_container.classList.remove("show");
      });
      modal
        .querySelector(".button-cancel")
        .addEventListener("click", function (e) {
          modal_edit_container.classList.add("hidden");
        });

      var multiselect_filter = (select) => {
        let search = new RegExp(
          multiselect.querySelector("input").value.toString(),
          "ui"
        );
        if (
          !search.test(
            select.innerHTML.replace(
              '<i class="fa-solid fa-xmark cancel-multiselect"></i>',
              ""
            )
          )
        ) {
          multiselect
            .querySelector("#multiselect-available")
            .querySelector(
              '[data-value="' +
                select.getAttribute("data-value").toString() +
                '"]'
            )
            .classList.add("hidden");
        } else if (
          multiselect
            .querySelector("#multiselect-selected")
            .querySelector(
              '[data-value="' +
                select.getAttribute("data-value").toString() +
                '"]'
            )
            .classList.contains("hidden")
        )
          multiselect
            .querySelector("#multiselect-available")
            .querySelector(
              '[data-value="' +
                select.getAttribute("data-value").toString() +
                '"]'
            )
            .classList.remove("hidden");
      };
      var multiselect_setup = (type, key, content) => {
        multiselect.innerHTML = multiselect_html;
        multiselect.querySelector("#btnClose").addEventListener("click", () => {
          multiselect
            .querySelector("#modal-edit-container")
            .classList.add("hidden");
        });
        multiselect.querySelector("input").addEventListener("input", () => {
          multiselect
            .querySelector("#multiselect-available")
            .querySelectorAll(".multiselect-content")
            .forEach((select) => {
              multiselect_filter(select);
            });
        });
        multiselect
          .querySelector(".button-cancel")
          .addEventListener("click", () => {
            multiselect
              .querySelector("#multiselect-selected")
              .querySelectorAll(".multiselect-content")
              .forEach(function (select) {
                multiselect
                  .querySelector("#multiselect-selected")
                  .querySelector(
                    '[data-value="' +
                      select.getAttribute("data-value").toString() +
                      '"]'
                  )
                  .classList.add("hidden");
                multiselect
                  .querySelector("#multiselect-available")
                  .querySelector(
                    '[data-value="' +
                      select.getAttribute("data-value").toString() +
                      '"]'
                  )
                  .classList.remove("hidden");
              });
            multiselect.querySelector("input").value = "";
          });
        multiselect.querySelector("#multiselect-header").innerHTML =
          "Chọn " + type;
        multiselect.querySelector("#multiselect-selected").innerHTML =
          content.replace(
            /class="multiselect-content"/gi,
            'class="multiselect-content hidden"'
          );
        multiselect.querySelector("#multiselect-available").innerHTML = content;
        multiselect
          .querySelector("#multiselect-selected")
          .querySelectorAll(".multiselect-content")
          .forEach((select) =>
            select.addEventListener("click", function () {
              this.classList.add("hidden");
              multiselect
                .querySelector("#multiselect-available")
                .querySelector(
                  '[data-value="' +
                    this.getAttribute("data-value").toString() +
                    '"]'
                )
                .classList.remove("hidden");
              multiselect_filter(this);
            })
          );
        multiselect
          .querySelector("#multiselect-available")
          .querySelectorAll(".multiselect-content")
          .forEach((select) =>
            select.addEventListener("click", function () {
              multiselect
                .querySelector("#multiselect-selected")
                .querySelector(
                  '[data-value="' +
                    this.getAttribute("data-value").toString() +
                    '"]'
                )
                .classList.remove("hidden");
              this.classList.add("hidden");
            })
          );

        multiselect_array[key].forEach(function (select) {
          multiselect
            .querySelector("#multiselect-selected")
            .querySelector('[data-value="' + select.toString() + '"]')
            .classList.remove("hidden");
          multiselect
            .querySelector("#multiselect-available")
            .querySelector('[data-value="' + select.toString() + '"]')
            .classList.add("hidden");
        });
        multiselect
          .querySelector(".button-confirm")
          .addEventListener("click", function (e) {
            e.preventDefault();
            multiselect_array[key] = [];
            multiselect
              .querySelector("#multiselect-selected")
              .querySelectorAll(".multiselect-content")
              .forEach(function (select) {
                if (!select.classList.contains("hidden"))
                  multiselect_array[key].push(
                    select.getAttribute("data-value")
                  );
              });
            multiselect
              .querySelector("#modal-edit-container")
              .classList.add("hidden");
            modal.querySelector("#" + key + "-amount").innerHTML =
              "Đã chọn " +
              multiselect_array[key].length.toString() +
              " " +
              type;
          });
      };
      modal
        .querySelector("#category-multiselect")
        .addEventListener("click", () => {
          multiselect_setup("thể loại", "category", category_content);
        });
      modal
        .querySelector("#author-multiselect")
        .addEventListener("click", () => {
          multiselect_setup("tác giả", "author", author_content);
        });
      // confirm edit
      modal
        .querySelector(".button-confirm")
        .addEventListener("click", function (e) {
          e.preventDefault();
          if (!checkInput()) return;
          image = document.getElementById("imagePreview").src;
          if (chooseImgContainer.classList.contains("hidden")) image = "";
          $.ajax({
            url: "../controller/admin/product.controller.php",
            type: "post",
            dataType: "html",
            data: {
              function: "edit",
              field: {
                id: id,
                name: modal.querySelector("#name").value,
                publisher_id: modal.querySelector("#publisher_id").value,
                supplier_id: modal.querySelector("#supplier_id").value,
                image: image,
                price: modal.querySelector("#price").value,
                category: multiselect_array["category"].concat(
                  multiselect_array["category_hidden"]
                ),
                author: multiselect_array["author"].concat(
                  multiselect_array["author_hidden"]
                ),
                status: modal.querySelector("#status").value,
              },
            },
          }).done(function (result) {
            loadItem();
            $("#sqlresult").html(result);
            setTimeout(() => {
              $("#sqlresult").html(""); // Xóa nội dung sau 3 giây
            }, 3000);
          });
          modal_edit_container.classList.add("hidden");
        });
    });
  }

  // delete

  const del_btns = document.getElementsByClassName("actions--delete");

  for (var i = 0; i < del_btns.length; i++) {
    del_btns[i].addEventListener("click", function () {
      let selected_content = this.parentNode.parentNode;
      let product_id = selected_content.querySelector(".id").innerHTML;
      let product_name = selected_content.querySelector(".name").innerHTML;
      let img_link = selected_content.querySelector("img").src;
      var del_html = `
      <div class="modal-edit-product-container show" id="modal-edit-container">
      <div class="modal-edit-product">
          <div class="modal-header">
              <h3>Xác nhận xóa sản phẩm</h3>
              <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
          </div>
          <div class="modal-body">
              <div class="del-body">
                  <div class="image">
                      <img id="imagePreview" src="${img_link}" alt="image not found">
                  </div>
                  <div class="thongtin">
                      <div><span style="font-weight: bold;">Mã sản phẩm :</span> <span id="product-delete-id">${product_id}</span> </div>
                      <div><span style="font-weight: bold;">Tên sản phẩm :</span> <span>${product_name}</span> </div>
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
        var $id = $("#product-delete-id").html();
        $.ajax({
          url: "../controller/admin/product.controller.php",
          type: "post",
          dataType: "html",
          data: {
            function: "delete",
            id: $id,
          },
        }).done(function (result) {
          loadItem();
          $("#sqlresult").html(result);
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
  function checkInput() {
    var success = true;

    const price_regex = /^[0-9]+$/;

    const fileinput = modal.querySelector("#fileInput");
    const name = modal.querySelector("#name");
    const price = modal.querySelector("#price");
    const publisher = modal.querySelector("#publisher_id");
    const supplier = modal.querySelector("#supplier_id");

    fileinput.classList.remove("error-field");
    name.classList.remove("error-field");
    price.classList.remove("error-field");
    publisher.classList.remove("error-field");
    supplier.classList.remove("error-field");

    const fileinput_err = modal.querySelector(".error-message.fileInput");
    const name_err = modal.querySelector(".error-message.name");
    const price_err = modal.querySelector(".error-message.price");
    const publisher_err = modal.querySelector(".error-message.publisher_id");
    const supplier_err = modal.querySelector(".error-message.supplier_id");

    fileinput_err.classList.add("hidden");
    name_err.classList.add("hidden");
    price_err.classList.add("hidden");
    publisher_err.classList.add("hidden");
    supplier_err.classList.add("hidden");

    if (!modal.querySelector(".choose-img").classList.contains("hidden"))
      if (fileinput.files.length == 0) {
        fileinput.classList.add("error-field");
        fileinput_err.classList.remove("hidden");
        fileinput_err.innerHTML = "Chưa chọn file hình ảnh";
        success = false;
      }
    if (name.value.trim().length == 0) {
      name.classList.add("error-field");
      name_err.classList.remove("hidden");
      name_err.innerHTML = "Không được để trống";
      success = false;
    }
    console.log(typeof price.value);
    if (price.value.trim().length == 0) {
      price.classList.add("error-field");
      price_err.classList.remove("hidden");
      price_err.innerHTML = "Không được để trống";
      success = false;
    } else if (!price_regex.test(price.value) || parseFloat(price.value) <= 0) {
      price_err.classList.remove("hidden");
      price_err.innerHTML = "Giá phải là số dương";
      success = false;
    }
    if (publisher.value == "") {
      publisher.classList.add("error-field");
      publisher_err.classList.remove("hidden");
      publisher_err.innerHTML = "Chưa chọn NXB";
      success = false;
    }
    if (supplier.value == "") {
      supplier.classList.add("error-field");
      supplier_err.classList.remove("hidden");
      supplier_err.innerHTML = "Chưa chọn nhà cung cấp ";
      success = false;
    }
    return success;
  }
};
