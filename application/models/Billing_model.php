<?php
class Billing_model extends CI_Model
{
  public function getBillingByIDTiketDesc($id)
  {
    return $this->db->where('id_tiket', $id)
      ->order_by('date_created', 'DESC')
      ->get('tb_billing')
      ->row_array();
  }

  public function save($data)
  {
    $simpan = $this->db->set($data)->insert('tb_billing');
    if ($simpan) {
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
        'status' => 200,
        'data' => [
          'header' => 'Gagal...',
          'body' => 'E-Billing gagal di kirim!',
          'status' => 'error'
        ]
      ];
    }

    return $result;
  }
}
