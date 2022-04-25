<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
  public function getUser($id)
  {
    // $query = "SELECT * FROM tb_users INNER JOIN tb_profile ON tb_users.id_user=tb_profile.id_user WHERE tb_users.id_user='$id'";
    // $result = $this->db->query($query);

    // $result = $this->db->get();
    $result = $this->db->get_where('tb_profile', ['id_user' => $id]);

    return $result->row_array();
  }

  public function saveUser($data, $level)
  {
    $timestamp = date('Y-m-d H:i:s');
    $id_user = $this->session->userdata('id_user');
    $data = [
      'username' => $data['username'],
      'email' => $data['email'],
      'password' => md5($data['password']),
      'pass_view' => $data['password'],
      'level' => $level,
      'date_created' => $timestamp,
      'date_updated' => $timestamp,
      'created_by' => $id_user,
      'updated_by' => $id_user,
    ];

    $simpan = $this->db->insert('tb_users', $data);
    if ($simpan) {
      $result = [
        'status' => 200,
        'timestamp' => $timestamp
      ];
    } else {
      $result = [
        'status' => 400
      ];
    }

    return $result;
  }

  public function getUserByUsername($username)
  {
    $hasil = $this->db->get_where('tb_users', ['username' => $username]);
    if ($hasil->num_rows() > 0) {
      $result = [
        'status' => 200,
      ];
    } else {
      $result = [
        'status' => 400
      ];
    }

    return $result;
  }

  public function getUserByEmail($email)
  {
    $hasil = $this->db->get_where('tb_users', ['email' => $email]);
    if ($hasil->num_rows() > 0) {
      $result = [
        'status' => 200,
      ];
    } else {
      $result = [
        'status' => 400
      ];
    }

    return $result;
  }
}
