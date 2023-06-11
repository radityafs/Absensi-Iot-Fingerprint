<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absen extends CI_Controller
{

    public function index()
    {
        $this->load->view('errors/index.html');
    }
    public function mode()
    {
        if (isset($_GET['key'])) {
            $key = $this->input->get('key');
            $check = $this->db->get_where('devices', ['key_devices' => $key])->row_array();
            $checkmode = $check['mode_devices'];
            if ($checkmode == 1) {
                $mode = "SCAN";
            } else if ($checkmode == 2) {
                $mode = "ADD";
            } else {
                $mode = "ERROR";
            }
            if ($check == null) {
                echo (json_encode(array('STATUS' => 'BAD', 'KET' => 'KEY TIDAK VALID')));
            } else {
                echo (json_encode(array('STATUS' => 'OK', 'mode' => $mode, 'KET' => 'SUCCESSFULY')));
            }
        } else {
            echo (json_encode(array('STATUS' => 'BAD', 'KET' => 'KEY TIDAK ADA')));
        }
    }

    public function insert()
    {
        $this->load->model('person_model', 'person');
        $waktu = $this->db->get('config')->row_array();

        if (isset($_GET['key']) && isset($_GET['id'])) {
            $key = $this->input->get('key');
            $id = $this->input->get('id');

            $check = $this->db->get_where('devices', ['key_devices' => $key])->row_array();
            if ($check == null) {
                echo (json_encode(array('STATUS' => 'BAD', 'KET' => 'DEVICE TIDAK TERDAFTAR')));
            } else {
                $check = $this->db->get_where('devices', ['key_devices' => $key])->row_array();
                if ($check['is_active'] == 0) {
                    echo (json_encode(array('STATUS' => 'BAD', 'KET' => 'DEVICE TIDAK AKTIF')));
                } else {
                    $check1 = $this->db->get_where('devices', ['key_devices' => $key])->row_array();
                    $kelas = $check1['nama_devices'];
                    $check = $this->db->get_where('user_data', ['kelas' => $kelas, 'id_finger' => $id])->row_array();
                    if ($check == null) {
                        echo (json_encode(array('STATUS' => 'BAD', 'KET' => 'ID FINGER INVALID')));
                    } else {
                        $awaldatang = strtotime($waktu['waktu_masuk_awal']);
                        $akhirdatang = strtotime($waktu['waktu_masuk_akhir']);
                        $awalpulang = strtotime($waktu['waktu_pulang_awal']);
                        $akhirpulang = strtotime($waktu['waktu_pulang_akhir']);
                        $waktu = strtotime(date('H:i'));

                        if ($waktu > $awaldatang && $waktu < $akhirdatang) {
                            $absensi1 = $this->db->get_where('data_absensi', ['nama' => $check['nama'], 'devices' => $check1['nama_devices'], 'status' => 1, 'tanggal' => date('m/d/Y')])->result_array();
                            $absensi2 = $this->db->get_where('data_absensi', ['nama' => $check['nama'], 'devices' => $check1['nama_devices'], 'status' => 2, 'tanggal' => date('m/d/Y')])->result_array();

                            if ($absensi1 == null && $absensi2 == null) {
                                echo (json_encode(array('STATUS' => 'OK', 'NAMA' => $check['nama'], 'TANGGAL' => date('m/d/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Selamat Datang')));
                                $data = [
                                    'nama' => $check['nama'],
                                    'id_finger' => $check['id_finger'],
                                    'devices' => $check1['nama_devices'],
                                    'tanggal' => date('m/d/Y'),
                                    'waktu' => date('H:i'),
                                    'status' => 1,
                                    'date' => time(),
                                ];
                                $this->db->insert('data_absensi', $data);
                                $message = 'Ananda ' . $check['nama'] . ' Sudah Sampai Disekolah';
                                $this->person->sendwhatsapp($check['nomor_orang_tua'], $message);

                            } else {
                                echo (json_encode(array('STATUS' => 'BAD', 'NAMA' => $check['nama'], 'TANGGAL' => date('m/d/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Sudah Absensi')));
                            }
                        } else if ($waktu > $akhirdatang && $waktu < $awalpulang) {
                            $absensi1 = $this->db->get_where('data_absensi', ['nama' => $check['nama'], 'devices' => $check1['nama_devices'], 'status' => 1, 'tanggal' => date('m/d/Y')])->result_array();
                            $absensi2 = $this->db->get_where('data_absensi', ['nama' => $check['nama'], 'devices' => $check1['nama_devices'], 'status' => 2, 'tanggal' => date('m/d/Y')])->result_array();
                            if ($absensi1 == null && $absensi2 == null) {
                                echo (json_encode(array('STATUS' => 'OK', 'NAMA' => $check['nama'], 'TANGGAL' => date('m/d/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Belum Absensi Masuk')));
                                $data = [
                                    'nama' => $check['nama'],
                                    'id_finger' => $check['id_finger'],
                                    'devices' => $check1['nama_devices'],
                                    'tanggal' => date('m/d/Y'),
                                    'waktu' => date('H:i'),
                                    'status' => 2,
                                    'date' => time(),
                                ];
                                $this->db->insert('data_absensi', $data);
                                $message = 'Ananda ' . $check['nama'] . ' Terlambat Masuk Sekolah';
                                $this->person->sendwhatsapp($check['nomor_orang_tua'], $message);
                            } else {
                                echo (json_encode(array('STATUS' => 'BAD', 'NAMA' => $check['nama'], 'TANGGAL' => date('m/d/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Sudah Absensi')));
                            }
                        } else if ($waktu > $awalpulang && $waktu < $akhirpulang) {
                            $absensi1 = $this->db->get_where('data_absensi', ['nama' => $check['nama'], 'devices' => $check1['nama_devices'], 'status' => 1, 'tanggal' => date('m/d/Y')])->result_array();
                            $absensi2 = $this->db->get_where('data_absensi', ['nama' => $check['nama'], 'devices' => $check1['nama_devices'], 'status' => 2, 'tanggal' => date('m/d/Y')])->result_array();
                            if ($absensi1 == null && $absensi2 == null) {
                                echo (json_encode(array('STATUS' => 'BAD', 'NAMA' => $check['nama'], 'TANGGAL' => date('m/d/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Belum Absensi')));
                            } else {
                                echo (json_encode(array('STATUS' => 'OK', 'NAMA' => $check['nama'], 'TANGGAL' => date('m/d/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Selamat Pulang')));
                                $data = [
                                    'nama' => $check['nama'],
                                    'id_finger' => $check['id_finger'],
                                    'devices' => $check1['nama_devices'],
                                    'tanggal' => date('m/d/Y'),
                                    'waktu' => date('H:i'),
                                    'status' => 3,
                                    'date' => time(),
                                ];
                                $this->db->insert('data_absensi', $data);
                                $message = 'Ananda ' . $check['nama'] . ' Sudah Pulang Sekolah';
                                if ($notif == true) {
                                    $this->person->sendwhatsapp($check['nomor_orang_tua'], $message);
                                } else {

                                }
                            }
                        } else {
                            echo (json_encode(array('STATUS' => 'BAD', 'NAMA' => $check['nama'], 'TANGGAL' => date('m/d/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Huhungi Admin')));
                        }
                    }
                }
            }

        } else {
            echo (json_encode(array('STATUS' => 'BAD', 'KET' => 'KEY & ID TIDAK ADA')));
        }
    }

    public function addfinger()
    {
        if (isset($_GET['key']) && isset($_GET['finger-id'])) {
            $key = $this->input->get('key');
            $idfinger = $this->input->get('finger-id');
            $keydevice = $this->db->get_where('devices', ['key_devices' => $key])->row_array();
            $namadevices = $keydevice['nama_devices'];
            if ($keydevice != null) {
                $data = [
                    'devices' => $namadevices,
                    'id_finger' => $idfinger,
                    'status' => 0,
                ];
                $this->db->insert('finger_data', $data);
                echo (json_encode(array('STATUS' => 'OK', 'ID' => $idfinger, 'KET' => 'Sudah Didaftarkan')));

            }
        }

    }

    public function getid()
    {
        if (isset($_GET['key'])) {
            $key = $this->input->get('key');
            $keydevice = $this->db->get_where('devices', ['key_devices' => $key])->row_array();
            if ($keydevice != null) {
                $checknama = $keydevice['nama_devices'];
                for ($x = 1; $x <= 127; $x++) {
                    $checkfinger = $this->db->get_where('finger_data', ['devices' => $checknama, 'id_finger' => $x])->row_array();
                    if ($checkfinger == null) {
                        echo (json_encode(array('STATUS' => 'OK', 'ID' => $x, 'KET' => 'Belum Didaftarkan')));
                        break;
                    }
                }
            }
        }
    }
}
