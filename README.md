# Filament Activity Log

Laravel + Filament projeleri için sade, kontrol edilebilir ve genişletilebilir **Activity Log** paketi.

Bu paket;  
Filament admin panelinde yapılan **görüntüleme, oluşturma, güncelleme ve silme** işlemlerini
kayıt altına almak için tasarlanmıştır.

> Paket, kurulum sırasında gerekli dosyaları **otomatik olarak projenize ekler**  
> Ekstra `vendor:publish` veya manuel kurulum adımı gerektirmez.

---

## ✨ Özellikler

- Laravel **11 & 12** uyumlu
- **Filament 3** uyumlu
- Manuel ve kontrollü loglama
- `old_data` / `new_data` desteği
- Filament Resource (**List / View**) hazır
- Migration otomatik kurulur
- Basit, genişletilebilir mimari
- Enum veya event zorunluluğu yok
- Proje içine kopyalanan dosyalar **üzerine yazılmaz**

---

## 📦 Kurulum

```bash
composer require ardaasevinc/filament-activity-log
