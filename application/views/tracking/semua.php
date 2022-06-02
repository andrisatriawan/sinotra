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
            <table id="table-all" class="table dt-responsive wrap-text w-100">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Perusahaan</th>
                  <th>Pengujian</th>
                  <th>Status</th>
                  <th>Tanggal Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="data-all">
              </tbody>
            </table>
          </div>
        </div> <!-- end card-body-->
      </div> <!-- end card-->
    </div> <!-- end col -->
  </div> <!-- end row -->

</div>

<script>
  function tampil() {
    var url = "<?= base_url('index.php/tracking/getAllPengujian') ?>"
    $.ajax({
      type: "POST",
      dataType: "HTML",
      url: url,
      success: function(data) {
        $('#table-all').DataTable().destroy()
        $('#data-all').html("")
        $('#data-all').html(data)
        $('#table-all').DataTable()
      }
    })
  }

  tampil()
</script>