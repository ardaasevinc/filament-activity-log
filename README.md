# Filament Activity Log

Filament Activity Log

Laravel + Filament projeleri için sade, kontrollü ve genişletilebilir bir Activity Log paketi.

Bu paket; Filament admin panelinde yapılan görüntüleme (view), oluşturma (create), güncelleme (update) ve silme (delete) işlemlerini kayıt altına almak için tasarlanmıştır.
Event, Observer veya karmaşık yapı zorunluluğu yoktur. Kontrol tamamen geliştiricidedir.

✨ Özellikler

Laravel 11 & 12 uyumlu

Filament v3 uyumlu

Manuel ve kontrollü loglama

old_data / new_data desteği

Hazır Filament Resource (Liste & Detay)

Paket içinden migration (stub tabanlı)

Publish edilebilir Model, Service, Trait ve Resource

Basit, okunabilir ve genişletilebilir mimari

📦 Kurulum
1️⃣ Paketi Yükle
composer require ardaasevinc/filament-activity-log

2️⃣ Gerekli Dosyaları Yayınla (Publish)
php artisan vendor:publish --tag=filament-activity-log-migrations


Bu komut aşağıdaki dosyaları projenize ekler:

database/migrations/xxxx_xx_xx_xxxxxx_create_activity_logs_table.php

app/Models/ActivityLog.php
app/Services/FilamentActivityLogger.php
app/Filament/Concerns/HasActivityLogger.php
app/Filament/Resources/ActivityLogResource.php
app/Filament/Resources/ActivityLogResource/Pages/ListActivityLogs.php
app/Filament/Resources/ActivityLogResource/Pages/ViewActivityLogs.php