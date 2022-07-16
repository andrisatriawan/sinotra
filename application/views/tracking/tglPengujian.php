<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?= base_url('index.php/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Tracking</li>
            <li class="breadcrumb-item active"><?= $page ?></li>
          </ol>
        </div>
        <h4 class="page-title"><?= $page ?></h4>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">

          <div class="table-responsive">
            <table id="table-tiket" class="table dt-responsive wrap-text w-100">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Perusahaan</th>
                  <th>Pengujian</th>
                  <th>Estimasi Tanggal</th>
                  <th>Admin LHU</th>
                  <th>Analis</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="data-tiket">
              </tbody>
            </table>
          </div>
        </div> <!-- end card-body-->
      </div> <!-- end card-->
    </div> <!-- end col -->
  </div> <!-- end row -->

</div>


<!-- Modal iframe -->
<div class="modal fade" id="iframe-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="iframe-modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="iframe-modalLabel">Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5 class="h5" id="keterangan"></h5>
        <small class="small text-secondary" id="date-uploaded"></small>
        <embed src="" type="application/pdf" style="height: 100%; width: 100%; max-width: none;" id="iframe-berkas" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tanggal -->
<div class="modal fade" id="modal-tgl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-tglLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-tglLabel">Estimasi Tanggal Pengujian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3 needs-validation" id="form-tgl" novalidate>
          <input type="hidden" id="id_tiket" value="">
          <div class="col-xl-12">
            <label for="tgl" class="form-label">Tanggal Pengujian</label>
            <input type="date" class="form-control" id="tgl" required>
          </div>
          <div class="col-md-12">
            <label for="admin_lhu" class="form-label">Admin LHU</label>
            <select id="admin_lhu" class="form-control select2" required>
              <option value="" selected disabled>Pilih salah satu</option>
            </select>
          </div>
          <div class="col-xl-12">
            <label for="analis" class="form-label">Analis</label>
            <select id="analis" class="form-control select2" required>
              <option value="" selected disabled>Pilih salah satu</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn-tgl">Kirim</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tanggal -->
<div class="modal fade" id="modal-edit-admin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-edit-adminLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-edit-adminLabel">Ubah Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3 needs-validation" id="form-ubah-admin" novalidate>
          <div class="col-md-12">
            <label for="admin_lhu-ubah" class="form-label">Admin LHU</label>
            <select id="admin_lhu-ubah" class="form-control select2 admin_lhu-ubah" required>
              <option value="" selected disabled>Pilih salah satu</option>
            </select>
          </div>
          <div class="col-xl-12">
            <label for="analis-ubah" class="form-label">Analis</label>
            <select id="analis-ubah" class="form-control select2" required>
              <option value="" selected disabled>Pilih salah satu</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary ubah-admin" id="ubah-admin">Kirim</button>
      </div>
    </div>
  </div>
</div>

<script>
  function tampil() {
    var url = "<?= base_url('index.php/tracking/getAllTiketPengujian') ?>"
    $.ajax({
      type: "POST",
      dataType: "HTML",
      url: url,
      success: function(data) {
        $('#table-tiket').DataTable().destroy()
        $('#data-tiket').html("")
        $('#data-tiket').html(data)
        $('#table-tiket').DataTable()
      }
    })
  }

  function getPetugas() {
    var url = "<?= base_url('index.php/tracking/getPetugas') ?>"

    $.ajax({
      type: "POST",
      dataType: "HTML",
      url: url,
      success: function(data) {
        $('.admin_lhu-ubah').html(data);
        $('#admin_lhu').html(data);
      }
    })
  }

  function getAnalis() {
    var url = "<?= base_url('index.php/tracking/getAnalis') ?>"

    $.ajax({
      type: "POST",
      dataType: "HTML",
      url: url,
      success: function(data) {
        $('#analis-ubah').html(data)
        $('#analis').html(data)
      }
    })
  }

  function getDataTiket(id = null) {
    var url = `<?= base_url('index.php/tracking/getTicketByID/') ?>${id}`;

    $.ajax({
      type: "POST",
      dataType: "JSON",
      url: url,
      success: function(data) {
        $("#id_tiket").val(data.id_tiket);
        $("#admin_lhu-ubah").val(data.admin_lhu).change();
        $("#analis-ubah").val(data.analis).change();
      }
    })
  }

  function updateAdmin() {
    var url = `<?= base_url('index.php/tracking/updateAdmin') ?>`;
    $.ajax({
      type: "POST",
      dataType: "JSON",
      url: url,
      data: {
        'id_tiket': $("#id_tiket").val(),
        'admin_lhu': $(".admin_lhu-ubah").val(),
        'analis': $("#analis-ubah").val()
      },
      success: function(data) {
        sweetAlert(data.data.header, data.data.body, data.data.status, {
          button: null
        });
        if (data.status == 200) {
          tampil();
          $("#modal-edit-admin").modal('hide');
          $('.ubah-admin').removeAttr('disabled');
        } else {
          $('.ubah-admin').removeAttr('disabled');
        }
      }
    })
  }

  getPetugas();
  getAnalis();

  tampil()

  $('#admin_lhu').select2({
    dropdownParent: $('#modal-tgl')
  })

  $('#analis').select2({
    dropdownParent: $('#modal-tgl')
  })

  $('#admin_lhu-ubah').select2({
    dropdownParent: $('#modal-edit-admin')
  })

  $('#analis-ubah').select2({
    dropdownParent: $('#modal-edit-admin')
  })

  function simpan_tgl() {
    var url = "<?= base_url('index.php/tracking/saveTglRencana') ?>";
    var id_tiket = $("#id_tiket").val()
    var tgl = $("#tgl").val()
    var admin_lhu = $("#admin_lhu").val()
    var analis = $("#analis").val()
    var ket = label[status]

    $.ajax({
      type: "POST",
      dataType: "JSON",
      url: url,
      data: {
        id_tiket: id_tiket,
        tgl: tgl,
        admin_lhu: admin_lhu,
        analis: analis,
        ket: ket,
      },
      success: function(data) {
        sweetAlert(data.data.header, data.data.body, data.data.status, {
          button: null
        });
        if (data.status == 200) {
          tampil();
          $("#modal-tgl").modal('hide');
        } else {
          $('#btn-tgl').removeAttr('disabled');
        }
      }
    })
  }

  var label = {
    '4': 'Estimasi Tanggal Pengujian',
  }

  var modal_edit = document.getElementById('modal-edit-admin')
  modal_edit.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget
    var id = button.getAttribute('data-bs-id')
    getDataTiket(id);
    console.log(id);
  });

  var modal_tgl = document.getElementById('modal-tgl')
  modal_tgl.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget
    var id = button.getAttribute('data-bs-id')
    var status = button.getAttribute('data-status')
    $("#modal-tglLabel").html(label[status])
    $('#id_tiket').val(id);
    $('#status').val(status);
    $('#admin_lhu').val("").change()
    $('#tgl').val("")
  });

  var modal_iframe = document.getElementById('iframe-modal')
  modal_iframe.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget
    var src = button.getAttribute('data-bs-file')
    var tgl = button.getAttribute('data-bs-tgl')
    var date_upload = button.getAttribute('data-date-upload')

    if (tgl != null) {
      $('#keterangan').html("Pembayaran dilakukan pada tanggal " + tgl)
      $('#keterangan').show()
    } else {
      $('#keterangan').html("")
      $('#keterangan').hide()
    }

    $("#date-uploaded").html("Diupload pada tanggal : " + date_upload)
    var url = "<?= base_url() . 'assets/files/' ?>" + src;
    console.log(url)
    $('#iframe-berkas').attr('src', url)
  });

  (function() {
    'use strict'
    var forms = document.querySelectorAll('#form-tgl.needs-validation')
    var btn_simpan = document.getElementById('btn-tgl')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
      .forEach(function(form) {
        btn_simpan.addEventListener('click', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          } else {
            $('#btn-tgl').attr('disabled', true);
            simpan_tgl()
          }

          form.classList.add('was-validated')
        }, false)
      })
  })();

  (function() {
    'use strict'
    var forms = document.querySelectorAll('#form-ubah-admin.needs-validation')
    var btn_simpan = document.getElementById('ubah-admin')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
      .forEach(function(form) {
        btn_simpan.addEventListener('click', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          } else {
            $('.ubah-admin').attr('disabled', true);
            updateAdmin();
            // simpan_tgl()
          }

          form.classList.add('was-validated')
        }, false)
      })
  })();
</script>