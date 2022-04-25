<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <title>Log In | SINOTRA</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="Aplikasi Notifikasi Tracking Balai K3 Medan" name="description" />
  <meta content="andrisatriawan" name="author" />
  <!-- App favicon -->
  <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo-header.png">

  <!-- App css -->
  <link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
  <link href="<?= base_url() ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
  <link href="<?= base_url() ?>assets/vendor/sweetalert/sweetalert.css" rel="stylesheet">

</head>

<body class="loading authentication-bg" data-layout-config='{"darkMode":false}'>
  <div class="account-pages min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xxl-4 col-lg-5 d-flex flex-column align-items-center justify-content-center">
          <div class="text-center mb-3">
            <a href="<?= base_url() ?>" class="text-primary d-flex align-items-center w-auto">
              <img src="<?= base_url() ?>assets/images/logo-header.png" alt="logo" style="max-height: 35px;">
              <span class="fw-bold" style="font-size: 35px;margin-left: 10px;"> SINOTRA</span>
            </a>
          </div>
          <div class="card">
            <div class="card-header py-3 text-center text-white bg-primary">
              <h5 class="h4 mb-1">Sistem Notifikasi Tracking Hasil Pengujian</h5>
              <h5 class="h4 my-0">Balai K3 Medan</h5>
            </div>

            <div class="card-body px-4 pt-2 py-4">

              <div class="text-center w-75 m-auto">
                <h4 class="text-dark-50 text-center pb-2 fw-bold">Log In</h4>
              </div>

              <form class="needs-validation" novalidate>

                <div class="mb-3">
                  <label for="username" class="form-label">Username atau Email</label>
                  <input class="form-control" type="text" id="username" placeholder="Masukkan Username atau Email" required>
                </div>

                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control" placeholder="Masukkan password" required>
                    <div class="input-group-text" data-password="false">
                      <span class="password-eye"></span>
                    </div>
                  </div>
                </div>

                <div class="mb-3 mb-0 text-center">
                  <button class="btn btn-primary" type="button" id="btn-login"> Log In </button>
                </div>

              </form>
            </div> <!-- end card-body -->
          </div>
          <!-- end card -->
          <footer class="footer footer-alt" style="position: relative;">
            2022 &copy; Balai K3 Medan - SINOTRA
          </footer>
        </div> <!-- end col -->
      </div>
      <!-- end row -->
    </div>
    <!-- end container -->
  </div>
  <!-- end page -->


  <!-- bundle -->
  <script src="<?= base_url() ?>assets/js/vendor.min.js"></script>
  <script src="<?= base_url() ?>assets/js/app.min.js"></script>
  <script src="<?= base_url() ?>assets/vendor/sweetalert/sweetalert.min.js"></script>

  <script>
    function login() {
      var username = $('#username').val();
      var password = $('#password').val();
      var url = "<?= base_url('index.php/auth/do_login') ?>";
      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: url,
        data: {
          username: username,
          password: password
        },
        success: function(data) {
          sweetAlert(data.data.header, data.data.body, data.data.status, {
            button: null
          });
          if (data.status != 200) {
            $('#btn-login').removeAttr('disabled');
          } else {
            setTimeout(function() {
              window.location.href = "<?= base_url('index.php/dashboard') ?>"
            }, 1000)
          }
        }
      })
    }
    (function() {
      'use strict'
      var forms = document.querySelectorAll('.needs-validation')
      var btn_login = document.getElementById('btn-login')

      // Loop over them and prevent submission
      Array.prototype.slice.call(forms)
        .forEach(function(form) {
          btn_login.addEventListener('click', function(event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            } else {
              $('#btn-login').attr('disabled', true);
              login()
            }

            form.classList.add('was-validated')
          }, false)
        })
    })();
  </script>

</body>

<!-- Mirrored from coderthemes.com/hyper/saas/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 22 Apr 2022 16:20:09 GMT -->

</html>