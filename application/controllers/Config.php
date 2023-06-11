<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Config extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_logged();
    }

    public function index()
    {
        $data['title'] = 'Pengaturan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['waktu'] = $this->db->get('config')->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('config/index', $data);
        $this->load->view('templates/footer', $data);
    }
    public function waktu()
    {
        if (isset($_POST['waktudatang']) && isset($_POST['waktupulang'])) {
            $waktudatang = explode("-", $this->input->post('waktudatang'));
            $waktupulang = explode("-", $this->input->post('waktupulang'));
            $data = [
                'waktu_masuk_awal' => $waktudatang[0],
                'waktu_masuk_akhir' => $waktudatang[1],
                'waktu_pulang_awal' => $waktupulang[0],
                'waktu_pulang_akhir' => $waktupulang[1],
            ];
            $this->db->update('config', $data, array('id' => 1));
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil Mengubah Peraturan</div>');
            redirect('config');
        } else {
            echo "error";
        }
    }
    public function notifikasi()
    {
        if (isset($_POST['detail']) && isset($_POST['status'])) {
            $detail = $this->input->post('detail');
            $status = $this->input->post('status');
            $this->db->update('config', [$detail => $status], array('id' => 1));
        }
    }
}
