<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_model extends CI_Model
{

    public $table = 'data_absensi';
    public $column_order = array(null, 'NISN', 'nama', 'kelas', 'id_finger', 'nomor_orang_tua'); //set column field database for datatable orderable
    public $column_search = array('nama', 'kelas', 'nomor_orang_tua'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    public $order = array('id' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {

        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                {
                    $this->db->group_end();
                }
                //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function save($data)
    {
        $this->db->update('finger_data', ['status' => 1], ['id_finger' => $data['id_finger']]);
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $this->db->update('finger_data', ['status' => 1], ['id_finger' => $data['id_finger']]);
        $idlama = $this->db->get_where('user_data', ['NISN' => $data['NISN']])->row_array();
        $this->db->update('finger_data', ['status' => 0], ['id_finger' => $idlama['id_finger']]);
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
        //  $this->db->affected_rows()
    }

    public function delete_by_id($id)
    {
        $idlama = $this->db->get_where('user_data', ['id' => $id])->row_array();
        $this->db->delete('finger_data', array('id_finger' => $idlama['id_finger']));
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    public function sendwhatsapp($nomer, $message)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://128.199.226.92:3000/whatsapp/?nomer=' . urlencode($nomer) . '&pesan=' . urlencode($message) . '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

}
