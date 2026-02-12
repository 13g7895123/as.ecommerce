# Demo - 靜態網站展示版本

本目錄包含三個品牌的靜態 HTML 網站原型。

## 📂 目錄說明

### fashion/ - S.Collection 時尚服飾
```
fashion/
├── index.html          # 首頁
├── product.html        # 商品列表
├── checkout.html       # 結帳頁面
├── login.html          # 登入
├── register.html       # 註冊
├── member.html         # 會員中心
├── search.html         # 搜尋頁面
└── style.css           # 樣式表
```

**主題**: 奢華黑金風格  
**配色**: #333 (黑色) + #c49b63 (金色)

---

### home/ - H.Collection 家居用品
```
home/
├── index.html          # 首頁
├── product.html        # 商品列表
├── checkout.html       # 結帳頁面
├── login.html          # 登入
├── register.html       # 註冊
├── member.html         # 會員中心
├── search.html         # 搜尋頁面
└── style.css           # 樣式表
```

**主題**: 溫暖家居風格  
**配色**: #8B4513 (棕色) + #F5F5DC (米色)

---

### general/ - G.Collection 生活雜貨
```
general/
├── index.html          # 首頁
├── product.html        # 商品列表
├── checkout.html       # 結帳頁面
├── login.html          # 登入
├── register.html       # 註冊
├── member.html         # 會員中心
├── search.html         # 搜尋頁面
└── style.css           # 樣式表
```

**主題**: 自然清新風格  
**配色**: #556B2F (橄欖綠) + #D4A574 (金色)

## 🚀 如何運行

### 方法 1: 直接開啟
雙擊 `index.html` 即可在瀏覽器中查看。

### 方法 2: 使用 Live Server (推薦)
```bash
# 全域安裝 live-server
npm install -g live-server

# 運行 fashion 網站
cd fashion
live-server

# 運行 home 網站
cd home
live-server

# 運行 general 網站
cd general
live-server
```

### 方法 3: 使用 Python HTTP Server
```bash
# Fashion
cd fashion
python -m http.server 8000

# Home
cd home
python -m http.server 8001

# General
cd general
python -m http.server 8002
```

### 方法 4: 使用 Bun (最快)
```bash
# Fashion
cd fashion
bunx serve

# Home  
cd home
bunx serve

# General
cd general
bunx serve
```

## ✨ 功能特點

### 共同功能
- ✅ 響應式設計（RWD）
- ✅ 輪播圖（自動播放、拖曳、無限循環）
- ✅ 商品展示網格
- ✅ 購物車功能
- ✅ 會員系統（登入/註冊/會員中心）
- ✅ 搜尋功能
- ✅ Font Awesome 圖標

### 輪播功能
- 自動播放（5 秒間隔）
- 支援滑鼠/觸控拖曳
- 無限循環播放
- 指示點導航
- 左右箭頭切換

### 會員功能
- 表單驗證
- localStorage 儲存
- 個人資料管理
- 訂單記錄查詢

## 🎨 設計規範

### 字體
- 英文: -apple-system, BlinkMacSystemFont, 'Segoe UI'
- 中文: 'Noto Sans TC', Arial, sans-serif
- 標題: Georgia, 'Times New Roman', serif

### 間距系統
- 小間距: 8px, 12px, 16px
- 中間距: 20px, 24px, 30px
- 大間距: 40px, 60px, 80px

### 圓角
- 按鈕: 8px
- 卡片: 12px
- 輪播: 12px

### 陰影
- 小陰影: 0 2px 8px rgba(0,0,0,0.08)
- 中陰影: 0 4px 15px rgba(0,0,0,0.1)
- 大陰影: 0 10px 30px rgba(0,0,0,0.15)

## 📝 技術細節

### 技術棧
- **HTML5**: 語意化標籤
- **CSS3**: Grid, Flexbox, Transitions, Animations
- **JavaScript**: ES6+, DOM API, localStorage
- **Icons**: Font Awesome 6.4.0 (CDN)

### 瀏覽器支援
- Chrome (最新)
- Firefox (最新)
- Safari (最新)
- Edge (最新)

### 效能優化
- CSS 使用 transform 動畫（GPU 加速）
- 圖片使用 loading="lazy"
- 事件監聽使用 passive
- localStorage 快取機制

## 🔄 遷移到 Nuxt 3

這些靜態網站已經遷移到 Nuxt 3 框架，參見 `../frontend/` 目錄：
- 使用 Vue 3 組件化
- TypeScript 類型安全
- SSR/SSG 支援
- 更好的效能和 SEO
- 共用元件和邏輯

Demo 版本保留作為原型參考和快速展示用途。
