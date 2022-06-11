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

    .timeline-steps .timeline-step:not(:last-child):after {
      content: "";
      display: block;
      border-top: .25rem dotted #d3d3d3;
      width: 3.46rem;
      position: absolute;
      left: 7.5rem;
      top: .3125rem
    }

    .timeline-steps .timeline-step:not(:first-child):before {
      content: "";
      display: block;
      border-top: .25rem dotted #d3d3d3;
      width: 3.8125rem;
      position: absolute;
      right: 7.5rem;
      top: .3125rem
    }
  }

  .timeline-steps {
    display: flex;
    justify-content: center;
    flex-wrap: wrap
  }

  .timeline-steps .timeline-step {
    align-items: center;
    display: flex;
    flex-direction: column;
    position: relative;
    margin: 1rem
  }

  .timeline-steps .timeline-content {
    width: 10rem;
    text-align: center
  }

  .timeline-steps .timeline-content .inner-circle {
    border-radius: 1.5rem;
    height: 1rem;
    width: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #3b82f6
  }

  .timeline-steps .timeline-content .inner-circle:before {
    content: "";
    background-color: #d3d3d3;
    display: inline-block;
    height: 3rem;
    width: 3rem;
    min-width: 3rem;
    border-radius: 6.25rem;
    opacity: .5
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

  <?php
  if ($status_now['status'] >= 0 && $status_now['status'] < 3) {
    $bg_billing = 'bg-warning';
    $bg_tgl = 'bg-secondary';
    $bg_pengujian = 'bg-secondary';
    $bg_proses = 'bg-secondary';
    $bg_laporan = 'bg-secondary';
  } else if ($status_now['status'] >= 3 && $status_now['status'] < 5) {
    $bg_billing = 'bg-primary';
    $bg_tgl = 'bg-warning';
    $bg_pengujian = 'bg-secondary';
    $bg_proses = 'bg-secondary';
    $bg_laporan = 'bg-secondary';
  } else if ($status_now['status'] >= 5 && $status_now['status'] < 7) {
    $tgl_pengujian = date('Ymd', strtotime($tiket['tgl_pengujian']));
    $tgl_now = date('Ymd');
    if ($tgl_pengujian <= $tgl_now || $status_now['status'] == 6) {
      $bg_billing = 'bg-primary';
      $bg_tgl = 'bg-primary';
      $bg_pengujian = 'bg-warning';
    } else {
      $bg_billing = 'bg-primary';
      $bg_tgl = 'bg-warning';
      $bg_pengujian = 'bg-secondary';
    }
    $bg_proses = 'bg-secondary';
    $bg_laporan = 'bg-secondary';
  } else if ($status_now['status'] >= 7 && $status_now['status'] < 15) {
    $bg_billing = 'bg-primary';
    $bg_tgl = 'bg-primary';
    $bg_pengujian = 'bg-primary';
    $bg_proses = 'bg-warning';
    $bg_laporan = 'bg-secondary';
  } else if ($status_now['status'] == 15) {
    $bg_billing = 'bg-primary';
    $bg_tgl = 'bg-primary';
    $bg_pengujian = 'bg-primary';
    $bg_proses = 'bg-primary';
    $bg_laporan = 'bg-warning';
  } else if ($status_now['status'] == 16) {
    $bg_billing = 'bg-primary';
    $bg_tgl = 'bg-primary';
    $bg_pengujian = 'bg-primary';
    $bg_proses = 'bg-primary';
    $bg_laporan = 'bg-primary';
  } else {
    echo $status_now['status'];
  }
  ?>

  <div class="container my-5">
    <div class="row">
      <div class="col">
        <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
          <div class="timeline-step">
            <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top">
              <div class="inner-circle <?= $bg_billing ?>"></div>
              <p class="h6 mt-3 mb-1">E-Billing dan Pembayaran</p>
            </div>
          </div>
          <div class="timeline-step">
            <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top">
              <div class="inner-circle <?= $bg_tgl ?>"></div>
              <p class="h6 mt-3 mb-1">Penentuan Tanggal dan Surat Tugas</p>
            </div>
          </div>
          <div class="timeline-step">
            <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top">
              <div class="inner-circle <?= $bg_pengujian ?>"></div>
              <p class="h6 mt-3 mb-1">Pengambilan Sampel</p>
            </div>
          </div>
          <div class="timeline-step">
            <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top">
              <div class="inner-circle <?= $bg_proses ?>"></div>
              <p class="h6 mt-3 mb-1">Pembuatan Laporan</p>
            </div>
          </div>
          <div class="timeline-step mb-0">
            <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top">
              <div class="inner-circle <?= $bg_laporan ?>"></div>
              <p class="h6 mt-3 mb-1">Laporan Dikirim</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="timeline" dir="ltr">

          <?php
          $tgl_before = '';
          $status_before = 0;
          $numb = 1;
          // echo json_encode($tiket);
          foreach ($status as $row) :
            if ($row['status'] >= $status_before) {
              $bullet_color = 'primary';
              $status_before = $row['status'];
            } else {
              $bullet_color = 'secondary';
            }
            if ($numb % 2 == 1) {
              $align = 'right';
            } else {
              $align = 'left';
            }
            $numb++;

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
                  <span class="timeline-icon bg-<?= $bullet_color ?>"><i class="mdi mdi-adjust text-white"></i></span>
                  <h4 class="mt-0 mb-1 font-16"><?= $detail_status[$row['status']] ?></h4>
                  <p class="text-muted"><small><?= 'Waktu input ke sistem, ' . date('d M Y h:i:s A', strtotime($row['date_created'])) ?></small></p>
                  <?php
                  if ($row['status'] == '4') {
                    if ($this->session->userdata('level') == 4) {
                      $keterangan = "Estimasi tanggal kegiatan : " . date('d M Y', strtotime($tiket['tgl_pengujian']));
                    } else {
                      $keterangan = "Estimasi tanggal kegiatan : " . date('d M Y', strtotime($tiket['tgl_pengujian'])) . "
                      <br>
                      Admin LHU : $tiket[nama]
                      <br>
                      Analis : $tiket[analis]
                      ";
                    }
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