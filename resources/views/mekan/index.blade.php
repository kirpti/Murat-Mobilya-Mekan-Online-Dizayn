<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>MEKÂN — Mobilya Tasarım Stüdyosu</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300&family=DM+Mono:wght@300;400&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #F5F2EE; --surface: #FDFCFA; --ink: #1A1714; --ink-soft: #6B6560;
    --ink-faint: #C4BFB8; --accent: #8B6F47; --accent-warm: #C4956A;
    --accent-light: #F0E8DF; --border: #E2DDD6; --shadow: rgba(26,23,20,0.08); --radius: 2px;
  }
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'DM Mono', monospace; background: var(--bg); color: var(--ink); min-height: 100vh; font-size: 13px; letter-spacing: 0.01em; }
  header { padding: 28px 40px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: baseline; gap: 24px; background: var(--surface); }
  .logo { font-family: 'Cormorant Garamond', serif; font-size: 28px; font-weight: 300; letter-spacing: 0.12em; }
  .logo span { color: var(--accent); font-style: italic; }
  .tagline { color: var(--ink-soft); font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase; }
  .step-indicator { margin-left: auto; display: flex; gap: 6px; align-items: center; }
  .step-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--ink-faint); transition: all 0.3s; }
  .step-dot.active { background: var(--accent); transform: scale(1.4); }
  .step-dot.done { background: var(--accent-warm); }
  .app { display: grid; grid-template-columns: 340px 1fr; min-height: calc(100vh - 73px); }
  .sidebar { background: var(--surface); border-right: 1px solid var(--border); overflow-y: auto; }
  .panel { border-bottom: 1px solid var(--border); }
  .panel-header { padding: 18px 24px 16px; display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none; }
  .panel-header:hover { background: var(--accent-light); }
  .panel-num { width: 20px; height: 20px; border: 1px solid var(--ink-faint); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; color: var(--ink-soft); flex-shrink: 0; }
  .panel-num.active { background: var(--accent); border-color: var(--accent); color: white; }
  .panel-title { font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase; color: var(--ink-soft); }
  .panel-title.active { color: var(--ink); }
  .panel-body { padding: 0 24px 20px; }
  .upload-zone { border: 1.5px dashed var(--border); border-radius: var(--radius); padding: 32px 20px; text-align: center; cursor: pointer; transition: all 0.25s; position: relative; overflow: hidden; }
  .upload-zone:hover { border-color: var(--accent); background: var(--accent-light); }
  .upload-zone input { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
  .upload-icon { font-size: 28px; margin-bottom: 10px; opacity: 0.5; }
  .upload-text { font-size: 11px; color: var(--ink-soft); line-height: 1.6; letter-spacing: 0.04em; }
  .upload-preview { width: 100%; border-radius: var(--radius); display: none; position: relative; }
  .upload-preview img { width: 100%; height: 160px; object-fit: cover; border-radius: var(--radius); display: block; }
  .upload-preview .remove-btn { position: absolute; top: 6px; right: 6px; width: 22px; height: 22px; background: var(--ink); color: white; border: none; border-radius: 50%; cursor: pointer; font-size: 12px; display: flex; align-items: center; justify-content: center; }
  .field-group { margin-bottom: 14px; }
  label { display: block; font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase; color: var(--ink-soft); margin-bottom: 5px; }
  input[type="number"], input[type="text"], select, textarea { width: 100%; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 8px 10px; font-family: 'DM Mono', monospace; font-size: 13px; color: var(--ink); outline: none; transition: border-color 0.2s; }
  input:focus, select:focus, textarea:focus { border-color: var(--accent); }
  .dims-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
  .unit-field { display: flex; align-items: center; }
  .unit-field input { border-radius: var(--radius) 0 0 var(--radius); }
  .unit-badge { background: var(--border); border: 1px solid var(--border); border-left: none; padding: 8px 10px; font-size: 11px; color: var(--ink-soft); border-radius: 0 var(--radius) var(--radius) 0; white-space: nowrap; }
  .furniture-list { display: flex; flex-direction: column; gap: 6px; }
  .furniture-item { display: flex; align-items: center; gap: 8px; padding: 8px 10px; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); cursor: pointer; transition: all 0.2s; user-select: none; }
  .furniture-item:hover { border-color: var(--accent-warm); }
  .furniture-item.selected { border-color: var(--accent); background: var(--accent-light); }
  .furn-icon { font-size: 18px; width: 28px; text-align: center; flex-shrink: 0; }
  .furn-info { flex: 1; }
  .furn-name { font-size: 12px; color: var(--ink); margin-bottom: 2px; }
  .furn-dims { font-size: 10px; color: var(--ink-soft); }
  .furn-toggle { width: 16px; height: 16px; border: 1.5px solid var(--ink-faint); border-radius: 2px; flex-shrink: 0; transition: all 0.2s; display: flex; align-items: center; justify-content: center; font-size: 10px; color: transparent; }
  .furniture-item.selected .furn-toggle { background: var(--accent); border-color: var(--accent); color: white; }
  .mat-badge { display: inline-block; background: var(--accent-light); border: 1px solid var(--border); border-radius: 2px; padding: 1px 5px; font-size: 9px; letter-spacing: 0.06em; text-transform: uppercase; color: var(--accent); vertical-align: middle; }
  .style-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
  .style-btn { padding: 10px 8px; border: 1px solid var(--border); border-radius: var(--radius); background: var(--bg); cursor: pointer; text-align: center; font-family: 'DM Mono', monospace; font-size: 11px; color: var(--ink-soft); transition: all 0.2s; letter-spacing: 0.03em; }
  .style-btn:hover { border-color: var(--accent-warm); color: var(--ink); }
  .style-btn.selected { border-color: var(--accent); background: var(--accent-light); color: var(--ink); }
  .style-btn .style-icon { font-size: 16px; display: block; margin-bottom: 4px; }
  .btn { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 20px; border-radius: var(--radius); border: none; cursor: pointer; font-family: 'DM Mono', monospace; font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase; transition: all 0.2s; width: 100%; }
  .btn-primary { background: var(--ink); color: var(--bg); }
  .btn-primary:hover { background: var(--accent); }
  .btn-outline { background: transparent; color: var(--ink); border: 1px solid var(--border); }
  .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
  .actions { padding: 20px 24px; }
  .actions .btn { margin-bottom: 8px; }
  .canvas-area { display: flex; flex-direction: column; background: var(--bg); }
  .canvas-toolbar { padding: 12px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; background: var(--surface); flex-wrap: wrap; }
  .canvas-title { font-family: 'Cormorant Garamond', serif; font-size: 18px; font-weight: 300; color: var(--ink-soft); flex: 1; font-style: italic; }
  .tool-btn { padding: 6px 12px; border: 1px solid var(--border); border-radius: var(--radius); background: transparent; cursor: pointer; font-family: 'DM Mono', monospace; font-size: 10px; letter-spacing: 0.06em; text-transform: uppercase; color: var(--ink-soft); transition: all 0.2s; display: flex; align-items: center; gap: 5px; }
  .tool-btn:hover { border-color: var(--accent); color: var(--accent); }
  .tool-btn.active { background: var(--ink); color: white; border-color: var(--ink); }
  .canvas-wrapper { flex: 1; display: flex; align-items: center; justify-content: center; padding: 32px; overflow: auto; }
  #roomCanvas { max-width: 100%; max-height: 100%; border-radius: var(--radius); box-shadow: 0 4px 40px var(--shadow), 0 1px 4px var(--shadow); cursor: crosshair; display: block; }
  .ai-panel { border-top: 1px solid var(--border); background: var(--surface); }
  .ai-panel-header { padding: 14px 24px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border); cursor: pointer; }
  .ai-panel-header:hover { background: var(--accent-light); }
  .ai-dot { width: 8px; height: 8px; background: var(--accent); border-radius: 50%; animation: pulse 2s infinite; }
  @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
  .ai-label { font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase; color: var(--ink-soft); }
  .ai-toggle { margin-left: auto; font-size: 16px; color: var(--ink-faint); transition: 0.3s; }
  .ai-toggle.open { transform: rotate(180deg); }
  .ai-body { padding: 16px 24px; max-height: 280px; overflow-y: auto; display: none; }
  .ai-body.open { display: block; }
  .ai-response { font-family: 'Cormorant Garamond', serif; font-size: 16px; line-height: 1.8; color: var(--ink); font-weight: 300; }
  .ai-response.loading { color: var(--ink-soft); font-style: italic; }
  .suggestion-item { padding: 10px 14px; border-left: 2px solid var(--accent); margin: 10px 0; background: var(--accent-light); border-radius: 0 var(--radius) var(--radius) 0; font-family: 'DM Mono', monospace; font-size: 12px; line-height: 1.6; }
  .legend { padding: 12px 24px; border-top: 1px solid var(--border); display: flex; flex-wrap: wrap; gap: 8px; background: var(--surface); }
  .legend-item { display: flex; align-items: center; gap: 5px; font-size: 10px; color: var(--ink-soft); letter-spacing: 0.04em; }
  .legend-dot { width: 10px; height: 10px; border-radius: 2px; }
  .toast { position: fixed; bottom: 24px; right: 24px; background: var(--ink); color: var(--bg); padding: 10px 16px; border-radius: var(--radius); font-size: 11px; letter-spacing: 0.04em; opacity: 0; transform: translateY(8px); transition: all 0.3s; z-index: 999; pointer-events: none; }
  .toast.show { opacity: 1; transform: translateY(0); }
  .canvas-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; color: var(--ink-faint); text-align: center; padding: 40px; }
  .canvas-empty .big-icon { font-size: 48px; opacity: 0.4; }
  .canvas-empty p { font-size: 12px; line-height: 1.7; max-width: 240px; }
  .hint { font-size: 10px; color: var(--ink-faint); letter-spacing: 0.04em; padding: 6px 0 0; line-height: 1.5; }
  .divider { height: 1px; background: var(--border); margin: 10px 0; }
  ::-webkit-scrollbar { width: 4px; }
  ::-webkit-scrollbar-thumb { background: var(--ink-faint); border-radius: 2px; }
</style>
</head>
<body>

<header>
  <div class="logo">MEK<span>Â</span>N</div>
  <div class="tagline">Mobilya Tasarım Stüdyosu</div>
  <div class="step-indicator">
    <div class="step-dot active" id="s1"></div>
    <div class="step-dot" id="s2"></div>
    <div class="step-dot" id="s3"></div>
  </div>
</header>

<div class="app">
  <aside class="sidebar">

    {{-- Panel 1: Mekan --}}
    <div class="panel">
      <div class="panel-header" onclick="togglePanel(1)">
        <div class="panel-num active" id="pnum1">1</div>
        <div class="panel-title active" id="ptitle1">Mekan Tanımı</div>
      </div>
      <div class="panel-body" id="pbody1">
        <div class="field-group">
          <label>Oda Fotoğrafı</label>
          <div class="upload-zone" id="uploadZone">
            <input type="file" accept="image/*" id="photoInput" onchange="handlePhotoUpload(event)">
            <div class="upload-icon">📷</div>
            <div class="upload-text">Fotoğraf yükle<br><span style="opacity:0.5">JPG, PNG — maks 10MB</span></div>
          </div>
          <div class="upload-preview" id="uploadPreview">
            <img id="previewImg" src="" alt="">
            <button class="remove-btn" onclick="removePhoto()">×</button>
          </div>
        </div>
        <div class="field-group">
          <label>Oda Boyutları</label>
          <div class="dims-grid">
            <div>
              <label>Genişlik</label>
              <div class="unit-field">
                <input type="number" id="roomW" placeholder="500" min="100" max="2000">
                <span class="unit-badge">cm</span>
              </div>
            </div>
            <div>
              <label>Derinlik</label>
              <div class="unit-field">
                <input type="number" id="roomD" placeholder="400" min="100" max="2000">
                <span class="unit-badge">cm</span>
              </div>
            </div>
            <div>
              <label>Tavan Yüks.</label>
              <div class="unit-field">
                <input type="number" id="roomH" placeholder="260" min="180" max="600">
                <span class="unit-badge">cm</span>
              </div>
            </div>
            <div>
              <label>Oda Tipi</label>
              <select id="roomType">
                <option value="salon">Salon</option>
                <option value="yatak">Yatak Odası</option>
                <option value="yemek">Yemek Odası</option>
                <option value="calisma">Çalışma Odası</option>
                <option value="mutfak">Mutfak</option>
                <option value="banyo">Banyo</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Panel 2: Mobilyalar --}}
    <div class="panel">
      <div class="panel-header" onclick="togglePanel(2)">
        <div class="panel-num" id="pnum2">2</div>
        <div class="panel-title" id="ptitle2">Mobilya Seçimi</div>
      </div>
      <div class="panel-body" id="pbody2" style="display:none">
        <div class="field-group">
          <label>Stil Tercihi</label>
          <div class="style-grid">
            <button class="style-btn selected" onclick="selectStyle(this,'minimal')" data-style="minimal"><span class="style-icon">◻</span>Minimal</button>
            <button class="style-btn" onclick="selectStyle(this,'skandinav')" data-style="skandinav"><span class="style-icon">◇</span>Skandinav</button>
            <button class="style-btn" onclick="selectStyle(this,'endüstri')" data-style="endüstri"><span class="style-icon">⬡</span>Endüstriyel</button>
            <button class="style-btn" onclick="selectStyle(this,'klasik')" data-style="klasik"><span class="style-icon">◈</span>Klasik</button>
          </div>
        </div>
        <div class="divider"></div>
        <div class="field-group">
          <label>Mobilya Ekle</label>
          <div class="furniture-list" id="furnitureList"></div>
          <p class="hint">Seçmek için tıkla → sonra tuval üzerine yerleştir<br>Çift tıkla = 90° döndür</p>
        </div>
      </div>
    </div>

    {{-- Panel 3: Tasarım --}}
    <div class="panel">
      <div class="panel-header" onclick="togglePanel(3)">
        <div class="panel-num" id="pnum3">3</div>
        <div class="panel-title" id="ptitle3">Tasarım Tercihleri</div>
      </div>
      <div class="panel-body" id="pbody3" style="display:none">
        <div class="field-group">
          <label>Renk Paleti</label>
          <div class="style-grid">
            <button class="style-btn selected" onclick="selectColor(this,'nötr')" data-color="nötr">
              <span class="style-icon" style="background:linear-gradient(135deg,#e8e0d4,#c4b9a8);border-radius:3px;display:block;height:20px;margin:0 auto 4px;width:30px;"></span>Nötr
            </button>
            <button class="style-btn" onclick="selectColor(this,'sıcak')" data-color="sıcak">
              <span class="style-icon" style="background:linear-gradient(135deg,#d4956a,#8b4513);border-radius:3px;display:block;height:20px;margin:0 auto 4px;width:30px;"></span>Sıcak
            </button>
            <button class="style-btn" onclick="selectColor(this,'serin')" data-color="serin">
              <span class="style-icon" style="background:linear-gradient(135deg,#8ba7c4,#4a6fa5);border-radius:3px;display:block;height:20px;margin:0 auto 4px;width:30px;"></span>Serin
            </button>
            <button class="style-btn" onclick="selectColor(this,'doğal')" data-color="doğal">
              <span class="style-icon" style="background:linear-gradient(135deg,#8db48e,#4a7c4e);border-radius:3px;display:block;height:20px;margin:0 auto 4px;width:30px;"></span>Doğal
            </button>
          </div>
        </div>
        <div class="field-group">
          <label>Ek Notlar (isteğe bağlı)</label>
          <textarea id="extraNotes" rows="3" placeholder="Pencerenin önü boş kalsın, ahşap detay istiyorum…" style="resize:vertical;"></textarea>
        </div>
      </div>
    </div>

    <div class="actions">
      <button class="btn btn-primary" onclick="generateDesign()">✦ Tasarım Oluştur</button>
      <button class="btn btn-outline" onclick="clearCanvas()">Tuvali Temizle</button>
    </div>

  </aside>

  <main class="canvas-area">
    <div class="canvas-toolbar">
      <div class="canvas-title">Plan Görünümü</div>
      <button class="tool-btn active" id="btnPlan" onclick="setView('plan')">⊡ Plan</button>
      <button class="tool-btn" id="btnPersp" onclick="setView('persp')">⬚ 3B Taslak</button>
      <button class="tool-btn" onclick="downloadCanvas()">↓ İndir</button>
    </div>

    <div class="canvas-wrapper">
      <div class="canvas-empty" id="canvasEmpty">
        <div class="big-icon">⊡</div>
        <p>Oda boyutlarını girin, mobilyaları seçin ve <strong>Tasarım Oluştur</strong>'a tıklayın.</p>
      </div>
      <canvas id="roomCanvas" style="display:none;"></canvas>
    </div>

    <div class="legend" id="legendBar" style="display:none;"></div>

    <div class="ai-panel">
      <div class="ai-panel-header" onclick="toggleAIPanel()">
        <div class="ai-dot"></div>
        <div class="ai-label">AI Tasarım Asistanı</div>
        <span class="ai-toggle" id="aiToggle">▾</span>
      </div>
      <div class="ai-body" id="aiBody">
        <div class="ai-response" id="aiResponse" style="color:var(--ink-faint);font-style:italic;">
          Tasarım oluşturulduktan sonra AI önerilerini burada göreceksiniz…
        </div>
      </div>
    </div>
  </main>
</div>

<div class="toast" id="toast"></div>

<script>
// CSRF token — Laravel'in gerektirdiği güvenlik başlığı
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const AI_URL     = '{{ route("ai.suggest") }}';

// ── State ─────────────────────────────────────────────────
const state = {
  photoData: null,
  roomW: 500, roomD: 400, roomH: 260,
  roomType: 'salon',
  style: 'minimal',
  colorPalette: 'nötr',
  selectedFurniture: new Set(),
  placedItems: [],
  view: 'plan',
  dragging: null,
  dragOffset: { x: 0, y: 0 },
};

// ── Furniture Catalog (Sunta / MDF / Ahşap) ───────────────
const FURNITURE = {
  salon: [
    { id: 'tvunit_low',    icon: '📺', name: 'Alçak TV Ünitesi',       w: 180, d: 45,  color: '#6B5E4E', mat: 'MDF' },
    { id: 'tvunit_tall',   icon: '🗄', name: 'Yüksek TV Dolabı',       w: 160, d: 40,  color: '#6B5E4E', mat: 'MDF' },
    { id: 'bookshelf_w',   icon: '📚', name: 'Kitaplık (90cm)',         w: 90,  d: 30,  color: '#8B6F47', mat: 'MDF' },
    { id: 'bookshelf_180', icon: '📚', name: 'Kitaplık (180cm)',        w: 180, d: 30,  color: '#8B6F47', mat: 'MDF' },
    { id: 'coffeetable',   icon: '◻',  name: 'Orta Sehpa',             w: 120, d: 60,  color: '#9C7A52', mat: 'Ahşap' },
    { id: 'sidetable',     icon: '◫',  name: 'Yan Sehpa',              w: 50,  d: 50,  color: '#9C7A52', mat: 'Ahşap' },
    { id: 'consoletable',  icon: '◪',  name: 'Konsol Masa',            w: 120, d: 35,  color: '#8B6F47', mat: 'MDF' },
    { id: 'display_cab',   icon: '🗄', name: 'Vitrin Dolabı',          w: 90,  d: 40,  color: '#5E5248', mat: 'MDF' },
    { id: 'plant',         icon: '🪴', name: 'Saksı Standı',           w: 35,  d: 35,  color: '#7A9B6E', mat: 'Ahşap' },
  ],
  yatak: [
    { id: 'baza_160',      icon: '⬛', name: 'Baza 160cm (Çift)',      w: 160, d: 210, color: '#6B5E4E', mat: 'Sunta' },
    { id: 'baza_90',       icon: '⬛', name: 'Baza 90cm (Tek)',        w: 90,  d: 200, color: '#6B5E4E', mat: 'Sunta' },
    { id: 'baza_140',      icon: '⬛', name: 'Baza 140cm (Fransız)',   w: 140, d: 200, color: '#6B5E4E', mat: 'Sunta' },
    { id: 'wardrobe_2',    icon: '🚪', name: 'Gardrop 2 Kapı (120)',   w: 120, d: 60,  color: '#8B7355', mat: 'Sunta' },
    { id: 'wardrobe_3',    icon: '🚪', name: 'Gardrop 3 Kapı (180)',   w: 180, d: 60,  color: '#8B7355', mat: 'Sunta' },
    { id: 'wardrobe_4',    icon: '🚪', name: 'Gardrop 4 Kapı (240)',   w: 240, d: 60,  color: '#8B7355', mat: 'Sunta' },
    { id: 'komodin',       icon: '◫',  name: 'Komodin',                w: 50,  d: 45,  color: '#8B7355', mat: 'MDF' },
    { id: 'sifonyer',      icon: '◪',  name: 'Şifonyer (5 Çekmece)',  w: 100, d: 50,  color: '#8B7355', mat: 'MDF' },
    { id: 'dresoir',       icon: '◫',  name: 'Makyaj Masası',          w: 90,  d: 45,  color: '#8B7355', mat: 'MDF' },
    { id: 'desk_bed',      icon: '🖥', name: 'Çalışma Masası',         w: 120, d: 60,  color: '#6B6560', mat: 'MDF' },
    { id: 'bookshelf_b',   icon: '📚', name: 'Kitaplık',               w: 80,  d: 30,  color: '#8B6F47', mat: 'MDF' },
  ],
  yemek: [
    { id: 'dtable_4',      icon: '◻',  name: 'Yemek Masası 4 Kişi',  w: 120, d: 80,  color: '#9C7A52', mat: 'Ahşap' },
    { id: 'dtable_6',      icon: '◻',  name: 'Yemek Masası 6 Kişi',  w: 180, d: 90,  color: '#9C7A52', mat: 'Ahşap' },
    { id: 'dtable_8',      icon: '◻',  name: 'Yemek Masası 8 Kişi',  w: 220, d: 100, color: '#9C7A52', mat: 'Ahşap' },
    { id: 'dtable_round',  icon: '⭕',  name: 'Yuvarlak Masa (Ø120)', w: 120, d: 120, color: '#9C7A52', mat: 'Ahşap' },
    { id: 'sideboard_lg',  icon: '◪',  name: 'Büfe (180cm)',          w: 180, d: 45,  color: '#6B5E4E', mat: 'MDF' },
    { id: 'sideboard_sm',  icon: '◪',  name: 'Büfe (120cm)',          w: 120, d: 45,  color: '#6B5E4E', mat: 'MDF' },
    { id: 'vitrin',        icon: '🗄', name: 'Vitrin Dolabı',         w: 90,  d: 40,  color: '#5E5248', mat: 'MDF' },
    { id: 'servis_arabasi',icon: '◫',  name: 'Servis Arabası',        w: 60,  d: 40,  color: '#9C7A52', mat: 'Ahşap' },
  ],
  calisma: [
    { id: 'desk_std',      icon: '🖥', name: 'Düz Masa',              w: 140, d: 70,  color: '#6B6560', mat: 'MDF' },
    { id: 'desk_l',        icon: '🖥', name: 'L-Şekil Masa',          w: 200, d: 80,  color: '#6B6560', mat: 'MDF' },
    { id: 'desk_corner',   icon: '🖥', name: 'Köşe Masası',           w: 160, d: 160, color: '#6B6560', mat: 'MDF' },
    { id: 'bookshelf_t',   icon: '📚', name: 'Kitaplık (90cm)',        w: 90,  d: 30,  color: '#8B6F47', mat: 'MDF' },
    { id: 'bookshelf_180c',icon: '📚', name: 'Kitaplık (180cm)',       w: 180, d: 30,  color: '#8B6F47', mat: 'MDF' },
    { id: 'dosya_dol',     icon: '🗄', name: 'Dosya Dolabı',          w: 45,  d: 60,  color: '#6B6560', mat: 'MDF' },
    { id: 'printer',       icon: '🖨', name: 'Yazıcı Sehpası',        w: 60,  d: 50,  color: '#6B6560', mat: 'MDF' },
    { id: 'toplanti',      icon: '◻',  name: 'Toplantı Masası',       w: 180, d: 90,  color: '#9C7A52', mat: 'Ahşap' },
  ],
  mutfak: [
    { id: 'alt_dolap_std', icon: '▬',  name: 'Alt Dolap (120cm)',     w: 120, d: 60,  color: '#7A6A5A', mat: 'Sunta' },
    { id: 'alt_dolap_l',   icon: '▬',  name: 'Alt Dolap (300cm)',     w: 300, d: 60,  color: '#7A6A5A', mat: 'Sunta' },
    { id: 'ust_dolap',     icon: '▭',  name: 'Üst Dolap Sırası',      w: 200, d: 35,  color: '#8B7B6B', mat: 'Sunta' },
    { id: 'tezgah',        icon: '◻',  name: 'Tezgah / Çalışma Yüzeyi',w:180, d: 60,  color: '#9C8C7C', mat: 'Sunta' },
    { id: 'kitchen_isle',  icon: '⬛', name: 'Mutfak Adası (120)',     w: 120, d: 80,  color: '#7A6A5A', mat: 'Sunta' },
    { id: 'kitchen_isle_lg',icon:'⬛', name: 'Mutfak Adası (160)',     w: 160, d: 90,  color: '#7A6A5A', mat: 'Sunta' },
    { id: 'fridge',        icon: '🧊', name: 'Buzdolabı',              w: 70,  d: 70,  color: '#D0CCCA', mat: 'Beyaz Eşya' },
    { id: 'fridge_lg',     icon: '🧊', name: 'Büyük Buzdolabı',       w: 90,  d: 70,  color: '#D0CCCA', mat: 'Beyaz Eşya' },
    { id: 'dishwasher',    icon: '🍽', name: 'Bulaşık Makinesi',      w: 60,  d: 60,  color: '#D0CCCA', mat: 'Beyaz Eşya' },
    { id: 'oven',          icon: '🔥', name: 'Fırın / Ocak',          w: 60,  d: 60,  color: '#5E5248', mat: 'Beyaz Eşya' },
    { id: 'dtable_mut',    icon: '◻',  name: 'Mutfak Masası',         w: 100, d: 70,  color: '#9C7A52', mat: 'Ahşap' },
  ],
  banyo: [
    { id: 'lavabo_tek',    icon: '🚰', name: 'Lavabo Dolabı (Tek)',   w: 60,  d: 50,  color: '#D0CCCA', mat: 'MDF' },
    { id: 'lavabo_cift',   icon: '🚰', name: 'Lavabo Dolabı (Çift)',  w: 120, d: 50,  color: '#D0CCCA', mat: 'MDF' },
    { id: 'wc',            icon: '🚽', name: 'Klozet',                w: 40,  d: 65,  color: '#FAFAFA', mat: 'Seramik' },
    { id: 'dus',           icon: '🚿', name: 'Duş Kabini',            w: 90,  d: 90,  color: '#C8E0E8', mat: 'Cam' },
    { id: 'kuve',          icon: '🛁', name: 'Küvet',                 w: 170, d: 75,  color: '#C8E0E8', mat: 'Akrilik' },
    { id: 'ayna_dol',      icon: '◫',  name: 'Ayna Dolabı',           w: 60,  d: 15,  color: '#8B7B6B', mat: 'MDF' },
    { id: 'havluluk',      icon: '◫',  name: 'Havluluk Dolabı',       w: 40,  d: 30,  color: '#8B7B6B', mat: 'MDF' },
  ],
};

// ── Init ──────────────────────────────────────────────────
function init() {
  renderFurnitureList();
  setupCanvas();
}

function renderFurnitureList() {
  const type  = document.getElementById('roomType').value;
  const items = FURNITURE[type] || FURNITURE.salon;
  document.getElementById('furnitureList').innerHTML = items.map(f => `
    <div class="furniture-item ${state.selectedFurniture.has(f.id) ? 'selected' : ''}" onclick="toggleFurniture('${f.id}')">
      <div class="furn-icon">${f.icon}</div>
      <div class="furn-info">
        <div class="furn-name">${f.name}</div>
        <div class="furn-dims">${f.w} × ${f.d} cm &nbsp;<span class="mat-badge">${f.mat || 'MDF'}</span></div>
      </div>
      <div class="furn-toggle">${state.selectedFurniture.has(f.id) ? '✓' : ''}</div>
    </div>`).join('');
}

document.getElementById('roomType').addEventListener('change', () => {
  state.selectedFurniture.clear();
  renderFurnitureList();
});

function toggleFurniture(id) {
  state.selectedFurniture.has(id) ? state.selectedFurniture.delete(id) : state.selectedFurniture.add(id);
  renderFurnitureList();
}

function togglePanel(n) {
  const b = document.getElementById(`pbody${n}`);
  b.style.display = b.style.display !== 'none' ? 'none' : 'block';
}
function openPanel(n) { document.getElementById(`pbody${n}`).style.display = 'block'; }

function selectStyle(btn, s) {
  document.querySelectorAll('[data-style]').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected'); state.style = s;
}
function selectColor(btn, c) {
  document.querySelectorAll('[data-color]').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected'); state.colorPalette = c;
}

// ── Photo ─────────────────────────────────────────────────
function handlePhotoUpload(e) {
  const file = e.target.files[0]; if (!file) return;
  const reader = new FileReader();
  reader.onload = ev => {
    state.photoData = ev.target.result;
    document.getElementById('uploadZone').style.display = 'none';
    document.getElementById('uploadPreview').style.display = 'block';
    document.getElementById('previewImg').src = ev.target.result;
    showToast('Fotoğraf yüklendi ✓');
  };
  reader.readAsDataURL(file);
}
function removePhoto() {
  state.photoData = null;
  document.getElementById('uploadZone').style.display = 'block';
  document.getElementById('uploadPreview').style.display = 'none';
  document.getElementById('photoInput').value = '';
}

// ── Canvas ────────────────────────────────────────────────
const canvas = document.getElementById('roomCanvas');
const ctx    = canvas.getContext('2d');
let scale = 1, PADDING = 48;

function setupCanvas() {
  canvas.addEventListener('mousedown', onMouseDown);
  canvas.addEventListener('mousemove', onMouseMove);
  canvas.addEventListener('mouseup',   onMouseUp);
  canvas.addEventListener('dblclick',  onDoubleClick);
}

function computeScale(W, D) {
  const maxW = Math.min(window.innerWidth - 380, 800);
  const maxH = Math.min(window.innerHeight - 200, 600);
  return Math.min((maxW - PADDING*2)/W, (maxH - PADDING*2)/D, 2);
}

function generateDesign() {
  const W = parseFloat(document.getElementById('roomW').value) || 500;
  const D = parseFloat(document.getElementById('roomD').value) || 400;
  const H = parseFloat(document.getElementById('roomH').value) || 260;
  state.roomW = W; state.roomD = D; state.roomH = H;
  state.roomType = document.getElementById('roomType').value;

  if (!state.selectedFurniture.size) { showToast('En az bir mobilya seçin!'); openPanel(2); return; }

  scale = computeScale(W, D);
  canvas.width  = W * scale + PADDING * 2;
  canvas.height = D * scale + PADDING * 2;

  autoArrange();
  document.getElementById('canvasEmpty').style.display = 'none';
  canvas.style.display = 'block';
  drawRoom(); renderLegend(); callAI();

  document.getElementById('s1').className = 'step-dot done';
  document.getElementById('s2').className = 'step-dot done';
  document.getElementById('s3').className = 'step-dot active';
  showToast('Tasarım oluşturuldu ✦');
}

function autoArrange() {
  const catalog = FURNITURE[state.roomType] || FURNITURE.salon;
  const { roomW: W, roomD: D } = state;
  state.placedItems = [];
  const m = 20;
  let cx = m, cy = m, rowH = 0;

  state.selectedFurniture.forEach(id => {
    const f = catalog.find(x => x.id === id); if (!f) return;
    let px = cx, py = cy;
    if      (id.startsWith('tvunit'))                      { px=(W-f.w)/2; py=m; }
    else if (id.startsWith('baza'))                        { px=(W-f.w)/2; py=m+40; }
    else if (id.startsWith('wardrobe'))                    { px=W-f.w-m;   py=m; }
    else if (id.startsWith('dtable')||id==='toplanti')     { px=(W-f.w)/2; py=(D-f.d)/2; }
    else if (id.startsWith('kitchen_isle'))                { px=(W-f.w)/2; py=(D-f.d)/2; }
    else if (id.startsWith('alt_dolap')||id==='tezgah')    { px=m;         py=m; }
    else if (id.startsWith('ust_dolap'))                   { px=m;         py=m; }
    else if (id==='fridge'||id==='fridge_lg')              { px=W-f.w-m;   py=m; }
    else if (id==='oven'||id==='dishwasher')               { px=W-f.w-m*2-70; py=m; }
    else if (id.startsWith('bookshelf'))                   { px=m;         py=m; }
    else if (id.startsWith('sideboard')||id==='vitrin')    { px=(W-f.w)/2; py=m; }
    else if (id==='wc')                                    { px=W-f.w-m;   py=D-f.d-m; }
    else if (id==='dus')                                   { px=W-f.w-m;   py=m; }
    else if (id==='kuve')                                  { px=m;         py=m; }
    else if (id.startsWith('lavabo'))                      { px=(W-f.w)/2; py=D-f.d-m; }
    else if (id==='consoletable')                          { px=(W-f.w)/2; py=m; }
    else if (id==='desk_corner')                           { px=m;         py=m; }
    else {
      if (cx+f.w>W-m) { cx=m; cy+=rowH+m; rowH=0; }
      px=cx; py=cy; cx+=f.w+m; rowH=Math.max(rowH,f.d);
    }
    px = Math.max(m, Math.min(px, W-f.w-m));
    py = Math.max(m, Math.min(py, D-f.d-m));
    state.placedItems.push({...f, x:px, y:py, rotation:0});
  });
}

// ── Draw Room ─────────────────────────────────────────────
const COLORS = {
  'nötr':  { wall:'#D4CFC8', floor:'#EDE8E1' },
  'sıcak': { wall:'#C4956A', floor:'#F0DFC8' },
  'serin': { wall:'#8BA7C4', floor:'#D8E8F0' },
  'doğal': { wall:'#8DB48E', floor:'#DCE8D4' },
};

function drawRoom() {
  const P=PADDING, W=state.roomW*scale, H=state.roomD*scale;
  const pal = COLORS[state.colorPalette] || COLORS['nötr'];
  ctx.clearRect(0,0,canvas.width,canvas.height);
  const doDraw = () => { drawFloorGrid(P,W,H,pal); drawWalls(P,W,H,pal); drawItems(P); drawDimensions(P,W,H); };
  if (state.photoData) {
    const img=new Image(); img.onload=()=>{ ctx.globalAlpha=0.18; ctx.drawImage(img,P,P,W,H); ctx.globalAlpha=1; doDraw(); }; img.src=state.photoData;
  } else doDraw();
}

function drawFloorGrid(P,W,H,pal) {
  ctx.fillStyle=pal.floor; ctx.fillRect(P,P,W,H);
  ctx.strokeStyle='rgba(0,0,0,0.04)'; ctx.lineWidth=0.5;
  const s=50*scale;
  for(let x=P;x<=P+W;x+=s){ctx.beginPath();ctx.moveTo(x,P);ctx.lineTo(x,P+H);ctx.stroke();}
  for(let y=P;y<=P+H;y+=s){ctx.beginPath();ctx.moveTo(P,y);ctx.lineTo(P+W,y);ctx.stroke();}
}

function drawWalls(P,W,H,pal) {
  const t=8; ctx.fillStyle=pal.wall;
  ctx.fillRect(P-t,P-t,W+t*2,t); ctx.fillRect(P-t,P+H,W+t*2,t);
  ctx.fillRect(P-t,P-t,t,H+t*2); ctx.fillRect(P+W,P-t,t,H+t*2);
  ctx.strokeStyle='rgba(0,0,0,0.15)'; ctx.lineWidth=1; ctx.strokeRect(P,P,W,H);
  ctx.fillStyle='rgba(0,0,0,0.25)'; ctx.font=`bold 10px DM Mono,monospace`; ctx.textAlign='center';
  ctx.fillText('K',P+W/2,P-16);
}

function drawItems(P) {
  state.placedItems.forEach(item => {
    const x=P+item.x*scale, y=P+item.y*scale, w=item.w*scale, h=item.d*scale;
    ctx.save(); ctx.translate(x+w/2,y+h/2); ctx.rotate(item.rotation*Math.PI/180); ctx.translate(-w/2,-h/2);
    ctx.shadowColor='rgba(0,0,0,0.12)'; ctx.shadowBlur=6; ctx.shadowOffsetX=2; ctx.shadowOffsetY=3;
    ctx.fillStyle=item.color+'DD'; ctx.beginPath(); ctx.roundRect(0,0,w,h,3); ctx.fill();
    ctx.shadowColor='transparent';
    ctx.strokeStyle=item.color; ctx.lineWidth=1.5; ctx.stroke();
    ctx.strokeStyle='rgba(255,255,255,0.4)'; ctx.lineWidth=1; ctx.beginPath(); ctx.moveTo(4,4); ctx.lineTo(w-4,4); ctx.stroke();
    if(Math.min(w,h)>30){ ctx.fillStyle='rgba(255,255,255,0.9)'; ctx.font=`${Math.min(w,h)*0.35}px serif`; ctx.textAlign='center'; ctx.textBaseline='middle'; ctx.fillText(item.icon,w/2,h/2-6); }
    if(w>50&&h>30){ ctx.fillStyle='rgba(255,255,255,0.8)'; ctx.font=`${Math.max(8,Math.min(w*0.12,11))}px DM Mono,monospace`; ctx.textBaseline='bottom'; ctx.fillText(`${item.w}×${item.d}`,w/2,h-3); }
    ctx.restore();
  });
}

function drawDimensions(P,W,H) {
  ctx.fillStyle='rgba(107,101,96,0.7)'; ctx.font=`10px DM Mono,monospace`; ctx.textAlign='center'; ctx.textBaseline='middle';
  ctx.fillText(`${state.roomW} cm`,P+W/2,P+H+PADDING*0.5);
  ctx.save(); ctx.translate(P-PADDING*0.5,P+H/2); ctx.rotate(-Math.PI/2); ctx.fillText(`${state.roomD} cm`,0,0); ctx.restore();
}

// ── 3D Sketch ─────────────────────────────────────────────
function setView(v) {
  state.view=v;
  document.getElementById('btnPlan').classList.toggle('active',v==='plan');
  document.getElementById('btnPersp').classList.toggle('active',v==='persp');
  if(state.placedItems.length) v==='persp' ? draw3DSketch() : drawRoom();
}

function draw3DSketch() {
  const P=PADDING, W=state.roomW*scale, H=state.roomD*scale;
  const pal=COLORS[state.colorPalette]||COLORS['nötr'];
  ctx.clearRect(0,0,canvas.width,canvas.height);
  const iso=(x,y,z)=>({ sx:P+(x-y)*0.5*scale*0.6+W*0.3, sy:P+(x+y)*0.25*scale*0.6-z*0.5+H*0.3 });
  const {roomW:RW,roomD:RD,roomH:RH}=state;
  const poly=(pts,fill,stroke)=>{ ctx.beginPath(); ctx.moveTo(pts[0].sx,pts[0].sy); pts.slice(1).forEach(p=>ctx.lineTo(p.sx,p.sy)); ctx.closePath(); ctx.fillStyle=fill; ctx.fill(); ctx.strokeStyle=stroke||'rgba(0,0,0,0.1)'; ctx.lineWidth=1; ctx.stroke(); };
  poly([iso(0,0,0),iso(RW,0,0),iso(RW,RD,0),iso(0,RD,0)],pal.floor);
  poly([iso(0,0,0),iso(0,0,RH),iso(0,RD,RH),iso(0,RD,0)],pal.wall+'88');
  poly([iso(0,0,0),iso(RW,0,0),iso(RW,0,RH),iso(0,0,RH)],pal.wall+'AA');
  state.placedItems.forEach(item=>{
    const ih=Math.min(item.w,item.d)*0.5;
    const[x,y,w,d]=[item.x,item.y,item.w,item.d];
    poly([iso(x,y,ih),iso(x+w,y,ih),iso(x+w,y+d,ih),iso(x,y+d,ih)],item.color+'DD',item.color);
    poly([iso(x,y+d,0),iso(x+w,y+d,0),iso(x+w,y+d,ih),iso(x,y+d,ih)],item.color+'88',item.color);
    poly([iso(x+w,y+d,0),iso(x+w,y,0),iso(x+w,y,ih),iso(x+w,y+d,ih)],item.color+'55',item.color);
    const c=iso(x+w/2,y+d/2,ih);
    ctx.fillStyle='rgba(255,255,255,0.85)'; ctx.font='9px DM Mono,monospace'; ctx.textAlign='center'; ctx.textBaseline='middle';
    ctx.fillText(item.icon,c.sx,c.sy);
  });
}

// ── Drag & Drop ───────────────────────────────────────────
function getItemAt(mx,my){
  const P=PADDING;
  for(let i=state.placedItems.length-1;i>=0;i--){
    const item=state.placedItems[i];
    const x=P+item.x*scale,y=P+item.y*scale,w=item.w*scale,h=item.d*scale;
    if(mx>=x&&mx<=x+w&&my>=y&&my<=y+h) return i;
  }
  return -1;
}
function onMouseDown(e){ const r=canvas.getBoundingClientRect(),mx=e.clientX-r.left,my=e.clientY-r.top,idx=getItemAt(mx,my); if(idx>=0){state.dragging=idx;const i=state.placedItems[idx];state.dragOffset={x:mx-(PADDING+i.x*scale),y:my-(PADDING+i.y*scale)};canvas.style.cursor='grabbing';} }
function onMouseMove(e){ if(state.dragging===null){canvas.style.cursor=getItemAt(e.clientX-canvas.getBoundingClientRect().left,e.clientY-canvas.getBoundingClientRect().top)>=0?'grab':'default';return;} const r=canvas.getBoundingClientRect(),mx=e.clientX-r.left,my=e.clientY-r.top,item=state.placedItems[state.dragging]; item.x=Math.max(0,Math.min((mx-state.dragOffset.x-PADDING)/scale,state.roomW-item.w)); item.y=Math.max(0,Math.min((my-state.dragOffset.y-PADDING)/scale,state.roomD-item.d)); drawRoom(); }
function onMouseUp(){ state.dragging=null; canvas.style.cursor='default'; }
function onDoubleClick(e){ const r=canvas.getBoundingClientRect(),idx=getItemAt(e.clientX-r.left,e.clientY-r.top); if(idx>=0){const i=state.placedItems[idx]; i.rotation=(i.rotation+90)%360; if(i.rotation%180!==0)[i.w,i.d]=[i.d,i.w]; drawRoom(); showToast('Döndürüldü 90°');} }

// ── Legend ────────────────────────────────────────────────
function renderLegend(){
  const bar=document.getElementById('legendBar');
  if(!state.placedItems.length){bar.style.display='none';return;}
  bar.style.display='flex';
  bar.innerHTML=state.placedItems.map(i=>`<div class="legend-item"><div class="legend-dot" style="background:${i.color}"></div>${i.name}</div>`).join('');
}

// ── Clear ─────────────────────────────────────────────────
function clearCanvas(){
  state.placedItems=[]; state.selectedFurniture.clear(); renderFurnitureList();
  ctx.clearRect(0,0,canvas.width,canvas.height); canvas.style.display='none';
  document.getElementById('canvasEmpty').style.display='flex';
  document.getElementById('legendBar').style.display='none';
  document.getElementById('aiResponse').textContent='Tasarım oluşturulduktan sonra AI önerilerini burada göreceksiniz…';
  ['s1','s2','s3'].forEach((s,i)=>document.getElementById(s).className='step-dot'+(i===0?' active':''));
  showToast('Tuval temizlendi');
}

// ── Download ──────────────────────────────────────────────
function downloadCanvas(){
  if(canvas.style.display==='none'){showToast('Önce tasarım oluşturun');return;}
  const a=document.createElement('a'); a.download=`mekan-tasarim-${Date.now()}.png`; a.href=canvas.toDataURL('image/png'); a.click();
  showToast('PNG olarak indirildi ↓');
}

// ── AI Panel ──────────────────────────────────────────────
function toggleAIPanel(){
  const b=document.getElementById('aiBody'),t=document.getElementById('aiToggle');
  b.classList.toggle('open'); t.classList.toggle('open');
}

async function callAI() {
  const aiBody=document.getElementById('aiBody'), aiResponse=document.getElementById('aiResponse');
  aiBody.classList.add('open'); document.getElementById('aiToggle').classList.add('open');
  aiResponse.className='ai-response loading'; aiResponse.textContent='Tasarım analiz ediliyor…';

  const furnitureNames = state.placedItems.map(i=>`${i.name} (${i.w}×${i.d} cm)`).join(', ');
  const notes          = document.getElementById('extraNotes').value;

  try {
    // ★ Artık doğrudan Anthropic değil, kendi Laravel route'una gidiyor
    const resp = await fetch(AI_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN':  CSRF_TOKEN,     // Laravel CSRF koruması
        'Accept':        'application/json',
      },
      body: JSON.stringify({
        roomType:     state.roomType,
        roomW:        state.roomW,
        roomD:        state.roomD,
        roomH:        state.roomH,
        style:        state.style,
        colorPalette: state.colorPalette,
        furniture:    furnitureNames,
        notes:        notes,
      }),
    });

    const data = await resp.json();
    aiResponse.className = 'ai-response';
    if (data.success) {
      aiResponse.innerHTML = formatAIResponse(data.text);
    } else {
      aiResponse.textContent = data.text || 'Bir hata oluştu.';
    }
  } catch(err) {
    aiResponse.className = 'ai-response';
    aiResponse.textContent = 'Sunucu bağlantı hatası. Tasarımınız hazır, AI önerileri şu an kullanılamıyor.';
  }
}

function formatAIResponse(text){
  return text
    .replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>')
    .replace(/^(\d+\..+)/gm,'<div class="suggestion-item">$1</div>')
    .replace(/^[-•]\s*(.+)/gm,'<div class="suggestion-item">→ $1</div>')
    .replace(/\n\n/g,'<br><br>').replace(/\n/g,'<br>');
}

// ── Toast ─────────────────────────────────────────────────
function showToast(msg){
  const t=document.getElementById('toast'); t.textContent=msg; t.classList.add('show');
  setTimeout(()=>t.classList.remove('show'),2200);
}

init();
document.getElementById('roomW').addEventListener('blur',()=>openPanel(2));
</script>
</body>
</html>
