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

  <input type="hidden" id="id_user" value="<?= $user['id_user'] ?>">

  <div class="row">
    <div class="col-xl-4 col-lg-5">
      <div class="card text-center">
        <div class="card-body">
          <img src="<?= base_url('assets/images/users/') . $user['foto'] ?>" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

          <h4 class="mb-0 mt-2"><?= $user['nama'] ?></h4>
          <p class="text-muted font-14"><?= $level[$user['level']] ?></p>

          <div class="text-start mt-3">
            <h4 class="font-13 text-uppercase">About Me :</h4>
            <p class="text-muted mb-2 font-13"><strong>Nama :</strong> <span class="ms-2"><?= $user['nama'] ?></span></p>

            <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ms-2 "><?= $user['email'] ?></span></p>
            <?php
            if ($user['level'] == 4) {
            ?>
              <p class="text-muted mb-1 font-13"><strong>Alamat :</strong> <span class="ms-2"><?= $user['alamat'] ?></span></p>
            <?php } ?>
          </div>
        </div> <!-- end card-body -->
      </div> <!-- end card -->

    </div> <!-- end col-->

    <div class="col-xl-8 col-lg-7">
      <div class="card">
        <div class="card-body">
          <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
            <li class="nav-item">
              <a href="#aboutme" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0 active">
                Profile
              </a>
            </li>
            <li class="nav-item">
              <a href="#settings" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                Password
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="aboutme">
              <?php
              if ($user['level'] != 4) {
              ?>
                <form class="row g-3 needs-validation" id="form-akun" novalidate>
                  <div class="col-md-12">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group has-validation">
                      <span class="input-group-text" id="inputGroupPrepend">@</span>
                      <input type="text" class="form-control" id="username" value="<?= $user['username'] ?>" aria-describedby="inputGroupPrepend" readonly>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" value="<?= $user['nama'] ?>" placeholder="Nama" required>
                  </div>
                  <div class="col-md-12">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    <input type="text" class="form-control" id="jabatan" value="<?= $user['jabatan'] ?>" placeholder="Jabatan" required>
                  </div>
                  <div class="col-md-12">
                    <label for="email" class="form-label">E-Mail</label>
                    <input type="email" class="form-control" id="email" value="<?= $user['email'] ?>" placeholder="E-Mail" required>
                  </div>

                  <!-- <div class="mb-0 text-center">
                    <button class="btn btn-primary text-white" type="button" id="btn-simpan"><i class="mdi mdi-content-save"></i> Simpan </button>
                  </div> -->
                </form>
              <?php } else {
              ?>
                <form class="row g-3 needs-validation" id="form-akun" novalidate>
                  <div class="col-md-12">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group has-validation">
                      <span class="input-group-text" id="inputGroupPrepend">@</span>
                      <input type="text" class="form-control" id="username" value="<?= $user['username'] ?>" aria-describedby="inputGroupPrepend" readonly>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <label for="nama" class="form-label">Nama Perusahaan</label>
                    <input type="text" class="form-control" id="nama" placeholder="Nama Perusahaan" value="<?= $user['nama'] ?>" required>
                  </div>
                  <div class="col-md-12">
                    <label for="no_telp" class="form-label">No. Telp / Hp</label>
                    <input type="text" class="form-control" id="no_telp" value="<?= $user['no_telp'] ?>" placeholder="No. Telp. / Hp." required>
                  </div>
                  <div class="col-md-12">
                    <label for="email" class="form-label">E-Mail Perusahaan</label>
                    <input type="email" class="form-control" id="email" value="<?= $user['email'] ?>" placeholder="E-Mail Perusahaan" required>
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
                    <textarea id="alamat" class="form-control" rows="3" value="<?= $user['alamat'] ?>" placeholder="Detail Alamat" required></textarea>
                  </div>
                  <!-- <div class="mb-0 text-center">
                    <button class="btn btn-primary text-white" type="button" id="btn-simpan"><i class="mdi mdi-content-save"></i> Simpan </button>
                  </div> -->
                </form>
              <?php
              } ?>
            </div> <!-- end tab-pane -->
            <!-- end about me section content -->

            <div class="tab-pane" id="settings">
              <form class="row g-3 needs-validation" id="form-password" novalidate>
                <div class="row my-3">
                  <label for="old-password" class="col-md-4 col-form-label">Password Lama</label>
                  <div class="col-md-8">
                    <div class="input-group input-group-merge">
                      <input type="password" id="old-password" class="form-control" placeholder="Masukkan Password Lama" required>
                      <div class="input-group-text" data-password="false">
                        <span class="password-eye"></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="new-password" class="col-md-4 col-form-label">Password Baru</label>
                  <div class="col-md-8">
                    <div class="input-group input-group-merge">
                      <input type="password" id="new-password" class="form-control" placeholder="Masukkan Password Baru" required>
                      <div class="input-group-text" data-password="false">
                        <span class="password-eye"></span>
                      </div>
                    </div>
                    <small class="small text-danger" id="wrong-length-pass"></small>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="re-new-password" class="col-md-4 col-form-label">Ulangi Password Baru</label>
                  <div class="col-md-8">
                    <div class="input-group input-group-merge">
                      <input type="password" id="re-new-password" class="form-control" placeholder="Ulangi Password Baru" required>
                      <div class="input-group-text" data-password="false">
                        <span class="password-eye"></span>
                      </div>
                    </div>
                    <small class="small text-danger" id="wrong-pass"></small>
                  </div>
                </div>

                <div class="col-12 text-center">
                  <a class="btn btn-primary disabled" id="btn-password"><i class="bx bxs-save"></i> Simpan</a>
                </div>
              </form>
            </div>
            <!-- end settings content-->

          </div> <!-- end tab-content -->
        </div> <!-- end card body -->
      </div> <!-- end card -->
    </div> <!-- end col -->
  </div>

</div>

<script>
  <?php
  if ($user['level'] == 4) {
  ?>
    $("#alamat").val('<?= $user['alamat'] ?>')
    $('.select2').select2()

    function getUser(handleData, id) {
      var url = "<?= base_url('index.php/users/getUser') ?>"
      var tipe = 1;
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

    var id = $("#id_user").val()

    console.log(id)

    getUser(function(data) {
      $('#prov').select2().val(data.data.prov).trigger('change')
      getKab(function(val) {
        $('#kab').html(val);
        $("#kab").val(data.data.kab)
        $(`#kab[value=${data.data.kab}]`).prop('selected', true)
        getKec(function(val) {
          $('#kec').html(val);
          $("#kec").val(data.data.kec)
          $(`#kec[value=${data.data.kec}]`).prop('selected', true)

          getKel(function(val) {
            $("#kel").html(val);
            $("#kel").val(data.data.kel)
            $(`#kel[value=${data.data.kel}]`).prop('selected', true)
          }, $("#kec").val())
        }, $("#kab").val())
      }, $('#prov').val())

    }, <?= $user['id_user'] ?>);

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

  <?php
  }
  ?>

  $('#re-new-password').keyup(function() {
    var pass = $('#new-password').val()

    if ($(this).val() != pass) {
      $('#wrong-pass').html('password tidak sesuai')
      $('#btn-password').addClass('disabled')

    } else {
      $('#wrong-pass').html('password sesuai')
      $('#btn-password').removeClass('disabled')
    }
  })

  $('#new-password').keyup(function() {
    var jumlah = $(this).val()
    if (jumlah.length < 6) {
      $('#wrong-length-pass').html('Jumlah password harus lebih dari 6 karakter')
    } else {
      $('#wrong-length-pass').html('')
    }
  })

  function simpan_password() {
    var url = "<?= base_url('index.php/users/') . 'update_password' ?>"
    var id_user = $('#id_user').val()
    var old_password = $('#old-password').val()
    var new_password = $('#new-password').val()
    // var old_password = $('#old-password')

    var form_data = new FormData();

    form_data.append('id_user', id_user);
    form_data.append('old_password', old_password);
    form_data.append('new_password', new_password);

    $.ajax({
      type: "POST",
      dataType: "JSON",
      url: url,
      processData: false,
      contentType: false,
      cache: false,
      enctype: 'multipart/form-data',
      data: form_data,
      success: function(data) {
        sweetAlert(data.data.header, data.data.body, data.data.status, {
          button: null
        });

        if (data.status == 200) {
          setTimeout(function() {
            window.location.href = "<?= base_url('index.php/users/') ?>profile"
          }, 1000)
        }
      }
    })
  }

  (function() {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation#form-password')
    var button_simpan = document.querySelector('#btn-password')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
      .forEach(function(form) {
        button_simpan.addEventListener('click', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          } else {
            simpan_password();
          }

          form.classList.add('was-validated')
        }, false)
      })
  })();
</script>