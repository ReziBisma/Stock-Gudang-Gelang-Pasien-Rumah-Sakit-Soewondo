# 🏥 Sistem Manajemen Stok Gelang Pasien Rumah Sakit

Aplikasi berbasis **PHP Native** dan **MySQL/MariaDB** untuk mengelola stok gelang pasien di Rumah Sakit.  
Sistem ini mendukung manajemen barang, pencatatan stok masuk & keluar, serta autentikasi user dengan role berbeda.

---

## Note:
bila memakai php -s localhost:xxxx, mohon di bagian /config/database.php hostnya buat menjadi seperti ini
$host = "127.0.0.1 or localhost"

bila memakai docker-compose, hostnya buat menjadi seperti ini:
$host = "db"


## 🚀 Fitur Utama

- 🔐 Login & Register User
- 👥 Role Management (Admin & Operator)
- 📦 Manajemen Data Barang
- 📊 Manajemen Stok (Masuk & Keluar)
- 📅 Pencatatan berdasarkan tanggal
- 📈 Dashboard Monitoring
- 🛡 Session Authentication & Proteksi Halaman
- 💾 Prepared Statement (lebih aman dari SQL Injection)

---

## 👤 Role User

### 🛠 Admin
- Mengelola data barang
- Mengelola data stok
- Melihat dashboard
- Menghapus data

### 👨‍⚕️ Operator
- Mengelola data stok
- Melihat dashboard

---

## 🛠 Tech Stack

- PHP 8+
- MySQL / MariaDB
- Bootstrap 5
- HTML5 & CSS3
- Git & GitHub
- Docker

---

## 📂 Struktur Folder

```
Stock-Gudang-Gelang-Pasien-Rumah-Sakit-Soewondo
    ├── admin
    │   ├── barang.php
    │   └── users.php
    ├── auth
    │   ├── export_barang.php
    │   ├── export_stok.php
    │   ├── login_process.php
    │   └── register_process.php
    ├── config
    │   ├── put your database.php here 
    │   └── proses_transaksi.php
    ├── controller
    │   └── register.php
    ├── dashboard.php
    ├── docker (if use docker)
    │   └── mysql-init
    │       └── put your dump database here
    ├── docker-compose.yml
    ├── Dockerfile
    ├── index.php
    ├── logout.php
    ├── operator
    │   └── stok.php
    ├── partials
    │   └── sidebar.php
    ├── README.MD
    └── views
        ├── barang_view.php
        ├── dashboard_view.php
        ├── login_view.php
        ├── register_view.php
        ├── stok_view.php
        └── users_view.php


```


## ⚙️ Cara Instalasi

### 1️⃣ Clone Repository
```bash
git clone https://github.com/USERNAME/Stock-Gudang-Gelang-Pasien-Rumah-Sakit-Soewondo.git
cd Stock-Gudang-Gelang-Pasien-Rumah-Sakit-Soewondo
php -S localhost:xxxx 
sudo docker-compose up -d --build (for docker)


## 👨‍💻 Author
Feedd
ReziBisma
Valeforr
