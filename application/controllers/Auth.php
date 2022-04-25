<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
    }
    public function index()
    {
        redirect(base_url('index.php/auth/login'));
    }

    public function login()
    {
        $username = $this->session->userdata('username');
        if ($username != null) {
            redirect(base_url('index.php/dashboard'));
            exit;
        }
        $this->load->view('auth/login');
    }

    public function do_login()
    {
        $username = $this->session->userdata('username');
        if ($username != null) {
            redirect(base_url('index.php/dashboard'));
            exit;
        }
        $data = $this->Auth_model->getLogin($_POST);
        if ($data['status'] == 200) {
            $userdata = [
                'id_user' => $data['data']['id_user'],
                'username' => $data['data']['username'],
                'email' => $data['data']['email'],
                'level' => $data['data']['level'],
            ];
            $this->session->set_userdata($userdata);
            $result = [
                'status' => 200,
                'data' => [
                    'header' => 'Berhasil...',
                    'body' => 'Anda berhasil login!',
                    'status' => 'success'
                ]
            ];
        } else {
            $result = [
                'status' => 400,
                'data' => [
                    'header' => 'Gagal...',
                    'body' => 'Username atau password salah!',
                    'status' => 'error'
                ]
            ];
        }

        echo json_encode($result);
    }

    public function logout()
    {
        $username = $this->session->userdata('username');
        if ($username == null) {
            redirect(base_url('index.php/dashboard'));
            exit;
        }
        $userdata = array('id_user', 'username', 'email', 'level');
        $this->session->unset_userdata($userdata);

        redirect(base_url());
    }
}
