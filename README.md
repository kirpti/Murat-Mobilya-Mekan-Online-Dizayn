# MEKÂN — Mobilya Tasarım Stüdyosu

<div align="center">

![MEKÂN Banner](https://img.shields.io/badge/MEKÂN-Mobilya%20Tasarım%20Stüdyosu-8B6F47?style=for-the-badge&labelColor=1A1714)

**Oda fotoğrafı yükle · Ölçü gir · Mobilya seç · AI önerisi al**

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Claude AI](https://img.shields.io/badge/Claude-Sonnet-D97757?style=flat-square)](https://anthropic.com)
[![License](https://img.shields.io/badge/License-MIT-22C55E?style=flat-square)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-Welcome-brightgreen?style=flat-square)](CONTRIBUTING.md)

[🚀 Kurulum](#-kurulum) · [🤝 Katkı](#-katkı) · [📖 Dokümantasyon](#-dokümantasyon) · [🗺 Yol Haritası](#-yol-haritası)

</div>

---

## 🎯 Ne İşe Yarar?

MEKÂN, odanızın fotoğrafını ve ölçülerini girerek **sunta / MDF / ahşap mobilyaları** dijital ortamda yerleştirmenizi sağlayan, Laravel tabanlı açık kaynaklı bir iç mekan tasarım aracıdır. Anthropic Claude ile entegre AI asistanı yerleştirme önerileri ve ergonomi uyarıları sunar.

```
┌─────────────────────────────────────────────────────────────────┐
│  MEKÂN  Mobilya Tasarım Stüdyosu                          ● ● ● │
├──────────────────┬──────────────────────────────────────────────┤
│                  │                                              │
│  1 Mekan Tanımı  │     Plan Görünümü            ⊡  ⬚  ↓       │
│  ─────────────   │  ┌────────────────────────────────────┐     │
│  📷 Fotoğraf     │  │  K                                 │     │
│                  │  │  ════════════════════════════════   │     │
│  Genişlik 500cm  │  │  ║  📺 TV Ünitesi (180×45)   ║    │     │
│  Derinlik 400cm  │  │  ║                             ║   │     │
│  Tavan    260cm  │  │  ║  ⬛ Baza    🚪 Gardrop     ║   │     │
│  Oda: Yatak      │  │  ════════════════════════════════   │     │
│                  │  └────────────────────────────────────┘     │
│  2 Mobilya Seç   │                                              │
│  3 Tasarım       │  ▾ AI Tasarım Asistanı                      │
│                  │  → Gardrop kapı açılımı için 60cm boşluk... │
│  [✦ Oluştur]     │                                              │
└──────────────────┴──────────────────────────────────────────────┘
```

---

## ✨ Özellikler

### 🏠 Mekan
- Oda fotoğrafı yükleme (plan arka planı olarak)
- cm cinsinden boyut girişi (genişlik, derinlik, tavan yüksekliği)
- 6 oda tipi: Salon, Yatak Odası, Yemek Odası, Çalışma Odası, Mutfak, Banyo

### 🪵 Mobilya Kataloğu — Yalnızca Sunta / MDF / Ahşap
| Kategori | Ürünler |
|----------|---------|
| **Salon** | TV ünitesi (alçak/yüksek), kitaplık, sehpa, konsol, vitrin |
| **Yatak Odası** | Baza (90/140/160 cm), gardrop (2/3/4 kapı), komodin, şifonyer, makyaj masası |
| **Yemek Odası** | Masa (4/6/8 kişi + yuvarlak Ø120), büfe (120/180 cm), vitrin |
| **Çalışma Odası** | Düz masa, L-masa, köşe masa, kitaplık, dosya dolabı, toplantı masası |
| **Mutfak** | Alt/üst dolap, tezgah, mutfak adası, beyaz eşya |
| **Banyo** | Lavabo dolabı (tek/çift), klozet, duş, küvet, ayna dolabı |

### 🎨 Tasarım
- 4 stil: Minimal · Skandinav · Endüstriyel · Klasik
- 4 renk paleti: Nötr · Sıcak · Serin · Doğal
- Plan görünümü (2D üstten)
- 3B izometrik taslak
- Sürükle & bırak ile konumlandırma
- Çift tıkla ile 90° döndürme
- PNG dışa aktarma

### 🤖 AI Tasarım Asistanı
- Anthropic Claude Sonnet ile çalışır
- Yerleştirme mantığı, ergonomi uyarıları, stil önerileri
- **Güvenli:** API anahtarı sunucu taraflı proxy ile kullanılır, tarayıcıya hiç gönderilmez

---

## 📦 Kurulum

### Gereksinimler

| | Versiyon |
|---|---|
| PHP | ≥ 8.2 |
| Laravel | 11.x |
| Composer | 2.x |
| Anthropic API Key | [Buradan alın](https://console.anthropic.com) |

### Adımlar

```bash
# 1. Klonlayın
git clone https://github.com/KULLANICI_ADINIZ/mekan-mobilya.git
cd mekan-mobilya

# 2. Bağımlılıkları yükleyin
composer install

# 3. Ortam dosyasını oluşturun
cp .env.example .env
php artisan key:generate

# 4. .env dosyasını düzenleyin
# ANTHROPIC_API_KEY=sk-ant-xxxxxxxx satırını doldurun

# 5. Çalıştırın
php artisan serve
# → http://localhost:8000
```

---

## 🌐 Deploy

### cPanel / Shared Hosting

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

`public_html/index.php` dosyasını şöyle yönlendirin:
```php
require __DIR__.'/../mekan/public/index.php';
```

### Nginx + VPS

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/mekan/public;
    index index.php;

    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

```bash
sudo certbot --nginx -d yourdomain.com  # SSL
```

### Docker

```bash
docker compose up -d
```

```yaml
# docker-compose.yml (örnek)
services:
  app:
    build: .
    environment:
      - ANTHROPIC_API_KEY=${ANTHROPIC_API_KEY}
  nginx:
    image: nginx:alpine
    ports: ["80:80"]
```

---

## 📁 Proje Yapısı

```
mekan-mobilya/
├── app/Http/Controllers/
│   └── MekanController.php     ← Sayfa + AI proxy
├── routes/
│   └── web.php                 ← GET / ve POST /ai-suggest
├── resources/views/mekan/
│   └── index.blade.php         ← Tüm UI (Canvas, sidebar, AI panel)
├── config/
│   └── services.php            ← Anthropic key bağlantısı
├── .env.example
├── CONTRIBUTING.md
├── LICENSE
└── README.md
```

---

## 🔒 Güvenlik Mimarisi

```
Tarayıcı
  │  POST /ai-suggest  +  X-CSRF-TOKEN
  ▼
MekanController::aiSuggest()
  │  validate() → ANTHROPIC_API_KEY (.env'den)
  ▼
api.anthropic.com
  │  Yanıt
  ▼
JSON → Tarayıcı
```

> API anahtarı hiçbir zaman tarayıcıya ulaşmaz.

---

## 🤝 Katkı

Her türlü katkıya açığız! Ayrıntılar için [CONTRIBUTING.md](CONTRIBUTING.md) dosyasına bakın.

```bash
git checkout -b feature/yeni-ozellik
# Değişikliklerinizi yapın
git commit -m "feat: yeni özellik eklendi"
git push origin feature/yeni-ozellik
# GitHub'da Pull Request açın
```

### Katkı Alanları

- 🪑 **Yeni mobilya** — `FURNITURE` nesnesine ekleyin
- 🌍 **Çoklu dil** — İngilizce, Almanca, Arapça vb.
- 🎨 **Yeni stil / palet**
- 📱 **Mobil uyumluluk**
- ♿ **Erişilebilirlik (a11y)**
- 🧪 **Test coverage**
- 🐛 **Hata düzeltme**

### Yeni Mobilya Ekleme

`index.blade.php` içindeki `FURNITURE` nesnesine ekleyin:

```javascript
{
  id:    'benzersiz_id',   // snake_case, benzersiz
  icon:  '🗄',            // Emoji
  name:  'Mobilya Adı',   // Görünen isim
  w:     120,             // Genişlik (cm)
  d:     60,              // Derinlik (cm)
  color: '#8B7355',       // HEX renk
  mat:   'Sunta'          // Malzeme etiketi
}
```

### Commit Formatı

```
feat:     yeni özellik
fix:      hata düzeltme
docs:     dokümantasyon
style:    biçimlendirme
refactor: yeniden yapılandırma
test:     test ekleme
```

---

## 🗺 Yol Haritası

- [ ] Tasarım kaydetme & kullanıcı hesabı
- [ ] PDF / SVG dışa aktarma
- [ ] Gerçek 3B görünüm (Three.js)
- [ ] Paylaşım linki
- [ ] Oda şablonları (hazır yerleşimler)
- [ ] Çoklu dil desteği (i18n)
- [ ] PWA / mobil uygulama
- [ ] Mobilya kütüphanesi genişletme

---

## 🐛 Sorun Bildirme

[GitHub Issues](../../issues) üzerinden bildirin. İyi bir rapor şunları içerir:

- Hatanın kısa açıklaması
- Yeniden oluşturma adımları
- PHP / Laravel sürümü
- Ekran görüntüsü (varsa)

---

## 📄 Lisans

[MIT](LICENSE) — Özgürce kullanın, değiştirin, dağıtın.

---

## 🙏 Teşekkürler

[Laravel](https://laravel.com) · [Anthropic Claude](https://anthropic.com) · [Google Fonts](https://fonts.google.com)

---

<div align="center">

**Beğendiyseniz ⭐ vermeyi unutmayın — açık kaynağın yakıtı bu!**

Soru ve öneriler için [Discussions](../../discussions) bölümünü kullanabilirsiniz.

</div>
