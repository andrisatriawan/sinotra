<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?= base_url('index.php/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Users</li>
            <li class="breadcrumb-item active"><?= $page ?></li>
          </ol>
        </div>
        <h4 class="page-title"><?= $page ?></h4>
      </div>
    </div>
  </div>

  <div class="row mb-2">
    <div class="col-sm-4">
      <a href="" class="btn btn-danger rounded-pill mb-3" data-bs-toggle="modal" data-bs-target="#modal-akun"><i class="mdi mdi-plus"></i> Tambah Akun</a>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">

          <div class="table-responsive">
            <table id="table-akun" class="table dt-responsive wrap-text w-100">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Jabatan</th>
                  <th>Level Admin</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="data-akun">
              </tbody>
            </table>
          </div>
        </div> <!-- end card-body-->
      </div> <!-- end card-->
    </div> <!-- end col -->
  </div> <!-- end row -->

</div>

<!-- Modal Akun -->
<div class="modal fade" id="modal-akun" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal-akunLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-akunLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3 needs-validation" id="form-akun" novalidate>
          <input type="hidden" id="tipe" value="0">
          <input type="hidden" id="id_user" value="">
          <div class="col-md-4">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" placeholder="Nama" required>
          </div>
          <div class="col-md-4">
            <label for="jabatan" class="form-label">Jabatan</label>
            <input type="text" class="form-control" id="jabatan" placeholder="Jabatan" required>
          </div>
          <div class="col-md-4">
            <label for="email" class="form-label">E-Mail</label>
            <input type="email" class="form-control" id="email" placeholder="E-Mail">
            <small class="small text-danger" id="email-text"></small>
          </div>
          <div class="col-md-4">
            <label for="level" class="form-label">Level</label>
            <select id="level" class="form-control select2" style="width: 100%;" required>
              <option value="" selected disabled>Pilih salah satu...</option>
              <option value="1">Admin Persuratan</option>
              <option value="2">Bendahara PNBP</option>
              <option value="5">Manager Teknis ISO 17025</option>
              <option value="3">Admin LHU</option>
              <option value="6">Arsip Laporan Pengujian</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="username" class="form-label">Username</label>
            <div class="input-group has-validation">
              <span class="input-group-text" id="inputGroupPrepend">@</span>
              <input type="text" class="form-control" id="username" aria-describedby="inputGroupPrepend" required>
            </div>
          </div>
          <div class="col-md-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password">
            <small class="small text-danger" id="password-text">Kosongkan jika tidak ingin mengubah password!</small>
          </div>
          <div class="mb-0 text-center">
            <button class="btn btn-primary text-white" type="button" id="btn-simpan"><i class="mdi mdi-content-save"></i> Simpan </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function tampil() {
    var url = "<?= base_url('index.php/users/get_admin') ?>"
    $.ajax({
      type: "POST",
      dataType: "HTML",
      url: url,
      success: function(data) {
        $('#table-akun').DataTable().destroy()
        $('#data-akun').html("")
        $('#data-akun').html(data)
        $('#table-akun').DataTable()
      }
    })
  }

  tampil()

  $('.select2').select2({
    dropdownParent: $('#modal-akun')
  })

  function simpan() {
    var url = "<?= base_url('index.php/users/save') ?>"
    var tipe = $("#tipe").val()
    var id_user = $("#id_user").val()
    var nama = $("#nama").val()
    var jabatan = $("#jabatan").val()
    var email = $("#email").val()
    var level = $("#level").val()
    var username = $("#username").val()
    var password = $("#password").val()

    $.ajax({
      type: "POST",
      dataType: "JSON",
      url: url,
      data: {
        tipe: tipe,
        id_user: id_user,
        nama: nama,
        jabatan: jabatan,
        level: level,
        email: email,
        username: username,
        password: password
      },
      success: function(data) {
        sweetAlert(data.data.header, data.data.body, data.data.status, {
          button: null
        });
        if (data.status == 200) {
          tampil();
          $("#modal-akun").modal('hide');
        } else {
          $('#btn-simpan').removeAttr('disabled');
        }
      }
    })
  }

  function getUser(handleData, id) {
    var url = "<?= base_url('index.php/users/getUser') ?>"
    var tipe = $("#tipe").val()
    $.ajax({
      type: "POST",
      dataType: "JSON",
      url: url,
      data: {
        id_user: id,
        tipe: tipe
      },
      success: function(data) {
        handleData(data)
      }
    })
  }

  var modal_akun = document.getElementById('modal-akun')
  modal_akun.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget
    var id = button.getAttribute('data-bs-id')
    $('#btn-simpan').removeAttr('disabled');
    $(".was-validated").removeClass('was-validated')

    if (id != null) {
      $('#modal-akunLabel').html('Ubah Akun')
      $('#email-text').html('Email default nama@sinotra')
      $('#password-text').html('Kosongkan jika tidak ingin mengubah password!')
      //   $("#password").removeAttr('required')
      $("#password").val("")
      getUser(function(data) {
        $("#id_user").val(data.data.id_user)
        $("#nama").val(data.data.nama)
        $("#jabatan").val(data.data.jabatan)
        $("#email").val(data.data.email)
        $("#level").val(data.data.level).change()

        $("#username").val(data.data.username)
        $("#username").attr('readonly', true)
      }, id);
    } else {
      $('#modal-akunLabel').html('Tambah Akun')
      $('#password-text').html('Password default : 123456')
      $('#email-text').html('Email default nama@sinotra')
      //   $('#password').attr('required', true)
      $("#id_user").val("")
      $("#nama").val("")
      $("#jabatan").val("")
      $("#email").val("")
      $("#level").val("").change()
      $("#username").val("")
      $("#password").val("")
      $("#username").removeAttr('readonly')
    }
  });

  (function() {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    var btn_simpan = document.getElementById('btn-simpan')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
      .forEach(function(form) {
        btn_simpan.addEventListener('click', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          } else {
            $('#btn-simpan').attr('disabled', true);
            simpan()
          }

          form.classList.add('was-validated')
        }, false)
      })
  })();
</script>