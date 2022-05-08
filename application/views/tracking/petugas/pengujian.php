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
                  <th>Tanggal Kegiatan</th>
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

<!-- Modal Konfirmasi Pengujian -->
<div class="modal fade" id="modal-pengujian" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-pengujianLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-pengujianLabel">Upload SPT</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3 needs-validation" id="form-pengujian" novalidate>
          <input type="hidden" id="id_tiket" value="">
          <div class="col-xl-12">
            <label for="tgl-awal" class="form-label">Tanggal Awal Pengujian</label>
            <input type="date" class="form-control" id="tgl-awal" required>
          </div>
          <div class="col-xl-12">
            <label for="tgl-akhir" class="form-label">Tanggal Akhir Pengujian</label>
            <input type="date" class="form-control" id="tgl-akhir" required>
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

<!-- Modal Tanggal -->
<div class="modal fade" id="modal-tgl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-tglLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-tglLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3 needs-validation" id="form-tgl" novalidate>
          <input type="hidden" id="status" value="">
          <div class="col-xl-12">
            <label for="tgl" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tgl" required>
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

<script>
  function tampil() {
    var url = "<?= base_url('index.php/tracking/getAllTicketPetugas') ?>"
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
    var url = "<?= base_url('index.php/tracking/saveTglPengujian') ?>";

    var id_tiket = $('#id_tiket').val()
    var tgl_awal = $('#tgl-awal').val()
    var tgl_akhir = $('#tgl-akhir').val()

    var form_data = new FormData()
    form_data.append('id_tiket', id_tiket)
    form_data.append('tgl_awal', tgl_awal)
    form_data.append('tgl_akhir', tgl_akhir)

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
          $("#modal-pengujian").modal('hide');
        } else {
          $('#btn-simpan').removeAttr('disabled');
        }
      }
    })
  }

  function simpan_tgl() {
    var url = "<?= base_url('index.php/tracking/saveTgl') ?>";
    var id_tiket = $("#id_tiket").val()
    var status = $("#status").val()
    var tgl = $("#tgl").val()
    var ket = label[status]

    $.ajax({
      type: "POST",
      dataType: "JSON",
      url: url,
      data: {
        id_tiket: id_tiket,
        status: status,
        tgl: tgl,
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
    '7': 'Tanggal Masuk Analisa Lab',
    '8': 'Tanggal Keluar Analisa Lab',
    '9': 'Tanggal Masuk Analisa Hasil',
    '10': 'Tanggal Keluar Analisa Hasil',
    '11': 'Tanggal Verifikasi LHU',
    '12': 'Tanggal DHU Ditandatangani',
    '13': 'Tanggal Pencetakan dan Penomoran Laporan',
    '14': 'Tanggal Laporan Dikirim',
  }

  var modal_spt = document.getElementById('modal-pengujian')
  modal_spt.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget
    var id = button.getAttribute('data-bs-id')
    $('#id_tiket').val(id);
  });

  var modal_spt = document.getElementById('modal-tgl')
  modal_spt.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget
    var id = button.getAttribute('data-bs-id')
    var status = button.getAttribute('data-status')
    $("#modal-tglLabel").html(label[status])
    $('#id_tiket').val(id);
    $('#status').val(status);
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
    var forms = document.querySelectorAll('#form-pengujian.needs-validation')
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
</script>