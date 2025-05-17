document.addEventListener("DOMContentLoaded", () => {
  // Select elements
  const ckbAllAddCart = document.querySelector("#checkbox-all-product");
  const ckbAddCarts = document.querySelectorAll(".checkbox-add-cart");
  const btnAddQnts = document.querySelectorAll(".btn-add-qty");
  const btnSubQnts = document.querySelectorAll(".btn-substract-qty");

  // Update price per product
  function updateTotalPricePerProduct(btn) {
    const parentEle = btn.closest(".cart-item");
    const price = +parentEle.querySelector(".price-hidden").value;
    const amount = +parentEle.querySelector(".qty-cart").value;
    const totalPrice = price * amount;
    const formatPrice = totalPrice.toLocaleString("vi-VN", {
      style: "currency",
      currency: "VND",
    });
    parentEle.querySelector(".cart-total-price .cart-price .price").innerHTML =
      formatPrice;
  }

  // Update all checkboxes
  function updateAllCheckbox(checked) {
    ckbAddCarts.forEach((ckb) => {
      ckb.checked = checked;
    });
  }

  // Calculate total price
  function calculateTotalPrice() {
    let totalPrice = 0;
    const ckbAddCarts = document.querySelectorAll(".checkbox-add-cart:checked");
    ckbAddCarts.forEach((ckb) => {
      const parentEle = ckb.closest(".cart-item");
      const price = +parentEle.querySelector(".price-hidden").value;
      const amount = +parentEle.querySelector(".qty-cart").value;
      totalPrice += price * amount;
    });
    return totalPrice;
  }

  // Update total price display
  function updateTotalPrice() {
    const totalPrice = calculateTotalPrice();
    document.querySelector(
      ".cart-total p"
    ).innerHTML = `Tổng cộng: ${totalPrice.toLocaleString("vi-VN", {
      style: "currency",
      currency: "VND",
    })}`;
  }

  // Count selected checkboxes
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

  // Handle "Select All" checkbox
  ckbAllAddCart.addEventListener("change", (e) => {
    updateAllCheckbox(e.target.checked);
    updateTotalPrice();
    countCkbSelected();
  });

  // Handle individual checkboxes
  ckbAddCarts.forEach((ckb) => {
    ckb.addEventListener("change", () => {
      updateTotalPrice();
      const count = countCkbSelected();
      ckbAllAddCart.checked = count === ckbAddCarts.length;
    });
  });

  // Handle quantity increase
  btnAddQnts.forEach((btnAddQnt) => {
    btnAddQnt.addEventListener("click", (e) => {
      e.preventDefault();
      const input = btnAddQnt.previousElementSibling;
      const maxQuantity = +btnAddQnt.getAttribute("data-max-quantity");
      // if (+input.value < maxQuantity) {
      input.value = +input.value + 1;
      input.setAttribute("value", input.value);
      updateTotalPricePerProduct(btnAddQnt);
      updateTotalPrice();
      // }
    });
  });

  // Handle quantity decrease
  btnSubQnts.forEach((btnSubQnt) => {
    btnSubQnt.addEventListener("click", (e) => {
      e.preventDefault();
      const input = btnSubQnt.nextElementSibling;
      // if (+input.value > 1) {
      input.value = +input.value - 1;
      input.setAttribute("value", input.value);
      updateTotalPricePerProduct(btnSubQnt);
      updateTotalPrice();
      //}
    });
  });

  // Handle quantity updates via AJAX
  $(document).on("click", ".btn-add-qty, .btn-substract-qty", function (e) {
    e.preventDefault();
    const parentEle = $(this).closest(".cart-item")[0];
    const productId = parentEle.querySelector(".checkbox-add-cart").value;
    const amount = parentEle.querySelector(".qty-cart").value;
    updateAmount(productId, amount);
  });

  // Update quantity in cart via AJAX
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

  // Initial updates
  updateTotalPrice();
  countCkbSelected();
});
