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
  $("#close").click(function () {
    document.querySelector("#chitiet").classList.toggle("show");
  });
  $("#filter").click(function () {
    $.ajax({
      url: "../controller/admin/index.controller.php",
      type: "post",
      dataType: "html",
      data: {
        getBestSellers: true,
        date_start: document.querySelector("#startdate").value,
        date_end: document.querySelector("#enddate").value,
      },
    }).done(function (result) {
      $(".result").html(result);
      console.log(result);
    });
    $.ajax({
      type: "post",
      url: "../controller/admin/index.controller.php",
      dataType: "html",
      data: {
        getStats: true,
        date_start: document.querySelector("#startdate").value,
        date_end: document.querySelector("#enddate").value,
      },
    }).done(function (result) {
      if (result) {
        $("#thongke-container").html(result);
        document.querySelectorAll(".chitietbtn").forEach((btn) =>
          btn.addEventListener("click", function () {
            document.querySelector("#chitiet").classList.toggle("show");
            document.querySelector("#title").querySelector("span").innerHTML =
              btn.parentNode.querySelector(".sanpham").innerHTML;
            title = btn.getAttribute("data-id");
            orderby = "id";
            order_type = "ASC";
            StatDetail();
          })
        );
      } else {
        alert("Hệ thống gặp sự cố!");
      }
    });
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
      page: "home",
      fncid: 1,
    },
    {
      page: "product",
      name: "Sản phẩm",
      icon: "fa-book",
      page: "product",
      fncid: 2,
    },
    {
      page: "order",
      name: "Đơn hàng",
      icon: "fa-cart-shopping",
      page: "order",
      fncid: 3,
    },
    {
      page: "account",
      name: "Thành viên",
      icon: "fa-user",
      page: "account",
      fncid: 4,
    },
    {
      page: "publisher",
      name: "Nhà xuất bản",
      icon: "fa-upload",
      page: "publisher",
      fncid: 5,
    },
    {
      page: "author",
      name: "Tác giả",
      icon: "fa-book-open-reader",
      page: "author",
      fncid: 6,
    },
    {
      page: "category",
      name: "Thể loại sách",
      icon: "fa-list",
      page: "category",
      fncid: 7,
    },
    {
      page: "supplier",
      name: "Nhà cung cấp",
      icon: "fa-industry",
      page: "supplier",
      fncid: 8,
    },
    {
      page: "receipt",
      name: "Nhập hàng",
      icon: "fa-file-invoice",
      page: "receipt",
      fncid: 9,
    },

    {
      page: "role",
      name: "Phân quyền",
      icon: "fa-gavel",
      page: "role",
      fncid: 10,
    },
    {
      page: "discount",
      name: "Khuyễn mãi",
      icon: "fa-file-invoice",
      page: "discount",
      fncid: 11,
    },
  ];

  let html = ""; // Khởi tạo biến html ở đây

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
      // return;
    }

    if (href == "#" && siderbarItem.fncid != 1) {
      nonActive = "nonActive";
    }

    html += `<li class="sidebar__item ${active} ${nonActive}"  fncid="${siderbarItem.fncid}" page="${siderbarItem.page}">
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

function StatDetail() {
  $.ajax({
    type: "post",
    url: "../controller/admin/index.controller.php",
    dataType: "html",
    data: {
      getStatDetails: true,
      category_id: title,
      date_start: document.querySelector("#startdate").value,
      date_end: document.querySelector("#enddate").value,
      orderby: orderby,
      order_type: order_type,
    },
  }).done(function (result) {
    $(".table-container").html(result);
    var chitiet_table = document.querySelector("#chitiet-table");
    chitiet_table
      .querySelector("[data-order=" + "'" + orderby + "']")
      .querySelector("." + order_type)
      .classList.remove("hidden");

    chitiet_table
      .querySelector(".table-container")
      .querySelectorAll("th")
      .forEach((th) => {
        if (th.hasAttribute("data-order"))
          th.addEventListener("click", () => {
            if (orderby == "")
              orderby = chitiet_table
                .querySelector("[data-order]")
                .getAttribute("data-order");
            if (
              orderby == th.getAttribute("data-order") &&
              order_type == "ASC"
            ) {
              order_type = "DESC";
            } else {
              order_type = "ASC";
            }
            orderby = th.getAttribute("data-order");
            StatDetail();
          });
      });
  });
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
