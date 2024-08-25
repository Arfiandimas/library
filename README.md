# Library App

## Instalasi

Saat melakukan setup aplikasi ini pastikan device anda sudah terinstall docker, setelah itu jalankan perintah berikut pada base directory projek

```bash
./start.sh
```

Setelah selesai maka api dapat diakses dengan url http://localhost:8004/

## Menjalankan unit test

```bash
docker compose run artisan test
```

## Dokumentasi API

Dokumentasi api menggunakan Swagger https://github.com/DarkaOnLine/L5-Swagger , jalankan perintah berikut untuk generate dokumentasi API

```bash
docker compose run artisan l5-swagger:generate
```

Untuk mengaksesnya dokumentasi API menggunakan url http://localhost:8004/api/documentation

## Desain Pattern
