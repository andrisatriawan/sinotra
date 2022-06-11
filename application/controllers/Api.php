<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
  private $key;
  public function __construct()
  {
    parent::__construct();

    $this->load->model('User_model');
    $this->load->model('Perusahaan_model');
    $this->load->model('Menu_model');
    $this->load->model('Ticket_model');
  }

  public function allPerusahaan()
  {
    $perusahaan = $this->Perusahaan_model->getAllPerusahaan();

    if ($perusahaan != null) {
      $result = [
        'status' => 200,
        'message' => 'success',
        'data' => $perusahaan
      ];
    } else {
      $result = [
        'status' => 400,
        'message' => 'data kosong'
      ];
    }

    echo json_encode($result);
  }

  public function getPerusahaanByID()
  {
    $id = $this->input->post('id');
    $perusahaan = $this->Perusahaan_model->getPerusahaan($id);

    if ($perusahaan != null) {
      $result = [
        'status' => 200,
        'message' => 'success',
        'data' => $perusahaan
      ];
    } else {
      $result = [
        'status' => 400,
        'message' => 'data tidak ditemukan'
      ];
    }

    echo json_encode($result);
  }

  public function getTiketLHU()
  {
  }

  public function saveAdmLHU()
  {
    $post = $this->input->post();
    $level = '3';
    if ($post['password'] == '') {
      $password = '123456';
    } else {
      $password = $post['password'];
    }

    $userpass = [
      'username' => $post['username'],
      'password' => $password
    ];

    $user = $this->User_model->saveUser($post['email'], $level, $userpass);

    if ($user['status'] == 200) {
      $simpan_profile = $this->User_model->saveProfile($post, $user['timestamp']);

      echo json_encode($simpan_profile);
    }
  }

  public function getTiketPetugas()
  {
    $id = $this->input->post('id');

    $data = $this->Ticket_model->getAllTicket();
    $array_data = [];
    foreach ($data as $row) {
      if ($id != null && $row['petugas'] == $id && $row['is_read_lhu'] != null) {
        $array_data[] = [
          'id_tiket' => $row['id_tiket'],
          'id_perusahaan' => $row['id_perusahaan'],
          'tgl_estimasi' => $row['tgl_pengujian'],
          'petugas' => $row['petugas'],
          'analis' => $row['analis'],
          'is_read_lhu' => $row['is_read_lhu'],
          'is_read_analis' => $row['is_read_analis'],
          'is_read_lab' => $row['is_read_lab'],
          'nama_perusahaan' => $row['nama'],
        ];
      }
    }

    if (count($array_data) != null) {
      $result = [
        'status' => 200,
        'message' => 'success',
        'data' => $array_data
      ];
    } else {
      $result = [
        'status' => 400,
        'message' => 'Failed, data not found!'
      ];
    }


    echo json_encode($result);
  }
}
