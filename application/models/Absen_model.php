<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absen_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function waktu_absensi()
    {
        $waktu = $this->db->get('config')->row_array();
        $awaldatang = strtotime($waktu['waktu_masuk_awal']);
        $akhirdatang = strtotime($waktu['waktu_masuk_akhir']);
        $awalpulang = strtotime($waktu['waktu_pulang_awal']);
        $akhirpulang = strtotime($waktu['waktu_pulang_akhir']);
        $waktusekarang = strtotime(date('H:i'));

        return ['awaldatang' => $awaldatang, 'akhirdatang' => $akhirdatang, 'awalpulang' => $awalpulang, 'akhirpulang' => $akhirpulang, 'waktu' => $waktusekarang];
    }

    public function check_absensi($nama, $kelas)
    {
        $tepat = $this->db->get_where('data_absensi', ['nama' => $nama, 'devices' => $kelas, 'status' => 1, 'tanggal' => date('d/m/Y')])->result_array();
        $terlambat = $this->db->get_where('data_absensi', ['nama' => $nama, 'devices' => $kelas, 'status' => 2, 'tanggal' => date('d/m/Y')])->result_array();
        if ($tepat == null && $terlambat == null) {
            return "ALLOW_MASUK";
        } else if ($tepat != null || $terlambat || null) {
            return "ALLOW_PULANG";
        } else {
            return "NOT ALLOWED";
        }
    }
    public function save_insert($nama, $id_finger, $kelas, $status)
    {
        $data = [
            'nama' => $nama,
            'id_finger' => $id_finger,
            'devices' => $kelas,
            'tanggal' => date('d/m/Y'),
            'waktu' => date('H:i'),
            'status' => $status,
            'date' => time(),
        ];
        $this->db->insert('data_absensi', $data);
    }

    public function update_insert($nama, $id_finger, $kelas, $status)
    {
        $data = [
            'nama' => $nama,
            'id_finger' => $id_finger,
            'devices' => $kelas,
            'tanggal' => date('d/m/Y'),
        ];

        $this->db->where($data);
        $this->db->set('status_pulang', $status);
        $this->db->update('data_absensi');
    }

    public function get_mode($key)
    {
        $query = $this->db->get_where('devices', ['key_devices' => $key])->row_array();

        if ($query != null) {
            if ($query['mode_devices'] == 1) {
                $mode = "SCAN";
            } else if ($query['mode_devices'] == 2) {
                $mode = "ADD";
            }
            return $mode;
        } else {
            return ['nama_devices' => null, 'KET' => 'DEVICE TIDAK TERDAFTAR'];
        }
    }
    public function device_status($key)
    {
        $query = $this->db->get_where('devices', ['key_devices' => $key])->row_array();
        if ($query == null) {
            return ['nama_devices' => null, 'KET' => 'DEVICE TIDAK TERDAFTAR'];
        } else {
            if ($query['is_active'] == 0) {
                return ['nama_devices' => null, 'KET' => 'DEVICE TIDAK AKTIF'];
            } else {
                return ['nama_devices' => $query['nama_devices'], 'KET' => 'DEVICE AKTIF'];
            }
        }

    }
    public function insert($kelas, $id)
    {
        $absensi = $this->db->get_where('user_data', ['kelas' => $kelas, 'id_finger' => $id])->row_array();
        $waktu = $this->waktu_absensi();
        $check = $this->check_absensi($absensi['nama'], $kelas);
        if ($absensi == null) {
            return (json_encode(array('STATUS' => 'BAD', 'NAMA' => 'BELUM DIDAFTARKAN', 'TANGGAL' => date('d/m/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Hubungi Admin')));
        } else {
            if ($waktu['waktu'] > $waktu['awaldatang'] && $waktu['waktu'] < $waktu['akhirdatang']) {
                if ($check == "ALLOW_MASUK") {
                    $this->save_insert($absensi['nama'], $absensi['id_finger'], $kelas, 1);
                    $this->check_notifikasi('notifikasi_masuk', 'Datang Tepat Waktu', $absensi['nama'], $absensi['nomor_orang_tua']);
                    return (json_encode(array('STATUS' => 'OK', 'NAMA' => $absensi['nama'], 'TANGGAL' => date('d/m/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Selamat Datang')));
                } else {
                    return (json_encode(array('STATUS' => 'BAD', 'NAMA' => $absensi['nama'], 'TANGGAL' => date('d/m/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Sudah Absensi')));
                }
            } else if ($waktu['waktu'] > $waktu['akhirdatang'] && $waktu['waktu'] < $waktu['awalpulang']) {
                if ($check == "ALLOW_MASUK") {
                    $this->save_insert($absensi['nama'], $absensi['id_finger'], $kelas, 2);
                    $this->check_notifikasi('notifikasi_terlambat', 'Datang Terlambat', $absensi['nama'], $absensi['nomor_orang_tua']);
                    return (json_encode(array('STATUS' => 'OK', 'NAMA' => $absensi['nama'], 'TANGGAL' => date('d/m/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Terlambat Datang')));
                } else {
                    return (json_encode(array('STATUS' => 'BAD', 'NAMA' => $absensi['nama'], 'TANGGAL' => date('d/m/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Sudah Absensi')));
                }
            } else if ($waktu['waktu'] > $waktu['awalpulang'] && $waktu['waktu'] < $waktu['akhirpulang']) {
                if ($check == "ALLOW_PULANG") {
                    $this->update_insert($absensi['nama'], $absensi['id_finger'], $kelas, 1);
                    $this->check_notifikasi('notifikasi_pulang', 'Sudah Pulang Sekolah', $absensi['nama'], $absensi['nomor_orang_tua']);
                    return (json_encode(array('STATUS' => 'OK', 'NAMA' => $absensi['nama'], 'TANGGAL' => date('d/m/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Selamat Pulang')));
                } else {
                    return (json_encode(array('STATUS' => 'BAD', 'NAMA' => $absensi['nama'], 'TANGGAL' => date('d/m/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Belum Absensi Pagi')));
                }
            } else {
                return (json_encode(array('STATUS' => 'BAD', 'NAMA' => $absensi['nama'], 'TANGGAL' => date('d/m/Y'), 'WAKTU' => date('H:i:s'), 'KET' => 'Huhungi Admin')));
            }
        }
    }
    public function save_finger($key, $id_finger)
    {
        $device_check = $this->device_status($key);
        if ($device_check != null) {
            $this->db->insert('finger_data', ['devices' => $device_check['nama_devices'], 'id_finger' => $id_finger, 'status' => 0]);
            return (json_encode(array('STATUS' => 'OK', 'ID' => $id_finger, 'KET' => 'Sudah Didaftarkan')));
        }

    }
    public function get_id($key)
    {
        $device_check = $this->device_status($key);
        if ($device_check != null) {
            for ($x = 1; $x <= 127; $x++) {
                $checkfinger = $this->db->get_where('finger_data', ['devices' => $device_check['nama_devices'], 'id_finger' => $x])->row_array();
                if ($checkfinger == null) {
                    return (json_encode(array('STATUS' => 'OK', 'ID' => $x, 'KET' => 'Belum Didaftarkan')));
                    break;
                }
            }
        }
    }

    public function notifikasi_whatsapp($nomer, $nama, $pesan)
    {
        $body = "Ananda Bpk/Ibu " . $nama . ", " . $pesan;
        $post = "nomer=" . $nomer . "&pesan=" . $body;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://128.199.226.92/whatsapp");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $decode = json_decode($result);
    }

    public function check_notifikasi($notif, $pesan, $nama, $nomer)
    {
        $this->db->select($notif);
        $result = $this->db->get('config')->result_array();
        if ($result[0][$notif] == 0) {
            return 'NOT_ALLOWED';
        } else if ($result[0][$notif] == 1) {
            $this->notifikasi_whatsapp($nomer, $nama, $pesan);
            return 'ALLOWED';
        }
    }
}
