<!DOCTYPE html>
<html lang="en">
  <body>
    <footer>
      <div
        style="
          width: 1270px;
          background-color: #fff;
          margin: 0px auto;
          border-radius: 20px;
        "
      >
        <div class="footer">
          <div class="leftFooter">
            <div>
              <a class="leftFooterLogo">
                <img
                  src="assets/images/logoweb.jpg"
                />
              </a>
            </div>
            <div>
              Lầu 5, 387-389 Hai Bà Trưng Quận 3 TP HCMCông Ty Cổ Phần Phát Hành
              Sách TP HCM - Boocify
            </div>
            <div>
              <a target="_blank" style="text-decoration: none">
                60 - 62 Lê Lợi, Quận 1, TP. HCM, Việt Nam
              </a>
            </div>
            <div>
              <a style="text-decoration: none; display: flex">
                Fahasa.com nhận đặt hàng trực tuyến và giao hàng tận nơi. KHÔNG
                hỗ trợ đặt mua và nhận hàng trực tiếp tại văn phòng cũng như tất
                cả Hệ Thống Fahasa trên toàn quốc.
              </a>
            </div>

            <div class="boCongThuong">
              <img
                src="https://cdn0.fahasa.com/media/wysiwyg/Logo-NCC/logo-bo-cong-thuong-da-thong-bao1.png"
              />
            </div>
            <div
              style="
                font-size: 14.5px;
                margin-bottom: 20px;
                margin-top: 10px;
                padding: 0px;
                text-align: left;
                margin-left: 10px;
              "
              class="logo"
            >
              <a title="Facebook">
                <img
                  alt="Facebook"
                  src="https://cdn0.fahasa.com/skin/frontend/ma_vanese/fahasa/images/footer/Facebook-on.png"
                />
              </a>
              <a title="Instagram">
                <img
                  alt="Instagram"
                  src="https://cdn0.fahasa.com/skin/frontend/ma_vanese/fahasa/images//footer/Insta-on.png"
                />
              </a>
              <a title="Youtube">
                <img
                  alt="Youtube"
                  src="https://cdn0.fahasa.com/skin/frontend/ma_vanese/fahasa/images//footer/Youtube-on.png"
                />
              </a>
              <a title="Tumblr">
                <img
                  alt="Tumblr"
                  src="https://cdn0.fahasa.com/skin/frontend/ma_vanese/fahasa/images//footer/tumblr-on.png"
                />
              </a>
              <a title="Twitter">
                <img
                  alt="Twitter"
                  src="https://cdn0.fahasa.com/skin/frontend/ma_vanese/fahasa/images//footer/twitter-on.png"
                />
              </a>
              <a title="Pinterest">
                <img
                  alt="Pinterest"
                  src="https://cdn0.fahasa.com/skin/frontend/ma_vanese/fahasa/images//footer/pinterest-on.png"
                />
              </a>
            </div>
            <div class="downloadLogo">
              <div>
                <a>
                  <img
                    alt="FAHASA.COM"
                    src="https://cdn0.fahasa.com/media/wysiwyg/Logo-NCC/android1.png"
                    style="max-width: 110px"
                  />
                </a>
              </div>
              <div>
                <a>
                  <img
                    alt="FAHASA.COM"
                    src="https://cdn0.fahasa.com/media/wysiwyg/Logo-NCC/appstore1.png"
                    style="max-width: 110px"
                  />
                </a>
              </div>
            </div>
          </div>
          <div class="rightFooter">
            <div class="thongtin">
              <h3>DỊCH VỤ</h3>
              <li id="gt" onclick="show1(this);">
                <a style="text-decoration: none" href="#">Điều khoản sử dụng</a>
              </li>
              <li id="dt" onclick="show1(this);">
                <a style="text-decoration: none" href="#"
                  >Chính sách sử dụng thông tin cá nhân</a
                >
              </li>
              <li id="bm" onclick="show1(this);">
                <a style="text-decoration: none" href="#"
                  >Chính sách bảo mật thanh toán</a
                >
              </li>
              <li id="gh" onclick="show1(this);">
                <a style="text-decoration: none" href="#">Giới thiệu Boocify</a>
              </li>
              <li id="bh" onclick="show1(this);">
                <a style="text-decoration: none" href="#"
                  >Hệ thống trung tâm nhà sách</a
                >
              </li>
            </div>
            <div class="thongtin">
              <h3>Hỗ trợ</h3>
              <li>Chính sách đổi trả- hoàn tiền</li>
              <li>Chính sách bảo hành - bồi hoàn</li>
              <li>Chính sách vận chuyển</li>
              <li>Chính sách khách sĩ</li>
              <li>Phương thức thanh toán và xuất HD</li>
            </div>
            <div class="thongtin">
              <h3>Tài khoản của tôi</h3>
              <li>Đăng nhập/Tạo tài khoản mới</li>
              <li>Thay đổi địa chỉ khách hàng</li>
              <li>Chi tiết tài khoản</li>
              <li>lịch sử mua hàng</li>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </body>
  <script>
    var searchInput = document.getElementById("searchInput");
    var notification = document.querySelector(".notification"); // Use querySelector instead of getElementById

    // Add focus event to search input
    searchInput.addEventListener("focus", function () {
      notification.style.display = "flex";

      // Close notification when clicking outside after a delay
      setTimeout(function () {
        document.addEventListener("click", clickOutsideHandler);
      }, 0);
    });

    // Function to handle click outside
    function clickOutsideHandler(event) {
      var isClickInside =
        notification.contains(event.target) ||
        searchInput.contains(event.target);
      if (!isClickInside && event.target !== searchInput) {
        // Kiểm tra xem không phải click vào trường tìm kiếm
        notification.style.display = "none";
        document.removeEventListener("click", clickOutsideHandler);
      }
    }

    // Hàm để hiện phần thông tin của tôi
    function showNotification() {
      document.getElementById("overlay").style.display = "block";
      document.getElementById("notificationBox").style.display = "block";
    }

    function hideNotification() {
      document.getElementById("overlay").style.display = "none";
      document.getElementById("notificationBox").style.display = "none";
    }
    function saveInfo() {
      var newName = document.getElementById("newName").value;
      var newPhone = document.getElementById("newPhone").value;
      var newAddress = document.getElementById("newAddress").value;

      document.getElementById("name").innerText = newName;
      document.getElementById("phone").innerText = newPhone;
      document.getElementById("address").innerText = newAddress;

      hideNotification();
    }
  </script>
</html>
