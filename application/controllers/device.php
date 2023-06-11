<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Device extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_logged();
    }

    public function index()
    {
        $data['title'] = 'Devices Manager';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['menu'] = $this->db->get('devices')->result_array();

        $this->form_validation->set_rules('nama_devices', 'Nama Devices', 'required|max_length[128]');
        $this->form_validation->set_rules('key_devices', 'Key Devices', 'required|max_length[128]');
        $this->form_validation->set_rules('mode_devices', 'Mode Devices', 'required|integer|exact_length[1]');
        $this->form_validation->set_rules('is_active', 'Activated Devices', 'required|integer|exact_length[1]');

        if ($this->form_validation->run() == false) {

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('devices/index', $data);
            $this->load->view('templates/footer', $data);
        } else {
            $data = [
                'nama_devices' => htmlspecialchars($this->input->post('nama_devices', true)),
                'key_devices' => htmlspecialchars($this->input->post('key_devices', true)),
                'mode_devices' => htmlspecialchars($this->input->post('mode_devices', true)),
                'is_active' => htmlspecialchars($this->input->post('is_active', true)),
            ];
            $this->db->insert('devices', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User Successfully Added</div>');
            redirect('Device');
        }
    }

    public function DeleteDevice($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('devices');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Devices Successfully Deleted</div>');
        redirect('Device');
    }

    public function EditDevices($id)
    {
        $data = array(
            'nama_devices' => htmlspecialchars($this->input->post('nama_devices', true)),
            'key_devices' => htmlspecialchars($this->input->post('key_devices', true)),
            'mode_devices' => htmlspecialchars($this->input->post('mode_devices', true)),
            'is_active' => htmlspecialchars($this->input->post('is_active', true)),
        );
        $this->db->where('id', $id);
        $this->db->update('devices', $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Devices Successfully Updated</div>');
        redirect('Device');

    }

}
