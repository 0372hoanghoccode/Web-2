var filter_form = document.querySelector('.admin__content--body__filter')

function hideNotifications() {  
  const notifications = document.querySelectorAll('.success, .failed');  
  notifications.forEach(notification => {  
      setTimeout(() => {  
          notification.style.display = 'none';  
      }, 2000);  
  });  
} 

function getFilterFromURL() {
  filter_form.querySelector('#supplierName').value = urlParams['name'] != null ? urlParams['name'] : ''
  filter_form.querySelector('#supplierId').value = urlParams['id'] != null ? urlParams['id'] : ''
  filter_form.querySelector('#statusSelect').value = urlParams['status'] != null ? urlParams['status'] : 'active'
}

function pushFilterToURL() {
  var filter = getFilterFromForm()
  var url_key = {
    supplier_name: 'name',
    supplier_id: 'id',
    supplier_status: 'status',
  }
  var url = ''
  Object.keys(filter).forEach((key) => {
    url += filter[key] != null && filter[key] != '' ? `&${url_key[key]}=${filter[key]}` : ''
  })
  return url
}

function getFilterFromForm() {
  return {
    supplier_name: filter_form.querySelector('#supplierName').value,
    supplier_id: filter_form.querySelector('#supplierId').value,
    supplier_status: filter_form.querySelector('#statusSelect').value,
  }
}
// Load the jquery
var script = document.createElement('SCRIPT')
script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js'
script.type = 'text/javascript'
document.getElementsByTagName('head')[0].appendChild(script)
var search = location.search.substring(1)
urlParams = JSON.parse('{"' + search.replace(/&/g, '","').replace(/=/g, '":"') + '"}', function (key, value) {
  return key === '' ? value : decodeURIComponent(value)
})
var number_of_item = urlParams['item']
var current_page = urlParams['pag']
var orderby = urlParams['orderby']
var order_type = urlParams['order_type']
if (current_page == null) {
  current_page = 1
}
if (number_of_item == null) {
  number_of_item = 5
}
if (orderby == null) {
  orderby = ''
}
if (order_type != 'ASC' && order_type != 'DESC') {
  order_type = 'ASC'
}

function checkReady() {
  return new Promise(async function (resolve) {
    while (!window.jQuery) {
      await new Promise((resolve) => setTimeout(resolve, 20))
    }
    resolve()
  })
}
async function loadForFirstTime() {
  await checkReady()
  getFilterFromURL()
  loadItem()
  // the ajax below are for create product
}

function pagnationBtn() {
  // pagnation
  document.querySelectorAll('.pag').forEach((btn) =>
    btn.addEventListener('click', function () {
      current_page = btn.innerHTML
      loadItem()
    })
  )
  if (document.getElementsByClassName('pag-pre').length > 0)
    document.querySelector('.pag-pre').addEventListener('click', function () {
      current_page = Number(document.querySelector('span.active').innerHTML) - 1
      loadItem(number_of_item, current_page)
    })
  if (document.getElementsByClassName('pag-con').length > 0)
    document.querySelector('.pag-con').addEventListener('click', function () {
      current_page = Number(document.querySelector('span.active').innerHTML) + 1
      loadItem()
    })
}

function loadItem() {
  var filter = getFilterFromForm()
  $.ajax({
    url: '../controller/admin/pagnation.controller.php',
    type: 'post',
    dataType: 'html',
    data: {
      number_of_item: number_of_item,
      current_page: current_page,
      function: 'getRecords',
      filter: filter,
    },
  }).done(function (result) {
    if (current_page > parseInt(result)) current_page = parseInt(result)
    if (current_page < 1) current_page = 1
    $.ajax({
      url: '../controller/admin/pagnation.controller.php',
      type: 'post',
      dataType: 'html',
      data: {
        number_of_item: number_of_item,
        current_page: current_page,
        function: 'render',
        orderby: orderby,
        order_type: order_type,
        filter: filter,
      },
    }).done(function (result) {
      var newurl =
        window.location.protocol +
        '//' +
        window.location.host +
        window.location.pathname +
        '?page=' +
        urlParams['page'] +
        '&item=' +
        number_of_item +
        '&current_page=' +
        current_page
      newurl += pushFilterToURL()
      window.history.pushState(
        {
          path: newurl,
        },
        '',
        newurl
      )
      $('.result').html(result)
      pagnationBtn()
      filterBtn()
      js()
    })
  })
}
document.addEventListener('DOMContentLoaded', () => {
  loadForFirstTime()
})

function filterBtn() {
  $('.body__filter--action__filter').click((e) => {
    e.preventDefault()
    var supplierId = filter_form.querySelector('#supplierId').value.trim()
    var message = filter_form.querySelector('#message')
    var check = true
    var regex = /^\d+$/
    if (supplierId !== '' && !supplierId.match(regex)) {
      message.innerHTML = '*Mã nhà cung cấp phải là kí tự số'
      filter_form.querySelector('#supplierId').focus()
      check = false
    }

    if (check === true) {
      message.innerHTML = ''
      current_page = 1
      loadItem()
    }
  })
  $('.body__filter--action__reset').click((e) => {
    check = true
    message.innerHTML = ''
    current_page = 1
    status_value = 'active'
    $.ajax({
      url: '../controller/admin/pagnation.controller.php',
      type: 'post',
      dataType: 'html',
      data: {
        number_of_item: number_of_item,
        current_page: current_page,
        function: 'render',
        filter: {
          supplier_status: status_value,
        },
      },
    }).done(function (result) {
      var newurl =
        window.location.protocol +
        '//' +
        window.location.host +
        window.location.pathname +
        '?page=' +
        urlParams['page'] +
        '&item=' +
        number_of_item +
        '&current_page=' +
        current_page
      window.history.pushState(
        {
          path: newurl,
        },
        '',
        newurl
      )
      $('.result').html(result)
      pagnationBtn()
      js()
    })
  })
}

var js = function () {
  if (orderby != '' && order_type != '')
    document.querySelector('[data-order=' + "'" + orderby + "']").innerHTML +=
      order_type == 'ASC' ? ' <i class="fas fa-sort-up">' : ' <i class="fas fa-sort-down">'
  else
    document.querySelector('[data-order]').innerHTML +=
      order_type == 'ASC' ? ' <i class="fas fa-sort-up">' : ' <i class="fas fa-sort-down">'
  document
    .querySelector('.result')
    .querySelectorAll('th')
    .forEach((th) => {
      if (th.hasAttribute('data-order'))
        th.addEventListener('click', () => {
          if (orderby == '') orderby = document.querySelector('[data-order]').getAttribute('data-order')
          if (orderby == th.getAttribute('data-order') && order_type == 'ASC') {
            order_type = 'DESC'
          } else {
            order_type = 'ASC'
          }
          orderby = th.getAttribute('data-order')
          loadItem()
        })
    })
  const create_html = `<div class="modal-edit-product-container show" id="modal-edit-container">
<div class="modal-edit-product">
    <div class="modal-header">
        <h3>Thêm nhà cung cấp</h3>
        <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
        <form action="">

            <div class="modal-body-2">
                <div class="flex">
                    <label for="name">Tên nhà cung cấp</label>
                    <input id="namesupplier" type="text" add-index="2" placeholder="Tên nhà cung cấp">  
                    <p id ="message_name" class ="message"></p>                 
                </div>

                <div class="flex">
                    <label for="email">Email nhà cung cấp</label>
                    <input id="emailsupplier" type="text" add-index="2" placeholder="Email nhà cung cấp"> 
                    <p id ="message_email" class ="message"></p>                  
                </div>

                <div class="flex">
                    <label for="sdt">Số điện thoại nhà cung cấp</label>
                    <input id="sdtsupplier" type="text" add-index="2" placeholder="Số điện thoại nhà cung cấp">    
                    <p id ="message_sdt" class ="message"></p>               
                </div>
            </div>
            <div>
            </div>
            <input type="reset" value="Hủy" class="button-cancel">
            <input type="submit" value="Xác nhận" class="button-confirm" add-index="9">
        </form>
    </div>
</div>
</div>`

  document.querySelector('.body__filter--action__add').addEventListener('click', (e) => {
    e.preventDefault()
    modal.innerHTML = create_html
    const modal_create_container = document.querySelector('#modal-edit-container')

    modal_create_container.querySelector('.button-confirm').addEventListener('click', function (e) {
      e.preventDefault()
      const message_name = modal_create_container.querySelector('#message_name')
      const message_email = modal_create_container.querySelector('#message_email')
      const message_sdt = modal_create_container.querySelector('#message_sdt')
      let name = modal_create_container.querySelector('#namesupplier').value.trim()
      const email = modal_create_container.querySelector('#emailsupplier').value.trim()
      const sdt = modal_create_container.querySelector('#sdtsupplier').value.trim()
      const regexPhoneNumber = /^0\d{9}$/
      const regexEmail = /^[a-zA-Z0-9.-_]+@[a-zA-Z0-9.-_]+\.[a-zA-Z]{2,}$/
      let check = true

      // Kiểm tra tên nhà cung cấp
      if (name === '') {
        message_name.innerHTML = '*Vui lòng điền tên nhà cung cấp'
        modal_create_container.querySelector('#namesupplier').focus()
        check = false
      } else {
        message_name.innerHTML = ''
      }

      // Kiểm tra email
      if (email === '') {
        message_email.innerHTML = '*Vui lòng điền email'
        modal_create_container.querySelector('#emailsupplier').focus()
        check = false
      } else if (!email.match(regexEmail)) {
        message_email.innerHTML = '* - email không hợp lệ <br> - Ví dụ :(example@gmail.com)'
        modal_create_container.querySelector('#emailsupplier').focus()
        check = false
      } else {
        message_email.innerHTML = ''
      }

      // Kiểm tra số điện thoại
      if (sdt === '') {
        message_sdt.innerHTML = '*Vui lòng điền số điện thoại'
        modal_create_container.querySelector('#sdtsupplier').focus()
        check = false
      } else if (!sdt.match(regexPhoneNumber)) {
        message_sdt.innerHTML =
          "* - Số điện thoại không đúng định dạng <br> - Số điện thoại bao gồm 10 số và bắt đầu bằng '0'<br> - Ví dụ : (0331256391)"
        modal_create_container.querySelector('#namesupplier').focus()
        check = false
      } else {
        message_sdt.innerHTML = ''
      }

      // Nếu tất cả các kiểm tra qua
      if (check) {
        name = name.toUpperCase();
        console.log(name);
        // Kiểm tra tính duy nhất của email và số điện thoại cùng lúc
        $.ajax({
          url: '../controller/admin/supplier.controller.php',
          type: 'post',
          dataType: 'json',
          data: {
            function: 'checkEmailAndPhoneExists',
            email: email,
            sdt: sdt,
            name: name,
          },
        }).done(function (result) {
          if (result.emailExists) {
            message_email.innerHTML = '*Email đã tồn tại !'
            modal_create_container.querySelector('#emailsupplier').focus()
          } else if (result.phoneExists) {
            message_sdt.innerHTML = '*Số điện thoại đã tồn tại !'
            modal_create_container.querySelector('#sdtsupplier').focus()
          }else if (result.nameExists) {
            message_name.innerHTML = '*Tên đã tồn tại !'
            modal_create_container.querySelector('#sdtsupplier').focus()
          }
           else {
            // Gửi yêu cầu tạo nhà cung cấp mới nếu cả email và số điện thoại không tồn tại
            $.ajax({
              url: '../controller/admin/supplier.controller.php',
              type: 'post',
              dataType: 'html',
              data: {
                function: 'create',
                field: {
                  name: name,
                  email: email,
                  sdt: sdt,
                },
              },
            }).done(function (result) {
              loadItem()
              $('#sqlresult').html(result)
              hideNotifications();
            })
            modal_create_container.classList.add('hidden')
          }
        })
      }
    })

    document.querySelector('#btnClose').addEventListener('click', () => {
      modal_create_container.classList.add('hidden')
    })

    document.querySelector('.button-cancel').addEventListener('click', () => {
      modal_create_container.classList.add('hidden')
    }) 
  })

  const edit_html = `<div class="modal-edit-product-container show" id="modal-edit-container">
<div class="modal-edit-product">
    <div class="modal-header">
        <h3>Sửa nhà cung cấp</h3>
        <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
        <form action="">

            <div class="modal-body-2">
                <div class="flex">
                    <label for="name">Tên nhà cung cấp</label>
                    <input id="name" type="text" add-index="2" placeholder="Tên nhà cung cấp" disabled>
                    <p id ="message_name" class = "message"></p>
                </div>

                <div class="flex">
                <label for="email">Email nhà cung cấp</label>
                <input id="email" type="text" add-index="2" placeholder="Email nhà cung cấp">      
                <p id ="message_email" class = "message"></p>             
            </div>

            <div class="flex">
                <label for="sdt">Số điện thoại nhà cung cấp</label>
                <input id="sdt" type="text" add-index="2" placeholder="Email nhà cung cấp">    
                <p id ="message_sdt" class = "message"></p>               
            </div>

            
                
            </div>
            <div>
            </div>
            <input type="button" value="Hủy" class="button-cancel" id="btn-cancel">
            <input type="submit" value="Xác nhận" class="button-confirm" add-index="9">
        </form>
    </div>
</div>
</div>`

  var edit_btns = document.getElementsByClassName('actions--edit')
  for (var i = 0; i < edit_btns.length; i++) {
    edit_btns[i].addEventListener('click', function (e) {
      modal.innerHTML = edit_html
      const modal_edit_container = document.querySelector('#modal-edit-container')
      modal.querySelector('#btnClose').addEventListener('click', () => {
        modal_edit_container.classList.remove('show')
      })
      console.log(modal.querySelector('#btn-cancel'))
      modal.querySelector('#btn-cancel').addEventListener('click', () => {
        modal_edit_container.classList.remove('show')
        
      })
      var id = this.parentNode.parentNode.querySelector('.id').innerHTML
      var currentEmail = this.parentNode.parentNode.querySelector('.email').innerHTML
      var currentSdt = this.parentNode.parentNode.querySelector('.number_phone').innerHTML
      let currentName = this.parentNode.parentNode.querySelector('.name').innerHTML

      modal.querySelector('#name').value = currentName
      modal.querySelector('#email').value = currentEmail
      modal.querySelector('#sdt').value = currentSdt

      modal.querySelector('.button-confirm').addEventListener('click', function (e) {
        e.preventDefault()
        const message_name = modal_edit_container.querySelector('#message_name')
        const message_email = modal_edit_container.querySelector('#message_email')
        const message_sdt = modal_edit_container.querySelector('#message_sdt')
        let name = modal_edit_container.querySelector('#name').value.trim()
        const email = modal_edit_container.querySelector('#email').value.trim()
        const sdt = modal_edit_container.querySelector('#sdt').value.trim()
        const regexPhoneNumber = /^0\d{9}$/
        const regexEmail = /^[a-zA-Z0-9.-_]+@[a-zA-Z0-9.-_]+\.[a-zA-Z]{2,}$/
        var check = true

        // Kiểm tra tên nhà cung cấp
        if (name === '') {
          message_name.innerHTML = '*Vui lòng điền tên nhà cung cấp'
          modal_edit_container.querySelector('#name').focus()
          check = false
        } else {
          message_name.innerHTML = ''
        }

        // Kiểm tra email
        if (email === '') {
          message_email.innerHTML = '*Vui lòng điền email nhà cung cấp'
          modal_edit_container.querySelector('#email').focus()
          check = false
        } else if (!email.match(regexEmail)) {
          message_email.innerHTML = '* - email không hợp lệ <br> - Ví dụ :(example@gmail.com)'
          modal_edit_container.querySelector('#email').focus()
          check = false
        } else {
          message_email.innerHTML = ''
        }

        // Kiểm tra số điện thoại
        if (sdt === '') {
          message_sdt.innerHTML = '*Vui lòng điền số điện thoại nhà cung cấp'
          modal_edit_container.querySelector('#sdt').focus()
          check = false
        } else if (!sdt.match(regexPhoneNumber)) {
          message_sdt.innerHTML =
            "* - Số điện thoại không đúng định dạng <br> - Số điện thoại bao gồm 10 số và bắt đầu bằng '0'<br> - Ví dụ : (033125639)"
          modal_edit_container.querySelector('#sdt').focus()
          check = false
        } else {
          message_sdt.innerHTML = ''
        }

        // Nếu tất cả các kiểm tra qua
        if (check) {
          message_name.innerHTML = ''
          message_email.innerHTML = ''
          message_sdt.innerHTML = ''
          name = name.toUpperCase();
          currentName = currentName.toUpperCase();

          // Kiểm tra tính duy nhất của email và số điện thoại trước khi cập nhật
          $.ajax({
            url: '../controller/admin/supplier.controller.php',
            type: 'post',
            dataType: 'json',
            data: {
              function: 'checkEditEmailAndPhoneExists',
              email: email,
              sdt: sdt,
              name: name,
              currentEmail: currentEmail,
              currentSdt: currentSdt,
              currentName: currentName,
            },
          }).done(function (result) {
            
            if (result.emailExists) {
              message_email.innerHTML = '*Email đã tồn tại trong hệ thống'
              modal_edit_container.querySelector('#email').focus()
            } else if (result.phoneExists) {
              message_sdt.innerHTML = '*Số điện thoại đã tồn tại trong hệ thống'
              modal_edit_container.querySelector('#sdt').focus()
            } else if (result.nameExists) {
              message_name.innerHTML = '*Tên đã tồn tại trong hệ thống'
              modal_edit_container.querySelector('#name').focus()
            }
            else {
              // Gửi yêu cầu chỉnh sửa thông tin nhà cung cấp
              $.ajax({
                url: '../controller/admin/supplier.controller.php',
                type: 'post',
                dataType: 'html',
                data: {
                  function: 'edit',
                  field: {
                    id: id,
                    name: name,
                    email: email,
                    sdt: sdt,
                  },
                },
              }).done(function (result) {
                loadItem()
                $("#sqlresult").html(result);
               setTimeout(() => {
                $("#sqlresult").html(""); // Xóa nội dung sau 3 giây
            }, 3000);
                hideNotifications();  
              })
              modal_edit_container.classList.remove('show')
            }
          })
        }
      })
    })
 
  }

  // delete

  const del_btns = document.getElementsByClassName('actions--delete')

  for (var i = 0; i < del_btns.length; i++) {
    del_btns[i].addEventListener('click', function () {
      let selected_content = this.parentNode.parentNode
      let supplier_id = selected_content.querySelector('.id').innerHTML
      let supplier_name = selected_content.querySelector('.name').innerHTML

      var del_html = `
        <div class="modal-edit-product-container show" id="modal-edit-container">
        <div class="modal-edit-product">
            <div class="modal-header">
                <h3>Xác nhận xóa nhà cung cấp</h3>
                <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="del-body">
                    
                    <div class="thongtin">
                        <div><span style="font-weight: bold;">Mã nhà cung cấp :</span> <span id="supplier-delete-id">${supplier_id}</span> </div>
                        <div><span style="font-weight: bold;">Tên nhà cung cấp :</span> <span>${supplier_name}</span> </div>
                    </div>
                </div>
                <div class="del-btn-container">
                    <input type="button" value="Hủy" class="del-cancel">
                    <input type="button" value="Xác nhận" class="del-confirm">
                </div>
            </div>
        </div>
    </div>
        `

      modal.innerHTML = del_html
      $('.del-confirm').click(function (e) {
        e.preventDefault()
        var $id = $('#supplier-delete-id').html()
        $.ajax({
          url: '../controller/admin/supplier.controller.php',
          type: 'post',
          dataType: 'html',
          data: {
            function: 'delete',
            id: $id,
          },
        }).done(function (result) {
          loadItem()
          $("#sqlresult").html(result);
          setTimeout(() => {
           $("#sqlresult").html(""); // Xóa nội dung sau 3 giây
       }, 3000);
          modal_edit_container.classList.remove('show')
        })
      })

      // Button close
      const modal_edit_container = document.querySelector('#modal-edit-container')

      const btnClose = document.querySelector('#btnClose')
      // console.log(btnClose)
      btnClose.addEventListener('click', () => {
        // console.log(modal_edit_container)
        modal_edit_container.classList.remove('show')
      })
      // Button cancel
      const btnCancel = document.querySelector('.del-cancel')
      // console.log(btnClose)
      btnCancel.addEventListener('click', () => {
        // console.log(modal_edit_container)
        modal_edit_container.classList.remove('show')
      })
      hideNotifications()
    })
  }
}
