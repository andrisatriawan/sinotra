<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('username') == null) {
      redirect(base_url('index.php/auth/login'));
    }

    $this->load->model('User_model');
    $this->load->model('Perusahaan_model');
    $this->load->model('Menu_model');
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
                    <a href='#' class='action-icon' data-bs-id='$row[id_user]'> <i class='mdi mdi-information-outline'></i></a>
                    <a href='#' class='action-icon' data-bs-id='$row[id_user]' data-bs-toggle='modal' data-bs-target='#modal-akun'> <i class='mdi mdi-square-edit-outline'></i></a>
                    <a href='#' class='action-icon' data-bs-id='$row[id_user]'> <i class='mdi mdi-delete'></i></a>
                  </td>
                </tr>";
      $no++;
    }

    echo $result;
  }

  public function save()
  {
    $tipe = $this->input->post('tipe');
    $id_user = $this->input->post('id_user');
    $username = $this->input->post('username');
    $email = $this->input->post('email');

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
      echo json_encode($result);
      exit;
    } else if ($cek_email['status'] == 200) {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oopss...',
          'body' => 'Email telah digunakan, silahkan masukkan email baru',
          'status' => 'error'
        ]
      ];
      echo json_encode($result);
      exit;
    }

    if ($tipe == 1) {
      $level = '4';
      if ($id_user == '') {
        $simpan_user = $this->User_model->saveUser($this->input->post(), $level);
        if ($simpan_user['status'] == 200) {
          $result = $this->Perusahaan_model->savePerusahaan($this->input->post(), $simpan_user['timestamp']);
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
    }

    echo json_encode($result);
  }
}
