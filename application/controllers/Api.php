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
}
