<div class="content-page">
  <div class="content">
    <!-- Topbar Start -->
    <div class="navbar-custom">
      <ul class="list-unstyled topbar-menu float-end mb-0">

        <li class="dropdown notification-list">
          <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
            <span class="account-user-avatar">
              <img src="<?= base_url() ?>assets/images/users/<?= $user['foto'] ?>" alt="user-image" class="rounded-circle">
            </span>
            <span>
              <span class="account-user-name"><?= $user['nama'] ?></span>
              <?php
              if ($this->session->userdata('level') != 4) {
                $detail = $user['jabatan'];
              } else {
                $detail = $user['email'];
              }
              ?>
              <span class="account-position"><?= $detail ?></span>
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
            <!-- item-->
            <div class=" dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome !</h6>
            </div>

            <!-- item-->
            <a href="<?= base_url('index.php/users/profile') ?>" class="dropdown-item notify-item">
              <i class="mdi mdi-account-circle me-1"></i>
              <span>My Account</span>
            </a>

            <!-- item-->
            <a href="<?= base_url('index.php/auth/logout') ?>" class="dropdown-item notify-item">
              <i class="mdi mdi-logout me-1"></i>
              <span>Logout</span>
            </a>
          </div>
        </li>

      </ul>
      <button class="button-menu-mobile open-left">
        <i class="mdi mdi-menu"></i>
      </button>
    </div>
    <!-- end Topbar -->