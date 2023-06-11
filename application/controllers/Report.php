<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('report_model', 'report');
        $this->load->library('form_validation');
        check_logged();

    }

    public function index()
    {
        $data['title'] = 'Report';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['menu'] = $this->data();
        $this->load->helper('url');

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('Report/index', $data);
        $this->load->view('templates/footer', $data);

    }

    public function absensi()
    {
        $data['title'] = 'Report';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        if (isset($_POST['awal']) && isset($_GET['kelas'])) {
            $waktu = explode(' - ', $_POST['daterange']);
            $data['menu'] = $this->data_absensi_range($_GET['kelas'], $_POST['awal'], $_POST['akhir']);
            $data['pdf'] = base_url("Report/laporan_pdf/?kelas=" . $_GET['kelas'] . '&awal=' . $waktu[0] . '&akhir=' . $waktu[1]);
        } else if (isset($_GET['kelas'])) {
            $data['menu'] = $this->data_absensi($_GET['kelas']);
        } else {
            redirect('report');
            exit();
        }
        $this->load->helper('url');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('Report/absensi', $data);
        $this->load->view('templates/footer', $data);
    }

    public function data_absensi($kelas)
    {
        $this->db->like('devices', $kelas);
        $this->db->select(array('id', 'nama', 'tanggal', 'waktu', 'status', 'status_pulang'));
        $this->db->limit(36);
        $query = $this->db->get('data_absensi')->result_array();
        return $query;
    }

    public function data_absensi_range($kelas, $awal, $akhir)
    {

        $this->db->select(array('id', 'nama', 'tanggal', 'waktu', 'status', 'status_pulang'));
        $array = array('devices' => $kelas, 'tanggal >=' => $awal, 'tanggal <=' => $akhir);
        $this->db->where($array);
        $query = $this->db->get('data_absensi')->result_array();
        return $query;
    }

    public function data()
    {
        $device = $this->device();
        $total = count($device);
        $data = [];
        for ($x = 0; $x < $total; $x++) {
            $kelas = $device[$x]['nama_devices'];
            $total = $this->siswa($kelas);
            $detail = [
                'kelas' => $kelas,
                'siswa' => $total,
            ];
            array_push($data, $detail);
        }
        return $data;
    }

    public function ajax_update($id)
    {
        $this->db->set('status_pulang', htmlspecialchars($this->input->post('status', true)));
        $this->db->where('id', $id);
        $this->db->update('data_absensi');
        echo json_encode(array("status" => true, "data" => $this->input->post('status', true)));
    }

    public function fetch_nama()
    {
        $nama = $this->input->post('nama', true);
        $this->db->select('nama');
        $this->db->like('nama', $nama);
        $query = $this->db->get('user_data')->result_array();
        if (count($query) == 0) {
            echo 'Ketik Nama Dengan Benar';
        } else {
            print_r($query[0]['nama']);
        }
    }

    public function ajax_edit($id)
    {
        $data = $this->report->get_by_id($id);
        echo json_encode($data);
    }

    public function device()
    {
        $this->db->select('nama_devices');
        $query = $this->db->get('devices')->result_array();
        return $query;
    }

    public function ajax_add()
    {
        $this->db->select(array('nama', 'kelas', 'id_finger'));
        $this->db->like('nama', htmlspecialchars($this->input->post('nama', true)));
        $detail = $this->db->get('user_data')->result_array();
        $data = [
            'nama' => htmlspecialchars($this->input->post('nama', true)),
            'id_finger' => $detail[0]['id_finger'],
            'devices' => $detail[0]['kelas'],
            'status' => htmlspecialchars($this->input->post('status', true)),
            'tanggal' => htmlspecialchars($this->input->post('tanggal', true)),
            'waktu' => date('H:i'),
            'date' => time(),
        ];
        $this->db->insert('data_absensi', $data);
        echo json_encode(array("status" => true));

    }

    public function siswa($kelas)
    {
        $this->db->like('kelas', $kelas);
        $this->db->from('user_data');
        $total = $this->db->count_all_results();
        return $total;
        // Produces an integer, like 17
    }

    public function laporan_pdf()
    {
        $this->load->library('pdf');
        $data['awal'] = $_GET['awal'];
        $data['akhir'] = $_GET['akhir'];
        $data['kelas'] = $_GET['kelas'];
        $data['menu'] = $this->laporan_absensi($_GET['kelas'], $_GET['awal'], $_GET['akhir']);
        $this->load->view('Print/absensi', $data);

        $paper_size = 'A4';
        $orientation = 'landscape';
        $html = $this->output->get_output();
        $this->pdf->set_paper($paper_size, $orientation);
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("Laporan_Absensi.pdf", array("Attachment" => 0));

    }

    public function laporan_absensi($kelas, $awal, $akhir)
    {
        $data = $this->siswa_kelas($kelas);
        $tanggal = $this->tanggal_masuk($awal, $akhir);
        $data_absensi = [];
        for ($a = 0; $a < count($data); $a++) {
            $siswa = $data[$a]['nama'];
            $alfa = 0;
            $masuk = 0;
            $ijin = 0;
            $sakit = 0;
            $terlambat = 0;
            $bolos = 0;
            for ($x = 0; $x < count($tanggal); $x++) {
                $tanggalnya = $tanggal[$x]['tanggal'];
                $absensinya = $this->check_absensi($siswa, $kelas, $tanggalnya);
                if ($absensinya == 'ALFA') {
                    $alfa++;
                } else if ($absensinya == 'MASUK') {
                    $masuk++;
                } else if ($absensinya == 'TERLAMBAT') {
                    $terlambat++;
                    $masuk++;
                } else if ($absensinya == 'SAKIT') {
                    $sakit++;
                } else if ($absensinya == 'IJIN') {
                    $ijin++;
                } else if ($absensinya == 'BOLOS') {
                    $bolos++;
                }
            }
            $detail = [
                'NAMA' => $siswa,
                'KELAS' => $kelas,
                'Hari Efektif' => count($tanggal),
                'Alfa' => $alfa,
                'Masuk' => $masuk,
                'Terlambat' => $terlambat,
                'Ijin' => $ijin,
                'Sakit' => $sakit,
                'Bolos' => $bolos,
            ];
            array_push($data_absensi, $detail);
        }
        return $data_absensi;
    }

    public function siswa_kelas($kelas)
    {
        $this->db->select(array('nama', 'kelas'));
        $this->db->like('kelas', $kelas);
        $query = $this->db->get('user_data')->result_array();
        return $query;
    }

    public function tanggal_masuk()
    {
        $awal = '01/03/2020';
        $akhir = '10/03/2020';
        $kelas = '11 MIPA 7';
        $this->db->select('tanggal');
        $array = array('devices' => $kelas, 'tanggal >=' => $awal, 'tanggal <=' => $akhir);
        $this->db->where($array);
        $query = $this->db->get('data_absensi')->result_array();
        function unique_key($array, $keyname)
        {
            $new_array = array();
            foreach ($array as $key => $value) {

                if (!isset($new_array[$value[$keyname]])) {
                    $new_array[$value[$keyname]] = $value;
                }
            }
            $new_array = array_values($new_array);
            return $new_array;
        }
        $tanggal_efektif = unique_key($query, 'tanggal');
        return $tanggal_efektif;

    }

    public function check_absensi($siswa, $kelas, $tanggalnya)
    {
        $this->db->select(array('status', 'status_pulang'));
        $array = array('nama' => $siswa, 'devices' => $kelas, 'tanggal' => $tanggalnya);
        $this->db->where($array);
        $query = $this->db->get('data_absensi')->result_array();
        if (empty($query)) {
            $KET = 'ALFA';
        } else if ($query[0]['status'] == 1 && $query[0]['status_pulang'] == 1) {
            $KET = 'MASUK';
        } else if ($query[0]['status'] == 2 && $query[0]['status_pulang'] == 1) {
            $KET = 'TERLAMBAT';
        } else if ($query[0]['status'] == 3 && $query[0]['status_pulang'] == 0) {
            $KET = 'SAKIT';
        } else if ($query[0]['status'] == 4 && $query[0]['status_pulang'] == 0) {
            $KET = 'IJIN';
        } else if ($query[0]['status'] == 1 || $query[0]['status'] == 2 && $query[0]['status_pulang'] == 0) {
            $KET = 'BOLOS';
        } else {
            $KET = 'ALFA';
        }

        return $KET;
    }
}
