<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
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

  public function index()
  {
    $data['page'] = 'Dashboard';
    $this->_template('dashboard/index', $data);
  }
}
