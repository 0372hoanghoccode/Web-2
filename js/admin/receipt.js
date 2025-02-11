var filter_form = document.querySelector(".admin__content--body__filter");
function getFilterFromURL() {
  filter_form.querySelector("#id").value =
    urlParams["id"] != null ? urlParams["id"] : "";
  filter_form.querySelector("#supplierName").value =
    urlParams["supplier_id"] != null ? urlParams["supplier_id"] : "";
  filter_form.querySelector("#staff_id").value =
    urlParams["staff_id"] != null ? urlParams["staff_id"] : "";
  filter_form.querySelector("#date_start").value =
    urlParams["date_start"] != null ? urlParams["date_start"] : "";
  filter_form.querySelector("#date_end").value =
    urlParams["date_end"] != null ? urlParams["date_end"] : "";
  filter_form.querySelector("#price_start").value =
    urlParams["price_start"] != null ? urlParams["price_start"] : "";
  filter_form.querySelector("#price_end").value =
    urlParams["price_end"] != null ? urlParams["price_end"] : "";
}
function pushFilterToURL() {
  var filter = getGRFilterFromForm();
  var url_key = {
    supplierName: "supplierName",
    id: "id",
    staff_id: "staff_id",
    date_start: "date_start",
    date_end: "date_end",
    price_start: "price_start",
    price_end: "price_end",
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
function getGRFilterFromForm() {
  return {
    supplierName: filter_form.querySelector("#supplierName").value,
    id: filter_form.querySelector("#id").value,
    staff_id: filter_form.querySelector("#staff_id").value,
    date_start: filter_form.querySelector("#date_start").value,
    date_end: filter_form.querySelector("#date_end").value,
    price_start: filter_form.querySelector("#price_start").value,
    price_end: filter_form.querySelector("#price_end").value,
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
    btn.addEventListener(
      "click",
      function () {
        current_page = btn.innerHTML;
        loadItem();
      },
      { once: true }
    )
  );
  if (document.getElementsByClassName("pag-pre").length > 0)
    document.querySelector(".pag-pre").addEventListener(
      "click",
      function () {
        current_page =
          Number(document.querySelector("span.active").innerHTML) - 1;
        loadItem(number_of_item, current_page);
      },
      { once: true }
    );
  if (document.getElementsByClassName("pag-con").length > 0)
    document.querySelector(".pag-con").addEventListener(
      "click",
      function () {
        current_page =
          Number(document.querySelector("span.active").innerHTML) + 1;

        loadItem();
      },
      { once: true }
    );
}
function loadItem() {
  var filter = getGRFilterFromForm();
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
    var regex2 = /^[0-9]\d*$/;
    var message_date_end = filter_form.querySelector("#message_date_end");
    var message_date_start = filter_form.querySelector("#message_date_begin");
    var message_price_end = filter_form.querySelector("#message_price_end");
    var message_price_start = filter_form.querySelector("#message_price_begin");
    const start_date_str = filter_form.querySelector("#date_start").value;
    const end_date_str = filter_form.querySelector("#date_end").value;
    const start_price_str = filter_form.querySelector("#price_start").value;
    const end_price_str = filter_form.querySelector("#price_end").value;
    const start_date = new Date(start_date_str);
    const end_date = new Date(end_date_str);
    const start_price = parseInt(start_price_str);
    const end_price = parseInt(end_price_str);
    var check = true;

    if (!start_date_str && end_date_str) {
      message_date_start.innerHTML = "*Vui lòng chọn ngày bắt đầu";
      check = false;
    } else if (!end_date_str && start_date_str) {
      message_date_end.innerHTML = "*Vui lòng chọn ngày kết thúc";
      check = false;
    } else if (start_date > end_date) {
      message_date_start.innerHTML =
        "*Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc.";
      check = false;
    } else {
      message_date_start.innerHTML = "";
      message_date_end.innerHTML = "";
    }

    if (start_price_str || end_price_str) {
      if (!start_price_str) {
        message_price_start.innerHTML = "*Nhập giá bắt đầu";
        check = false;
      } else if (!regex2.test(start_price_str)) {
        message_price_start.innerHTML = "*Giá tiền phải là 1 số không âm";
        console.log("sda");
        check = false;
      } else if (
        end_price_str &&
        regex2.test(end_price_str) &&
        start_price > end_price
      ) {
        message_price_start.innerHTML =
          "*Giá tiền bắt đầu phải nhỏ hơn giá tiền kết thúc";
        check = false;
      } else {
        message_price_start.innerHTML = "";
      }

      if (!end_price_str) {
        message_price_end.innerHTML = "*Nhập giá kết thúc";
        check = false;
      } else if (!regex2.test(end_price_str)) {
        message_price_end.innerHTML = "*Giá tiền phải là 1 số không âm";
        check = false;
      } else if (
        start_price_str &&
        regex2.test(start_price_str) &&
        start_price > end_price
      ) {
        message_price_end.innerHTML =
          "*Giá tiền bắt đầu phải nhỏ hơn giá tiền kết thúc";
        check = false;
      } else {
        message_price_end.innerHTML = "";
      }
    }

    if (check) {
      current_page = 1;
      loadItem();
    }
  });

  $(".body__filter--action__reset").click(e => {
    var message_date_end = filter_form.querySelector("#message_date_end");
    var message_date_start = filter_form.querySelector("#message_date_begin");
    var message_price_end = filter_form.querySelector("#message_price_end");
    var message_price_start = filter_form.querySelector("#message_price_begin");
    message_date_start.innerHTML = "";
    message_date_end.innerHTML = "";
    message_price_start.innerHTML = "";
    message_price_end.innerHTML = "";

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

function addProduct() {
  let productName = "";
  let productId = "";
  let quantity = "";
  let inputPrice = "";
  let productNumber = 0;
  const productIdDropdown = document.getElementById("productId");
  productIdDropdown.addEventListener("change", function () {
    const selectedProductId = this.value;
    productName = document.getElementById("productId").selectedOptions[0].text;
    productId = document.getElementById("productId").value;
    console.log(productId);
    if (!selectedProductId) return;

    $.ajax({
      url: "../controller/admin/receipt.controller.php",
      type: "post",
      dataType: "html",
      data: {
        function: "getPrice",
        field: { id: selectedProductId },
      },
    })
      .done(function (result) {
        inputPrice = 0.8 * parseFloat(result);
        document.getElementById("inputPrice").value = inputPrice.toLocaleString(
          "vi-VN",
          { style: "currency", currency: "VND" }
        );
      })
      .fail(function () {
        alert("Đã xảy ra lỗi khi lấy giá.");
      });

    $.ajax({
      url: "../controller/admin/receipt.controller.php",
      type: "post",
      dataType: "json",
      data: {
        function: "getProductsNumber",
        field: { id: selectedProductId },
      },
      success: function (response) {
        if (response.success) {
          // Hiển thị số lượng sản phẩm
          productNumber = response.product_count;
          console.log("Số lượng sản phẩm: " + typeof response.product_count);
        } else {
          console.error("Lỗi: " + response.error);
        }
      },
      error: function (xhr, status, error) {
        console.error("Yêu cầu thất bại: " + error);
      },
    });
  });

  document.getElementById("addProduct").addEventListener("click", function () {
    var regex = /^[1-9]\d*$/;

    quantity = document.getElementById("quantity").value;

    if (productId.trim() === "" || quantity.trim() === "") {
      alert("Vui lòng nhập đầy đủ thông tin.");
      return;
    }

    if (!regex.test(quantity)) {
      alert("Số lượng phải là số nguyên dương lớn hơn 0");
      return;
    }

    if (Number(quantity) > 100001 - productNumber) {
      alert("Số lượng không hợp lệ!");
      return;
    }

    const tableBody = document.getElementById("productTableBody");
    let productExists = false;

    Array.from(tableBody.rows).forEach(function (row) {
      if (row.cells[0].textContent === productId) {
        let prevQuantity = parseInt(row.cells[2].textContent);
        let newQuantity = prevQuantity + parseInt(quantity);
        row.cells[2].textContent = newQuantity;
        row.cells[3].textContent = inputPrice.toLocaleString("vi-VN", {
          style: "currency",
          currency: "VND",
        });
        productExists = true;
      }
    });

    if (!productExists) {
      const newRow = tableBody.insertRow();
      newRow.innerHTML = `
        <td>${productId}</td>
        <td>${productName}</td> 
        <td>${quantity}</td>
        <td>${inputPrice.toLocaleString("vi-VN", {
          style: "currency",
          currency: "VND",
        })}</td>
      `;
    }

    document.getElementById("productId").value = "";
    document.getElementById("quantity").value = "";
    document.getElementById("inputPrice").value = "";
  });
}

function deleteRow() {
  const table = document.getElementById("addTable");
  const rowCount = table.rows.length;
  if (rowCount > 1) {
    table.deleteRow(rowCount - 1);
  } else {
    alert("Không có dòng để xóa.");
  }
}
function getProductsBySupplier(selectElement) {
  const tableBody = document.getElementById("productTableBody");
  tableBody.innerHTML = "";

  const supplierId = selectElement.value;
  $.ajax({
    url: "../controller/admin/receipt.controller.php",
    type: "post",
    dataType: "html",
    data: {
      function: "getIdProducts",
      field: {
        id: supplierId,
      },
    },
  }).done(function (htmlResult) {
    const idProductSelect = document.getElementById("productId");
    idProductSelect.innerHTML = htmlResult;
  });
}

const openModal = addHtml => {
  const addModal = document.getElementById("addReiceptModal");
  const addModalContent = document.querySelector(".addModal-content .form");
  const openModalBtn = document.querySelector(".body__filter--action__add");
  const addButton = document.getElementById("addButton");
  const closeAddIcon = document.querySelector(".addModal-content .close i");

  openModalBtn.addEventListener(
    "click",
    function () {
      addModalContent.innerHTML = addHtml;
      addModalContent.addEventListener("click", addProduct(), { once: true });
      addModal.style.display = "block";

      $.ajax({
        url: "../controller/admin/receipt.controller.php",
        type: "post",
        dataType: "html",
        data: {
          function: "getSuppliers",
        },
      }).done(function (htmlResult) {
        const supplierSelect = document.getElementById("supplier");
        supplierSelect.innerHTML = htmlResult;
      });
    },
    { once: true }
  );

  closeAddIcon.addEventListener("click", function () {
    addModal.style.display = "none";
  });

  addButton.addEventListener(
    "click",
    function (e) {
      e.preventDefault();

      const supplierId = document.getElementById("supplier").value;

      const products = document.querySelectorAll("#productTableBody tr");
      let totalPrice = 0;

      let detailData = [];
      products.forEach(product => {
        const productId = product.cells[0].textContent;
        const quantity = product.cells[2].textContent;
        const inputPriceText = product.cells[3].textContent;
        const inputPrice =
          parseFloat(inputPriceText.replace(/[^\d.-]/g, "")) * 1000;
        totalPrice += parseFloat(quantity) * inputPrice;
        detailData.push({ productId, quantity, inputPrice });
      });
      if (totalPrice === 0) {
        alert("Vui lòng nhập đầy đủ thông tin.");
        return;
      }
      const staffName = document
        .querySelector(".topbar__admin-info h2")
        .innerHTML.trim();
      $.ajax({
        url: "../controller/admin/receipt.controller.php",
        type: "post",
        dataType: "html",
        data: {
          function: "create",
          field: {
            supplierId: supplierId,
            totalPrice: totalPrice,
            details: detailData,
            staffId: staffName,
          },
        },
      }).done(function (result) {
        loadItem();
        $("#sqlresult").html(result);
        setTimeout(() => {
          $("#sqlresult").html(""); // Xóa nội dung sau 3 giây
        }, 3000);
        addModal.style.display = "none";
      });
    },
    { once: true }
  );

  const editModal = document.getElementById("editModal");
  const editModalContent = document.querySelector(".editModal-content .form");
  const closeEditIcon = document.querySelector(".editModal-content .close i");

  const editHtml = `
<div class="form">
  <h2>Xem thông tin đơn nhập hàng</h2>
  <form id="form">
    <div class="input-field">
      <label for="idReceipt">Mã đơn nhập</label>
      <input type="text" id="idForm" readonly="">
      </div>
      <div class="input-field">
      <label for="supplierName" >Tên nhà cung cấp</label>
      <input type="text" id="supplierNameForm" readonly="">
    </div>
    <div class="input-field">
      <label for="staff_id">Tên người nhập hàng</label>
      <input type="email" id="staff_idForm" readonly="" >
      </div>
      <div class="input-field">
      <label for="total_price">Tổng giá</label>
      <input type="text" id="total_price" readonly="">
    </div>
    <div class="input-field">
      <label for="date_create">Ngày lập</label>
      <input type="text" id="date_create" readonly="">
    </div>
  
    <div class="book-table">
    <table id=Table> </table>
    </div>

</div>`;

  closeEditIcon.addEventListener("click", () => {
    editModal.style.display = "none";
  });

  window.addEventListener("click", event => {
    if (event.target === editModal) {
      editModal.style.display = "none";
    }
  });

  var edit_btns = document.querySelectorAll(".actions--edit");
  edit_btns.forEach(function (btn) {
    btn.addEventListener("click", function () {
      editModalContent.innerHTML = editHtml;
      editModal.style.display = "block";

      let id = this.closest("tr").querySelector(".id").innerHTML;

      document.getElementById("idForm").value = id;
      document.getElementById("supplierNameForm").value =
        this.closest("tr").querySelector(".supplierName").innerHTML;
      document.getElementById("total_price").value =
        this.closest("tr").querySelector(".total_price").innerHTML;
      document.getElementById("staff_idForm").value =
        this.closest("tr").querySelector(".staff_id").innerHTML;
      document.getElementById("date_create").value =
        this.closest("tr").querySelector(".date_create").innerHTML;

      let Table = document.querySelector("#Table");
      Table.innerHTML = "";
      $.ajax({
        url: "../controller/admin/receipt.controller.php",
        type: "post",
        dataType: "html",
        data: {
          function: "details",
          field: {
            id: id,
          },
        },
        success: function (response) {
          Table.innerHTML = response;
        },
      });
    });
  });

  closeEditIcon.addEventListener("click", () => {
    editModal.style.display = "none";
  });

  window.addEventListener("click", event => {
    if (event.target === editModal) {
      editModal.style.display = "none";
    }
  });
};

var js = function () {
  var addHtml = `
  <div class="form">
    <h2>Thêm thông tin đơn nhập hàng</h2>
    <form id="form">
      <div class="input-field">
        <label for="supplier">Nhà cung cấp:</label>
        <select id="supplier" onchange="getProductsBySupplier(this)" ">
        <!-- Options for suppliers will be dynamically added -->
      </select>
        </select>
      </div>
      <div class="input-field">
        <label for="productId">Mã sản phẩm:</label>
        <select id="productId">
          <!-- Options for products will be dynamically added -->
        </select>
      </div>
      <div class="input-field">
        <label for="quantity">Số lượng:</label>
        <input type="text" id="quantity">
      </div>
      <div class="input-field">
        <label for="inputPrice">Giá nhập:</label>
        <input type="text" id="inputPrice" readonly>
      </div>
      <div class="form-actions">
        <button type="button"  class="btn" id="addProduct">Thêm sản phẩm</button>
        <button type="button"  onclick="deleteRow()">Xóa</button>

      </div>    
    </form>
    <div class="book-table">
      <table id="addTable">
        <thead>
          <tr>
            <th style="width: 10%">Mã SP</th>
            <th style=" width: 55%">Tên SP</th>
            <th style=" width: 15%">Số Lượng</th>
            <th style=" width: 20%">Giá Nhập</th>
          </tr>
        </thead>
        <tbody id="productTableBody">
          <!-- Products added will be shown here -->
        </tbody>
      </table>
    </div>
  </div>`;
  let flag = false;
  if (orderby != "" && order_type != "")
    document.querySelector("[data-order=" + "'" + orderby + "']").innerHTML +=
      order_type == "ASC"
        ? ' <i class="fas fa-sort-up">'
        : ' <i class="fas fa-sort-down">';
  else if (!document.querySelector("[data-order]")) {
    openModal(addHtml);
    flag = true;
  } else
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
  !flag ? openModal(addHtml) : "";
};
