<style>
  .timeline-item-right {
    display: block;
    position: relative;
    padding-left: 50%;
  }

  .timeline-item-left {
    display: block;
    position: relative;
    padding-right: 50%;
  }

  @media (max-width: 768px) {
    .timeline-item-right {
      padding-left: 0;
    }

    .timeline-item-left {
      padding-right: 0;
    }
  }

  @media (min-width: 768px) {
    .timeline .timeline-desk {
      display: block;
      width: 100%;
    }
  }
</style>
<div class="container-fluid">
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
      <div class="timeline" dir="ltr">

        <?php
        $tgl_before = '';
        foreach ($status as $row) :
          if ($row['status'] % 2 == 1) {
            $align = 'right';
          } else {
            $align = 'left';
          }

          $keterangan = [
            '0' => "E-Billing diterbitkan dan dikirim ke perusahaan. Selanjutnya perusahaan akan membayar biaya pengujian yang tertera pada e-Billing",
            '1' => "Perusahaan membayar biaya pengujian sesuai dengan jumlah yang tertera di e-billing dan bukti pembayaran diupload ke sistem untuk selanjutnya di verifikasi oleh petugas",
            '2' => "Pembayaran ditolak dengan alasan $row[keterangan]. Perbaiki berkas dan upload ulang bukti pembayaran yang valid dan dapat dipertanggungjawabkan",
            '3' => "Pembayaran diterima oleh Balai, untuk selanjutnya balai akan mengirimkan SPT (Surat Perintah Tugas) yang berisi jadwal dan petugas yang akan berangkat",
          ];

          $tgl_show = date('d M Y', strtotime($row['tgl']));
          if ($tgl_show != $tgl_before) {
        ?>
            <div class="timeline-show mb-3 text-center">
              <h5 class="m-0 time-show-name"><?= $tgl_show ?></h5>
            </div>
          <?php
            $tgl_before = $tgl_show;
          }
          ?>


          <div class="timeline-lg-item timeline-item-<?= $align ?>">
            <div class="timeline-desk">
              <div class="timeline-box">
                <span class="arrow-alt"></span>
                <span class="timeline-icon"><i class="mdi mdi-adjust"></i></span>
                <h4 class="mt-0 mb-1 font-16"><?= $detail_status[$row['status']] ?></h4>
                <p class="text-muted"><small><?= 'Waktu input ke sistem, ' . date('d M Y h:i:s A', strtotime($row['date_created'])) ?></small></p>
                <?php
                if ($row['status'] == '4') {
                  $keterangan = "Rencana pengambilan sampel uji pada tanggal : " . date('d M Y', strtotime($tiket['tgl_pengujian']));
                } else {
                  $keterangan = $row['keterangan'];
                }
                ?>

                <p><?= $keterangan ?></p>

                <?php

                if ($row['file'] != '') {
                ?>
                  <a href="" class="btn btn-sm btn-light" data-date-upload='<?= $row['date_created'] ?>' data-bs-file='<?= $row['file'] ?>' data-bs-toggle='modal' data-bs-target='#iframe-modal'> <i class="mdi mdi-attachment"></i> </a>
                <?php
                }
                ?>

              </div>
            </div>
          </div>

        <?php
        endforeach;
        ?>

      </div>
    </div> <!-- end col -->
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
        <embed src="" type="application/pdf" style="height: 100%; width: 100%; max-width: none;" id="iframe-berkas" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
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
</script>