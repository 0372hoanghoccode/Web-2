var orderby = "id";
var order_type = "ASC";
var title = "";

var search = location.search.substring(1);
var atHome = search == "" || search == "page=home";

$(document).ready(function () {
  $(".btnLogoutAdmin").click(function () {
    $.ajax({
      type: "post",
      url: "../controller/admin/index.controller.php",
      dataType: "html",
      data: {
        isLogout: true,
      },
    }).done(function (result) {
      if (result) {
        alert("Đăng xuất thành công!");
        location.reload();
      } else {
        alert("Hệ thống gặp sự cố không thể đăng xuất!");
      }
    });
  });

  if (atHome) checkFunction();

  $("#filter").click(function () {
    $.ajax({
      url: "../controller/admin/index.controller.php",
      type: "post",
      dataType: "html",
      data: {
        getStats: true,
        date_start: document.querySelector("#startdate").value,
        date_end: document.querySelector("#enddate").value,
      },
    }).done(function (result) {
      $("#thongke-container").html(result);

      // Set up event listeners for the detail buttons
      document.querySelectorAll(".chitietbtn").forEach((btn) =>
        btn.addEventListener("click", function () {
          var username = btn.getAttribute("data-username");
          var detailsRow = document.querySelector("#details-" + username);
          var ordersContent = document.querySelector(
            "#orders-content-" + username
          );

          if (detailsRow.classList.contains("show")) {
            detailsRow.classList.remove("show");
          } else {
            // Show the row
            detailsRow.classList.add("show");

            // Load order details immediately
            $.ajax({
              type: "post",
              url: "../controller/admin/index.controller.php",
              dataType: "html",
              data: {
                getUserOrderDetails: true,
                username: username,
                date_start: document.querySelector("#startdate").value,
                date_end: document.querySelector("#enddate").value,
              },
            }).done(function (result) {
              ordersContent.innerHTML = result;
            });
          }
        })
      );
    });
  });

  // Handle order products detail view via event delegation
  $(document).on("click", ".xemchitiet", function () {
    var orderId = $(this).data("order");
    var productsRow = $("#products-row-" + orderId);
    var productsContent = $("#products-content-" + orderId);

    if (productsRow.is(":hidden")) {
      productsRow.show();

      // Load products if not already loaded
      if (productsContent.find(".loading").length) {
        $.ajax({
          type: "post",
          url: "../controller/admin/index.controller.php",
          dataType: "html",
          data: {
            getOrderProducts: true,
            order_id: orderId,
          },
        }).done(function (result) {
          productsContent.html(result);
        });
      }
    } else {
      productsRow.hide();
    }
  });

  $.ajax({
    type: "post",
    url: "../controller/admin/index.controller.php",
    dataType: "html",
    data: {
      isAutoUpdateData: true,
    },
  }).done(function (result) {
    const data = JSON.parse(result);
    if (data && atHome) {
      updateData4Boxes(data);
    }
  });

  $.ajax({
    type: "post",
    url: "../controller/admin/index.controller.php",
    dataType: "html",
    data: {
      isRender: true,
    },
  }).done(function (result) {
    const data = JSON.parse(result);
    renderSiderBars(data);
    notAllowedEntry(data);
  });
});

function renderSiderBars(data) {
  const siderBars = document.querySelector(".sidebar__items");
  siderBars.innerHTML = "";

  var params = new URLSearchParams(window.location.search);
  var page = params.get("page");

  var sidebarItems = [
    {
      page: "home",
      name: "Trang chủ",
      icon: "fa-house",
      fncid: 1,
    },
    {
      page: "product",
      name: "Sản phẩm",
      icon: "fa-book",
      fncid: 2,
    },
    {
      page: "order",
      name: "Đơn hàng",
      icon: "fa-cart-shopping",
      fncid: 3,
    },
    {
      page: "account",
      name: "Thành viên",
      icon: "fa-user",
      fncid: 4,
    },
    {
      page: "publisher",
      name: "Nhà xuất bản",
      icon: "fa-upload",
      fncid: 5,
    },
    {
      page: "author",
      name: "Tác giả",
      icon: "fa-book-open-reader",
      fncid: 6,
    },
    {
      page: "category",
      name: "Thể loại sách",
      icon: "fa-list",
      fncid: 7,
    },
    {
      page: "supplier",
      name: "Nhà cung cấp",
      icon: "fa-industry",
      fncid: 8,
    },
    // {
    //   // page: "receipt",
    //   // name: "Nhập hàng",
    //   // icon: "fa-file-invoice",
    //   // fncid: 9,
    // },
    // {
    //   // page: "role",
    //   // name: "Phân quyền",
    //   // icon: "fa-gavel",
    //   // fncid: 10,
    // },
    {
      page: "discount",
      name: "Khuyến mãi",
      icon: "fa-file-invoice",
      fncid: 9,
    },
  ];

  let html = "";

  sidebarItems.forEach((siderbarItem, index) => {
    let active = "";
    let href = "#";
    let nonActive = "";

    data.forEach((role) => {
      if (siderbarItem.fncid == role.function_id || siderbarItem.fncid == 1) {
        href = `?page=` + siderbarItem.page;
      }
    });

    active = page == siderbarItem.page ? "active" : "";
    if (page == null && siderbarItem.page == "home") {
      active = "active";
    }

    if (href == "#" && siderbarItem.fncid != 1) {
      nonActive = "nonActive";
    }

    html += `<li class="sidebar__item ${active} ${nonActive}" fncid="${siderbarItem.fncid}" page="${siderbarItem.page}">
              <a href="${href}"><i class="fa-solid ${siderbarItem.icon}"></i>${siderbarItem.name}</a>
            </li>`;
  });

  siderBars.innerHTML = html;
}

function notAllowedEntry(data) {
  var url = window.location.href;
  var urlParams = new URLSearchParams(new URL(url).search);
  var pageParam = urlParams.get("page");

  if (!pageParam || pageParam == "home") return;

  const fncid = document
    .querySelector(`.sidebar__items .sidebar__item[page="${pageParam}"]`)
    .getAttribute("fncid");

  var isIncludeRole = false;
  data.forEach((role) => {
    if (fncid == role.function_id) {
      isIncludeRole = true;
    }
  });

  if (!isIncludeRole) {
    window.location.href = "../admin";
  }
}

function updateData4Boxes(data) {
  const thunhap = document.querySelector(".thunhap");
  const donhang = document.querySelector(".donhang");
  const sanpham = document.querySelector(".sanpham");
  const thanhvien = document.querySelector(".thanhvien");

  const totalIncome = +data.totalIncome;
  const formattedTotalIncome = totalIncome.toLocaleString("vi-VN");

  thunhap.innerHTML = formattedTotalIncome + " đ";
  donhang.innerHTML = data.totalOrders;
  sanpham.innerHTML = data.totalProducts;
  thanhvien.innerHTML = data.totalAccounts;
}

function checkFunction() {
  $.ajax({
    type: "post",
    url: "../controller/admin/index.controller.php",
    dataType: "html",
    data: {
      checkFunction: true,
      function_id: 1,
    },
  }).done(function (result) {
    if (result == "1") {
      document
        .querySelector(".thongkechitiet__container")
        .classList.remove("hidden");
    } else document.querySelector(".thongkechitiet__container").remove();
  });
}
