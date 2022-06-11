<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tracking extends CI_Controller
{
  private $status;
  private $jenis_pengujian;
  private $simpelkan;

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

    $this->simpelkan = ['url' => 'http://localhost/simpelkan/api/index.php?page='];

    $this->jenis_pengujian = [
      [
        'id' => 1,
        'name' => 'Kebisingan',
      ],
      [
        'id' => 2,
        'name' => 'Iklim Kerja',
      ],
      [
        'id' => 3,
        'name' => 'Intensitas Penerangan',
      ],
      [
        'id' => 4,
        'name' => 'Getaran Seluruh Tubuh',
      ],
      [
        'id' => 5,
        'name' => 'Getaran Lengan Tanggan',
      ],
      [
        'id' => 6,
        'name' => 'Kadar Debu Total',
      ],

      [
        'id' => 7,
        'name' => 'Kualitas Udara Lingkungan Kerja',
      ],
      [
        'id' => 8,
        'name' => 'Radiasi Sinar Ultra Violet',
      ],

      [
        'id' => 9,
        'name' => 'Kualitas Udara Ambien/Tingkat Kebauan',
      ],

      [
        'id' => 10,
        'name' => 'Mikrobiologi di Udara',
      ],

      [
        'id' => 11,
        'name' => 'Kadar Logam di Udara',
      ],

      [
        'id' => 12,
        'name' => 'Radiasi Medan Magnet',
      ],
    ];

    if ($this->session->userdata('level') != 4) {
      $this->status = [
        '0' => 'E-Billing dikirim',
        '1' => 'Verifikasi pembayaran',
        '2' => 'Pembayaran ditolak',
        '3' => 'Pembayaran diterima',
        '4' => 'Estimasi tanggal kegiatan ditetapkan',
        '5' => 'Surat Tugas dikirim',
        '6' => 'Pengambilan sampel dilaksanakan',
        '7' => 'Pengambilan sampel selesai dilaksanakan',
        '8' => 'Sampel masuk ke Analisa Lab',
        '9' => 'Sampel keluar dari Analisa Lab',
        '10' => 'Sampel masuk ke Analisa Hasil',
        '11' => 'Sampel keluar dari Analisa Hasil',
        '12' => 'LHU diverifikasi',
        '13' => 'DHU ditandatangani',
        '14' => 'Laporan dinomori dan dicetak',
        '15' => 'Laporan dikirim',
        '16' => 'Laporan diterima',
      ];
    } else {
      $this->status = [
        '0' => 'Upload bukti pembayaran',
        '1' => 'Menunggu verifikasi pembayaran',
        '2' => 'Bukti pembayaran ditolak',
        '3' => 'Bukti pembayaran diterima',
        '4' => 'Estimasi tanggal kegiatan ditetapkan',
        '5' => 'Surat Tugas telah dikirim',
        '6' => 'Pengambilan sampel',
        '7' => 'Selesai pengambilan sampel',
        '8' => 'Sampel masuk ke Analisa Lab',
        '9' => 'Sampel keluar dari Analisa Lab',
        '10' => 'Sampel masuk ke Analisa Hasil',
        '11' => 'Sampel keluar dari Analisa Hasil',
        '12' => 'LHU diverifikasi',
        '13' => 'DHU ditandatangani',
        '14' => 'Laporan dinomori dan dicetak',
        '15' => 'Laporan dikirim',
        '16' => 'Laporan diterima',
      ];
    }
  }

  function _sendEmail($to, $subject, $message)
  {
    $config = [
      'protocol' => "smtp",
      'smtp_host' => "ssl://mail.simpelkan.org",
      'smtp_user' => "sinotra@simpelkan.org",
      'smtp_pass' => "sinotrabalaik3mdn",
      'smtp_port' => 465,
      'mailtype' => 'html',
      'charset' => 'utf-8',
      'newline' => "\r\n"
    ];

    $this->load->library('email', $config);

    $this->email->from('sinotra@simpelkan.org', 'System SINOTRA');
    $this->email->to($to);
    $this->email->subject($subject);
    $this->email->message($message);

    $this->email->send();
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
    $data['jenis_pengujian'] = $this->jenis_pengujian;
    $this->_template('tracking/ebilling', $data);
  }

  public function get_ticket()
  {
    $data = $this->Ticket_model->getAllTicket();
    $result = '';
    $no = 1;
    foreach ($data as $row) {
      $status = $this->Ticket_model->getStatusByTicketDesc($row['id_tiket']);
      if ($status['status'] <= 3) {
        $url = base_url('index.php/tracking/detail/') . $row['id_tiket'];
        if ($status['status'] == 1) {
          $color = 'warning';
          $aksi = "<div class='btn-group dropstart' role='group'>
                      <button id='aksi' type='button' class='btn btn-sm dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Pilih
                      </button>
                      <ul class='dropdown-menu dropdown-menu-start' aria-labelledby='aksi'>
          <li><button class='dropdown-item' data-bs-toggle='modal' data-date-upload='$status[date_created]' data-bs-tgl='$status[tgl]' data-bs-file='$status[file]' data-bs-target='#iframe-modal'><i class='mdi mdi-eye-outline'></i> Lihat Bukti Pembayaran</button></li>
                <li><button class='dropdown-item' data-bs-toggle='modal' data-bs-id='$row[id_tiket]' data-bs-target='#modal-terima'><i class='mdi mdi-check'></i> Terima Bukti Pembayaran</button></li>        
                <li><button class='dropdown-item' data-bs-toggle='modal' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tolak'><i class='mdi mdi-close'></i> Tolak Bukti Pembayaran</button></li>
                </ul>
                    </div>";
        } elseif ($status['status'] == 2) {
          $color = 'danger';
          $aksi = "<a class='action-icon' href='$url' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Tracking'><i class='uil-location-arrow'></i></a>";
        } elseif ($status['status'] == 3) {
          $color = 'success';
          $aksi = "<a class='action-icon' href='$url' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Tracking'><i class='uil-location-arrow'></i></a>";
        } else {
          $color = 'warning';
          $aksi = "<a class='action-icon' href='$url' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Tracking'><i class='uil-location-arrow'></i></a>";
        }
        $status = $this->status[$status['status']];
        $array_pengujian = explode(',', $row['pengujian']);
        $pengujian = '';
        foreach ($array_pengujian as $row_peng) {
          $nama = $this->jenis_pengujian[$row_peng];
          $pengujian .= "<span class='badge bg-primary'>$nama[name]</span> ";
        }
        $result .= "<tr>
                  <td>$no</td>
                  <td>$row[nama]</td>
                  <td width='300px'>$pengujian</td>
                  <td><span class='badge rounded-pill bg-$color'>$status</span></td>
                  <td>
                  <a href='#' class='action-icon' data-date-upload='$row[date_created]' data-bs-file='$row[file_ebilling]' data-bs-toggle='modal' data-bs-target='#iframe-modal' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Lihat'> <i class='mdi mdi-eye-outline'></i></a>
                  </td>
                  <td>
                        $aksi
                  </td>
                </tr>";
        $no++;
      }
    }
    echo $result;
  }

  public function getPerusahaan()
  {
    $data = $this->Perusahaan_model->getAllPerusahaan();
    $result = "<option value='' selected disabled>Pilih salah satu...</option>";
    foreach ($data as $row) {
      $result .= "<option value='$row[id_user]'>$row[nama]</option>";
    }

    echo $result;
  }

  function uploadFile($dir, $file_name, $file_upload)
  {
    $source = $_FILES["$file_upload"]["tmp_name"];
    $ext = array("jpeg", "jpg", "png", "pdf");
    $file_ext = strtolower(pathinfo($_FILES["$file_upload"]["name"], PATHINFO_EXTENSION));
    $basename = $file_name . '.' . $file_ext;
    $target_file = $dir . $basename;
    $size = $_FILES["$file_upload"]['size'];
    $max_size = 5000000;

    if (!in_array($file_ext, $ext)) {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oops!',
          'body' => 'File ekstensi tidak diijinkan! (pdf, jpg, jpeg, png)',
          'status' => 'error'
        ]
      ];

      return $result;
      exit;
    }

    if ($size > $max_size) {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oops!',
          'body' => 'File terlalu besar! (Maks. 5Mb)',
          'status' => 'error'
        ]
      ];

      return $result;
      exit;
    }

    if (move_uploaded_file($source, $target_file)) {
      $result = [
        'status' => 200,
        'message' => ' Berhasil upload file',
        'name' => $basename
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
    $file_name = "E-BILLING_$date";
    if (isset($_FILES['file_ebilling'])) {
      $upload = $this->uploadFile($dir, $file_name, 'file_ebilling');
      if ($upload['status'] != 200) {
        echo json_encode($upload);
        // echo json_encode($_FILES['file_ebilling']);
        exit;
      } else {
        $file_name = $upload['name'];
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
    $id = $this->session->userdata('id_user');
    $data = $this->Ticket_model->getTicketByUser($id);
    $result = '';
    $no = 1;

    foreach ($data as $row) {
      $url = base_url('index.php/tracking/detail/') . $row['id_tiket'];
      $status = $this->Ticket_model->getStatusByTicketDesc($row['id_tiket']);
      if ($status['status'] == 0) {
        $color = 'warning';
        $aksi = "<div class='btn-group dropstart' role='group'>
                      <button id='aksi' type='button' class='btn btn-sm dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Pilih
                      </button>
                      <ul class='dropdown-menu' aria-labelledby='aksi'>
                      <li><button class='dropdown-item' data-bs-toggle='modal' data-bs-id='$row[id_tiket]' data-bs-target='#modal-pembayaran'><i class='mdi mdi-ticket-confirmation-outline'></i> Upload Bukti Pembayaran</button></li>
                <li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>
                </ul>
                    </div>";
      } else if ($status['status'] == 2) {
        $color = 'danger';
        $aksi = "<div class='btn-group dropstart' role='group'>
                      <button id='aksi' type='button' class='btn btn-sm dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Pilih
                      </button>
                      <ul class='dropdown-menu' aria-labelledby='aksi'>
                      <li><button class='dropdown-item' data-bs-toggle='modal' data-bs-ket='$status[keterangan]' data-bs-id='$row[id_tiket]' data-bs-target='#modal-pembayaran'><i class='mdi mdi-ticket-confirmation-outline'></i> Upload Ulang Bukti Pembayaran</button></li>
                <li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>
                </ul>
                    </div>";
      } else if ($status['status'] == 1) {
        $color = 'warning';
        $aksi = "<div class='btn-group dropstart' role='group'>
                      <button id='aksi' type='button' class='btn btn-sm dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Pilih
                      </button>
                      <ul class='dropdown-menu' aria-labelledby='aksi'>
                      <li><a class='btn dropdown-item' data-bs-file='$status[file]' data-date-upload='$status[date_created]' data-bs-tgl='$status[tgl]' data-bs-toggle='modal' data-bs-target='#iframe-modal'> <i class='mdi mdi-eye-outline'></i> Lihat Bukti Pembayaran</a></li>
        <li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>
        </ul>
                    </div>";
      } else if ($status['status'] == 15) {
        $color = 'warning';
        $aksi = "<div class='btn-group dropstart' role='group'>
                      <button id='aksi' type='button' class='btn btn-sm dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Pilih
                      </button>
                      <ul class='dropdown-menu' aria-labelledby='aksi'>
                      <li><button class='dropdown-item' data-bs-toggle='modal' data-bs-id='$row[id_tiket]' data-bs-target='#modal-pengiriman'><i class='mdi mdi-file-check-outline'></i> Konfirmasi laporan diterima</button></li>
        <li><a class='btn dropdown-item' data-bs-file='$status[file]' data-resi='Y' data-date-upload='$status[date_created]' data-bs-toggle='modal' data-bs-target='#iframe-modal'> <i class='mdi mdi-eye-outline'></i> Lihat Bukti Pengiriman</a></li>
        <li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>
        </ul>
                    </div>";
      } else if ($status['status'] == 3) {
        $color = 'success';
        $aksi = "<a class='action-icon' href='$url' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Tracking'><i class='uil-location-arrow'></i></a>";
        // $aksi = "<a class='action-icon' href='$url'><i class='uil-location-arrow'></i></a>";
      } else {
        $color = 'secondary';
        // $aksi = "<a class='action-icon' href='$url'><i class='uil-location-arrow'></i></a>";
        $aksi = "<a class='action-icon' href='$url' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Tracking'><i class='uil-location-arrow'></i></a>";
      }
      $view_status = $this->status[$status['status']];
      $tgl_status = date('d M Y', strtotime($status['tgl']));
      $array_pengujian = explode(',', $row['pengujian']);
      $pengujian = '';
      foreach ($array_pengujian as $row_peng) {
        $nama = $this->jenis_pengujian[$row_peng];
        $pengujian .= "<span class='badge bg-primary'>$nama[name]</span> ";
      }
      $result .= "<tr>
                  <td>$no</td>
                  <td>$pengujian</td>
                  <td><span class='badge rounded-pill bg-$color'>$view_status</span></td>
                  <td>$tgl_status</td>
                  <td>
                  <a href='#' class='action-icon' data-date-upload='$row[date_created]' data-bs-file='$row[file_ebilling]' data-bs-toggle='modal' data-bs-target='#iframe-modal' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Lihat File'> <i class='mdi mdi-eye-outline'></i></a>
                  </td>
                  <td>
                        $aksi
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
    $file_name = "BUKTI-BAYAR_$date";
    if (isset($_FILES['file'])) {
      $upload = $this->uploadFile($dir, $file_name, 'file');
      if ($upload['status'] != 200) {
        echo json_encode($upload);
        // echo json_encode($_FILES['file_ebilling']);
        exit;
      } else {
        $file_name = $upload['name'];
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

    $bendahara = $this->User_model->getBendahara();
    $url_berkas = base_url() . $dir . $file_name;
    $url = base_url('index.php');
    $perusahaan = $this->Ticket_model->getPerusahaanByID($post['id_tiket']);
    $subject = "Notifikasi pembayaran oleh $perusahaan[nama]";

    if ($bendahara->num_rows() == 1) {
      $row = $bendahara->row_array();
      $message = "Halo $row[nama],<br>Kami informasikan pembayaran billing telah dilakukan oleh $perusahaan[nama]. Cek bukti bayar <a href='$url_berkas' target='blank'>disini</a>, dan login ke <a href='$url' target='blank'>SINOTRA</a> untuk memverifikasi.";
      $email = $row['email'];
      $this->_sendEmail($email, $subject, $message);
    } else if ($bendahara->num_rows() > 1) {
      foreach ($bendahara->result_array() as $row) {
        $message = "Halo $row[nama]<br>Kami informasikan pembayaran billing telah dilakukan oleh $perusahaan[nama]. Cek bukti bayar <a href='$url_berkas' target='blank'>disini</a>, dan login ke <a href='$url' target='blank'>SINOTRA</a> untuk memverifikasi.";
        $email = $row['email'];
        $this->_sendEmail($email, $subject, $message);
      }
    }

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
    $data['status'] = $this->Ticket_model->allStatusByDateDesc($id)->result_array();
    $data['status_now'] = $this->Ticket_model->allStatusByDateDesc($id)->row_array();
    $data['tiket'] = $this->Ticket_model->getTicketByID($id);
    $data['detail_status'] = $this->status;
    $this->_template('tracking/detail', $data);
  }

  public function surat_tugas()
  {
    $data['page'] = 'Surat Tugas';
    $this->_template('tracking/umum/spt', $data);
  }

  public function getTicketByTglKegiatan()
  {
    $data = $this->Ticket_model->getAllTicket();
    $result = '';
    $no = 1;
    foreach ($data as $row) {
      $status = $this->Ticket_model->getStatusByTicketDesc($row['id_tiket']);
      if ($status['status'] == '4' || $status['status'] == '5') {
        $url = base_url('index.php/tracking/detail/') . $row['id_tiket'];
        if ($status['status'] == 4) {
          $tgl = date('d M Y', strtotime($row['tgl_pengujian']));
          $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-tipe='0' data-bs-id='$row[id_tiket]' data-bs-target='#modal-spt'><i class='mdi mdi-upload'></i> Upload Surat Tugas</button></li>
          <li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
        } else {
          $tgl = date('d M Y', strtotime($row['tgl_pengujian']));
          $aksi = "<li><a class='btn dropdown-item' data-bs-file='$status[file]' data-date-upload='$status[date_created]' data-bs-tgl='$status[tgl]' data-bs-toggle='modal' data-bs-target='#iframe-modal'> <i class='mdi mdi-eye-outline'></i> Lihat Surat Tugas</a></li>
          <li><button class='dropdown-item' data-bs-toggle='modal' data-tipe='1' data-bs-id='$row[id_tiket]' data-bs-target='#modal-spt'><i class='mdi mdi-upload'></i> Re-Upload Surat Tugas</button></li>
          <li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
        }
        $view_status = $this->status[$status['status']];
        $array_pengujian = explode(',', $row['pengujian']);
        $pengujian = '';
        foreach ($array_pengujian as $row_peng) {
          $nama = $this->jenis_pengujian[$row_peng];
          $pengujian .= "<span class='badge bg-primary'>$nama[name]</span> ";
        }
        $result .= "<tr>
                  <td>$no</td>
                  <td>$row[nama]</td>
                  <td>$pengujian</td>
                  <td>$tgl</td>
                  <td><span class='badge rounded-pill bg-secondary'>$view_status</span></td>
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
    // $data = $this->User_model->getPetugas();
    $url = $this->simpelkan['url'] . 'getAdminLHU';
    $admin = file_get_contents($url);

    $data = json_decode($admin, true);

    $result = "<option value='' selected disabled>Pilih salah satu...</option>";
    foreach ($data['data'] as $row) {
      $result .= "<option value='$row[id]'>$row[nama]</option>";
    }

    echo $result;
    // echo $admin;
  }

  public function getAnalis()
  {
    // $data = $this->User_model->getPetugas();
    $url = 'http://simpelkan.org/api/index.php?page=getAdminAnalis';
    $admin = file_get_contents($url);

    $data = json_decode($admin, true);

    $result = "<option value='' selected disabled>Pilih salah satu...</option>";
    foreach ($data['data'] as $row) {
      $result .= "<option value='$row[id]'>$row[nama]</option>";
    }

    echo $result;
    // echo $admin;
  }

  public function saveTglRencana()
  {
    $post = $this->input->post();
    $data = [
      'id_tiket' => $post['id_tiket'],
      'status' => '4',
      'tgl' => date('Y-m-d'),
      'keterangan' => 'Tanggal pelaksanaan ditetapkan'
    ];

    $data_tiket = [
      'id_tiket' => $post['id_tiket'],
      'tgl_pengujian' => $post['tgl'],
      'petugas' => $post['petugas'],
      'analis' => $post['analis'],
      'is_read_lhu' => '0',
      'updated_by' => $this->session->userdata('id_user')
    ];
    $simpan = $this->Ticket_model->saveStatus($data, '');
    $update = $this->Ticket_model->updateTiket($data_tiket);

    if ($simpan && $update) {
      $result = [
        'status' => 200,
        'data' => [
          'header' => 'Berhasil...',
          'body' => 'Tanggal berhasil ditetapkan',
          'status' => 'success'
        ]
      ];
    } else {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oops...',
          'body' => 'Tanggal gagal ditetapkan',
          'status' => 'error'
        ]
      ];
    }

    echo json_encode($result);
  }

  public function saveSPT()
  {
    $dir = 'assets/files/';
    $date = date("d-m-Y_H-i-s");
    $file_name = "ST_$date";
    if (isset($_FILES['file'])) {
      $upload = $this->uploadFile($dir, $file_name, 'file');
      if ($upload['status'] != 200) {
        echo json_encode($upload);
        // echo json_encode($_FILES['file_ebilling']);
        exit;
      } else {
        $file_name = $upload['name'];
      }
    } else {
      $file_name = 'default.jpg';
    }

    $post = $this->input->post();
    if ($post['tipe'] == 0) {
      $data = [
        'id_tiket' => $post['id_tiket'],
        'status' => '5',
        'tgl' => date('Y-m-d'),
        'keterangan' => 'Surat tugas dikirim'
      ];
    } else {
      $data = [
        'id_tiket' => $post['id_tiket'],
        'status' => '5',
        'tgl' => date('Y-m-d'),
        'keterangan' => 'Surat tugas diubah'
      ];
    }

    // $data_tiket = [
    //   'id_tiket' => $post['id_tiket'],
    //   'petugas' => $post['petugas'],
    // ];

    $simpan = $this->Ticket_model->saveStatus($data, $file_name);
    // $update = $this->Ticket_model->updateTiket($data_tiket);

    if ($simpan) {
      $result = [
        'status' => 200,
        'data' => [
          'header' => 'Berhasil...',
          'body' => 'Surat Tugas berhasil dikirim',
          'status' => 'success'
        ]
      ];
    } else {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oops...',
          'body' => 'Surat Tugas gagal dikirim',
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
    $id_user = $this->session->userdata('id_user');
    $data = $this->Ticket_model->getAllTicket();
    $result = '';
    $no = 1;
    foreach ($data as $row) {
      if ($row['petugas'] == $id_user) {
        $status = $this->Ticket_model->getStatusByTicketDesc($row['id_tiket']);
        if ($status['status'] >= '5') {
          $url = base_url('index.php/tracking/detail/') . $row['id_tiket'];
          $tgl = date('d M Y', strtotime($row['tgl_pengujian']));
          if ($status['status'] == 5) {
            $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-bs-id='$row[id_tiket]' data-bs-target='#modal-pengujian'><i class='mdi mdi-calendar'></i> Konfirmasi waktu pengujian</button></li>
            <li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
          } else if ($status['status'] > 5) {
            $status_6 = $this->Ticket_model->getTicketByStatus($row['id_tiket'], '6');
            if ($row['tgl_pengujian'] != $status_6['tgl']) {
              $tgl = date('d M Y', strtotime($status_6['tgl']));
            }

            if ($status['status'] == 7) {
              $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='8' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal masuk Analisa Lab</button></li>";
            } elseif ($status['status'] == 8) {
              $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='9' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal selesai Analisa Lab</button></li>";
            } elseif ($status['status'] == 9) {
              $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='10' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal masuk Analisa Hasil</button></li>";
            } elseif ($status['status'] == 10) {
              $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='11' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal selesai Analisa Hasil</button></li>";
            } elseif ($status['status'] == 11) {
              $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='12' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal verifikasi LHU</button></li>";
            } elseif ($status['status'] == 12) {
              $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='13' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal DHU di tandatangani</button></li>";
            } elseif ($status['status'] == 13) {
              $aksi = "<li><button class='dropdown-item' data-bs-toggle='modal' data-status='14' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Tanggal Laporan dinomori dan dicetak</button></li>";
            } else {

              $aksi = '';
            }
            $aksi .= "<li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
          } else {
            $aksi = "<li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
          }
          $view_status = $this->status[$status['status']];
          $tgl_status = date('d M Y', strtotime($status['tgl']));
          $array_pengujian = explode(',', $row['pengujian']);
          $pengujian = '';
          foreach ($array_pengujian as $row_peng) {
            $nama = $this->jenis_pengujian[$row_peng];
            $pengujian .= "<span class='badge bg-primary'>$nama[name]</span> ";
          }
          $result .= "<tr>
                    <td>$no</td>
                    <td>$row[nama]</td>
                    <td>$pengujian</td>
                    <td>$tgl</td>
                    <td><span class='badge rounded-pill bg-secondary'>$view_status</span></td>
                    <td>$tgl_status</td>
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
    }

    echo $result;
  }

  public function saveTglPengujian()
  {
    $post = $this->input->post();
    $data_mulai = [
      'id_tiket' => $post['id_tiket'],
      'status' => '6',
      'tgl' => $post['tgl_awal'],
      'keterangan' => 'Tanggal Mulai Pengujian'
    ];

    $data_akhir = [
      'id_tiket' => $post['id_tiket'],
      'status' => '7',
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

  public function tglPengujian()
  {
    $data['page'] = 'Tanggal Pengujian';
    $this->_template('tracking/tglPengujian', $data);
  }

  public function getAllTiketPengujian()
  {
    $data = $this->Ticket_model->getAllTicket();
    $result = '';
    $no = 1;
    foreach ($data as $row) {
      $status = $this->Ticket_model->getStatusByTicketDesc($row['id_tiket']);
      if ($status['status'] == '3' || $status['status'] == '4') {
        $url = base_url('index.php/tracking/detail/') . $row['id_tiket'];
        if ($status['status'] == 3) {
          $tgl = "<span class='badge rounded-pill bg-warning'>Estimasi tanggal belum ditetapkan</span>";
          $admin_lhu = "<span class='badge rounded-pill bg-warning'>Admin LHU belum dipilih</span>";
          $analis = "<span class='badge rounded-pill bg-warning'>Analis belum dipilih</span>";
          $aksi = "<div class='btn-group' role='group'>
                      <button id='aksi' type='button' class='btn btn-sm dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Pilih
                      </button>
                      <ul class='dropdown-menu' aria-labelledby='aksi'>
                      <li><button class='dropdown-item' data-bs-toggle='modal' data-status='4' data-bs-id='$row[id_tiket]' data-bs-target='#modal-tgl'><i class='mdi mdi-calendar'></i> Estimasi Tanggal Pengujian</button></li>
          <li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>
          </ul>
                    </div>";
        } else {
          $tgl = date('d M Y', strtotime($row['tgl_pengujian']));
          $user = $this->User_model->getUser($row['petugas']);
          $url_lhu = $this->simpelkan['url'] . 'getUserByID';
          $idLHU = ['id' => $row['petugas']];
          $idAnalis = ['id' => $row['analis']];
          $optionsLHU = array(
            "http" => array(
              "method" => "POST",
              "header" => "Content-Type: application/x-www-form-urlencoded",
              "content" => http_build_query($idLHU)
            )
          );
          $apiAdmlhu = file_get_contents($url_lhu, false, stream_context_create($optionsLHU));

          $optionsAnalis = array(
            "http" => array(
              "method" => "POST",
              "header" => "Content-Type: application/x-www-form-urlencoded",
              "content" => http_build_query($idAnalis)
            )
          );
          $apiAnalis = file_get_contents($url_lhu, false, stream_context_create($optionsAnalis));

          // echo $apiAnalis;
          // exit;

          $user_lhu = json_decode($apiAdmlhu, true);
          $user_analis = json_decode($apiAnalis, true);
          $admin_lhu = $user_lhu['data']['nama'];
          $analis = $user_analis['data']['nama'];
          $aksi = "<a class='action-icon' href='$url' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Tracking'><i class='uil-location-arrow'></i></a>";
          // $aksi = "<a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a>";
        }
        $view_status = $this->status[$status['status']];
        $array_pengujian = explode(',', $row['pengujian']);
        $pengujian = '';
        foreach ($array_pengujian as $row_peng) {
          $nama = $this->jenis_pengujian[$row_peng];
          $pengujian .= "<span class='badge bg-primary'>$nama[name]</span> ";
        }
        $result .= "<tr>
                  <td>$no</td>
                  <td>$row[nama]</td>
                  <td>$pengujian</td>
                  <td>$tgl</td>
                  <td>$admin_lhu</td>
                  <td>$analis</td>
                  <td><span class='badge rounded-pill bg-secondary'>$view_status</span></td>
                  <td>
                    
                        $aksi
                      
                  </td>
                </tr>";
        $no++;
      }
    }

    echo $result;
  }

  public function laporan()
  {
    $data['page'] = 'Laporan Pengujian';
    $this->_template('tracking/laporan', $data);
  }

  public function getAllTicketLaporan()
  {
    $data = $this->Ticket_model->getAllTicket();
    $result = '';
    $no = 1;
    foreach ($data as $row) {
      $status = $this->Ticket_model->getStatusByTicketDesc($row['id_tiket']);
      if ($status['status'] == '14' || $status['status'] == '15' || $status['status'] == '16') {
        $url = base_url('index.php/tracking/detail/') . $row['id_tiket'];
        if ($status['status'] == '14') {
          $color = 'warning';
          $aksi = "<div class='btn-group' role='group'>
                      <button id='aksi' type='button' class='btn btn-sm dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Pilih
                      </button>
                      <ul class='dropdown-menu' aria-labelledby='aksi'>
          <li><button class='dropdown-item' data-bs-toggle='modal' data-bs-id='$row[id_tiket]' data-bs-target='#modal-pengiriman'><i class='mdi mdi-file-upload-outline'></i> Upload Bukti Pengiriman</button></li>
          <li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>
          </ul>
                    </div>";
        } else {
          $color = 'secondary';
          $aksi = "<a class='action-icon' href='$url' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Tracking'><i class='uil-location-arrow'></i></a>";
        }
        // $aksi .= "<li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
        $tgl = date('d M Y', strtotime($row['tgl_pengujian']));
        $view_status = $this->status[$status['status']];
        $tgl_status = date('d M Y', strtotime($status['tgl']));
        $array_pengujian = explode(',', $row['pengujian']);
        $pengujian = '';
        foreach ($array_pengujian as $row_peng) {
          $nama = $this->jenis_pengujian[$row_peng];
          $pengujian .= "<span class='badge bg-primary'>$nama[name]</span> ";
        }
        $result .= "<tr>
                  <td>$no</td>
                  <td>$row[nama]</td>
                  <td>$pengujian</td>
                  <td>$tgl</td>
                  <td><span class='badge rounded-pill bg-$color'>$view_status</span></td>
                  <td>$tgl_status</td>
                  <td>
                    
                        $aksi
                      
                  </td>
                </tr>";
        $no++;
      }
    }

    echo $result;
  }

  public function savePengiriman()
  {
    $post = $this->input->post();
    if ($post['jenis_pengiriman'] == 0) {
      $ket = "Laporan dikirim oleh petugas Balai K3 Medan Sdr. " . $post['ekspedisi'];
    } else {
      $ket = "Jasa ekpedisi : " . $post['ekspedisi'];
    }

    $dir = 'assets/files/';
    $date = date("d-m-Y_H-i-s");
    $file_name = "RESI_$date";
    if (isset($_FILES['file'])) {
      $upload = $this->uploadFile($dir, $file_name, 'file');
      if ($upload['status'] != 200) {
        echo json_encode($upload);
        // echo json_encode($_FILES['file_ebilling']);
        exit;
      } else {
        $file_name = $upload['name'];
      }
    } else {
      $file_name = 'default.jpg';
    }

    $data = [
      'id_tiket' => $post['id_tiket'],
      'status' => '15',
      'tgl' => $post['tgl'],
      'keterangan' => $ket
    ];

    $simpan = $this->Ticket_model->saveStatus($data, $file_name);

    if ($simpan) {
      $result = [
        'status' => 200,
        'data' => [
          'header' => 'Berhasil...',
          'body' => 'Bukti pengiriman berhasil dikirim',
          'status' => 'success'
        ]
      ];
    } else {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oops...',
          'body' => 'Bukti pengiriman gagal dikirim',
          'status' => 'error'
        ]
      ];
    }

    echo json_encode($result);
  }

  public function saveKonfirm()
  {
    $post = $this->input->post();

    $dir = 'assets/files/';
    $date = date("d-m-Y_H-i-s");
    $file_name = "BUKTI_TERIMA_$date";
    if (isset($_FILES['file'])) {
      $upload = $this->uploadFile($dir, $file_name, 'file');
      if ($upload['status'] != 200) {
        echo json_encode($upload);
        // echo json_encode($_FILES['file_ebilling']);
        exit;
      } else {
        $file_name = $upload['name'];
      }
    } else {
      $file_name = 'default.jpg';
    }

    $data = [
      'id_tiket' => $post['id_tiket'],
      'status' => '16',
      'tgl' => $post['tgl'],
      'keterangan' => 'Laporan diterima'
    ];

    $simpan = $this->Ticket_model->saveStatus($data, $file_name);

    if ($simpan) {
      $result = [
        'status' => 200,
        'data' => [
          'header' => 'Berhasil...',
          'body' => 'Laporan berhasil diterima',
          'status' => 'success'
        ]
      ];
    } else {
      $result = [
        'status' => 400,
        'data' => [
          'header' => 'Oops...',
          'body' => 'Data gagal disimpan',
          'status' => 'error'
        ]
      ];
    }

    echo json_encode($result);
  }

  public function all()
  {
    $data['page'] = "Semua Pengujian";
    $this->_template('tracking/semua', $data);
  }

  public function getAllPengujian()
  {
    $data = $this->Ticket_model->getAllTicket();
    $result = '';
    $no = 1;
    foreach ($data as $row) {
      $status = $this->Ticket_model->getStatusByTicketDesc($row['id_tiket']);
      $url = base_url('index.php/tracking/detail/') . $row['id_tiket'];
      $color = 'secondary';

      // $aksi .= "<li><a class='btn dropdown-item' href='$url'><i class='uil-location-arrow'></i> Tracking</a></li>";
      $tgl = date('d M Y', strtotime($row['tgl_pengujian']));
      $view_status = $this->status[$status['status']];
      $tgl_status = date('d M Y', strtotime($status['tgl']));
      $array_pengujian = explode(',', $row['pengujian']);
      $pengujian = '';
      foreach ($array_pengujian as $row_peng) {
        $nama = $this->jenis_pengujian[$row_peng];
        $pengujian .= "<span class='badge bg-primary'>$nama[name]</span> ";
      }
      $result .= "<tr>
                  <td>$no</td>
                  <td>$row[nama]</td>
                  <td>$pengujian</td>
                  <td><span class='badge rounded-pill bg-$color'>$view_status</span></td>
                  <td>$tgl_status</td>
                  <td>
                    <a class='action-icon' href='$url' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Tracking'><i class='uil-location-arrow'></i></a>
                  </td>
                </tr>";
      $no++;
    }

    echo $result;
  }
}
