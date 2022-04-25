<div class="container-fluid">
  <!-- <style>
    .select2-container {
      z-index: 100000;
    }
  </style> -->
  <!-- start page title -->
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?= base_url('index.php/dashboard') ?>">Dashboard</a></li>
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
                  <th>Nama Perusahaan</th>
                  <th>E-mail</th>
                  <th>No. Telp.</th>
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
          <input type="hidden" id="tipe" value="1">
          <input type="hidden" id="id_user" value="">
          <div class="col-md-4">
            <label for="nama" class="form-label">Nama Perusahaan</label>
            <input type="text" class="form-control" id="nama" placeholder="Nama Perusahaan" required>
          </div>
          <div class="col-md-4">
            <label for="no_telp" class="form-label">No. Telp / Hp</label>
            <input type="text" class="form-control" id="no_telp" placeholder="No. Telp. / Hp." required>
          </div>
          <div class="col-md-4">
            <label for="email" class="form-label">E-Mail Perusahaan</label>
            <input type="email" class="form-control" id="email" placeholder="E-Mail Perusahaan" required>
          </div>
          <div class="col-md-12">
            <label for="alamat" class="form-label">Alamat</label>
            <div class="row">
              <div class="col-md-3 mb-3">
                <select id="prov" class="form-control select2" style="width: 100%;" required>
                  <option value="" selected disabled>Pilih Provinsi</option>
                </select>
              </div>
              <div class="col-md-3 mb-3">
                <select id="kab" class="form-control select2" style="width: 100%;" required>
                  <option value="" selected disabled>Pilih Kabupaten</option>
                </select>
              </div>
              <div class="col-md-3 mb-3">
                <select id="kec" class="form-control select2" style="width: 100%;" required>
                  <option value="" selected disabled>Pilih Kecamatan</option>
                </select>
              </div>
              <div class="col-md-3 mb-3">
                <select id="kel" class="form-control select2" style="width: 100%;" required>
                  <option value="" selected disabled>Pilih Kelurahan</option>
                </select>
              </div>
            </div>
            <textarea id="alamat" class="form-control" rows="3" placeholder="Detail Alamat" required></textarea>
          </div>
          <div class="col-md-6">
            <label for="username" class="form-label">Username</label>
            <div class="input-group has-validation">
              <span class="input-group-text" id="inputGroupPrepend">@</span>
              <input type="text" class="form-control" id="username" aria-describedby="inputGroupPrepend" required>
            </div>
          </div>
          <div class="col-md-6">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" required>
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
    var url = "<?= base_url('index.php/users/get_perusahaan') ?>"
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
    var no_telp = $("#no_telp").val()
    var email = $("#email").val()
    var alamat = $("#alamat").val()
    var prov = $("#prov").val()
    var kab = $("#kab").val()
    var kec = $("#kec").val()
    var kel = $("#kel").val()
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
        no_telp: no_telp,
        email: email,
        alamat: alamat,
        prov: prov,
        kab: kab,
        kec: kec,
        kel: kel,
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

  function getUser(id) {
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
        console.log(data)
        $("#id_user").val(data.data.id_user)
        $("#nama").val(data.data.nama)
        $("#no_telp").val(data.data.no_telp)
        $("#email").val(data.data.email)
        $("#alamat").html(data.data.alamat)
        $('#prov').select2().val(data.data.prov).trigger('change')
        // console.log($('#prov').val())
        // $("#prov").val(data.data.prov).change()
        getKab(function(val) {
          $('#kab').html(val);
          $("#kab").val(data.data.kab)
          $(`#kab[value=${data.data.kab}]`).prop('selected', true)
          // $('#kab').select2().val(data.data.kab).trigger('change')
          // $("#kab").val(data.data.kab).change();
          getKec(function(val) {
            $('#kec').html(val);
            // $('#kec').select2().val(data.data.kec).trigger('change')
            $("#kec").val(data.data.kec)
            $(`#kec[value=${data.data.kec}]`).prop('selected', true)

            // $("#kec").val(data.data.kec).change();
            getKel(function(val) {
              $("#kel").html(val);
              // $('#kel').select2().val(data.data.kel).trigger('change')
              $("#kel").val(data.data.kel)
              $(`#kel[value=${data.data.kel}]`).prop('selected', true)

              // $("#kel").val(data.data.kel).change();
            }, $("#kec").val())
          }, $("#kab").val())
        }, $('#prov').val())

        $("#username").val(data.data.username)
        $("#username").attr('readonly', true)
      }
    })
  }

  var modal_akun = document.getElementById('modal-akun')
  modal_akun.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget
    var id = button.getAttribute('data-bs-id')
    if (id != null) {
      $('#modal-akunLabel').html('Ubah Akun')
      $('#password-text').html('Kosongkan jika tidak ingin mengubah password!')
      getUser(id);
    } else {
      $('#modal-akunLabel').html('Tambah Akun')
      $('#password-text').html('')
      $("#id_user").val("")
      $("#nama").val("")
      $("#no_telp").val("")
      $("#email").val("")
      $("#alamat").html("")
      $("#prov").val("").change()
      $("#username").val("")
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

  getProv(function(data) {
    $('#prov').html(data);
  })

  $('#prov').change(function(e) {
    e.preventDefault();
    getKab(function(data) {
      $('#kab').html(data)
    }, $(this).val())
    getKec(function(data) {
      $('#kec').html(data)
    }, $(this).val())
    getKel(function(data) {
      $('#kel').html(data)
    }, $(this).val())
  })

  $('#kab').change(function(e) {
    e.preventDefault();
    getKec(function(data) {
      $('#kec').html(data)
    }, $(this).val())
    getKel(function(data) {
      $('#kel').html(data)
    }, $(this).val())
  })

  $('#kec').change(function(e) {
    e.preventDefault();
    getKel(function(data) {
      $('#kel').html(data)
    }, $(this).val())
  })

  function getProv(handleData) {
    var base_url = "<?= base_url('index.php/') ?>"
    $.ajax({
      type: "POST",
      url: `${ base_url }daerah/prov`,
      success: function(data) {
        handleData(data);
      }
    })
  }

  function getKab(handleData, id) {
    var base_url = "<?= base_url('index.php/') ?>"
    $.ajax({
      type: "POST",
      url: `${ base_url }daerah/kab/${ id }`,
      success: function(data) {
        handleData(data);
      }
    })
  }

  function getKec(handleData, id) {
    var base_url = "<?= base_url('index.php/') ?>"

    $.ajax({
      type: "POST",
      url: `${ base_url }daerah/kec/${ id }`,
      success: function(data) {
        handleData(data);
      }
    })
  }

  function getKel(handleData, id) {
    var base_url = "<?= base_url('index.php/') ?>"

    $.ajax({
      type: "POST",
      url: `${ base_url }daerah/kel/${ id }`,
      success: function(data) {
        if (data != null) {
          handleData(data)
        }
      }
    })
  }
</script>