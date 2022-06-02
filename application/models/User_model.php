<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
  public function getUser($id)
  {
    // $query = "SELECT * FROM tb_users INNER JOIN tb_profile ON tb_users.id_user=tb_profile.id_user WHERE tb_users.id_user='$id'";
    // $result = $this->db->query($query);

    // $result = $this->db->get();
    $this->db->select('*');
    $this->db->from('tb_users');
    $this->db->join('tb_profile', 'tb_users.id_user=tb_profile.id_user');
    $this->db->where('tb_users.id_user', $id);
    $result = $this->db->get();

    return $result->row_array();
  }

  public function saveUser($email, $level, $userpass)
  {
    $timestamp = date('Y-m-d H:i:s');
    $id_user = $this->session->userdata('id_user');
    $data = [
      'username' => $userpass['username'],
      'email' => $email,
      'password' => md5($userpass['password']),
      'pass_view' => $userpass['password'],
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
        'data' => $hasil->row_array()
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

  public function updateUser($data, $level)
  {
    $timestamp = date('Y-m-d H:i:s');
    $id_user = $this->session->userdata('id_user');
    $id = $data['id_user'];
    if ($data['password'] == '') {
      $user = $this->getOnlyUser($id);
      $password = $user['password'];
      $pass_view = $user['pass_view'];
    } else {
      $password = md5($data['password']);
      $pass_view = $data['password'];
    }

    $data = [
      'username' => $data['username'],
      'email' => $data['email'],
      'password' => $password,
      'pass_view' => $pass_view,
      'level' => $level,
      'date_updated' => $timestamp,
      'updated_by' => $id_user
    ];
    // $this->db->where('id_user', $data['id_user']);
    $update = $this->db->update('tb_users', $data, ['id_user' => $id]);
    if ($update) {
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

  public function getOnlyUser($id)
  {
    $result = $this->db->get_where('tb_users', ['id_user' => $id]);

    return $result->row_array();
  }

  public function getAllAdmin()
  {
    $this->db->select('*');
    $this->db->from('tb_users');
    $this->db->join('tb_profile', 'tb_users.id_user=tb_profile.id_user');
    $this->db->where('tb_users.level!=', '0');
    $result = $this->db->get();

    return $result->result_array();
  }

  public function saveProfile($data, $timestamp)
  {
    $user = $this->getUserByTimestamp($timestamp);
    $id_user = $this->session->userdata('id_user');

    $data = [
      'id_user' => $user['id_user'],
      'nama' => $data['nama'],
      'jabatan' => $data['jabatan'],
      'foto' => 'default.jpg',
      'date_created' => $timestamp,
      'date_updated' => $timestamp,
      'created_by' => $id_user,
      'updated_by' => $id_user,
    ];

    $simpan = $this->db->insert('tb_profile', $data);
    if ($simpan) {
      $result = [
        'status' => 200,
        'data' => [
          'header' => 'Berhasil...',
          'body' => 'Berhasil disimpan',
          'status' => 'success'
        ]
      ];
    } else {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oopss...',
          'body' => 'Profile gagal disimpan',
          'status' => 'error'
        ]
      ];
    }

    return $result;
  }

  public function updateProfile($data, $timestamp)
  {
    $id_user = $this->session->userdata('id_user');
    $id = $data['id_user'];
    $data = [
      'nama' => $data['nama'],
      'jabatan' => $data['jabatan'],
      'foto' => 'default.jpg',
      'date_updated' => $timestamp,
      'updated_by' => $id_user,
    ];
    // $this->db->where('id_user', $data['id_user']);
    $update = $this->db->update('tb_profile', $data, ['id_user' => $id]);
    if ($update) {
      $result = [
        'status' => 200,
        'data' => [
          'header' => 'Berhasil...',
          'body' => 'Berhasil disimpan',
          'status' => 'success'
        ]
      ];
    } else {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oopss...',
          'body' => 'Profile gagal disimpan',
          'status' => 'error'
        ]
      ];
    }

    return $result;
  }

  public function getUserByTimestamp($timestamp)
  {
    $result = $this->db->get_where('tb_users', ['date_created' => $timestamp]);
    return $result->row_array();
  }

  public function getPetugas()
  {
    $where = [
      'tb_users.level' => '2',
      'tb_users.level' => '3'
    ];
    $this->db->select('*');
    $this->db->from('tb_users');
    $this->db->join('tb_profile', 'tb_users.id_user=tb_profile.id_user');
    $this->db->where('tb_users.level', '3');
    $this->db->or_where('tb_users.level', '2');
    $result = $this->db->get();

    return $result->result_array();
  }

  public function getBendahara()
  {
    $this->db->select('*');
    $this->db->from('tb_users');
    $this->db->join('tb_profile', 'tb_users.id_user=tb_profile.id_user');
    $this->db->where('tb_users.level', '2');
    $result = $this->db->get();

    return $result;
  }

  public function updatePassword($post)
  {
    $data = [
      'password' => md5($post['new_password']),
      'pass_view' => $post['new_password'],
    ];
    $update = $this->db->update('tb_users', $data, ['id_user' => $post['id_user']]);

    if ($update) {
      $result = [
        'status' => 200,
        'data' => [
          'header' => 'Berhasil...',
          'body' => 'Password berhasil diubah!',
          'status' => 'success'
        ]
      ];
    } else {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oopss...',
          'body' => 'Password gagal diubah!',
          'status' => 'error'
        ]
      ];
    }

    return $result;
  }
}
