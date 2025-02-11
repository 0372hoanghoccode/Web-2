var filter_form = document.querySelector(".admin__content--body__filter");
function getFilterFromURL() {
    filter_form.querySelector("#roleName").value = (urlParams['name'] != null) ? urlParams['name'] : "";
    filter_form.querySelector("#roleId").value = (urlParams['id'] != null) ? urlParams['id'] : "";
   

}
function pushFilterToURL() {
    var filter = getFilterFromForm();
    var url_key = {
        "role_name": "name",
        "role_id": "id",
        // "role_status":"status"
    }
    var url = "";
    Object.keys(filter).forEach(key => {
        url += (filter[key] != null && filter[key] != "") ? `&${url_key[key]}=${filter[key]}` : "";
    });
    return url;
}
function getFilterFromForm() {
    return {
        "role_name": filter_form.querySelector("#roleName").value,
        "role_id": filter_form.querySelector("#roleId").value,     
        // "role_status": filter_form.querySelector("#statusSelect").value,
        
    }
}
// Load the jquery
var script = document.createElement("SCRIPT");
script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js';
script.type = 'text/javascript';
document.getElementsByTagName("head")[0].appendChild(script);
var search = location.search.substring(1);
urlParams = JSON.parse('{"' + search.replace(/&/g, '","').replace(/=/g, '":"') + '"}', function (key, value) { return key === "" ? value : decodeURIComponent(value) })
var number_of_item = urlParams["item"];
var current_page = urlParams["pag"];
var orderby = urlParams['orderby'];
var order_type = urlParams['order_type'];
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
    })
}
async function loadForFirstTime() {
    await checkReady();
    getFilterFromURL();
    loadItem();
    // the ajax below are for create product

}
function pagnationBtn() {
    // pagnation
    document.querySelectorAll('.pag').forEach((btn) => btn.addEventListener('click', function () {
        current_page = btn.innerHTML;
        loadItem();
    }));
    if (document.getElementsByClassName('pag-pre').length > 0)
        document.querySelector('.pag-pre').addEventListener('click', function () {
            current_page = Number(document.querySelector('span.active').innerHTML) - 1;
            loadItem(number_of_item, current_page);
        });
    if (document.getElementsByClassName('pag-con').length > 0)
        document.querySelector('.pag-con').addEventListener('click', function () {
            current_page = Number(document.querySelector('span.active').innerHTML) + 1;
            loadItem();
        });
}
function loadItem() {   
    var filter = getFilterFromForm();
    $.ajax({
        url: '../controller/admin/pagnation.controller.php',
        type: "post",
        dataType: 'html',
        data: {
            number_of_item: number_of_item,
            current_page: current_page,
            function: "getRecords",
            filter: filter
        }
    }).done(function (result) {
        if (current_page > parseInt(result)) current_page = parseInt(result)
        if (current_page < 1) current_page = 1;
        $.ajax({
            url: '../controller/admin/pagnation.controller.php',
            type: "post",
            dataType: 'html',
            data: {
                number_of_item: number_of_item,
                current_page: current_page,
                function: "render",
                orderby: orderby,
                order_type: order_type,
                filter: filter
            }
        }).done(function (result) {

            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?page=' + urlParams['page'] + '&item=' + number_of_item + '&current_page=' + current_page;
            newurl += pushFilterToURL();
            window.history.pushState({ path: newurl }, '', newurl);
            $('.result').html(result);
            pagnationBtn();
            filterBtn();
            js();
        })
    })
};
document.addEventListener("DOMContentLoaded", () => {
    loadForFirstTime()

});

function filterBtn() {
    $(".body__filter--action__filter").click((e) => {
        e.preventDefault();
        const id = filter_form.querySelector("#roleId").value.trim();
        var message = document.getElementById("message");
        var check = true;
        if(isNaN(id) && id !== "") {
            message.innerHTML = "Mã quyền không hợp lệ";
            filter_form.querySelector("#roleId").focus();
            check = false;
        } else if(id <= 0 && id !== "") {
            message.innerHTML = "Mã quyền không thể là số âm";
            filter_form.querySelector("#roleId").focus();
            check = false;
        } else {
            message.innerHTML = "";
        }
        if(check) {
         current_page = 1;
         loadItem();
        }
    })
    $(".body__filter--action__reset").click((e) => {
        message.innerHTML = "";
        current_page = 1;
        status_value = "active";
        $.ajax({
            url: '../controller/admin/pagnation.controller.php',
            type: "post",
            dataType: 'html',
            data: {
                number_of_item: number_of_item,
                current_page: current_page,
                function: "render",
                filter: {
                    role_status: status_value
                }
            }
        }).done(function (result) {
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?page=' + urlParams[  'page'] + '&item=' + number_of_item + '&current_page=' + current_page ;
            window.history.pushState({ path: newurl }, '', newurl);
            $('.result').html(result);
            pagnationBtn();
            js();
        })
    })

}

var js = function () {
    if (orderby != "" && order_type != "") document.querySelector("[data-order=" + "'" + orderby + "']").innerHTML+=(order_type=="ASC")?' <i class="fas fa-sort-up">':' <i class="fas fa-sort-down">';
    else document.querySelector("[data-order]").innerHTML+=(order_type=="ASC")?' <i class="fas fa-sort-up">':' <i class="fas fa-sort-down">';
    document.querySelector(".result").querySelectorAll("th").forEach((th) => {
        if (th.hasAttribute("data-order")) th.addEventListener("click", () => {
            if (orderby == "") orderby = document.querySelector("[data-order]").getAttribute("data-order");
            if (orderby == th.getAttribute("data-order") && order_type == "ASC") {
                order_type = "DESC";
            }
            else {
                order_type = "ASC"
            }
            orderby = th.getAttribute("data-order");
            loadItem();
        })
    });
    const modal = document.getElementById("modal");
    const create_html = `<div class="modal-edit-product-container show" id="modal-edit-container">
<div class="modal-edit-product">
    <div class="modal-header">
        <h3>Thêm quyền</h3>
        <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
        <form action="">

            <div class="modal-body-2">
                <div class="flex">
                    <label for="name">Tên quyền</label>
                    <input id="namerole" type="text" add-index="2" placeholder="Tên quyền">                   
                </div>
                
            </div>
            <div>
            </div>
            <input type="reset" value="Hủy" class="button-cancel">
            <input type="submit" value="Xác nhận" class="button-confirm" add-index="9">
        </form>
    </div>
</div>
</div>`;

const html = `<div class="modal-edit-product-container show" id="modal-edit-container">
<div class="modal-edit-product">
    <div class="modal-header">
        <h3>Sửa quyền</h3>
        <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
    <form id="Form " style="margin-top: 10px;">
        <div class="input-field d-flex-start">
            <input type="checkbox" name="qlsp" id="1">
            <label for="qlsp">Quản lý sản phẩm</label>
        </div>
        <div class="input-field d-flex-start">
            <input type="checkbox" name="qldh" id="2">
            <label for="qldh">Quản lý đơn hàng</label>
        </div>
        <div class="input-field d-flex-start">
            <input type="checkbox" name="qltk" id="3">
            <label for="qltk">Quản lý tài khoản</label>
        </div>
        <div class="input-field d-flex-start">
            <input type="checkbox" name="qldm" id="4">
            <label for="qldm">Quản lý danh mục</label>
        </div>
        <div class="input-field d-flex-start">
            <input type="checkbox" name="qltkbc" id="5">
            <label for="qltkbc">Quản lý thống kê và báo cáo</label>
        </div>
        <div class="input-field d-flex-start">
            <input type="checkbox" name="qlttgh" id="6">
            <label for="qlttgh">Quản lý thông tin giao hàng</label>
        </div>
        <div class="input-field d-flex-start">
            <input type="checkbox" name="qlnxb" id="7">
            <label for="qlnxb">Quản lý nhà xuất bản</label>
        </div>
        <div class="input-field d-flex-start">
            <input type="checkbox" name="qltg" id="8">
            <label for="qltg">Quản lý tác giả</label>
        </div>
        <div class="input-field d-flex-start">
            <input type="checkbox" name="qlncc" id="9">
            <label for="qlncc">Quản lý nhà cung cấp</label>
        </div>
        <div class="input-field d-flex-start">
            <input type="checkbox" name="qlpq" id="10">
            <label for="qlpq">Quản lý phân quyền</label>
        </div>
            <input type="reset" value="Hủy" class="button-cancel">
            <input type="submit" value="Xác nhận" class="button-confirm" add-index="9">
        </form>
    </div>
</div>
</div>`;

document.querySelector(".body__filter--action__add").addEventListener("click", (e) => {
    e.preventDefault();
    $.ajax({
        url: '../controller/admin/role.controller.php',
        type: "post",
        dataType: 'html',
        data: {
            function : "init",
        }
    }).done(function(arr){
        console.log(arr);
        modal.innerHTML = arr;
       const modal_create_container = document.querySelector("#modal-edit-container");
       modal.querySelector('.button-confirm').addEventListener('click', function (e) {
           e.preventDefault();
           
           const chkRole = document.querySelectorAll('.input-field input[type=checkbox]');
           let checkboxValues = {}
           chkRole.forEach(function(checkbox) {
               checkboxValues[checkbox.id] = checkbox.checked == true ? 1 : 0;
           });
           console.log(checkboxValues);
           $.ajax({
               url: '../controller/admin/role.controller.php',
               type: "post",
               dataType: 'html',
               data: {
                   function: "staff_role_update",
                   field: {                   
                       checkboxValues,              
                   }
               }
           }).done(function (result) {
               loadItem();
               $("#sqlresult").html(result);
               setTimeout(() => {
                $("#sqlresult").html(""); // Xóa nội dung sau 3 giây
            }, 3000);
              
           })
           modal_create_container.classList.add('hidden');
       });
       document.querySelector("#btnClose").addEventListener("click", () => {
       modal_create_container.classList.add('hidden');
       });
       document.querySelector(".button-cancel").addEventListener("click", () => {
       modal_create_container.classList.add('hidden');
       });
    }) 
});


const edit_html = `<div class="modal-edit-product-container show" id="modal-edit-container">
<div class="modal-edit-product">
    <div class="modal-header">
        <h3>Sửa quyền nhân viên</h3>
        <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
        <form action="">

            <div class="modal-body-2">
                <div class="flex">
                    <label for="name">Tên quyền</label>
                    <input id="name" type="text" add-index="2" placeholder="Tên quyền">
                    
                </div>
            </div>
            <div>
            </div>
            <input type="reset" value="Hủy" class="button-cancel">
            <input type="submit" value="Xác nhận" class="button-confirm" add-index="9">
        </form>
    </div>
</div>
</div>`;

var edit_btns = document.getElementsByClassName("actions--edit");
    for (var i = 0; i < edit_btns.length; i++) {
        edit_btns[i].addEventListener('click', function(e) {
            modal.innerHTML = edit_html;
            const modal_edit_container = document.querySelector("#modal-edit-container");
            modal.querySelector("#btnClose").addEventListener("click", ()=> {
                modal_edit_container.classList.remove('show');
            });
            modal.querySelector('.button-cancel').addEventListener('click', ()=> {
                modal_edit_container.classList.remove('show');
            });
            var id = this.parentNode.parentNode.querySelector(".id").innerHTML;
            modal.querySelector('#name').value = this.parentNode.parentNode.querySelector(".name").innerHTML;
        
            modal.querySelector('.button-confirm').addEventListener('click', function (e) {
                e.preventDefault();
                const name = modal.querySelector('#name').value.trim();
                if(name === "") {
                    alert("Không được để trống tên quyền");
                    return;
                }
                $.ajax({
                    url: '../controller/admin/role.controller.php',
                    type: "post",
                    dataType: 'html',
                    data: {
                        function: "edit",
                        field: { 
                            id: id,                  
                            name: modal.querySelector('#name').value,                                 
                        }
                    }
                }).done(function (result) {
                    loadItem();
                    $("#sqlresult").html(result);
                   
                })
                modal_edit_container.classList.remove('show');
            });
            
        });

    }


    // delete

    const del_btns = document.getElementsByClassName("actions--delete");

    for (var i = 0; i < del_btns.length; i++) {
        del_btns[i].addEventListener('click', function () {
            let selected_content = this.parentNode.parentNode;
            let role_id = selected_content.querySelector('.id').innerHTML;
            let role_name = selected_content.querySelector('.name').innerHTML;

            var del_html = `
        <div class="modal-edit-product-container show" id="modal-edit-container">
        <div class="modal-edit-product">
            <div class="modal-header">
                <h3>Xác nhận xóa quyền này ?</h3>
                <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="del-body">
                    
                    <div class="thongtin">
                        <div><span style="font-weight: bold;">Mã quyền :</span> <span id="role-delete-id">${role_id}</span> </div>
                        <div><span style="font-weight: bold;">Tên quyền :</span> <span>${role_name}</span> </div>
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
            $('.del-confirm').click(function (e) {
                e.preventDefault();
                var $id = $('#role-delete-id').html();
                $.ajax({
                    url: '../controller/admin/role.controller.php',
                    type: "post",
                    dataType: 'html',
                    data: {
                        function: "delete",
                        id: $id
                    }
                }).done(function (result) {
                    loadItem();
                    $("#sqlresult").html(result);
                    modal_edit_container.classList.remove('show');
                })
            })

            // Button close
            const modal_edit_container = document.querySelector("#modal-edit-container");

            const btnClose = document.querySelector("#btnClose");
            // console.log(btnClose)
            btnClose.addEventListener('click', () => {
                // console.log(modal_edit_container)
                modal_edit_container.classList.remove('show')
            });
            // Button cancel
            const btnCancel = document.querySelector(".del-cancel");
            // console.log(btnClose)
            btnCancel.addEventListener('click', () => {
                // console.log(modal_edit_container)
                modal_edit_container.classList.remove('show')
            });
        });
    }


 }  