# Katkı Rehberi

MEKÂN projesine katkıda bulunmak istediğiniz için teşekkürler! Bu rehber süreci kolaylaştırmak için hazırlanmıştır.

## İçindekiler

- [Davranış Kuralları](#davranış-kuralları)
- [Nasıl Katkıda Bulunabilirim?](#nasıl-katkıda-bulunabilirim)
- [Geliştirme Ortamı](#geliştirme-ortamı)
- [Pull Request Süreci](#pull-request-süreci)
- [Kod Standartları](#kod-standartları)

---

## Davranış Kuralları

Bu proje, açık ve kapsayıcı bir ortam oluşturmayı taahhüt eder. Katkıda bulunan herkesin saygılı ve yapıcı bir iletişim kurması beklenir.

---

## Nasıl Katkıda Bulunabilirim?

### 🐛 Hata Bildirimi

1. [Issues](../../issues) sayfasında aynı hatanın zaten bildirilip bildirilmediğini kontrol edin
2. Yoksa yeni bir issue açın ve şablonu doldurun:
   - Hatanın kısa açıklaması
   - Yeniden oluşturma adımları (1, 2, 3...)
   - Beklenen davranış
   - Gerçekleşen davranış
   - PHP ve Laravel sürümü
   - Ekran görüntüsü (varsa)

### 💡 Özellik Önerisi

1. [Discussions](../../discussions) bölümünde öneriyi paylaşın
2. Topluluk geri bildirimi aldıktan sonra issue açın
3. Uygulama için PR gönderin

### 🪑 Yeni Mobilya Ekleme

En kolay katkı türü! `resources/views/mekan/index.blade.php` dosyasında `FURNITURE` nesnesini bulun ve ekleyin:

```javascript
// Örnek: yatak odasına boyama masası ekleme
{ 
  id:    'boyama_masasi',    // Benzersiz ID (snake_case)
  icon:  '🎨',              // İlgili emoji
  name:  'Boyama Masası',   // Türkçe görünen isim
  w:     100,               // Genişlik santimetre cinsinden
  d:     60,                // Derinlik santimetre cinsinden
  color: '#8B7355',         // Renk kodu (HEX)
  mat:   'MDF'              // Malzeme: 'Sunta', 'MDF', 'Ahşap', 'Cam', vb.
}
```

### 🌍 Çeviri / Dil Desteği

Uygulamayı başka dillere çevirmek istiyorsanız:

1. `resources/lang/` dizini oluşturun
2. `tr.json` referans alarak hedef dil dosyasını hazırlayın
3. Blade şablonundaki metinleri `__('anahtar')` fonksiyonuyla sarın

---

## Geliştirme Ortamı

### Kurulum

```bash
# Repoyu fork'layın, ardından klonlayın
git clone https://github.com/SIZIN_KULLANICI_ADINIZ/mekan-mobilya.git
cd mekan-mobilya

composer install
cp .env.example .env
php artisan key:generate
```

`.env` dosyasına test API anahtarınızı ekleyin:
```env
ANTHROPIC_API_KEY=sk-ant-test-xxxx
```

```bash
php artisan serve
```

### Branch Stratejisi

```
main          ← Kararlı, canlı sürüm
develop       ← Geliştirme dalı
feature/xxx   ← Yeni özellikler
fix/xxx       ← Hata düzeltmeleri
docs/xxx      ← Dokümantasyon
```

Her zaman `develop` branch'inden başlayın:

```bash
git checkout develop
git pull origin develop
git checkout -b feature/mobilya-ekle
```

---

## Pull Request Süreci

1. **Fork** — GitHub'da projeyi fork'layın
2. **Branch** — `develop`'tan yeni bir branch oluşturun
3. **Kod** — Değişikliklerinizi yapın
4. **Test** — Uygulamanın çalıştığını doğrulayın
5. **Commit** — Açıklayıcı commit mesajı yazın
6. **Push** — Branch'i fork'unuza gönderin
7. **PR** — Ana repoya Pull Request açın

### PR Açıklarken

- Başlık kısa ve açıklayıcı olsun
- Neyi değiştirdiğinizi ve neden açıklayın
- İlgili issue varsa `Closes #123` şeklinde bağlayın
- Ekran görüntüsü ekleyin (UI değişikliği varsa)

---

## Kod Standartları

### PHP (Laravel)

- PSR-12 kod stili
- Tip bildirimleri kullanın
- Her public method için docblock ekleyin
- Controller'lar ince olsun, iş mantığı service'lere taşınsın

```php
// ✅ İyi
public function aiSuggest(Request $request): JsonResponse
{
    $validated = $request->validate([...]);
    // ...
}

// ❌ Kaçının
public function aiSuggest($request)
{
    // Doğrulama yok
}
```

### JavaScript (Blade içinde)

- Modern ES6+ sözdizimi kullanın
- Fonksiyon isimleri camelCase olsun
- Anlaşılmayan satırlara kısa yorum ekleyin

### Commit Mesajları

[Conventional Commits](https://www.conventionalcommits.org/) formatını takip edin:

```
<tür>(<kapsam>): <açıklama>

[isteğe bağlı gövde]

[isteğe bağlı footer]
```

**Tür örnekleri:**

| Tür | Açıklama |
|-----|----------|
| `feat` | Yeni özellik |
| `fix` | Hata düzeltme |
| `docs` | Sadece dokümantasyon |
| `style` | Biçimlendirme (davranışı etkilemez) |
| `refactor` | Yeniden yapılandırma |
| `test` | Test ekleme / düzenleme |
| `chore` | Build araçları, bağımlılık güncellemeleri |

**Örnekler:**
```
feat(furniture): salon kategorisine konsol masa eklendi
fix(canvas): sürükle-bırak mobilde çalışmıyor düzeltildi
docs(readme): docker kurulum adımları eklendi
```

---

## Yardım

Takıldınız mı? [Discussions](../../discussions) bölümünde soru sorabilirsiniz.

Katkınız için şimdiden teşekkürler! 🎉
