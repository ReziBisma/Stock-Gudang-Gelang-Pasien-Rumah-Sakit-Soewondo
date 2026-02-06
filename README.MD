# 🏥 Sistem Manajemen Stok Gelang Pasien Rumah Sakit

Aplikasi berbasis **PHP Native** dan **MySQL/MariaDB** untuk mengelola stok gelang pasien di Rumah Sakit.  
Sistem ini mendukung manajemen barang, pencatatan stok masuk & keluar, serta autentikasi user dengan role berbeda.

---

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

---

## 📂 Struktur Folder

```
Stock-Gudang-Gelang-Pasien-Rumah-Sakit-Soewondo/
│
├── admin/
├── operator/
├── auth/
│   ├── login_process.php
│   └── register_process.php
│
├── config/
│   ├── database.php
│   └── config.php
│
├── views/
│   ├── login_view.php
│   └── register_view.php
│
├── partials/
│   └── sidebar.php
│
├── dashboard.php
├── index.php
├── register.php
└── stok_gelangrs.sql
```


## ⚙️ Cara Instalasi

### 1️⃣ Clone Repository
```bash
git clone https://github.com/USERNAME/Stock-Gudang-Gelang-Pasien-Rumah-Sakit-Soewondo.git
cd Stock-Gudang-Gelang-Pasien-Rumah-Sakit-Soewondo


## 👨‍💻 Author
Feedd
ReziBisma
Valeforr
