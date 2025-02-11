// Cập nhật giá thành tiền
function updateTotalPricePerProduct(btn) {
  const parentEle = btn.closest(".cart-item");
  const price = +parentEle.querySelector(".price-hidden").getAttribute("value");
  const amount = +parentEle.querySelector(".qty-cart").getAttribute("value");
  const totalPrice = price * amount;
  const formatPrice = totalPrice.toLocaleString("vi-VN", {
    style: "currency",
    currency: "VND",
  });
  parentEle.querySelector(".cart-total-price .cart-price .price").innerHTML =
    formatPrice;
}

// Hàm cập nhật trạng thái của tất cả các checkbox
function updateAllCheckbox(checked) {
  ckbAddCarts.forEach((ckb) => {
    if(ckb.parentElement.parentElement.querySelector(".not-selectable").classList.contains('hidden'))
    ckb.checked = checked;
  });
}

// Xử lý sự kiện khi click vào checkbox-all-product
const ckbAllAddCart = document.querySelector("#checkbox-all-product");
ckbAllAddCart.addEventListener("change", (e) => {
  updateAllCheckbox(ckbAllAddCart.checked);
  updateTotalPrice();
  countCkbSelected();
});

function calculateTotalPrice() {
  let totalPrice = 0;
  const ckbAddCarts = document.querySelectorAll(".checkbox-add-cart:checked");

  ckbAddCarts.forEach((ckb) => {
    const parentEle = ckb.closest(".cart-item");
    const price = +parentEle
      .querySelector(".price-hidden")
      .getAttribute("value");
    const amount = +parentEle.querySelector(".qty-cart").getAttribute("value");
    totalPrice += price * amount;
  });

  return totalPrice;
}

// Xử lý chọn sản phẩm trong giỏ hàng
const ckbAddCarts = document.querySelectorAll(".checkbox-add-cart");

ckbAddCarts.forEach((ckb) => {
  ckb.addEventListener("change", (e) => {
    updateTotalPrice();
    const count = countCkbSelected();
    ckbAllAddCart.checked = false;

    // Tự động checked ckbAllAddCart khi chọn đủ sản phẩm
    if (count == ckbAddCarts.length) {
      ckbAllAddCart.checked = true;
    }
  });
});

// Xử lý tăng/giảm số lượng sản phẩm trong giỏ hàng
const btnAddQnts = document.querySelectorAll(".btn-add-qty");
const btnSubQnts = document.querySelectorAll(".btn-substract-qty");

function updateTotalPrice() {
  const totalPrice = calculateTotalPrice();
  document.querySelector(
    ".cart-total p"
  ).innerHTML = `Tổng cộng: ${totalPrice.toLocaleString("vi-VN", {
    style: "currency",
    currency: "VND",
  })}`;
}

btnAddQnts.forEach((btnAddQnt) => {
  btnAddQnt.addEventListener("click", (e) => {
    const input = btnAddQnt.previousElementSibling;
    if (input.value < Number(btnAddQnt.getAttribute('data-max-quantity'))) {
      ++input.value;
      input.setAttribute("value", input.value);
    }

    updateTotalPricePerProduct(btnAddQnt);
    updateTotalPrice();
  });
});

btnSubQnts.forEach((btnSubQnt) => {
  btnSubQnt.addEventListener("click", (e) => {
    const input = btnSubQnt.nextElementSibling;
    if (input.value > 1) {
      --input.value;
      input.setAttribute("value", input.value);
    }

    updateTotalPricePerProduct(btnSubQnt);
    updateTotalPrice();
  });
});

function countCkbSelected() {
  let count = 0;
  ckbAddCarts.forEach((ckb) => {
    if (ckb.checked) {
      count++;
    }
  });

  document.querySelector(".num-items-checkbox").innerHTML = count;

  return count;
}

// Xử lý khi bấm tăng/giảm số lượng thì lưu vào luôn
$(document).on("click", ".btn-add-qty", function (e) {
  e.preventDefault();
  const parentEle = $(this).closest(".cart-item")[0];
  const productId = parentEle
    .querySelector(".checkbox-add-cart")
    .getAttribute("value");
  const amount = parentEle.querySelector(".qty-cart").getAttribute("value");

  updateAmount(productId, amount);
});

$(document).on("click", ".btn-substract-qty", function (e) {
  e.preventDefault();
  const parentEle = $(this).closest(".cart-item")[0];
  const productId = parentEle
    .querySelector(".checkbox-add-cart")
    .getAttribute("value");
  const amount = parentEle.querySelector(".qty-cart").getAttribute("value");

  updateAmount(productId, amount);
});

// Function xử lý updateQnt
function updateAmount(productId, amount) {
  $.ajax({
    type: "post",
    url: "controller/cart.controller.php",
    dataType: "html",
    data: {
      "product-action__updateAmount": true,
      productId: productId,
      amount: amount,
    },
  });
}
