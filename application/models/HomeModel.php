<?php

defined('BASEPATH') or exit('No direct script access allowed');

class HomeModel extends CI_Model
{
    public function insert($data)
    {
        return $this->db->insert('user', $data);
    }
}

/* End of file HomeModel.php */
