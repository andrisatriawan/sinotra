<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
  public function getLogin($data)
  {
    $where = [
      'username' => $data['username'],
      'password' => md5($data['password'])
    ];
    $this->db->where($where);
    $this->db->or_where('email', $data['username']);
    $query = $this->db->get('tb_users');

    if ($query->num_rows() > 0) {
      $result = [
        'status' => 200,
        'message' => 'success',
        'data' => $query->row_array()
      ];
    } else {
      $result = [
        'status' => 400,
        'message' => 'failed',
        'data' => 'No data!'
      ];
    }

    return $result;
  }
}
