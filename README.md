# Filament Activity Log

Laravel + Filament projeleri için sade, kontrollü ve genişletilebilir bir **Activity Log** paketi.

Bu paket; Filament admin panelinde yapılan **görüntüleme (view), oluşturma (create), güncelleme (update) ve silme (delete)** işlemlerini kayıt altına almak için tasarlanmıştır.  
Event, Observer veya karmaşık yapı zorunluluğu yoktur. Kontrol tamamen geliştiricidedir.

---

## ✨ Özellikler

- Laravel **11 & 12** uyumlu
- Filament **v3** uyumlu
- Manuel ve kontrollü loglama
- `old_data` / `new_data` desteği
- Hazır Filament Resource (Liste & Detay)
- Paket içinden migration
- Publish edilebilir Model, Service, Trait ve Resource
- Basit, okunabilir ve genişletilebilir mimari

---

## 📦 Kurulum

### 1️⃣ Paketi yükle

```bash
composer require ardaasevinc/filament-activity-log

##Yüklenen dosyaları Yayınla

php artisan vendor:publish --tag=filament-activity-log-migrations

###Bu komut aşağıdaki dosyaları projenize ekler:

app/Models/ActivityLog.php
app/Services/FilamentActivityLogger.php
app/Filament/Concerns/HasActivityLogger.php
app/Filament/Resources/ActivityLogResource.php
app/Filament/Resources/ActivityLogResource/Pages/ListActivityLogs.php
app/Filament/Resources/ActivityLogResource/Pages/ViewActivityLogs.php


Artık projenize log sistemi entegre edildi.

resource/pages create ve edit sayfalarına class içine

use \App\Filament\Concerns\HasActivityLogger; ekleyin, her modül için eklemelisiniz.
