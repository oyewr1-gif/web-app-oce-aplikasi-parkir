<?php

class KameraController extends Controller {

    public function index() {
        Session::requireLogin();
        if (Session::get('user_level') != 1) {
            Session::setFlash('Akses terbatas hanya untuk Administrator.', 'danger');
            $this->redirect('/dashboard');
        }

        $kameraModel = $this->model('KameraModel');
        $cameras = $kameraModel->getAllCameras();

        $data = [
            'title' => 'Pengaturan & Monitoring IP Camera',
            'cameras' => $cameras,
            'foto_dir' => 'D:/foto'
        ];

        $this->view('kamera/index', $data);
    }

    public function edit() {
        Session::requireLogin();
        if (Session::get('user_level') != 1) {
            Session::setFlash('Akses ditolak.', 'danger');
            $this->redirect('/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $nama = trim($_POST['nama'] ?? '');
            $lanip = trim($_POST['lanip'] ?? '');
            $user = trim($_POST['user'] ?? '');
            $pass = trim($_POST['pass'] ?? '');
            $encode = trim($_POST['encode'] ?? '');

            if ($id <= 0 || empty($nama) || empty($lanip)) {
                Session::setFlash('Nama kamera dan IP LAN wajib diisi.', 'danger');
                $this->redirect('/kamera');
            }

            $kameraModel = $this->model('KameraModel');
            $success = $kameraModel->updateCamera($id, [
                'nama' => $nama,
                'lanip' => $lanip,
                'user' => $user,
                'pass' => $pass,
                'encode' => $encode
            ]);

            if ($success) {
                Session::setFlash('Konfigurasi kamera ' . htmlspecialchars($nama) . ' berhasil diperbarui.', 'success');
            } else {
                Session::setFlash('Gagal memperbarui konfigurasi kamera.', 'danger');
            }
        }

        $this->redirect('/kamera');
    }

    public function testPing() {
        Session::requireLogin();
        header('Content-Type: application/json');

        $ip = trim($_POST['ip'] ?? '');
        $port = (int)($_POST['port'] ?? 80);

        if (empty($ip)) {
            echo json_encode(['success' => false, 'message' => 'Alamat IP tidak valid']);
            exit;
        }

        // Uji koneksi socket TCP dengan timeout 1.2 detik
        $errno = 0;
        $errstr = '';
        $fp = @fsockopen($ip, $port, $errno, $errstr, 1.2);

        if ($fp) {
            fclose($fp);
            echo json_encode([
                'success' => true, 
                'online' => true,
                'message' => "Koneksi berhasil! IP $ip pada port $port ONLINE."
            ]);
        } else {
            // Coba ping via OS
            $pingResult = false;
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $cmd = "ping -n 1 -w 1000 " . escapeshellarg($ip);
                exec($cmd, $output, $status);
                $pingResult = ($status === 0);
            }

            if ($pingResult) {
                echo json_encode([
                    'success' => true,
                    'online' => true,
                    'message' => "Host $ip merespons Ping ICMP (Port $port tertutup)."
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'online' => false,
                    'message' => "Kamera $ip OFFLINE atau tidak terjangkau di jaringan LAN."
                ]);
            }
        }
        exit;
    }

    // Endpoint penyajian gambar dengan Smart Visual Fallback
    public function foto() {
        $path = trim($_GET['path'] ?? '');
        $label = trim($_GET['label'] ?? 'KAMERA CCTV');
        $idtrx = trim($_GET['idtrx'] ?? '');
        $nopol = trim($_GET['nopol'] ?? '');
        $time = trim($_GET['time'] ?? date('Y-m-d H:i:s'));

        // Cek file fisik jika ada path
        $physicalFileFound = false;
        $targetFile = '';

        if (!empty($path)) {
            $candidates = [
                'D:/foto/' . ltrim($path, '/\\'),
                'D:' . $path,
                'D:/' . ltrim($path, '/\\'),
                dirname(__DIR__, 2) . '/public/' . ltrim($path, '/\\'),
                dirname(__DIR__, 2) . '/public/uploads/' . ltrim($path, '/\\')
            ];

            foreach ($candidates as $cand) {
                if (file_exists($cand) && is_file($cand)) {
                    $physicalFileFound = true;
                    $targetFile = $cand;
                    break;
                }
            }
        }

        if ($physicalFileFound && !empty($targetFile)) {
            $mime = mime_content_type($targetFile) ?: 'image/jpeg';
            header('Content-Type: ' . $mime);
            header('Content-Length: ' . filesize($targetFile));
            header('Cache-Control: public, max-age=86400');
            readfile($targetFile);
            exit;
        }

        // Smart Visual Fallback (SVG Image Placeholder)
        header('Content-Type: image/svg+xml; charset=utf-8');
        header('Cache-Control: public, max-age=3600');

        $cleanLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
        $cleanIdtrx = htmlspecialchars($idtrx ?: 'SIMULASI-OFFLINE', ENT_QUOTES, 'UTF-8');
        $cleanNopol = htmlspecialchars($nopol ?: 'PLAT-SIMULASI', ENT_QUOTES, 'UTF-8');
        $cleanTime  = htmlspecialchars($time, ENT_QUOTES, 'UTF-8');

        echo <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 480 320" width="480" height="320">
    <defs>
        <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#141E30" />
            <stop offset="100%" stop-color="#243B55" />
        </linearGradient>
        <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
            <path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/>
        </pattern>
    </defs>
    
    <!-- Background Frame -->
    <rect width="100%" height="100%" fill="url(#bg)" />
    <rect width="100%" height="100%" fill="url(#grid)" />

    <!-- Camera / CCTV Silhouette Icon -->
    <g transform="translate(190, 80)" fill="none" stroke="#f39c12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 20 h60 a10 10 0 0 1 10 10 v40 a10 10 0 0 1 -10 10 h-60 a10 10 0 0 1 -10 -10 v-40 a10 10 0 0 1 10 -10 z" fill="rgba(243, 156, 18, 0.15)"/>
        <circle cx="50" cy="50" r="16" fill="rgba(243, 156, 18, 0.3)"/>
        <circle cx="50" cy="50" r="8" fill="#f39c12"/>
        <path d="M90 35 l25 -15 v60 l-25 -15" fill="rgba(243, 156, 18, 0.25)"/>
        <circle cx="30" cy="32" r="3" fill="#e74c3c"/>
    </g>

    <!-- Top Info Bar (OSD / On-Screen Display) -->
    <rect x="10" y="10" width="460" height="28" rx="4" fill="rgba(0,0,0,0.6)" />
    <circle cx="25" cy="24" r="5" fill="#e74c3c" />
    <text x="38" y="29" font-family="'Courier New', Courier, monospace" font-size="12" font-weight="bold" fill="#00ffcc">REC [{$cleanLabel}]</text>
    <text x="460" y="29" font-family="'Courier New', Courier, monospace" font-size="12" fill="#ffffff" text-anchor="end">{$cleanTime}</text>

    <!-- Center Label -->
    <text x="240" y="195" font-family="Segoe UI, Helvetica, Arial, sans-serif" font-size="16" font-weight="bold" fill="#ffffff" text-anchor="middle">SNAPSHOT CCTV GATE</text>
    <text x="240" y="218" font-family="Segoe UI, Helvetica, Arial, sans-serif" font-size="12" fill="#bdc3c7" text-anchor="middle">Arsip gambar fisik tidak ditemukan di D:/foto</text>

    <!-- Bottom Watermark OSD Bar -->
    <rect x="10" y="250" width="460" height="60" rx="4" fill="rgba(0,0,0,0.75)" stroke="rgba(243, 156, 18, 0.5)" stroke-width="1"/>
    <text x="25" y="272" font-family="'Courier New', Courier, monospace" font-size="12" fill="#f39c12">ID TIKET : {$cleanIdtrx}</text>
    <text x="25" y="295" font-family="'Courier New', Courier, monospace" font-size="14" font-weight="bold" fill="#2ecc71">NOPOL    : {$cleanNopol}</text>
    <text x="455" y="295" font-family="'Courier New', Courier, monospace" font-size="11" fill="#7f8c8d" text-anchor="end">SYS MOCK READY</text>
</svg>
SVG;
        exit;
    }
}
