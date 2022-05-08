<?php

class Daerah extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('username') == null) {
      redirect(base_url('index.php/auth/login'));
    }
    $this->load->model('Daerah_model');
  }

  public function prov()
  {
    $result = "<option value='' selected disabled>Pilih Provinsi</option>";
    $data = $this->Daerah_model->getProv();

    foreach ($data as $row) {
      $result .= "<option value='$row[id_prov]'>$row[nama]</option>";
    }

    echo $result;
  }

  public function kab($id_prov)
  {
    $result = "<option value='' selected disabled>Pilih Kabupaten</option>";
    $data = $this->Daerah_model->getKab($id_prov);

    foreach ($data as $row) {
      $result .= "<option value='$row[id_kab]'>$row[nama]</option>";
    }

    echo $result;
  }

  public function kec($id_kab)
  {
    $result = "<option value='' selected disabled>Pilih Kecamatan</option>";
    $data = $this->Daerah_model->getKec($id_kab);

    foreach ($data as $row) {
      $result .= "<option value='$row[id_kec]'>$row[nama]</option>";
    }

    echo $result;
  }

  public function kel($id_kel)
  {
    $result = "<option value='' selected disabled>Pilih Kelurahan</option>";
    $data = $this->Daerah_model->getKel($id_kel);

    foreach ($data as $row) {
      $result .= "<option value='$row[id_kel]'>$row[nama]</option>";
    }

    echo $result;
  }
}
