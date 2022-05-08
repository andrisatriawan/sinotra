<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tracking extends CI_Controller
{
  private $status;
  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('username') == null) {
      redirect(base_url('index.php/auth/login'));
    }
    $this->load->model('User_model');
    $this->load->model('Perusahaan_model');
    $this->load->model('Menu_model');
    $this->load->model('Ticket_model');

    if ($this->session->userdata('level') != 4) {
      $this->status = [
        '0' => 'E-Billing dikirim',
        '1' => 'Verifikasi pembayaran',
        '2' => 'Pembayaran ditolak',
        '3' => 'Pembayaran diterima',
        '4' => 'SPT dikirim',
        '5' => 'Pengujian dilaksanakan',
        '6' => 'Pengujian selesai dilaksanakan',
        '7' => 'Sampel masuk ke Analisa Lab',
        '8' => 'Sampel keluar dari Analisa Lab',
        '9' => 'Sampel masuk ke Analisa Hasil',
        '10' => 'Sampel keluar dari Analisa Hasil',
        '11' => 'Verifikasi LHU',
        '12' => 'DHU ditandatangani',
        '13' => 'Laporan dinomori dan dicetak',
        '14' => 'Laporan dikirim',
      ];
    } else {
      $this->status = [
        '0' => 'Upload bukti pembayaran',
        '1' => 'Menunggu verifikasi pembayaran',
        '2' => 'Bukti pembayaran ditolak',
        '3' => 'Bukti pembayaran diterima',
        '4' => 'Tanggal pengujian ditetapkan',
        '5' => 'Pengambilan contoh uji',
        '6' => 'Selesai pengambilan contoh uji',
        '7' => 'Sampel masuk ke Analisa Lab',
        '8' => 'Sampel keluar dari Analisa Lab',
        '9' => 'Sampel masuk ke Analisa Hasil',
        '10' => 'Sampel keluar dari Analisa Hasil',
        '11' => 'Verifikasi LHU',
        '12' => 'DHU ditandatangani',
        '13' => 'Laporan dinomori dan dicetak',
        '14' => 'Laporan dikirim',
      ];
    }
  }

  function _template($url, $data)
  {
    $level = $this->session->userdata('level');
    $id_user = $this->session->userdata('id_user');
    if ($level != 4) {
      $data['user'] = $this->User_model->getUser($id_user);
    } else {
      $data['user'] = $this->Perusahaan_model->getPerusahaan($id_user);
    }

    $data['menu'] = $this->Menu_model->getAllMenu();

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view($url, $data);
    $this->load->view('template/footer', $data);
  }

  public function ebilling()
  {
    $data['page'] = "E-Billing";
    $this->_template('tracking/ebilling', $data);
  }

  public function get_ticket()
  {
    $data = $this->Ticket_model->getAllTicket();
    $result = '';
    $no = 1;
    foreach ($data as $row) {
      $status = $this->Ticket_model->getStatusByTicketDesc($row['id_tiket']);
      $url = base_url('index.php/tracking/detail/') . $row['id_tiket'];
      if ($status['status'] == 1) {
        $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-date-upload='$status[date_created]' data-bs-tgl='$status[tgl]' data-bs-file='$status[file]' data-bs-target='#iframe-modal'><i class='mdi mdi-eye-outline'></i> Lihat Bukti Pembayaran</button></li>
                <li><button class='dropdown-item' data-bs-toggle='modal' data-bs-id='$row[id_tiket]' data-bs-target='#modal-terima'><i class='mdi mdi-check'></i> Terima Bukti Pembayaran</button></li>        
                <li><button class='dropdown-item' data-bs-toggle='modal' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tolak'><i class='mdi mdi-close'></i> Tolak Bukti Pembayaran</button></li>";
      } else {
        $aksi = "<li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
      }
      $status = $this->status[$status['status']];
      $result .= "<tr>
                  <td>$no</td>
                  <td>$row[nama]</td>
                  <td>$row[pengujian]</td>
                  <td>$status</td>
                  <td>
                  <a href='#' class='action-icon' data-date-upload='$row[date_created]' data-bs-file='$row[file_ebilling]' data-bs-toggle='modal' data-bs-target='#iframe-modal'> <i class='mdi mdi-eye-outline'></i></a>
                  </td>
                  <td>
                    <div class='btn-group' role='group'>
                      <button id='aksi' type='button' class='btn btn-sm dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Pilih
                      </button>
                      <ul class='dropdown-menu' aria-labelledby='aksi'>
                        $aksi
                      </ul>
                    </div>
                  </td>
                </tr>";
      $no++;
    }
    echo $result;
  }

  public function getPerusahaan()
  {
    $data = $this->Perusahaan_model->getAllPerusahaan();
    $result = "<option value='' selected disabled>Pilih salah satu...</option>";
    foreach ($data as $row) {
      $result .= "<option value='$row[id_perusahaan]'>$row[nama]</option>";
    }

    echo $result;
  }

  function uploadFile($dir, $file_name, $file_upload)
  {
    $source = $_FILES["$file_upload"]["tmp_name"];
    $basename = $file_name;
    $target_file = $dir . $basename;
    if (move_uploaded_file($source, $target_file)) {
      $result = [
        'status' => 200,
        'message' => ' Berhasil upload file'
      ];
    } else {
      $result = [
        'status' => 400,
        'message' => 'Gagal upload file'
      ];
    }

    return $result;
  }

  public function save()
  {
    $dir = 'assets/files/';
    $date = date("d-m-Y_H-i-s");
    $file_name = "E-BILLING_$date.pdf";
    if (isset($_FILES['file_ebilling'])) {
      $upload = $this->uploadFile($dir, $file_name, 'file_ebilling');
      if ($upload['status'] != 200) {
        $file_name = 'default.jpg';
      }
    } else {
      $file_name = 'default.jpg';
    }

    $simpan = $this->Ticket_model->saveTicket($this->input->post(), $file_name);

    echo json_encode($simpan);
  }

  public function pengujian()
  {
    $data['page'] = 'Semua Pengujian';
    $this->_template('tracking/pengujian', $data);
  }

  public function getTicketByUser()
  {
    $data = $this->Ticket_model->getTicketByUser();
    $result = '';
    $no = 1;

    foreach ($data as $row) {
      $url = base_url('index.php/tracking/detail/') . $row['id_tiket'];
      $status = $this->Ticket_model->getStatusByTicketDesc($row['id_tiket']);
      if ($status['status'] == 0) {
        $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-bs-id='$row[id_tiket]' data-bs-target='#modal-pembayaran'><i class='mdi mdi-ticket-confirmation-outline'></i> Upload Bukti Pembayaran</button></li>
                <li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
      } else if ($status['status'] == 2) {
        $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-bs-ket='$status[keterangan]' data-bs-id='$row[id_tiket]' data-bs-target='#modal-pembayaran'><i class='mdi mdi-ticket-confirmation-outline'></i> Upload Ulang Bukti Pembayaran</button></li>
                <li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
      } else if ($status['status'] == 1) {
        $aksi = "<li><a class='btn dropdown-item' data-bs-file='$status[file]' data-date-upload='$status[date_created]' data-bs-tgl='$status[tgl]' data-bs-toggle='modal' data-bs-target='#iframe-modal'> <i class='mdi mdi-eye-outline'></i> Lihat Bukti Pembayaran</a></li>
        <li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
      } else {
        $aksi = "<li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
      }
      $status = $this->status[$status['status']];
      $result .= "<tr>
                  <td>$no</td>
                  <td>$row[pengujian]</td>
                  <td>$status</td>
                  <td>
                  <a href='#' class='action-icon' data-date-upload='$row[date_created]' data-bs-file='$row[file_ebilling]' data-bs-toggle='modal' data-bs-target='#iframe-modal'> <i class='mdi mdi-eye-outline'></i></a>
                  </td>
                  <td>
                    <div class='btn-group' role='group'>
                      <button id='aksi' type='button' class='btn btn-sm dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Pilih
                      </button>
                      <ul class='dropdown-menu' aria-labelledby='aksi'>
                        $aksi
                      </ul>
                    </div>
                  </td>
                </tr>";
      $no++;
    }

    echo $result;
  }

  public function saveBuktiBayar()
  {
    $dir = 'assets/files/';
    $date = date("d-m-Y_H-i-s");
    $file_name = "BUKTI-BAYAR_$date.pdf";
    if (isset($_FILES['file'])) {
      $upload = $this->uploadFile($dir, $file_name, 'file');
      if ($upload['status'] != 200) {
        $file_name = 'default.jpg';
      }
    } else {
      $file_name = 'default.jpg';
    }

    $post = $this->input->post();
    $data = [
      'id_tiket' => $post['id_tiket'],
      'status' => '1',
      'tgl' => $post['tgl'],
      'keterangan' => 'Bukti bayar dikirim'
    ];

    $simpan = $this->Ticket_model->saveStatus($data, $file_name);

    if ($simpan) {
      $result = [
        'status' => 200,
        'data' => [
          'header' => 'Berhasil...',
          'body' => 'Bukti bayar berhasil dikirim',
          'status' => 'success'
        ]
      ];
    } else {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oops...',
          'body' => 'Bukti bayar gagal dikirim',
          'status' => 'error'
        ]
      ];
    }

    echo json_encode($result);
  }

  public function rejectPembayaran()
  {
    $post = $this->input->post();
    $data = [
      'id_tiket' => $post['id_tiket'],
      'status' => '2',
      'tgl' => date('Y-m-d'),
      'keterangan' => $post['keterangan']
    ];

    $simpan = $this->Ticket_model->saveStatus($data, '');

    if ($simpan) {
      $result = [
        'status' => 200,
        'data' => [
          'header' => 'Berhasil...',
          'body' => 'Bukti bayar berhasil ditolak',
          'status' => 'success'
        ]
      ];
    } else {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oops...',
          'body' => 'Bukti bayar gagal ditolak',
          'status' => 'error'
        ]
      ];
    }

    echo json_encode($result);
  }

  public function acceptPembayaran()
  {
    $post = $this->input->post();
    $data = [
      'id_tiket' => $post['id_tiket'],
      'status' => '3',
      'tgl' => date('Y-m-d'),
      'keterangan' => 'Bukti bayar diterima'
    ];

    $simpan = $this->Ticket_model->saveStatus($data, '');

    if ($simpan) {
      $result = [
        'status' => 200,
        'data' => [
          'header' => 'Berhasil...',
          'body' => 'Bukti bayar berhasil diterima',
          'status' => 'success'
        ]
      ];
    } else {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oops...',
          'body' => 'Bukti bayar gagal diterima',
          'status' => 'error'
        ]
      ];
    }

    echo json_encode($result);
  }

  public function detail($id)
  {
    $data['page'] = 'Detail';
    $data['status'] = $this->Ticket_model->allStatusByDateDesc($id);
    $data['detail_status'] = $this->status;
    $this->_template('tracking/detail', $data);
  }

  public function spt()
  {
    $data['page'] = 'SPT';
    $this->_template('tracking/umum/spt', $data);
  }

  public function getTicketByTglKegiatan()
  {
    $data = $this->Ticket_model->getAllTicket();
    $result = '';
    $no = 1;
    foreach ($data as $row) {
      $status = $this->Ticket_model->getStatusByTicketDesc($row['id_tiket']);
      if ($status['status'] == '3' || $status['status'] == '4') {
        $url = base_url('index.php/tracking/detail/') . $row['id_tiket'];
        if ($status['status'] == 3) {
          $tgl = "Tanggal pengujian belum di tetapkan";
          $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-bs-id='$row[id_tiket]' data-bs-target='#modal-spt'><i class='mdi mdi-upload'></i> Upload SPT</button></li>";
        } else {
          $tgl = date('d M Y', strtotime($status['tgl']));
          $aksi = "<li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
        }
        $view_status = $this->status[$status['status']];
        $result .= "<tr>
                  <td>$no</td>
                  <td>$row[nama]</td>
                  <td>$row[pengujian]</td>
                  <td>$tgl</td>
                  <td>$view_status</td>
                  <td>
                    <div class='btn-group' role='group'>
                      <button id='aksi' type='button' class='btn btn-sm dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Pilih
                      </button>
                      <ul class='dropdown-menu' aria-labelledby='aksi'>
                        $aksi
                      </ul>
                    </div>
                  </td>
                </tr>";
        $no++;
      }
    }

    echo $result;
  }

  public function getPetugas()
  {
    $data = $this->User_model->getPetugas();
    $result = "<option value='' selected disabled>Pilih salah satu...</option>";
    foreach ($data as $row) {
      $result .= "<option value='$row[id_user]'>$row[nama]</option>";
    }

    echo $result;
  }

  public function saveSPT()
  {
    $dir = 'assets/files/';
    $date = date("d-m-Y_H-i-s");
    $file_name = "SPT_$date.pdf";
    if (isset($_FILES['file'])) {
      $upload = $this->uploadFile($dir, $file_name, 'file');
      if ($upload['status'] != 200) {
        $file_name = 'default.jpg';
      }
    } else {
      $file_name = 'default.jpg';
    }

    $post = $this->input->post();
    $data = [
      'id_tiket' => $post['id_tiket'],
      'status' => '4',
      'tgl' => date('Y-m-d'),
      'keterangan' => 'SPT ditetapkan'
    ];

    $simpan = $this->Ticket_model->saveStatus($data, $file_name);
    $update = $this->Ticket_model->updateTiket($post);

    if ($simpan && $update) {
      $result = [
        'status' => 200,
        'data' => [
          'header' => 'Berhasil...',
          'body' => 'SPT berhasil dikirim',
          'status' => 'success'
        ]
      ];
    } else {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oops...',
          'body' => 'SPT gagal dikirim',
          'status' => 'error'
        ]
      ];
    }

    echo json_encode($result);
  }

  public function all_pengujian()
  {
    $data['page'] = 'Semua Pengujian';
    $this->_template('tracking/petugas/pengujian', $data);
  }

  public function getAllTicketPetugas()
  {
    $data = $this->Ticket_model->getAllTicket();
    $result = '';
    $no = 1;
    foreach ($data as $row) {
      $status = $this->Ticket_model->getStatusByTicketDesc($row['id_tiket']);
      if ($status['status'] >= '4') {
        $url = base_url('index.php/tracking/detail/') . $row['id_tiket'];
        $tgl = date('d M Y', strtotime($status['tgl']));
        if ($status['status'] == 4) {
          $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-bs-id='$row[id_tiket]' data-bs-target='#modal-pengujian'><i class='mdi mdi-calendar'></i> Konfirmasi waktu pengujian</button></li>
          <li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
        } else if ($status['status'] > 4) {
          $status_5 = $this->Ticket_model->getTicketByStatus($row['id_tiket'], '5');
          if ($row['tgl_pengujian'] != $status_5['tgl']) {
            $tgl = date('d M Y', strtotime($status_5['tgl']));
          }

          if ($status['status'] == 6) {
            $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='7' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal masuk Analisa Lab</button></li>";
          } elseif ($status['status'] == 7) {
            $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='8' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal selesai Analisa Lab</button></li>";
          } elseif ($status['status'] == 8) {
            $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='9' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal masuk Analisa Hasil</button></li>";
          } elseif ($status['status'] == 9) {
            $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='10' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal selesai Analisa Hasil</button></li>";
          } elseif ($status['status'] == 10) {
            $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='11' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal verifikasi LHU</button></li>";
          } elseif ($status['status'] == 11) {
            $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='12' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal DHU di tandatangani</button></li>";
          } elseif ($status['status'] == 12) {
            $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='13' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal Laporan dinomori dan dicetak</button></li>";
          } elseif ($status['status'] == 13) {
            $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='14' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal Laporan di kirim</button></li>";
          } else {
            $aksi = '';
          }
          $aksi .= "<li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
        } else {
          $aksi = "<li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
        }
        $view_status = $this->status[$status['status']];
        $result .= "<tr>
                  <td>$no</td>
                  <td>$row[nama]</td>
                  <td>$row[pengujian]</td>
                  <td>$tgl</td>
                  <td>$view_status</td>
                  <td>
                    <div class='btn-group' role='group'>
                      <button id='aksi' type='button' class='btn btn-sm dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Pilih
                      </button>
                      <ul class='dropdown-menu' aria-labelledby='aksi'>
                        $aksi
                      </ul>
                    </div>
                  </td>
                </tr>";
        $no++;
      }
    }

    echo $result;
  }

  public function saveTglPengujian()
  {
    $post = $this->input->post();
    $data_mulai = [
      'id_tiket' => $post['id_tiket'],
      'status' => '5',
      'tgl' => $post['tgl_awal'],
      'keterangan' => 'Tanggal Mulai Pengujian'
    ];

    $data_akhir = [
      'id_tiket' => $post['id_tiket'],
      'status' => '6',
      'tgl' => $post['tgl_akhir'],
      'keterangan' => 'Tanggal Selesai Pengujian'
    ];

    $simpan_mulai = $this->Ticket_model->saveStatus($data_mulai, '');

    $simpan_akhir = $this->Ticket_model->saveStatus($data_akhir, '');
    if ($simpan_mulai && $simpan_akhir) {
      $result = [
        'status' => 200,
        'data' => [
          'header' => 'Berhasil...',
          'body' => 'Tanggal pengujian berhasil dikonfirmasi',
          'status' => 'success'
        ]
      ];
    } else {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oops...',
          'body' => 'Tanggal pengujian gagal dikonfirmasi',
          'status' => 'error'
        ]
      ];
    }

    echo json_encode($result);
  }

  public function saveTgl()
  {
    $post = $this->input->post();
    $data = [
      'id_tiket' => $post['id_tiket'],
      'status' => $post['status'],
      'tgl' => $post['tgl'],
      'keterangan' => $post['ket']
    ];

    $simpan = $this->Ticket_model->saveStatus($data, '');

    if ($simpan) {
      $result = [
        'status' => 200,
        'data' => [
          'header' => 'Berhasil...',
          'body' => 'Tanggal berhasil dikonfirmasi',
          'status' => 'success'
        ]
      ];
    } else {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oops...',
          'body' => 'Tanggal gagal dikonfirmasi',
          'status' => 'error'
        ]
      ];
    }

    echo json_encode($result);
  }
}
