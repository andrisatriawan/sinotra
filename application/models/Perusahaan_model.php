<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perusahaan_model extends CI_Model
{
  public function getPerusahaan($id)
  {
    $this->db->select('*');
    $this->db->from('tb_users');
    $this->db->join('tb_perusahaan', 'tb_users.id_user=tb_perusahaan.id_user');
    $this->db->where('tb_users.id_user', $id);
    $result = $this->db->get();

    return $result->row_array();
  }

  public function getAllPerusahaan()
  {
    $this->db->select('*');
    $this->db->from('tb_users');
    $this->db->join('tb_perusahaan', 'tb_users.id_user=tb_perusahaan.id_user');
    $this->db->order_by('tb_perusahaan.nama', 'ASC');

    $result = $this->db->get();

    return $result->result_array();
  }

  public function savePerusahaan($data, $timestamp)
  {
    $user = $this->getUserByTimestamp($timestamp);
    $id_user = $this->session->userdata('id_user');
    $data = [
      'id_user' => $user['id_user'],
      'nama' => $data['nama'],
      'jabatan' => 'Admin Perusahaan',
      'jenis_perusahaan' => $data['jenis_perusahaan'],
      'alamat' => $data['alamat'],
      'prov' => $data['prov'],
      'kab' => $data['kab'],
      'kec' => $data['kec'],
      'kel' => $data['kel'],
      'no_telp' => $data['no_telp'],
      'foto' => 'default.jpg',
      'date_created' => $timestamp,
      'date_updated' => $timestamp,
      'created_by' => $id_user,
      'updated_by' => $id_user,
    ];

    $simpan = $this->db->insert('tb_perusahaan', $data);
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

  public function getUserByTimestamp($timestamp)
  {
    $result = $this->db->get_where('tb_users', ['date_created' => $timestamp]);
    return $result->row_array();
  }

  public function updatePerusahaan($data, $timestamp)
  {
    $id_user = $this->session->userdata('id_user');
    $id = $data['id_user'];
    $data = [
      'nama' => $data['nama'],
      'jabatan' => 'Admin Perusahaan',
      'jenis_perusahaan' => $data['jenis_perusahaan'],
      'alamat' => $data['alamat'],
      'prov' => $data['prov'],
      'kab' => $data['kab'],
      'kec' => $data['kec'],
      'kel' => $data['kel'],
      'no_telp' => $data['no_telp'],
      'foto' => 'default.jpg',
      'date_updated' => $timestamp,
      'updated_by' => $id_user,
    ];
    // $this->db->where('id_user', $data['id_user']);
    $update = $this->db->update('tb_perusahaan', $data, ['id_user' => $id]);
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

  public function findByID($id)
  {
    $query = $this->db->select('a.id_user as user_id, b.*, c.nama as provinsi, d.nama as kabupaten, e.nama as kecamatan, f.nama as kelurahan')
      ->from('tb_users a')
      ->join('tb_perusahaan b', 'a.id_user=b.id_user')
      ->join('provinsi c', 'b.prov=c.id_prov', 'left')
      ->join('kabupaten d', 'b.kab=d.id_kab', 'left')
      ->join('kecamatan e', 'b.kec=e.id_kec', 'left')
      ->join('kelurahan f', 'b.kel=f.id_kel', 'left')
      ->where('a.id_user', $id)
      ->get()->row_array();

    return $query;
  }
}
