<?
    session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Form</title>
    <link rel="stylesheet" href="css/fonts/fonts.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/checkout/checkout.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/fontawesome-free-6.5.1-web/css/all.min.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="form-container">
        <form id="checkout-form" action="" method="post">
            <div class="container">
                <?php

                ?>
                <h3>Địa Chỉ Nhận Hàng</h3>
                <!-- <hr> -->
                <div class="form-group" style="flex-direction: column;">
                    <span id="diachi"></span>
                    <div class="popup">
                        <button type="button" class="popupbutton">Thay đổi</button>
                        <div class="popupwrapper" id="diachiMenu">
                            <div class="popupbg"></div>
                            <div class="popupmenu" id="addressMenu">
                                <div>
                                    <h3>Địa Chỉ Của Tôi</h3>
                                    <hr>
                                </div>

                                <div class="address-container">
                                    <!-- Render code -->
                                </div>
                                <div style="display: flex;flex-direction:row-reverse;">
                                    <button type="button" class="confirm" id="confirm-popup">Xác Nhận</button>
                                    <button type="button" class="cancel" id="cancel-popup">Hủy</button>
                                </div>
                            </div>
                            <div class="popupmenu" id="changeAddressMenu">
                                <div>
                                    <h3>Cập nhật địa chỉ</h3>
                                    <hr>
                                </div>
                                <div>
                                    <div class="update-container">
                                        <input type="hidden" name="update-user_info_id" class="update-user_info_id" value="">
                                        <fieldset style="flex:0 0 47%;">
                                            <legend>Họ và tên</legend>
                                            <input type="text" class="update-fullname">
                                        </fieldset>
                                        <fieldset style="flex:0 0 47%;margin-left:auto;">
                                            <legend>Số điện thoại</legend>
                                            <input type="text" class="update-phoneNumber">
                                        </fieldset>
                                        <fieldset class="fieldselect fieldselect-100" style="margin:0 auto 15px 0;">
                                            <legend>Tỉnh/Thành phố</legend>
                                            <select id="tinhthanh" name="tinhthanh">
                                            </select>
                                        </fieldset>
                                        <fieldset class="fieldselect" style="flex:0 0 47%;">
                                            <legend>Quận/huyện</legend>
                                            <select id="quanhuyen" name="quanhuyen" >
                                            </select>
                                        </fieldset>
                                        <fieldset class="fieldselect" style="flex:0 0 47%;margin-left:auto;margin-right: 0">
                                            <legend>Phường/xã</legend>
                                            <select id="phuongxa" name="phuongxa" >
                                            </select>
                                        </fieldset>
                                        <fieldset style="flex:0 0 100% ;margin-top:10px">
                                            <legend>Số nhà và tên đường</legend>
                                            <input type="text" class="update-address">
                                        </fieldset>
                                    </div>

                                </div>
                                <div style="display: flex;flex-direction:row-reverse;padding-top:5px">
                                    <button class="confirm create_address hidden">Xác Nhận</button>
                                    <button class="confirm change_address">Xác Nhận</button>
                                    <button class="cancel change_address"> Hủy</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="container">
                <h3>PHƯƠNG THỨC THANH TOÁN</h3>
                <!-- <hr> -->
                <div class="form-group"><input id="id4" type="radio" name="id_test" value="" checked />
                    <label for="id4"><i class="fa-solid fa-dong-sign"></i>Thanh toán bằng tiền mặt khi nhận hàng</label>
                </div>
                <div class="form-group"><input id="id4" type="radio" name="id_test" value="" />
                    <label for="idfake"><i class="fa-solid fa-dong-sign"></i>Thanh toán bằng thẻ ngân hàng(giao diện ảo do gv yêu cầu)</label>
                </div>

            </div>
            <div class="container">
                <h3>MÃ KHUYẾN MÃI</h3>
                <!-- <hr> -->
                <div class="form-group d-flex">
                    <div id="promo-container">
                        <input type="promo" id="promotion" name="promotion" placeholder="Nhập mã khuyến mãi">
                        <button type="button" class="promoBtn">Áp dụng</button>
                        <button type="button" class="promoChangeBtn hide">Huỷ mã</button>
                    </div>
                    <div>
                        <p class="promo-message"></p>
                    </div>
                    <!-- <div class="popup">
                        <u style="color:#2f80ed;" onclick="popupToggle()">Chọn mã khuyến mãi</u>
                        <div class="popupwrapper" id="menuKM">
                            <div class="popupbg" onclick="popupToggle()"></div>
                            <div class="popupmenu">
                                <div class="form-group" style="margin:10px;margin-left:20px" >
                                <i class="fa-solid fa-ticket" style="color:#2f80ed;float:left;font-size:20px;margin-right:10px"></i>
                                <span style="color:black;float:left;">CHỌN MÃ KHUYẾN MÃI</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <label>&nbsp;</label>
                    <p>Có thể áp dụng đồng thời nhiều mã &nbsp;<i class="fa-solid fa-circle-exclamation"></i></p> -->
                </div>
            </div>
            <!-- make this a different php file -->
            <div class="container">
                <h3>KIỂM TRA LẠI ĐƠN HÀNG</h3>
                <!-- <hr> -->
                <div class="sanpham">
                    <span class="sanpham__info-title">Hình ảnh</span>
                    <span class="sanpham__info-bookname">Tên sản phẩm</span>
                    <div class="sanpham__info-cost">
                        <div class="book-price">
                            <span class="price">Đơn giá</span>
                            <!-- <span class="old-price">120.000 &#8363;</span> -->
                        </div>
                        <span class="soluong">Số lượng</span>
                        <span class="booktotal">Tổng tiền</span>
                    </div>
                </div>
                <hr>

                <?php
                    $totalPriceAllProducts = 0;
                    if (isset($_SESSION['cart-selected']) && $_SESSION['cart-selected']) {
                        include_once('controller/product_detail.controller.php');

                        foreach ($_SESSION['cart-selected'] as $object) {
                            $productDetail = getProductDetailById($object['productId'], false);
                            $formatPrice = number_format($productDetail['price'], 0, ',', '.').'đ';

                            $totalPrice = $productDetail['price'] * $object['amount'];
                            $totalPriceAllProducts += $totalPrice;

                            $formatTotalPrice =  number_format($totalPrice, 0, ',', '.').'đ';
                            echo '
                            <div class="sanpham">
                                <img src="'.$productDetail['image_path'].'" />
                                <span class="bookname">'.$productDetail['product_name'].'</span>
                                <div class="cost">
                                    <div class="book-price">
                                        <span class="price">'.$formatPrice.'</span>
                                    </div>
                                    <span class="soluong">'.$object['amount'].'</span>
                                    <span class="booktotal">'.$formatTotalPrice.'</span>
                                </div>
                            </div>
                            <hr>';
                        }
                    }
                ?>

                <div class="form-group">
                </div>
            </div>
            <div class="container" id="total-bottom">
                <div class="total">
                    <div class="chiphi">
                        <span class="cost-name">Thành tiền </span>
                        <?php
                            $formatTotalPriceAllProducts = number_format($totalPriceAllProducts, 0, ',', '.').'đ';
                        ?>
                        <span class="money"><?php echo $formatTotalPriceAllProducts?></span><br>
                        <input type="hidden" class="totalPriceValue" value="<?=$totalPriceAllProducts?>">
                    </div>
                    <div class="chiphi">
                        <span class="cost-name">Giảm giá</span>
                        <span class="money giam-gia">- 0 &#8363;</span><br>
                    </div>
                    <div class="chiphi">
                        <span class="cost-name">Phí vận chuyển (Giao hàng tiêu chuẩn)</span>
                        <span class="money">19.000 &#8363;</span><br>
                    </div>
                    <div class="chiphi" id="tong">
                        <span class="cost-name">Tổng Số Tiền (gồm VAT)</span>
                        <span class="money tong-tien" id="tong-tien"><?php echo $formatTotalPriceAllProducts?></span><br>
                        <input type="hidden" class="finalTotalPriceValue" value="<?=$totalPriceAllProducts?>">
                    </div>
                </div>
            </div>
            <div class="container" id="dieukhoan-bottom" style="display: flex;">
                <div class="dieukhoan">
                    <i class="fa-solid fa-square-check" style="color:#ca3f3f;font-size:20px;padding:7px;flex:0 0 5%;"></i>

                    <span style="word-wrap: break-word;flex:0 0 85%;">
                        Bằng việc tiến hành Mua hàng, Bạn đã đồng ý với
                        <span>Điều khoản & Điều kiện của Boocify.com</span>
                    </span>
                </div>
            </div>
    </div>
    <div class="form-submit">
        <div class="total" id="total-fixed">
            <div class="chiphi">
                <span class="cost-name">Thành tiền </span>
                <?php
                    $formatTotalPriceAllProducts = number_format($totalPriceAllProducts, 0, ',', '.').'đ';
                ?>
                <span class="money"><?php echo $formatTotalPriceAllProducts?></span><br>
                <input type="hidden" class="totalPriceValue" value="<?=$totalPriceAllProducts?>">
            </div>
            <div class="chiphi">
                <span class="cost-name">Giảm giá</span>
                <span class="money giam-gia">- 0 &#8363;</span><br>
            </div>
            <div class="chiphi" id="tong">
                <span class="cost-name">Tổng Số Tiền (gồm VAT)</span>
                <span class="money tong-tien" id="tong-tien"><?php echo $formatTotalPriceAllProducts?></span><br>
                <input type="hidden" class="finalTotalPriceValue" value="<?=$totalPriceAllProducts?>">
            </div>
        </div>
        <hr style=" width: 100%;flex-shrink: 0;margin-bottom:5px;" id="line-fixed">
        <div class="dieukhoan" id="dieukhoan-fixed">
            <i class="fa-solid fa-square-check" style="color:#ca3f3f;font-size:20px;padding:7px"></i>
            <span>
                <span>Bằng việc tiến hành Mua hàng, Bạn đã đồng ý với</span> <br>
                <a href="#" style="cursor: unset;">Điều khoản & Điều kiện của Boocify.com</a>
            </span>
        </div>
        <button type="submit" class="xacNhanThanhToan">Xác nhận thanh toán</button>
    </div>
    </form>
    </div>
    <div class="modalNotEnoughQuantity-container hide">
        <div class="modalNotEnoughQuantity">
        <h2>Danh sách sản phẩm bạn cần mua quá số lượng tồn kho</h2>
        <div class="table-container">
            <table border="1">
                <thead>
                    <td style="width: 70%;">Sản phẩm</td>
                    <td>Số lượng tồn</td>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <a href="index.php?page=cart">Đến giỏ hàng để thay đổi số lượng</a>
        </div>
    </div>
    <script src="js/checkout.js?v=<?php echo time(); ?>"></script>
</body>

</html>
