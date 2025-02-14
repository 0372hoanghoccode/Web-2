$(document).ready(function () {
  $(".btnXemChiTiet").click(function (e) {
    e.preventDefault();
    const parentOrderEle = $(this).closest(".order")[0];
    const orderId = parentOrderEle.querySelector(".orderId").value;

    $.ajax({
      type: "post",
      url: "controller/order.controller.php",
      dataType: "html",
      data: {
        xemChiTiet: true,
        orderId,
        modelPath: "../model",
      },
    }).done(function (result) {
      const data = JSON.parse(result);
      renderHTMLDetailOrder(data);
      openModal();
    });
  });

  $(".btnHuyDon").click(function (e) {
    e.preventDefault();
    const result = confirm("Bạn có muốn xoá đơn hàng này không?");
    if (result) {
      const parentOrderEle = $(this).closest(".order")[0];
      const orderId = parentOrderEle.querySelector(".orderId").value;

      $.ajax({
        type: "post",
        url: "controller/order.controller.php",
        dataType: "html",
        data: {
          huyDonHang: true,
          orderId,
          modelPath: "../model",
        },
      }).done(function (result) {
        const data = JSON.parse(result);
        alert(data.message);
        location.reload();
      });
    }
  });
});

const modal = document.querySelector(".modal");
const overlayDetailOrder = document.querySelector(".overlayDetailOrder");

// Hàm xử lý render chi tiết đơn hàng
function renderHTMLDetailOrder(data) {
  modal.innerHTML = "";

  const formatDateCreate = new Date(data.order.date_create);
  const day = formatDateCreate.getDate();
  const month = formatDateCreate.getMonth() + 1;
  const year = formatDateCreate.getFullYear();

  const forrmatTotalPrice = parseFloat(data.order.total_price).toLocaleString(
    "vi-VN"
  );
  const discountCode = data.order.discount_code;
  let messageTotalPrice = "";
  if (discountCode) {
    messageTotalPrice = "(Đã tính mã khuyến mãi)";
  }

  let html = `
    <i class="fa-solid fa-xmark closeModalIcon"></i>
    <h2>Thông tin chi tiết</h2>
    <div class="info-wrapper">
      <h3>Thông tin giao hàng</h3>
      <span><strong>Họ và tên: </strong>${data.deliveryInfo.fullname}</span>
      <span><strong>Số điện thoại: </strong>${data.deliveryInfo.phone_number}</span>
      <span
        ><strong>Địa chỉ: </strong>${data.deliveryInfo.address}, ${data.deliveryInfo.ward}, ${data.deliveryInfo.district}, ${data.deliveryInfo.city}</span
      >
    </div>
    <div class="info-wrapper">
      <h3>Thông tin đơn hàng</h3>
      <span><strong>Ngày tạo: </strong>${day} tháng ${month}, ${year}</span>
      <span><strong>Trạng thái đơn hàng: </strong>${data.order.order_status}</span>
      <span><strong>Tổng giá trị: </strong>${forrmatTotalPrice} đ ${messageTotalPrice}</span>
    </div>
    <div class="table-wrapper">
      <table>
        <thead>
          <td style="width: 25%">Hình ảnh</td>
          <td style="width: 40%">Tên sản phẩm</td>
          <td style="width: 15%">Số lượng</td>
          <td style="width: 20%">Đơn giá</td>
        </thead>
        <tbody>`;

  data.orderDetails.forEach((orderDetail) => {
    const forrmatPrice = parseFloat(orderDetail.price).toLocaleString("vi-VN");

    html += `
      <tr>
        <td style="width: 20%"><img style="width: 60%;object-fit: cover;" src="${orderDetail.image_path}"/></td>
        <td >${orderDetail.product_name}</td>
        <td>${orderDetail.quantity}</td>
        <td>${forrmatPrice} đ</td>
      </tr>`;
  });

  html += `
    </tbody>
      </table>
    </div>`;

  modal.insertAdjacentHTML("beforeend", html);

  document.querySelector(".closeModalIcon").addEventListener("click", (e) => {
    closeModal();
  });
}

// Hàm xử lý mở modal
function openModal() {
  modal.classList.remove("hide");
  overlayDetailOrder.classList.remove("hide");
}

function closeModal() {
  modal.classList.add("hide");
  overlayDetailOrder.classList.add("hide");
}

overlayDetailOrder.addEventListener("click", (e) => {
  closeModal();
});
