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

  <div class="row mb-2">
    <div class="col-sm-4">
      <a href="" class="btn btn-danger rounded-pill mb-3" data-bs-toggle="modal" data-bs-target="#modal-tiket"><i class="mdi mdi-plus"></i> Buat Baru</a>
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
                  <th>Status</th>
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

<!-- Modal Akun -->
<div class="modal fade" id="modal-tiket" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal-tiketLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-tiketLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3 needs-validation" id="form-akun" novalidate>
          <div class="col-md-6">
            <label for="perusahaan" class="form-label">Perusahaan</label>
            <select id="perusahaan" class="form-control select2" style="width: 100%;" required>
              <option value="" selected disabled>Pilih salah satu...</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="pengujian" class="form-label">Pengujian</label>
            <input type="text" class="form-control" id="pengujian" placeholder="Pengujian" required>
          </div>
          <div class="col-md-6">
            <label for="no_ebilling" class="form-label">Nomor E-Billing</label>
            <input type="text" class="form-control" id="no_ebilling" placeholder="Nomor E-Billing" required>
          </div>
          <div class="col-md-6">
            <label for="file_ebilling" class="form-label">File E-Billing</label>
            <input type="file" class="form-control" id="file_ebilling" placeholder="File E-Billing" accept="application/pdf, image/*" required>
          </div>
          <div class="mb-0 text-center">
            <button class="btn btn-primary text-white" type="button" id="btn-simpan"><i class="mdi mdi-content-save"></i> Simpan </button>
          </div>
        </form>
      </div>
    </div>
  </div>
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
        <embed src="" type="application/pdf" style="height: 100%; width:100%; max-width: none;" id="iframe-berkas" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="modal-tolak" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-tolakLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-tolakLabel">Konfirmasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Anda yakin ingin menolak bukti bayar ini?</p>
        <form class="row g-3 needs-validation" id="form-tolak" novalidate>
          <input type="hidden" id="id_tiket_terima" value="">
          <div class="col-md-12">
            <div class="form-floating">
              <textarea class="form-control" placeholder="Alasan Penolakan" id="alasan-penolakan" style="height: 100px" required></textarea>
              <label for="alasan-penolakan">Alasan Penolakan</label>
            </div>
          </div>
        </form>
        <small class="small text-danger">*Alasan Penolakan wajib diisi</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn-tolak">Kirim</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Terima -->
<div class="modal fade" id="modal-terima" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-terimaLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-terimaLabel">Konfirmasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Anda yakin ingin menerima bukti bayar ini?</p>
        <input type="hidden" id="id_tiket_tolak" value="">
        <br>
        <small class="small">Pastikan bukti pembayaran valid dan dapat dipertanggungjawabkan!</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn-terima">Terima</button>
      </div>
    </div>
  </div>
</div>

<script>
  function tampil() {
    var url = "<?= base_url('index.php/tracking/get_ticket') ?>"
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

  function getPer() {
    var url = "<?= base_url('index.php/tracking/getPerusahaan') ?>"
    $.ajax({
      type: "POST",
      dataType: "HTML",
      url: url,
      success: function(data) {
        $('#perusahaan').html(data)
      }
    })
  }

  tampil()
  getPer()

  $('.select2').select2({
    dropdownParent: $('#modal-tiket')
  })

  function simpan() {
    var url = "<?= base_url('index.php/tracking/save') ?>"
    var perusahaan = $("#perusahaan").val()
    var pengujian = $("#pengujian").val()
    var no_ebilling = $("#no_ebilling").val()
    var file_ebilling = $("#file_ebilling").prop('files')[0]

    var form_data = new FormData();
    form_data.append('perusahaan', perusahaan);
    form_data.append('pengujian', pengujian);
    form_data.append('no_ebilling', no_ebilling);
    form_data.append('file_ebilling', file_ebilling);

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
          $("#modal-tiket").modal('hide');
        } else {
          $('#btn-simpan').removeAttr('disabled');
        }
      }
    })
  }

  function tolak() {
    var url = "<?= base_url('index.php/tracking/rejectPembayaran') ?>"
    var id_tiket = $("#id_tiket_tolak").val();
    var keterangan = $("#alasan-penolakan").val();

    $.ajax({
      type: "POST",
      dataType: "JSON",
      url: url,
      data: {
        id_tiket: id_tiket,
        keterangan: keterangan
      },
      success: function(data) {
        sweetAlert(data.data.header, data.data.body, data.data.status, {
          button: null
        });
        if (data.status == 200) {
          tampil();
          $("#modal-tolak").modal('hide');
        } else {
          $('#btn-tolak').removeAttr('disabled');
        }
      }
    })
  }

  function terima() {
    var url = "<?= base_url('index.php/tracking/acceptPembayaran') ?>"
    var id_tiket = $("#id_tiket_terima").val();

    $.ajax({
      type: "POST",
      dataType: "JSON",
      url: url,
      data: {
        id_tiket: id_tiket,
      },
      success: function(data) {
        sweetAlert(data.data.header, data.data.body, data.data.status, {
          button: null
        });
        if (data.status == 200) {
          tampil();
          $("#modal-terima").modal('hide');
        } else {
          $('#btn-terima').removeAttr('disabled');
        }
      }
    })
  }

  var modal_tiket = document.getElementById('modal-tiket')
  modal_tiket.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget
    var id = button.getAttribute('data-bs-id')
    $('#btn-simpan').removeAttr('disabled');
    $(".was-validated").removeClass('was-validated')

    if (id != null) {

    } else {
      $('#modal-tiketLabel').html('Buat Baru')
      $("#perusahaan").val("").change()
      $("#pengujian").val("")
      $("#no_ebilling").val("")
      $("#file_ebilling").val("")
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
    $('#iframe-berkas').attr('src', url)

  });

  var modal_tolak = document.getElementById('modal-tolak')
  modal_tolak.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget
    var id = button.getAttribute('data-bs-id')
    $(".was-validated").removeClass('was-validated')
    $("#id_tiket_tolak").val(id);
    $("#alasan-penolakan").val("");
  });

  var modal_terima = document.getElementById('modal-terima')
  modal_terima.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget
    var id = button.getAttribute('data-bs-id')
    $("#id_tiket_terima").val(id);
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

  $("#btn-terima").click(function() {
    $('#btn-terima').attr('disabled', true);
    terima();
  })

  (function() {
    'use strict'
    var forms = document.querySelectorAll('#form-tolak.needs-validation')
    var btn_tolak = document.getElementById('btn-tolak')

    Array.prototype.slice.call(forms)
      .forEach(function(form) {
        btn_tolak.addEventListener('click', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          } else {
            $('#btn-tolak').attr('disabled', true);
            tolak()
          }

          form.classList.add('was-validated')
        }, false)
      })
  })();
</script>