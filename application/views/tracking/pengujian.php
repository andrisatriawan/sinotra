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
                  <th>Pengujian</th>
                  <th>Status</th>
                  <th>Tanggal Status</th>
                  <th>E-Billing</th>
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

<!-- Modal pembayaran -->
<div class="modal fade" id="modal-pembayaran" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-pembayaranLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-pembayaranLabel">Upload Bukti Pembayaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-danger" id="alasan-penolakan">Alasan Penolakan Pembayaran : </p>
        <form class="row g-3 needs-validation" id="form-akun" novalidate>
          <input type="hidden" id="id_tiket" value="">
          <div class="col-xl-6">
            <label for="tgl" class="form-label">Tanggal Pembayaran</label>
            <input type="date" class="form-control" id="tgl" required>
          </div>
          <div class="col-xl-6">
            <label for="file" class="form-label">Bukti Pembayaran</label>
            <input type="file" class="form-control" id="file" placeholder="File E-Billing" accept="application/pdf, image/*" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn-simpan">Kirim</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Diterima -->
<div class="modal fade" id="modal-pengiriman" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-pengirimanLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-pengirimanLabel">Konfirmasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3 needs-validation" id="form-konfirm" novalidate>
          <div class="col-md-12">
            <label for="tgl_konfirm" class="form-label">Tanggal Diterima</label>
            <input type="date" class="form-control" id="tgl_konfirm" required>
          </div>
          <div class="col-md-12">
            <label for="file_konfirm" class="form-label">Bukti Diterima</label>
            <input type="file" class="form-control" id="file_konfirm" accept="image/*" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn-konfirm">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  function tampil() {
    var url = "<?= base_url('index.php/tracking/getTicketByUser') ?>"
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

  tampil()

  function simpan() {
    var url = "<?= base_url('index.php/tracking/saveBuktiBayar') ?>";

    var id_tiket = $('#id_tiket').val()
    var tgl = $('#tgl').val()
    var file = $('#file').prop('files')[0]

    var form_data = new FormData()
    form_data.append('id_tiket', id_tiket)
    form_data.append('tgl', tgl)
    form_data.append('file', file)

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
          tampil();
          $("#modal-pembayaran").modal('hide');
        } else {
          $('#btn-simpan').removeAttr('disabled');
        }
      }
    })
  }

  function konfirm() {
    var url = "<?= base_url('index.php/tracking/saveKonfirm') ?>";

    var id_tiket = $('#id_tiket').val()
    var tgl = $('#tgl_konfirm').val()
    var file = $('#file_konfirm').prop('files')[0]

    var form_data = new FormData()
    form_data.append('id_tiket', id_tiket)
    form_data.append('tgl', tgl)
    form_data.append('file', file)

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
          tampil();
          $("#modal-pengiriman").modal('hide');
        } else {
          $('#btn-konfirm').removeAttr('disabled');
        }
      }
    })
  }

  var modal_pembayaran = document.getElementById('modal-pembayaran')
  modal_pembayaran.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget
    var id = button.getAttribute('data-bs-id')
    var ket = button.getAttribute('data-bs-ket')
    $('.was-validated').removeClass('was-validated');
    $('#id_tiket').val(id);
    if (ket != null) {
      $("#modal-pembayaranLabel").html("Upload Ulang Bukti Pembayaran")
      $("#alasan-penolakan").html("Ditolak karena <strong>" + ket + "</strong>")
      $("#alasan-penolakan").show()
    } else {
      $("#modal-pembayaranLabel").html("Upload Bukti Pembayaran")
      $("#alasan-penolakan").html("")
      $("#alasan-penolakan").hide()
    }
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

  var modal_pengiriman = document.getElementById('modal-pengiriman')
  modal_pengiriman.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget
    var id = button.getAttribute('data-bs-id')
    $('#id_tiket').val(id);
    $('.was-validated').removeClass('was-validated');
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

  (function() {
    'use strict'
    var forms = document.querySelectorAll('#form-konfirm.needs-validation')
    var btn_simpan = document.getElementById('btn-konfirm')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
      .forEach(function(form) {
        btn_simpan.addEventListener('click', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          } else {
            $('#btn-konfirm').attr('disabled', true);
            konfirm()
          }

          form.classList.add('was-validated')
        }, false)
      })
  })();
</script>