<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserManage extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('person_model', 'person');
        $this->load->library('form_validation');

        check_logged();

    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['devices'] = $this->db->get('devices')->result_array();

        $this->load->helper('url');

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('UserManage/index', $data);
        $this->load->view('templates/footer', $data);

    }

    public function ajax_list()
    {
        $list = $this->person->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $person) {
            $no++;
            $row = array();
            $row[] = $person->NISN;
            $row[] = $person->nama;
            $row[] = $person->kelas;
            $row[] = $person->id_finger;
            $row[] = $person->nomor_orang_tua;

            //add html for action
            $row[] = '<a class="btn btn-sm float-left btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person(' . "'" . $person->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                  <a class="btn btn-sm float-right btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person(' . "'" . $person->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->person->count_all(),
            "recordsFiltered" => $this->person->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $data = $this->person->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add()
    {

        $data = array(
            'NISN' => htmlspecialchars($this->input->post('NISN', true)),
            'nama' => htmlspecialchars($this->input->post('Nama', true)),
            'kelas' => htmlspecialchars($this->input->post('kelas', true)),
            'id_finger' => htmlspecialchars($this->input->post('id_finger', true)),
            'nomor_orang_tua' => htmlspecialchars($this->input->post('nomor_orang_tua', true)),
        );
        $insert = $this->person->save($data);
        echo json_encode(array("status" => true));
    }

    public function ajax_update($id)
    {
        $data = array(
            'NISN' => htmlspecialchars($this->input->post('NISN', true)),
            'nama' => htmlspecialchars($this->input->post('Nama', true)),
            'kelas' => htmlspecialchars($this->input->post('kelas', true)),
            'id_finger' => htmlspecialchars($this->input->post('id_finger', true)),
            'nomor_orang_tua' => htmlspecialchars($this->input->post('nomor_orang_tua', true)),
        );
        $tolo = $this->person->update(array('id' => $id), $data);
        echo json_encode(array("status" => true, "data" => $data));
    }

    public function ajax_delete($id)
    {
        $this->person->delete_by_id($id);
        echo json_encode(array("status" => true));
    }

    public function fetch_database()
    {
        $query = $this->db->get_where('finger_data', ['devices' => $this->input->post('kelas')])->result_array();
        $output = '<option value="">Select ID</option>';
        // var_dump($query);
        foreach ($query as $row) {
            if ($row['status'] != 1) {
                $output .= '<option value="' . $row['id_finger'] . '">ID ' . $row['id_finger'] . '</option>';

            } else {

            }
        }
        echo ($output);
    }

}
