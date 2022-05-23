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
                  <th>Tanggal Pengujian</th>
                  <th>Status</th>
                  <th>Tanggal Status</th>
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

<!-- Modal Send Laporan -->
<div class="modal fade" id="modal-pengiriman" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-pengirimanLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-pengirimanLabel">Upload Bukti Pengiriman</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3 needs-validation" id="form-pengiriman" novalidate>
          <input type="hidden" id="id_tiket" value="">
          <div class="col-md-6">
            <label for="tgl" class="form-label">Tanggal Dikirim</label>
            <input type="date" class="form-control" id="tgl" required>
          </div>
          <div class="col-md-6">
            <label for="jenis_pengiriman" class="form-label">Jenis Pengiriman</label>
            <select id="jenis_pengiriman" class="form-control select2" required>
              <option value="" selected disabled>Pilih salah satu...</option>
              <option value="0">Dikirim langsung</option>
              <option value="1">Menggunakan jasa ekspedisi</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="ekspedisi" class="form-label" id="ekspedisi-label">Jasa Ekspedisi</label>
            <input type="text" class="form-control" id="ekspedisi" disabled>
          </div>
          <!-- <div class="col-md-6">
            <label for="no_resi" class="form-label">Nomor Resi</label>
            <input type="text" class="form-control" id="no_resi" disabled>
          </div> -->
          <div class="col-md-6">
            <label for="file" class="form-label">Bukti Pengiriman</label>
            <input type="file" class="form-control" id="file" accept="image/*" required>
          </div>
          <div class="col-md-12 text-center">
            <button type="button" class="btn btn-sm btn-primary" id="btn-simpan">Simpan</button>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  function tampil() {
    var url = "<?= base_url('index.php/tracking/getAllTicketLaporan') ?>"
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

  $('.select2').select2({
    dropdownParent: $('#modal-pengiriman')
  })

  $("#jenis_pengiriman").change(function() {
    if ($(this).val() == 0) {
      $("#ekspedisi-label").html("Pengirim Laporan")
      $("#ekspedisi").removeAttr("disabled")
      $("#ekspedisi").attr("required", true)
      // $("#no_resi").attr("disabled", true)
      // $("#no_resi").removeAttr("required")

    } else if ($(this).val() == 1) {
      $("#ekspedisi-label").html("Jasa Ekspedisi")
      $("#ekspedisi").removeAttr("disabled")
      $("#ekspedisi").attr("required", true)
      // $("#no_resi").removeAttr("disabled")
      // $("#no_resi").attr("required", true)
    }
  })

  function simpan() {
    var url = "<?= base_url('index.php/tracking/savePengiriman') ?>";

    var id_tiket = $('#id_tiket').val()
    var tgl = $('#tgl').val()
    var jenis_pengiriman = $('#jenis_pengiriman').val()
    var ekspedisi = $('#ekspedisi').val()
    // var no_resi = $('#no_resi').val()
    var file = $('#file').prop('files')[0]

    var form_data = new FormData()
    form_data.append('id_tiket', id_tiket)
    form_data.append('tgl', tgl)
    form_data.append('jenis_pengiriman', jenis_pengiriman)
    form_data.append('ekspedisi', ekspedisi)
    // form_data.append('no_resi', no_resi)
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
          $('#btn-simpan').removeAttr('disabled');
        }
      }
    })
  }

  var modal_pengiriman = document.getElementById('modal-pengiriman')
  modal_pengiriman.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget
    var id = button.getAttribute('data-bs-id')
    $('#id_tiket').val(id);
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
    var forms = document.querySelectorAll('#form-pengiriman.needs-validation')
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