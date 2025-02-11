<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Trang chủ</title>
    <link rel="stylesheet" href="../css/fonts/fonts.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../assets/fontawesome-free-6.5.1-web/css/all.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../css/admin/home.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../css/admin/product.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/filter.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
    <div class="content">
        <div class="card">
            <div class="icon">
                <i class="fa-solid fa-chart-simple"></i>
            </div>
            <div class="card-content">
                <p>
                    <span class="number thunhap">$1.000</span>
                </p>
                <h2>Thu nhập</h2>
            </div>
        </div>

        <div class="card">
            <div class="icon">
                <i class="fa-brands fa-dochub"></i>
            </div>
            <div class="card-content">
                <p>
                    <span class="number donhang">300</span>
                </p>
                <h2>Đơn hàng</h2>
            </div>
        </div>

        <div class="card">
            <div class="icon">
                <i class="fa-solid fa-tag"></i>
            </div>
            <div class="card-content">
                <p>
                    <span class="number sanpham">5</span>
                </p>
                <h2>Sản phẩm</h2>
            </div>
        </div>

        <div class="card">
            <div class="icon">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="card-content">
                <p>
                    <span class="number thanhvien">8</span>
                </p>
                <h2>Thành viên</h2>
            </div>
        </div>
    </div>
    <div class="thongkechitiet__container hidden">
        <h1>Thống kê</h1>
        <div class="content-container">
            <div class="date">
                <h3>Từ ngày:</h3>
                <input type="date" id="startdate" name="startdate" value="<?php date_default_timezone_set('Asia/Ho_Chi_Minh');
                                                                            echo date('Y-m-01', time()); ?>">

            </div>
            <div class="date">
                <h3>Đến ngày:</h3>
                <input type="date" id="enddate" name="enddate" value="<?php date_default_timezone_set('Asia/Ho_Chi_Minh');
                                                                        echo date('Y-m-d', time()); ?>">

            </div>
            <button type="button" id="filter">Lọc</button>
        </div>
        <div class="content-container" id="thongke-container">


        </div>
    </div>
    <div class="result" style="width: 95%;"></div>
    <div class="popup">
        <div class="popupwrapper" id="chitiet">
            <div class="popupbg">
                <div class="popupmenu" id="chitiet-table">
                    <div id="title">
                        <span>Thể loại</span>
                        <button type="button" id="close"><i class="fa-solid fa-xmark"></i></button>
 
                    </div>
                    <div class="table-container">
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>