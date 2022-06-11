<?php

class Ticket_model extends CI_Model
{
  public function getAllTicket()
  {
    $this->db->select('*');
    $this->db->from('tb_tiket');
    $this->db->join('tb_perusahaan', 'tb_tiket.id_perusahaan=tb_perusahaan.id_user');
    $this->db->order_by('tb_tiket.date_created', 'DESC');
    $result = $this->db->get();

    return $result->result_array();
  }

  public function getStatusByTicketDesc($id_tiket)
  {
    $this->db->where('id_tiket', $id_tiket);
    $this->db->order_by('date_created', 'DESC');
    $this->db->order_by('status', 'DESC');
    $result = $this->db->get('tb_status');
    return $result->row_array();
  }

  public function saveTicket($post, $file_name)
  {
    $timestamp = date('Y-m-d H:i:s');
    $id_user = $this->session->userdata('id_user');
    $data = [
      'id_perusahaan' => $post['perusahaan'],
      'pengujian' => $post['pengujian'],
      'no_ebilling' => $post['no_ebilling'],
      'file_ebilling' => $file_name,
      'date_created' => $timestamp,
      'date_updated' => $timestamp,
      'created_by' => $id_user,
      'updated_by' => $id_user
    ];

    $simpan = $this->db->insert('tb_tiket', $data);
    if ($simpan) {
      $tiket = $this->getTiketByTimestamp($timestamp);
      $data = [
        'id_tiket' => $tiket['id_tiket'],
        'status' => '0',
        'tgl' => date('Y-m-d'),
        'keterangan' => 'E-Billing dikirim',
      ];

      $simpan_status = $this->saveStatus($data, $file_name);
      if ($simpan_status) {
        $result = [
          'status' => 200,
          'data' => [
            'header' => 'Berhasil...',
            'body' => 'E-Billing berhasil di kirim!',
            'status' => 'success'
          ]
        ];
      } else {
        $result = [
          'status' => 400,
          'data' => [
            'header' => 'Oops...',
            'body' => 'E-Billing gagal di kirim!',
            'status' => 'error'
          ]
        ];
      }
    } else {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oops...',
          'body' => 'E-Billing gagal di kirim!',
          'status' => 'error'
        ]
      ];
    }

    return $result;
  }

  public function saveStatus($post, $file_name)
  {
    $timestamp = date('Y-m-d H:i:s');
    $id_user = $this->session->userdata('id_user');
    $data = [
      'id_tiket' => $post['id_tiket'],
      'status' => $post['status'],
      'file' => $file_name,
      'tgl' => $post['tgl'],
      'keterangan' => $post['keterangan'],
      'date_created' => $timestamp,
      'date_updated' => $timestamp,
      'created_by' => $id_user,
      'updated_by' => $id_user
    ];

    $simpan = $this->db->insert('tb_status', $data);

    return $simpan;
  }

  public function getTiketByTimestamp($timestamp)
  {
    $result = $this->db->get_where('tb_tiket', ['date_created' => $timestamp]);

    return $result->row_array();
  }

  public function getTicketByUser($id)
  {

    $this->db->order_by('date_created', 'DESC');
    $result = $this->db->get_where('tb_tiket', ['id_perusahaan' => $id]);

    return $result->result_array();
  }

  public function allStatusByDateDesc($id)
  {
    $this->db->order_by('date_created', 'DESC');
    $this->db->order_by('status', 'DESC');
    $result = $this->db->get_where('tb_status', ['id_tiket' => $id]);

    return $result;
  }

  public function getTicketByStatus($id, $status)
  {
    $this->db->where('id_tiket', $id);
    $this->db->where('status', $status);
    $result = $this->db->get('tb_status');

    return $result->row_array();
  }

  public function updateTiket($post)
  {
    $timestamp = date('Y-m-d H:i:s');
    $id_user = $this->session->userdata('id_user');
    $data = [
      'date_updated' => $timestamp,
      'updated_by' => $id_user,
    ];

    $final = array_merge($post, $data);

    $update = $this->db->update('tb_tiket', $final, ['id_tiket' => $post['id_tiket']]);

    return $update;
  }

  public function getTicketByID($id)
  {
    $this->db->select('*');
    $this->db->from('tb_tiket');
    $this->db->join('tb_profile', 'tb_tiket.petugas=tb_profile.id_user');
    $this->db->where('tb_tiket.id_tiket', $id);
    $result = $this->db->get();

    return $result->row_array();
  }

  public function getPerusahaanByID($id)
  {
    $this->db->select('*');
    $this->db->from('tb_tiket');
    $this->db->join('tb_perusahaan', 'tb_tiket.id_perusahaan=tb_perusahaan.id_user');
    $this->db->where('tb_tiket.id_tiket', $id);
    $result = $this->db->get();

    return $result->row_array();
  }
}
