<?php
class Menu_model extends CI_Model
{
  public function getAllMenu()
  {
    $level = $this->session->userdata('level');
    $this->db->select('*');
    $this->db->from('tb_menu');
    $this->db->join('tb_access_menu', 'tb_menu.id_menu=tb_access_menu.id_menu');
    $this->db->where('level', $level);
    $result = $this->db->get();
    return $result->result_array();
  }
}
