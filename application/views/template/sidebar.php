<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">

  <!-- LOGO -->
  <a href="<?= base_url('index.php/dashboard') ?>" class="logo text-center text-primary">
    <span class="logo-lg align-items-center w-auto">
      <img src="<?= base_url() ?>assets/images/logo-header.png" alt="" style="max-height: 20px;">
      <span class="fw-bold" style="font-size: 20px;margin-left: 10px;vertical-align: middle;"> SINOTRA</span>
    </span>
    <span class="logo-sm">
      <img src="<?= base_url() ?>assets/images/logo-header.png" alt="" style="max-height: 20px;">
    </span>
  </a>

  <div class="h-100" id="leftside-menu-container" data-simplebar>

    <!--- Sidemenu -->
    <ul class="side-nav">

      <li class="side-nav-title side-nav-item">Navigation</li>

      <li class="side-nav-item">
        <a href="<?= base_url('index.php/dashboard') ?>" class="side-nav-link">
          <i class="uil-home-alt"></i>
          <span> Dashboard </span>
        </a>
      </li>

      <li class="side-nav-title side-nav-item">Apps</li>

      <?php
      foreach ($menu as $row) {
        if ($row['is_submenu'] == 0) {
      ?>
          <li class="side-nav-item">
            <a href="<?= base_url('index.php/') . $row['link'] ?>" class="side-nav-link">
              <i class="uil-<?= $row['icon'] ?>"></i>
              <span> <?= $row['menu'] ?> </span>
            </a>
          </li>
        <?php
        } else {
        ?>
          <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#<?= $row['link'] ?>" aria-expanded="false" aria-controls="<?= $row['link'] ?>" class="side-nav-link">
              <i class="uil-<?= $row['icon'] ?>"></i>
              <span> <?= $row['menu'] ?> </span>
              <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="<?= $row['link'] ?>">
              <ul class="side-nav-second-level">
                <?php
                $level = $this->session->userdata('level');
                $this->db->select('*');
                $this->db->from('tb_submenu');
                $this->db->join('tb_access_menu', 'tb_submenu.id_submenu=tb_access_menu.id_submenu');
                $this->db->where('tb_access_menu.level', $level);
                $this->db->where('tb_submenu.id_menu', $row['id_menu']);
                $result = $this->db->get()->result_array();
                foreach ($result as $row_submenu) {
                ?>
                  <li>
                    <a href="<?= base_url('index.php/') . $row_submenu['link'] ?>"><?= $row_submenu['submenu'] ?></a>
                  </li>
                <?php
                }
                ?>
              </ul>
            </div>
          </li>
      <?php
        }
      }
      ?>
      <!-- 
      <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarDashboards" aria-expanded="false" aria-controls="sidebarDashboards" class="side-nav-link">
          <i class="uil-home-alt"></i>
          <span> Dashboards </span>
          <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarDashboards">
          <ul class="side-nav-second-level">
            <li>
              <a href="dashboard-analytics.html">Analytics</a>
            </li>
            <li>
              <a href="index.html">Ecommerce</a>
            </li>
            <li>
              <a href="dashboard-projects.html">Projects</a>
            </li>
            <li>
              <a href="dashboard-wallet.html">E-Wallet <span class="badge rounded bg-danger font-10 float-end">New</span></a>
            </li>
          </ul>
        </div>
      </li>

      <li class="side-nav-item">
        <a href="apps-calendar.html" class="side-nav-link">
          <i class="uil-calender"></i>
          <span> <?= date('Y-m-d h:i:s') ?> </span>
        </a>
      </li> -->

    </ul>
    <!-- End Sidebar -->

    <div class="clearfix"></div>

  </div>
  <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->