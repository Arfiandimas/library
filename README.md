# Library App

Menggunakan Laravel 11 https://github.com/laravel/laravel

## Instalasi

Saat melakukan setup aplikasi ini pastikan device anda sudah ***terinstall docker***, setelah docker berhasil dijalankan, eksekusi perintah berikut pada base directory projek

```bash
git clone https://github.com/Arfiandimas/library.git
cd library
./start.sh
```

Setelah selesai maka api dapat diakses dengan url http://localhost:8004/

## Dokumentasi API

Dokumentasi api menggunakan Swagger https://github.com/DarkaOnLine/L5-Swagger, jalankan perintah berikut untuk generate dokumentasi API

```bash
docker compose run artisan l5-swagger:generate
```

Setelah selesai eksekusi, akses dokumentasi API menggunakan url http://localhost:8004/api/documentation

## Menjalankan Unit Test

```bash
docker compose run artisan test
```

## Desain Pattern

Desain pattern yang digunakan dalam aplikasi ini menggunakan **Repository Pattern** dengan **Service Layer**. Repository Pattern berfungsi sebagai lapisan abstraksi antara lapisan akses data dan logika bisnis. Dengan menggunakan Repository Pattern, kita dapat memisahkan logika yang terkait dengan pengambilan dan penyimpanan data dari logika bisnis aplikasi, sehingga kode menjadi lebih bersih, mudah untuk diuji, dan lebih terorganisir.

Service Layer berperan sebagai lapisan tambahan yang mengelola logika bisnis aplikasi. Dengan menerapkan Service Layer, kita dapat memusatkan logika bisnis dalam satu tempat, yang membuatnya lebih mudah untuk dikelola dan diubah tanpa perlu memodifikasi lapisan lain dalam aplikasi. Service Layer juga memfasilitasi pengelompokan operasi bisnis yang kompleks, serta memungkinkan penggunaan kembali logika bisnis di berbagai tempat dalam aplikasi.

Secara keseluruhan, kombinasi dari Repository Pattern dan Service Layer ini memberikan struktur aplikasi yang lebih modular, mudah di-maintain, dan scalable. Ini juga memungkinkan kita untuk lebih mudah mengimplementasikan perubahan pada logika bisnis atau model data tanpa mengganggu bagian lain dari aplikasi.

## Performance Tuning

Teknik Performance Tuning yang digunakan adalah **Caching** dengan mengimplementasikan **Redis**

Caching diimplementasi pada api :

```bash
/authors/{id}/books
```

Dimana saat melakukan ***delete author***, ***update book*** dan ***delete book*** maka caching akan dihapus dan selanjutnya dilakukan caching saat mengakses endpoint ***/authors/{id}/books***.

Saat mengakses endpoint ***/authors/{id}/books*** secara sistem akan melakukan pengecekan pada redis dengan key author_id, jika ditemukan maka akan mengambil data pada redis jika tidak ditemukan maka akan mengambil data pada database selanjutnya dilakukan caching ke redis.