<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php echo view('includes/meta.php') ?>
    <?php echo view('includes/css.php') ?> 
</head>

<body>
   
 <?php echo view('includes/preloader') ?> 
    <div class="page-container">       
    <?php echo view('includes/sidebar.php') ?> 
        <div class="main-content">           
            <?php echo view('includes/topbar.php') ?>           
            <?php echo view('includes/breadcrumbs.php') ?> 
                <div class="main-content-inner">
                     <?php echo view('admin/users/sections/users_table') ?> 
                </div>
        </div>
    </div>     
<?php echo view('includes/scripts.php') ?>   
<script type="text/javascript">

    var users_table = $('#users_table').DataTable({
   scrollX: true,
   "dom": "<'row '<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
   buttons: [{
      extend: 'excel',
      text: 'Excel',
      className: 'btn btn-default ',
   }, ],
   "ajax": {
      "url": base_url + 'api/get-active-user',
      "type": "POST",
      "dataSrc": "",
   },
   'columns': [{
      data: null,
      render: function (data, type, row) {
         return '<a href="javascript:;"   data-id="' + data['user_id'] + '"  style="color: #000;" class="table-font-size "  >' + data['name'] + '</a>';
      }
   }, {
      data: null,
      render: function (data, type, row) {
         return '<a href="javascript:;"   data-id="' + data['user_id'] + '"  style="color: #000;" class="table-font-size "  >' + data['username'] + '</a>';
      }
   }, {
      data: null,
      render: function (data, type, row) {
         return '<a href="javascript:;"   data-id="' + data['user_id'] + '"  style="color: #000;" class="table-font-size  "  >' + data['user_type'] + '</a>';
      }
   }, {
      data: null,
      render: function (data, type, row) {
         return row.action;
      }
   }, ]
});
var inactiveusers_table = $('#inactiveusers_table').DataTable({
   scrollX: true,
   "ajax": {
      "url": base_url + 'api/get-inactive-user',
      "type": "POST",
      "dataSrc": "",
   },
   'columns': [{
      data: null,
      render: function (data, type, row) {
         return '<a href="javascript:;"   data-id="' + data['user_id'] + '"  style="color: #000;" class="table-font-size "  >' + data['name'] + '</a>';
      }
   }, {
      data: null,
      render: function (data, type, row) {
         return '<a href="javascript:;"   data-id="' + data['user_id'] + '"  style="color: #000;" class="table-font-size "  >' + data['username'] + '</a>';
      }
   }, {
      data: null,
      render: function (data, type, row) {
         return '<a href="javascript:;"   data-id="' + data['user_id'] + '"  style="color: #000;" class="table-font-size "  >' + data['user_type'] + '</a>';
      }
   }, {
      data: null,
      render: function (data, type, row) {
         return row.action;
      }
   }, ]
});
$('#add_user_form').on('submit', function (e) {
   e.preventDefault();
   const password = $('input[name=password]').val();
   const confirm_password = $('input[name=confirm_password]').val();
   const username = $('input[name=username]').val();
   if (username.length < 5) {
      Swal.fire({
         text: "Username must least 5 characters",
         icon: "error",
         buttonsStyling: false,
         confirmButtonText: "Ok, got it!",
         customClass: {
            confirmButton: "btn btn-primary"
         }
      });
   } else if (password != confirm_password) {
      Swal.fire({
         text: "Password Don't Match",
         icon: "error",
         buttonsStyling: false,
         confirmButtonText: "Ok, got it!",
         customClass: {
            confirmButton: "btn btn-primary"
         }
      });
   } else if (confirm_password.length < 6) {
      Swal.fire({
         text: "Password must least 6 characters",
         icon: "error",
         buttonsStyling: false,
         confirmButtonText: "Ok, got it!",
         customClass: {
            confirmButton: "btn btn-primary"
         }
      });
   } else {
      $.ajax({
         type: "POST",
         url: base_url + 'api/add-user',
         data: new FormData(this),
         contentType: false,
         cache: false,
         processData: false,
         dataType: 'json',
         beforeSend: function () {
            $('.btn-add-user').text('Please wait...');
            $('.btn-add-user').attr('disabled', 'disabled');
         },
         success: function (data) {
            if (data.response) {
               $('#add_user_form')[0].reset();
               $('.btn-add-user').text('Submit');
               $('.btn-add-user').removeAttr('disabled');
               $('.alert').html(' <div class="alert-dismiss mt-2">\ <div class="alert alert-success alert-dismissible fade show" role="alert">\ <strong>' + data.message + '.\ <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span class="fa fa-times"></span>\ </button>\ </div>\ </div>');
               setTimeout(function () {
                  $('.alert').html('')
               }, 3000);
               users_table.ajax.reload();
               inactiveusers_table.ajax.reload();
            } else {
               $('.btn-add-user').text('Submit');
               $('.btn-add-user').removeAttr('disabled');
               $('.alert').html(' <div class="alert-dismiss mt-2">\ <div class="alert alert-warning alert-dismissible fade show" role="alert">\ <strong>' + data.message + '.\ <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span class="fa fa-times"></span>\ </button>\ </div>\ </div>');
            }
         },
         error: function (xhr) {
            alert("Error occured.please try again");
            $('.btn-add-user').text('Submit');
            $('.btn-add-user').removeAttr('disabled');
         },
      });
   }
});
$(document).on('click', 'a#remove-user', function (e) {
   var id = $(this).data('id');
   var name = $(this).data('name');
   Swal.fire({
      title: "",
      text: "Delete " + name,
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes",
      cancelButtonText: "No, cancel!",
      reverseButtons: true
   }).then(function (result) {
      if (result.value) {
         $.ajax({
            type: "POST",
            url: base_url + 'api/delete-user',
            data: {
               id: id,
               status: status
            },
            cache: false,
            dataType: 'json',
            beforeSend: function () {
               Swal.fire({
                  title: "",
                  text: "Please Wait",
                  icon: "",
                  showCancelButton: false,
                  showConfirmButton: false,
                  reverseButtons: false,
                  allowOutsideClick: false
               })
            },
            success: function (data) {
               if (data.response) {
                  Swal.fire("", "Success", "success")
               }
               users_table.ajax.reload();
               inactiveusers_table.ajax.reload()
            }
         })
      } else if (result.dismiss === "cancel") {
         swal.close()
      }
   });
});
$(document).on('click', 'a#delete-user', function (e) {
   var id = $(this).data('id');
   var status = $(this).data('set');
   Swal.fire({
      title: "",
      text: "Set this user to inactive",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes",
      cancelButtonText: "No, cancel!",
      reverseButtons: true
   }).then(function (result) {
      if (result.value) {
         $.ajax({
            type: "POST",
            url: base_url + 'api/update-user-status',
            data: {
               id: id,
               status: status
            },
            cache: false,
            dataType: 'json',
            beforeSend: function () {
               Swal.fire({
                  title: "",
                  text: "Please Wait",
                  icon: "",
                  showCancelButton: false,
                  showConfirmButton: false,
                  reverseButtons: false,
                  allowOutsideClick: false
               });
            },
            success: function (data) {
               if (data.response) {
                  Swal.fire("", "Success", "success");
               }
               users_table.ajax.reload();
               inactiveusers_table.ajax.reload()
            }
         })
      } else if (result.dismiss === "cancel") {
         swal.close()
      }
   });
});
$(document).on('click', 'a#active-user', function (e) {
   var id = $(this).data('id');
   var status = $(this).data('set');
   Swal.fire({
      title: "",
      text: "Set this user to Active",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes",
      cancelButtonText: "No, cancel!",
      reverseButtons: true
   }).then(function (result) {
      if (result.value) {
         $.ajax({
            type: "POST",
            url: base_url + 'api/update-user-status',
            data: {
               id: id,
               status: status
            },
            cache: false,
            dataType: 'json',
            beforeSend: function () {
               Swal.fire({
                  title: "",
                  text: "Please Wait",
                  icon: "",
                  showCancelButton: false,
                  showConfirmButton: false,
                  reverseButtons: false,
                  allowOutsideClick: false
               });
            },
            success: function (data) {
               if (data.response) {
                  Swal.fire("", "Success", "success");
               }
               users_table.ajax.reload();
               inactiveusers_table.ajax.reload()
            }
         })
      } else if (result.dismiss === "cancel") {
         swal.close()
      }
   });
});



$(document).on('click', 'a#set-default-password', function (e) {
   var id = $(this).data('id');
   var status = $(this).data('set');
   Swal.fire({
      title: "Forgot Password?",
      text: "Set Username as Password",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Set",
      cancelButtonText: "No, cancel!",
      reverseButtons: true
   }).then(function (result) {
      if (result.value) {
         $.ajax({
            type: "POST",
            url: base_url + 'api/reset-user-password',
            data: {
               id: id,
               status: status
            },
            cache: false,
            dataType: 'json',
            beforeSend: function () {
               Swal.fire({
                  title: "",
                  text: "Please Wait",
                  icon: "",
                  showCancelButton: false,
                  showConfirmButton: false,
                  reverseButtons: false,
                  allowOutsideClick: false
               });
            },
            success: function (data) {
               if (data.response) {
                  Swal.fire("", "Success", "success");
               }
               users_table.ajax.reload();
               inactiveusers_table.ajax.reload()
            }
         })
      } else if (result.dismiss === "cancel") {
         swal.close()
      }
   });
});


</script>
</body>
</html>
