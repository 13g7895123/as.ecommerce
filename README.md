# E-commerce Project

多品牌電商平台專案，包含時尚、家居、雜貨三個子品牌。

## 📁 專案結構

```
04_ecommerce/
├── demo/                    # 靜態網站展示版本（HTML/CSS/JS）
│   ├── fashion/            # S.Collection - 時尚服飾網站
│   ├── home/               # H.Collection - 家居用品網站
│   └── general/            # G.Collection - 生活雜貨網站
│
├── frontend/               # Nuxt 3 前端應用（使用 Bun）
│   ├── apps/              # 各品牌獨立應用
│   │   ├── fashion/       # 時尚網站（port 3000）
│   │   ├── home/          # 家居網站（port 3001）
│   │   └── general/       # 雜貨網站（port 3002）
│   ├── layers/            # 共用層
│   │   ├── base/          # 基礎元件、composables、types
│   │   └── auth/          # 認證相關
│   ├── packages/          # 共用套件
│   │   ├── shared-types/  # TypeScript 類型定義
│   │   └── api-client/    # API 客戶端
│   └── package.json       # Monorepo 根配置
│
└── worktrees/             # Git worktrees（後端開發分支）
    └── backend/           # 後端 API 開發
```

## 🚀 快速開始

### Demo 版本（靜態網站）

靜態 HTML 網站，可直接在瀏覽器開啟：

```bash
# 使用任意 HTTP 服務器運行
cd demo/fashion
python -m http.server 8000

# 或使用 live-server
npx live-server demo/fashion
```

### Frontend（Nuxt 3）

使用 Bun 作為套件管理器和運行環境：

```bash
cd frontend

# 安裝依賴
bun install

# 開發模式 - 運行所有應用
bun run dev

# 開發模式 - 運行單一應用
bun run dev:fashion   # http://localhost:3000
bun run dev:home      # http://localhost:3001
bun run dev:general   # http://localhost:3002

# 建置生產版本
bun run build

# 生成靜態網站
bun run generate
```

## 🎨 品牌設計

### S.Collection - 時尚服飾
- 主色：黑色 #333
- 強調色：金色 #c49b63
- 風格：奢華、專業

### H.Collection - 家居用品
- 主色：棕色 #8B4513
- 強調色：米色 #F5F5DC
- 風格：溫暖、舒適

### G.Collection - 生活雜貨
- 主色：橄欖綠 #556B2F
- 強調色：金色 #D4A574
- 風格：自然、實用

## 🛠️ 技術棧

### Demo 版本
- HTML5
- CSS3（Grid, Flexbox）
- Vanilla JavaScript
- Font Awesome 6.4.0

### Frontend
- **Framework**: Nuxt 3.21.1
- **Runtime**: Bun 1.3.2
- **Build Tool**: Turborepo 2.8.6
- **State**: Pinia
- **Styling**: Tailwind CSS
- **TypeScript**: 5.9.3
- **Icons**: Font Awesome
- **Utils**: VueUse

## 📦 Monorepo 管理

使用 Turborepo 管理多應用建置：

```bash
# 建置所有應用
bun run build

# 清理快取
turbo clean

# 查看建置圖
turbo run build --graph
```

## 🔧 開發工具

### Git Worktrees

後端開發使用獨立的 worktree：

```bash
# 切換到後端分支
cd worktrees/backend

# 回到主專案
cd ../../
```

### VS Code 建議擴充

- Vue - Official
- Tailwind CSS IntelliSense
- TypeScript Vue Plugin (Volar)
- ESLint
- Prettier

## 📝 License

Private Project
