<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
  private $desc_level;
  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('username') == null) {
      redirect(base_url('index.php/auth/login'));
    }

    $this->load->model('User_model');
    $this->load->model('Perusahaan_model');
    $this->load->model('Menu_model');

    $this->desc_level = [
      '0' => 'Superadmin',
      '1' => 'Admin Persuratan',
      '2' => 'Bendahara PNBP',
      '3' => 'Pembuat Laporan',
      '4' => 'Admin Perusahaan',
      '5' => 'Manager Teknis ISO 17025',
      '6' => 'Arsip Laporan Pengujian',
      '7' => 'Kurir Pengirim Laporan',
    ];
  }

  function _template($url, $data)
  {
    $level = $this->session->userdata('level');
    $id_user = $this->session->userdata('id_user');
    if ($level != 4) {
      $data['user'] = $this->User_model->getUser($id_user);
    } else {
      $data['user'] = $this->Perusahaan_model->getPerusahaan($id_user);
    }

    $data['menu'] = $this->Menu_model->getAllMenu();

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view($url, $data);
    $this->load->view('template/footer', $data);
  }

  public function perusahaan()
  {
    $data['page'] = 'Akun Perusahaan';
    $this->_template('users/perusahaan/index', $data);
  }

  public function admin()
  {
    $data['page'] = 'Admin';
    $this->_template('users/admin/index', $data);
  }

  public function get_admin()
  {
    $data = $this->User_model->getAllAdmin();

    $result = '';
    $no = 1;
    foreach ($data as $row) {
      $level = $this->desc_level[$row['level']];

      if ($this->session->userdata('id_user') != $row['id_user']) {
        $action = "<a href='#' class='action-icon' data-bs-id='$row[id_user]'> <i class='mdi mdi-information-outline'></i></a>
                    <a href='#' class='action-icon' data-bs-id='$row[id_user]' data-bs-toggle='modal' data-bs-target='#modal-akun'> <i class='mdi mdi-square-edit-outline'></i></a>
                    <a href='#' class='action-icon' data-bs-id='$row[id_user]'> <i class='mdi mdi-delete'></i></a>";
      } else {
        $action = "<a href='#' class='action-icon' data-bs-id='$row[id_user]'> <i class='mdi mdi-information-outline'></i></a>
                    <a href='#' class='action-icon' data-bs-id='$row[id_user]' data-bs-toggle='modal' data-bs-target='#modal-akun'> <i class='mdi mdi-square-edit-outline'></i></a>";
      }
      $result .= "<tr>
                  <td>$no</td>
                  <td>$row[nama]</td>
                  <td>$row[jabatan]</td>
                  <td>$level</td>
                  <td>
                    $action
                  </td>
                </tr>";
      $no++;
    }

    echo $result;
  }

  public function get_perusahaan()
  {
    $data = $this->Perusahaan_model->getAllPerusahaan();
    $result = '';
    $no = 1;
    foreach ($data as $row) {
      $result .= "<tr>
                  <td>$no</td>
                  <td>$row[nama]</td>
                  <td>$row[email]</td>
                  <td>$row[no_telp]</td>
                  <td>
                    <a href='#' class='action-icon' data-bs-id='$row[id_user]' data-bs-toggle='modal' data-bs-target='#modal-detail'> <i class='mdi mdi-information-outline'></i></a>
                    <a href='#' class='action-icon' data-bs-id='$row[id_user]' data-bs-toggle='modal' data-bs-target='#modal-akun'> <i class='mdi mdi-square-edit-outline'></i></a>
                    <a href='#' class='action-icon' data-bs-id='$row[id_user]'> <i class='mdi mdi-delete'></i></a>
                  </td>
                </tr>";
      $no++;
    }

    echo $result;
  }

  function cekAkun($username, $email)
  {
    $cek_username = $this->User_model->getUserByUsername($username);
    $cek_email = $this->User_model->getUserByEmail($email);

    if ($cek_username['status'] == 200) {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oopss...',
          'body' => 'Username telah digunakan, silahkan masukkan username baru',
          'status' => 'error'
        ]
      ];
    } else if ($cek_email['status'] == 200) {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oopss...',
          'body' => 'Email telah digunakan, silahkan masukkan email baru',
          'status' => 'error'
        ]
      ];
    } else {
      $result = ['status' => 200];
    }

    return $result;
  }

  function UserPass($post)
  {
    $nama = strtolower($post['nama']);
    $first_username = preg_replace('/[^a-zA-Z0-9]/', '', $nama);
    $cek_username = $this->User_model->getUserByUsername($first_username);
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = substr(str_shuffle($permitted_chars), 0, 8);

    if ($cek_username['status'] != 200) {
      $result = [
        'status' => 200,
        'username' => $first_username,
        'password' => $password
      ];
    } else {
      while ($cek_username['status'] == 200) {
        $username = $first_username . rand(10, 99);
        $cek_username = $this->User_model->getUserByUsername($username);
      }

      $result = [
        'status' => 200,
        'username' => $username,
        'password' => $password
      ];
    }

    return $result;
  }

  public function save()
  {
    $post = $this->input->post();


    $tipe = $post['tipe'];
    $id_user = $post['id_user'];

    $email = $post['email'];

    if ($tipe == 1) {
      $userpass = $this->UserPass($post);
      $level = '4';
      if ($id_user == '') {
        $cekAkun = $this->cekAkun($userpass['username'], $email);
        if ($cekAkun['status'] != 200) {
          echo json_encode($cekAkun);

          exit;
        }

        $simpan_user = $this->User_model->saveUser($post, $level, $userpass);
        if ($simpan_user['status'] == 200) {
          $result = $this->Perusahaan_model->savePerusahaan($post, $simpan_user['timestamp']);
        } else {
          $result = [
            'status' => 400,
            'data' => [
              'header' => 'Oopss...',
              'body' => 'User gagal disimpan',
              'status' => 'error'
            ]
          ];
        }
      } else {
        $update_user = $this->User_model->updateUser($post, $level);
        if ($update_user['status'] == 200) {
          $result = $this->Perusahaan_model->updatePerusahaan($post, $update_user['timestamp']);
        } else {
          $result = [
            'status' => 400,
            'data' => [
              'header' => 'Oopss...',
              'body' => 'User gagal disimpan',
              'status' => 'error'
            ]
          ];
        }
      }
    } else {
      $userpass = [
        'username' => $post['username'],
        'password' => $post['password']
      ];
      $username = $post['username'];
      $level = $post['level'];
      if ($id_user == '') {
        $cekAkun = $this->cekAkun($username, $email);
        if ($cekAkun['status'] != 200) {
          echo json_encode($cekAkun);

          exit;
        }

        $simpan_user = $this->User_model->saveUser($post, $level, $userpass);
        if ($simpan_user['status'] == 200) {
          $result = $this->User_model->saveProfile($post, $simpan_user['timestamp']);
        } else {
          $result = [
            'status' => 400,
            'data' => [
              'header' => 'Oopss...',
              'body' => 'User gagal disimpan',
              'status' => 'error'
            ]
          ];
        }
      } else {
        $update_user = $this->User_model->updateUser($post, $level);
        if ($update_user['status'] == 200) {
          $result = $this->User_model->updateProfile($post, $update_user['timestamp']);
        } else {
          $result = [
            'status' => 400,
            'data' => [
              'header' => 'Oopss...',
              'body' => 'User gagal disimpan',
              'status' => 'error'
            ]
          ];
        }
      }
    }

    echo json_encode($result);
  }

  public function getUser()
  {
    $tipe = $this->input->post('tipe');
    $id_user = $this->input->post('id_user');

    if ($tipe == 1) {
      $data = $this->Perusahaan_model->getPerusahaan($id_user);
      $result = [
        'status' => 200,
        'data' => $data
      ];
    } else {
      $data = $this->User_model->getUser($id_user);
      $result = [
        'status' => 200,
        'data' => $data
      ];
    }

    echo json_encode($result);
  }
}
