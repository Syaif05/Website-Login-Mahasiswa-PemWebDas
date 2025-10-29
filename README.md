# Tugas MK Pemrograman Website Dasar: Portal Mahasiswa STMIK IKMI (PHP & Docker)

## 📖 Pendahuluan - Cerita Dikit Soal Proyek Ini

Yo, kenalin! Ini proyek buat tugas mata kuliah Pemrograman Website Dasar di STMIK IKMI Cirebon. Kebetulan dosen pengampunya itu Bapak Khaerul Anam. Nah, saya, Syaifulloh Rohmat dari kelas TI KIP P3 2024, ceritanya bikin semacam portal sederhana gitu buat mahasiswa. Isinya ya seputar akun pengguna aja sih.

Intinya, website ini tuh kayak tempat buat daftar akun baru, terus nanti bisa login pakai akun itu. Kalau udah login, baru deh bisa masuk ke halaman utama atau dashboard. Di dashboard itu nanti bisa lihat profil sendiri, terus bisa juga lihat daftar semua mahasiswa lain yang udah pada daftar. Semuanya dibikin pakai PHP murni (istilahnya _native_), terus nyambung ke database MySQL buat nyimpen datanya. Biar gampang jalaninnya di mana aja, ini semua dibungkus pakai Docker. Jadi, nggak perlu install XAMPP atau sejenisnya lagi di laptop masing-masing.

## 🤔 Cara Kerja Website Ini (Konsep Dasarnya Gimana Sih?)

Buat temen-temen yang mungkin baru pertama kali nyentuh web programming, biar kebayang alurnya, kira-kira gini cara kerja website ini:

1.  **Kamu (Pakai Browser):** Pas kamu buka Chrome terus ngetik `localhost:8080`, browser itu jadi **Klien**. Dia kayak orang yang lagi mesen menu di restoran.
2.  **Docker (Restorannya):** Alamat `localhost:8080` itu ngarah ke "restoran" kita, yaitu si **Docker**. Di dalem Docker ini udah ada:
    - **Apache (Pelayan):** Web server yang tugasnya nerima pesanan dari browser kamu.
    - **PHP (Koki):** Ini yang masak pesenannya di dapur. Dia terima instruksi dari Apache (misal: "tolong bikinin halaman login"), terus dia siapin bahan-bahannya.
    - **MySQL (Gudang Bahan):** Tempat nyimpen semua data penting (nama, NIM, email, password rahasia, nama file foto) dalam bentuk tabel-tabel rapi. Si Koki (PHP) bakal ngambil bahan dari sini atau nyimpen bahan baru ke sini.
3.  **HTML (Makanan Jadi):** Si Koki (PHP), setelah selesai masak (ngolah data dari gudang), hasil akhirnya itu **selalu** berupa **HTML**. HTML ini kayak makanan yang udah siap di piring.
4.  **Tampilan di Browser:** Si Pelayan (Apache) nganterin HTML tadi balik ke browser kamu. Browser nerima HTML itu, terus nampilin jadi halaman web yang bisa kamu lihat. Browser nggak ngerti PHP, dia cuma bisa nampilin HTML, CSS (buat gaya/tampilan), sama JavaScript (buat animasi/interaksi).

Intinya: PHP kerja di belakang layar (server), ngolah data dari database, hasilnya jadi HTML, terus HTML-nya dikirim ke browser buat ditampilin. Docker itu cuma "wadah" biar si Apache, PHP, dan MySQL bisa jalan bareng dengan gampang.

## ✨ Fitur-fitur Keren (Yang Udah Jadi)

Nah, ini dia daftar fitur yang udah ada di portal mahasiswa sederhana ini:

- **Pendaftaran Akun Baru (`daftar.php`):**
  Tempat buat bikin akun. Tinggal isi Nama, NIM (wajib 8 angka unik), Email (valid & unik), sama Password (minimal 6 karakter). Ada juga pilihan buat upload foto profil (maksimal 2MB, format gambar). Passwordnya nanti di-_hash_ biar aman, fotonya di-rename jadi `NIM-Nama_Kamu.jpg` (atau .png, .gif, .jpeg) terus disimpen.

- **Login Pengguna (`login.php`):**
  Buat masuk ke sistem. Tinggal masukin NIM sama Password. Nanti dicek ke database, cocok apa nggak. Kalau cocok, baru bisa masuk dashboard. Ada link "Lupa Password?" juga di sini.

- **Dashboard Utama (`dashboard.php`):**
  Halaman utama setelah login. Isinya daftar semua pengguna yang udah terdaftar, lengkap sama foto, NIM, nama, email. Ada fitur **pencarian** juga buat nyari user berdasarkan NIM atau nama. Terus, kalau usernya banyak, ada **nomor halaman (pagination)** biar nggak loading semua sekaligus.

- **Lihat Profil (`profil.php`):**
  Nampilin detail profil kamu sendiri (yang lagi login), kayak foto, nama, NIM, email. Lebih detail dari yang di dashboard.

- **Edit Profil (`edit_profil.php`):**
  Dari halaman profil, bisa klik tombol edit buat ke sini. Kamu bisa **ganti Nama Lengkap** atau **upload Foto Profil baru**. Kalau ganti nama, nama file fotonya juga otomatis ikut ke-update. Kalau upload foto baru, foto lama (kalau ada) bakal kehapus.

- **Lupa/Reset Password (`lupa_password.php`):**
  Ini fitur **simulasi** aja. Kamu masukin email terdaftar. Kalau emailnya ada, langsung muncul form buat bikin password baru. Nggak pake kirim email beneran, langsung update password di database.

- **Logout (`logout.php`):**
  Tombol buat keluar dari sistem. Biar sesi login kamu berakhir dengan aman.

- **Jalan di Docker:**
  Semua ini udah disiapin buat jalan pakai Docker. Jadi, instalasinya gampang dan konsisten di mana aja.

## 📸 Tampilan Aplikasi (Biar Kebayang Bentuknya)

Biar nggak nebak-nebak, ini dia penampakan beberapa halaman utama portalnya:

**1. Halaman Login (`login.php`)**
![Tampilan Halaman Login](src/screenshot/login.php.png)

**2. Halaman Pendaftaran (`daftar.php`)**
![Tampilan Halaman Pendaftaran](src/screenshot/daftar.php.png)

**3. Halaman Dashboard (`dashboard.php`)**
![Tampilan Halaman Dashboard](src/screenshot/dashboard.php.png)

**4. Halaman Profil (`profil.php`)**
![Tampilan Halaman Profil](src/screenshot/profil.php.png)

**5. Halaman Edit Profil (`edit_profil.php`)**
![Tampilan Halaman Edit Profil](src/screenshot/edit_profil.php.png)

**6. Halaman Lupa Password (`lupa_password.php`)**
![Tampilan Halaman Lupa Password](src/screenshot/lupa_password.php.png)


## 🛠️ Teknologi yang Dipakai (Bahan-bahannya Apa Aja?)

Proyek ini nggak pakai teknologi yang aneh-aneh banget kok, kebanyakan standar buat belajar web dasar:

- **PHP (versi 8.2):** Bahasa utama buat _backend_ (logika server).
- **MySQL (versi 8.0):** Database buat nyimpen data pengguna.
- **Apache:** Web server buat jalanin PHP.
- **PDO (PHP Data Objects):** Jembatan aman antara PHP dan MySQL.
- **Docker & Docker Compose:** Buat bungkus aplikasi jadi kontainer biar gampang dijalankan.
- **HTML:** Struktur dasar halaman web.
- **Tailwind CSS (via CDN):** Framework CSS buat _styling_ tampilan biar modern, dipakai langsung di HTML.
- **Font Poppins (via Google Fonts):** Font biar tulisannya cakep.

## 🗄️ Struktur Tabel Database (Tempat Nyimpen Data)

Di proyek ini, kita cuma pakai satu tabel database aja di dalam MySQL (di database `kampus_db`), namanya tabel `users`. Isinya buat nyimpen data tiap akun yang daftar.

Ini detail kolom-kolom di tabel `users`:

| Kolom      | Tipe           | Keterangan Penting                                                                                                                                       |
| :--------- | :------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `id`       | `INT`          | Nomor unik buat tiap user, otomatis nambah sendiri (Auto Increment). Ini **Primary Key**-nya.                                                            |
| `nama`     | `VARCHAR(100)` | Nama Lengkap pengguna.                                                                                                                                   |
| `nim`      | `VARCHAR(8)`   | Nomor Induk Mahasiswa. Harus unik, nggak boleh ada yang sama (Unique).                                                                                   |
| `email`    | `VARCHAR(100)` | Alamat email pengguna. Harus unik juga (Unique).                                                                                                         |
| `password` | `VARCHAR(255)` | **Hash** password pengguna. Ini _bukan_ password asli, tapi hasil acakan dari `password_hash()`. Panjangnya bisa beda-beda makanya pakai `VARCHAR(255)`. |
| `foto`     | `VARCHAR(255)` | Nama file foto profil pengguna (misal: `41247004-Syaifulloh_Rohmat.jpg`). Kalau nggak upload, defaultnya `'default.png'`.                                |

Jadi, semua informasi akun disimpen di tabel ini. Password disimpen dalam bentuk _hash_, dan buat foto profil, yang disimpen cuma nama filenya aja, file gambarnya ada di folder server.

## 🚀 Cara Instalasi & Menjalankan (Nyalain Portalnya!)

Nah, ini bagian penting biar kamu atau temenmu bisa nyoba jalanin portal ini di komputernya masing-masing. Syarat utamanya cuma satu: **harus udah install Docker Desktop** (Windows/Mac) atau **Docker Engine + Docker Compose** (Linux). Kalau belum, install dulu dari website resminya Docker.

Kalau Docker udah siap, tinggal ikutin langkah ini:

1.  **Download/Clone Proyek:** Pastikan semua file proyek ada di satu folder.
2.  **Buka Terminal:** Buka Terminal/Command Prompt/PowerShell.
3.  **Masuk ke Folder Proyek:** Pakai perintah `cd` sampai kamu ada di dalem folder utama proyek (yang ada `docker-compose.yml`).
4.  **Jalankan Docker Compose:** Ketik perintah ini terus Enter:
    ```bash
    docker-compose up -d --build
    ```
    - `up`: Nyalain kontainer.
    - `-d`: Jalan di belakang layar.
    - `--build`: Bikin _image_ PHP-nya dulu (penting pas pertama kali).
5.  **Tunggu:** Biarin Docker download dan ngebangun semuanya. Tunggu sampai nggak ada error.
6.  **Buka Website:** Buka browser, ketik alamat: `http://localhost:8080`. Harusnya halaman login muncul.
7.  **(Opsional) Intip Database:** Buka `http://localhost:8081` buat akses PhpMyAdmin (Login: server `db`, user `userphp`, pass `user123` - cek lagi `docker-compose.yml` kalau beda).
8.  **Matiin Lagi:** Kalau udah selesai, balik ke terminal (di folder proyek), ketik: `docker-compose down`. Kalau mau sekalian hapus data (reset), pakai `docker-compose down -v`.

## 🏗️ Struktur Folder Proyek (Isi Foldernya Apa Aja?)

Biar nggak bingung nyari file, ini gambaran isi foldernya:

**Struktur Folder Proyek**
![Struktur Folder Proyek](src/screenshot/struktur_folder.png)

Penjelasan singkatnya:

- **`docker-compose.yml` & `Dockerfile`:** Ada di luar, buat ngatur Docker-nya.
- **`src/`:** Ini folder utama tempat semua kode PHP dan aset web kita.
  - **`asset/`:** Buat nyimpen file-file pendukung.
    - **`images/`:** Tempat nyimpen foto profil pengguna yang diupload. Ada `default.png` juga di sini.
    - **`screenshot/`:** (Ini folder buat kamu nyimpen gambar Readme aja, nggak dipake aplikasi).
  - **File-file `.php`:** Semua file logika aplikasi ada di sini (`login.php`, `daftar.php`, `dashboard.php`, `koneksi.php`, dll).
- **`uploads/`:** (Folder ini sebenernya **nggak kepake lagi** karena kita udah pindahin upload ke `asset/images/`. Boleh dihapus kalau masih ada).

## 📂 Bedah Kode: Penjelasan Tiap File PHP

Nah, sekarang kita bongkar isi dapurnya. Ini penjelasan buat tiap file PHP yang ada di folder `src/`:

### 1. `koneksi.php`

- **Gunanya:** Colokan listrik database. Nyambungin PHP ke MySQL pakai PDO. Dipanggil sama semua file yang butuh akses DB.
- **Logika Penting:**
  - **Detail Koneksi:** Nyimpen info host (`db`), nama DB (`kampus_db`), user (`userphp`), pass (`user123`).
  - **Bikin Sambungan (PDO):** Nyoba konek pakai `new PDO()`. Kalau gagal, web berhenti dan ngasih tau errornya. Objek `$pdo` dipakai buat query.

### 2. `daftar.php`

- **Gunanya:** Halaman + logika pendaftaran akun baru.
- **Logika Penting:**
  - **Nampilin Form & Cek Kiriman `POST`:** Nampilin form HTML, terus ngecek kalau ada data dikirim (`$_SERVER['REQUEST_METHOD'] === 'POST'`).
  - **Validasi Input:** Ngecek Nama (min 2 char), NIM (8 digit angka, unik), Email (format valid, unik), Password (min 6 char). Error dikumpulin di array `$errors`.
  - **Proses Upload Foto:** Validasi file (format, size, MIME type). Kalau lolos, bikin nama file `NIM-Nama_Lengkap.ext`, pindahin ke `src/asset/images/`.
  - **Hashing Password:** Kalau nggak ada error, `password_hash()` passwordnya.
  - **Simpan ke DB:** Pakai `PDO::prepare()` dan `execute()` buat `INSERT` data user baru (termasuk hash pass & nama file foto) ke tabel `users`. Tangani error kalau NIM/Email udah ada.
  - **Tampilin Hasil:** Munculin pesan error atau sukses di halaman.

### 3. `login.php`

- **Gunanya:** Halaman + logika buat user masuk.
- **Logika Penting:**
  - **Cek Udah Login?:** Kalau udah ada session `login`, lempar ke `dashboard.php`.
  - **Proses `POST`:** Ambil NIM & Password.
  - **Cari User di DB:** `SELECT * FROM users WHERE nim = ?` pakai `PDO::prepare()`.
  - **Verifikasi Password:** Kalau user ketemu, bandingkan pakai `password_verify()`.
  - **Bikin Session (Sukses):** Simpen `$_SESSION['login'] = true`, `$_SESSION['nama']`, `$_SESSION['nim']`. Redirect ke `dashboard.php`.
  - **Kasih Error (Gagal):** Tampilkan pesan error kalau NIM/pass salah.

### 4. `dashboard.php`

- **Gunanya:** Halaman utama setelah login, nampilin daftar user.
- **Logika Penting:**
  - **Gerbang Session:** Wajib cek `$_SESSION['login']`. Kalau nggak ada, tendang ke `login.php`.
  - **Ambil Data Pengguna:** `SELECT id, nama, nim, email, foto FROM users`.
  - **Logika Pencarian:** Tambah `WHERE nim LIKE ? OR nama LIKE ?` ke query kalau ada `$_GET['search']`.
  - **Logika Pagination:** Hitung total data (`COUNT(*)`), tentukan limit per halaman, hitung offset, tambahin `LIMIT ? OFFSET ?` ke query. Bikin link nomor halaman. Query pakai `PDO::prepare()` dan `bindValue()` biar aman dan fleksibel.
  - **Tampilin ke Tabel HTML:** Looping data `$users`, tampilin pakai `htmlspecialchars()`. Foto diambil dari `asset/images/`.
  - **Navbar:** Tampilkan navbar dengan nama user (dari `$_SESSION['nama']`) dan dropdown logout/profil.

### 5. `profil.php`

- **Gunanya:** Nampilin detail profil user yang lagi login.
- **Logika Penting:**
  - **Gerbang Session:** Wajib.
  - **Ambil Data Spesifik:** `SELECT nama, nim, email, foto FROM users WHERE nim = ?` pakai `$_SESSION['nim']`.
  - **Tampilin Detail:** Tampilkan foto, nama, NIM, email pakai `htmlspecialchars()`.
  - **Link Edit:** Ada link ke `edit_profil.php`.

### 6. `edit_profil.php`

- **Gunanya:** Form + logika buat ganti Nama & Foto Profil.
- **Logika Penting:**
  - **Gerbang Session:** Wajib.
  - **Ambil Data Awal:** Ambil data user saat ini buat ditampilin di form.
  - **Proses `POST`:**
    - Ambil nama baru & cek file foto baru (`$_FILES['foto_baru']`).
    - **Validasi** nama baru.
    - **Proses Foto Baru:** Validasi (format, size, MIME), bikin nama `NIM-Nama_Baru.ext`, pindahin ke `asset/images/`, hapus foto lama (`unlink()`).
    - **Rename Foto Lama:** Kalau cuma ganti nama, coba `rename()` file foto lama.
    - **Update DB:** `UPDATE users SET nama = ?, foto = ? WHERE nim = ?` pakai `PDO::prepare()`.
    - **Update Session:** Update `$_SESSION['nama']`.
    - Tampilkan pesan sukses/error.

### 7. `lupa_password.php`

- **Gunanya:** Simulasi reset password.
- **Logika Penting:**
  - **Tahap 1 (Cek Email):** Form minta Email. Cek ke DB (`SELECT COUNT(*)`). Kalau ada, lanjut tahap 2.
  - **Tahap 2 (Input Pass Baru):** Form minta Password Baru + Konfirmasi. Validasi (min 6 char, cocok). Kalau valid, `password_hash()` password baru.
  - **Update DB:** `UPDATE users SET password = ? WHERE email = ?` pakai `PDO::prepare()`. Kasih pesan sukses.

### 8. `logout.php`

- **Gunanya:** Keluar sistem.
- **Logika Penting:** `session_start()`, `session_destroy()`, `header("Location: login.php")`.

### (File Tambahan, Tidak Utama)

- **`setup.php`:** Script sekali jalan buat nambah kolom `foto` ke tabel `users` kalau diperlukan.

Semua file yang nampilin data dari user/DB pakai `htmlspecialchars()`. Semua query ke DB pakai _prepared statement_ PDO. Aman! 👍

## 📂 Bedah Kode: Penjelasan Tiap File PHP

Nah, sekarang kita bongkar isi dapurnya. Ini penjelasan buat tiap file PHP yang ada di folder `src/`:

### 1. `koneksi.php`

- **Gunanya:** Colokan listrik database. Nyambungin PHP ke MySQL pakai PDO. Dipanggil sama semua file yang butuh akses DB.
- **Logika Penting:**
  - **Detail Koneksi:** Nyimpen info host (`db`), nama DB (`kampus_db`), user (`userphp`), pass (`user123`).
  - **Bikin Sambungan (PDO):** Nyoba konek pakai `new PDO()`. Kalau gagal, web berhenti dan ngasih tau errornya. Objek `$pdo` dipakai buat query.

### 2. `daftar.php`

- **Gunanya:** Halaman + logika pendaftaran akun baru.
- **Logika Penting:**
  - **Nampilin Form & Cek Kiriman `POST`:** Nampilin form HTML, terus ngecek kalau ada data dikirim (`$_SERVER['REQUEST_METHOD'] === 'POST'`).
  - **Validasi Input:** Ngecek Nama (min 2 char), NIM (8 digit angka, unik), Email (format valid, unik), Password (min 6 char). Error dikumpulin di array `$errors`.
  - **Proses Upload Foto:** Validasi file (format, size, MIME type). Kalau lolos, bikin nama file `NIM-Nama_Lengkap.ext`, pindahin ke `src/asset/images/`.
  - **Hashing Password:** Kalau nggak ada error, `password_hash()` passwordnya.
  - **Simpan ke DB:** Pakai `PDO::prepare()` dan `execute()` buat `INSERT` data user baru (termasuk hash pass & nama file foto) ke tabel `users`. Tangani error kalau NIM/Email udah ada.
  - **Tampilin Hasil:** Munculin pesan error atau sukses di halaman.

### 3. `login.php`

- **Gunanya:** Halaman + logika buat user masuk.
- **Logika Penting:**
  - **Cek Udah Login?:** Kalau udah ada session `login`, lempar ke `dashboard.php`.
  - **Proses `POST`:** Ambil NIM & Password.
  - **Cari User di DB:** `SELECT * FROM users WHERE nim = ?` pakai `PDO::prepare()`.
  - **Verifikasi Password:** Kalau user ketemu, bandingkan pakai `password_verify()`.
  - **Bikin Session (Sukses):** Simpen `$_SESSION['login'] = true`, `$_SESSION['nama']`, `$_SESSION['nim']`. Redirect ke `dashboard.php`.
  - **Kasih Error (Gagal):** Tampilkan pesan error kalau NIM/pass salah.

### 4. `dashboard.php`

- **Gunanya:** Halaman utama setelah login, nampilin daftar user.
- **Logika Penting:**
  - **Gerbang Session:** Wajib cek `$_SESSION['login']`. Kalau nggak ada, tendang ke `login.php`.
  - **Ambil Data Pengguna:** `SELECT id, nama, nim, email, foto FROM users`.
  - **Logika Pencarian:** Tambah `WHERE nim LIKE ? OR nama LIKE ?` ke query kalau ada `$_GET['search']`.
  - **Logika Pagination:** Hitung total data (`COUNT(*)`), tentukan limit per halaman, hitung offset, tambahin `LIMIT ? OFFSET ?` ke query. Bikin link nomor halaman. Query pakai `PDO::prepare()` dan `bindValue()` biar aman dan fleksibel.
  - **Tampilin ke Tabel HTML:** Looping data `$users`, tampilin pakai `htmlspecialchars()`. Foto diambil dari `asset/images/`.
  - **Navbar:** Tampilkan navbar dengan nama user (dari `$_SESSION['nama']`) dan dropdown logout/profil.

### 5. `profil.php`

- **Gunanya:** Nampilin detail profil user yang lagi login.
- **Logika Penting:**
  - **Gerbang Session:** Wajib.
  - **Ambil Data Spesifik:** `SELECT nama, nim, email, foto FROM users WHERE nim = ?` pakai `$_SESSION['nim']`.
  - **Tampilin Detail:** Tampilkan foto, nama, NIM, email pakai `htmlspecialchars()`.
  - **Link Edit:** Ada link ke `edit_profil.php`.

### 6. `edit_profil.php`

- **Gunanya:** Form + logika buat ganti Nama & Foto Profil.
- **Logika Penting:**
  - **Gerbang Session:** Wajib.
  - **Ambil Data Awal:** Ambil data user saat ini buat ditampilin di form.
  - **Proses `POST`:**
    - Ambil nama baru & cek file foto baru (`$_FILES['foto_baru']`).
    - **Validasi** nama baru.
    - **Proses Foto Baru:** Validasi (format, size, MIME), bikin nama `NIM-Nama_Baru.ext`, pindahin ke `asset/images/`, hapus foto lama (`unlink()`).
    - **Rename Foto Lama:** Kalau cuma ganti nama, coba `rename()` file foto lama.
    - **Update DB:** `UPDATE users SET nama = ?, foto = ? WHERE nim = ?` pakai `PDO::prepare()`.
    - **Update Session:** Update `$_SESSION['nama']`.
    - Tampilkan pesan sukses/error.

### 7. `lupa_password.php`

- **Gunanya:** Simulasi reset password.
- **Logika Penting:**
  - **Tahap 1 (Cek Email):** Form minta Email. Cek ke DB (`SELECT COUNT(*)`). Kalau ada, lanjut tahap 2.
  - **Tahap 2 (Input Pass Baru):** Form minta Password Baru + Konfirmasi. Validasi (min 6 char, cocok). Kalau valid, `password_hash()` password baru.
  - **Update DB:** `UPDATE users SET password = ? WHERE email = ?` pakai `PDO::prepare()`. Kasih pesan sukses.

### 8. `logout.php`

- **Gunanya:** Keluar sistem.
- **Logika Penting:** `session_start()`, `session_destroy()`, `header("Location: login.php")`.

### (File Tambahan, Tidak Utama)

- **`setup.php`:** Script sekali jalan buat nambah kolom `foto` ke tabel `users` kalau diperlukan.

Semua file yang nampilin data dari user/DB pakai `htmlspecialchars()`.

Terimakasih.
