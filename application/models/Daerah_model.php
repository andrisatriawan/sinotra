<?php

class Daerah_model extends CI_Model
{
  public function getProv()
  {
    $this->db->order_by('nama', 'ASC');
    $result = $this->db->get('provinsi');
    return $result->result_array();
  }

  public function getKab($id_prov)
  {
    $this->db->where('id_prov', $id_prov);
    $this->db->order_by('nama', 'ASC');
    $result = $this->db->get('kabupaten');
    return $result->result_array();
  }

  public function getKec($id_kab)
  {
    $this->db->where('id_kab', $id_kab);
    $this->db->order_by('nama', 'ASC');
    $result = $this->db->get('kecamatan');
    return $result->result_array();
  }

  public function getKel($id_kec)
  {
    $this->db->where('id_kec', $id_kec);
    $this->db->order_by('nama', 'ASC');
    $result = $this->db->get('kelurahan');
    return $result->result_array();
  }
}
