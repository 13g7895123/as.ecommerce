#!/bin/bash

# 快速啟動驗證腳本
# 用於驗證開發環境是否正確設置

set -e

echo "🚀 購物網站快速啟動驗證腳本"
echo "================================"
echo ""

# 顏色定義
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 檢查函數
check_command() {
    if command -v $1 &> /dev/null; then
        echo -e "${GREEN}✓${NC} $1 已安裝: $(command -v $1)"
        return 0
    else
        echo -e "${RED}✗${NC} $1 未安裝"
        return 1
    fi
}

check_version() {
    local cmd=$1
    local min_version=$2
    local current_version=$($cmd --version 2>&1 | grep -oE '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
    
    if [ -n "$current_version" ]; then
        echo -e "${GREEN}✓${NC} $cmd 版本: $current_version"
        return 0
    else
        echo -e "${YELLOW}⚠${NC} 無法檢測 $cmd 版本"
        return 1
    fi
}

# 1. 檢查系統需求
echo "📋 檢查系統需求..."
echo ""

REQUIREMENTS_MET=true

if check_command node; then
    check_version node "20.0.0"
else
    REQUIREMENTS_MET=false
fi

if check_command npm; then
    check_version npm "10.0.0"
else
    REQUIREMENTS_MET=false
fi

if check_command git; then
    check_version git "2.0.0"
else
    REQUIREMENTS_MET=false
fi

echo ""

if [ "$REQUIREMENTS_MET" = false ]; then
    echo -e "${RED}✗ 系統需求未滿足${NC}"
    echo ""
    echo "請安裝以下工具："
    echo "  - Node.js v20.x LTS: https://nodejs.org/"
    echo "  - Git v2.x: https://git-scm.com/"
    exit 1
fi

# 2. 檢查專案結構
echo "📁 檢查專案結構..."
echo ""

check_dir() {
    if [ -d "$1" ]; then
        echo -e "${GREEN}✓${NC} $1 存在"
        return 0
    else
        echo -e "${RED}✗${NC} $1 不存在"
        return 1
    fi
}

check_file() {
    if [ -f "$1" ]; then
        echo -e "${GREEN}✓${NC} $1 存在"
        return 0
    else
        echo -e "${RED}✗${NC} $1 不存在"
        return 1
    fi
}

PROJECT_STRUCTURE_OK=true

check_file "package.json" || PROJECT_STRUCTURE_OK=false
check_file "nuxt.config.ts" || PROJECT_STRUCTURE_OK=false
check_file "tsconfig.json" || PROJECT_STRUCTURE_OK=false
check_file "tailwind.config.ts" || PROJECT_STRUCTURE_OK=false

check_dir "app" || PROJECT_STRUCTURE_OK=false
check_dir "app/components" || PROJECT_STRUCTURE_OK=false
check_dir "app/pages" || PROJECT_STRUCTURE_OK=false
check_dir "app/stores" || PROJECT_STRUCTURE_OK=false
check_dir "server" || PROJECT_STRUCTURE_OK=false
check_dir "server/api" || PROJECT_STRUCTURE_OK=false

echo ""

if [ "$PROJECT_STRUCTURE_OK" = false ]; then
    echo -e "${YELLOW}⚠ 部分專案結構缺失，但可能不影響運作${NC}"
fi

# 3. 檢查依賴安裝
echo "📦 檢查依賴安裝..."
echo ""

if [ -d "node_modules" ]; then
    echo -e "${GREEN}✓${NC} node_modules 存在"
    
    # 檢查關鍵套件
    check_package() {
        if [ -d "node_modules/$1" ]; then
            echo -e "${GREEN}✓${NC} $1 已安裝"
            return 0
        else
            echo -e "${RED}✗${NC} $1 未安裝"
            return 1
        fi
    }
    
    PACKAGES_OK=true
    check_package "nuxt" || PACKAGES_OK=false
    check_package "vue" || PACKAGES_OK=false
    check_package "pinia" || PACKAGES_OK=false
    check_package "@nuxtjs/tailwindcss" || PACKAGES_OK=false
    check_package "vitest" || PACKAGES_OK=false
    check_package "@playwright/test" || PACKAGES_OK=false
    
    echo ""
    
    if [ "$PACKAGES_OK" = false ]; then
        echo -e "${RED}✗ 部分關鍵套件未安裝${NC}"
        echo "執行: npm install"
        exit 1
    fi
else
    echo -e "${RED}✗${NC} node_modules 不存在"
    echo "執行: npm install"
    exit 1
fi

# 4. 檢查配置文件
echo "⚙️  檢查配置文件..."
echo ""

CONFIG_OK=true

# 檢查 nuxt.config.ts 是否包含關鍵配置
if grep -q "@pinia/nuxt" nuxt.config.ts 2>/dev/null; then
    echo -e "${GREEN}✓${NC} Pinia 模組已配置"
else
    echo -e "${YELLOW}⚠${NC} Pinia 模組未在 nuxt.config.ts 中找到"
    CONFIG_OK=false
fi

if grep -q "typescript" nuxt.config.ts 2>/dev/null; then
    echo -e "${GREEN}✓${NC} TypeScript 配置已設置"
else
    echo -e "${YELLOW}⚠${NC} TypeScript 配置未在 nuxt.config.ts 中找到"
fi

echo ""

# 5. 測試 API 端點（如果服務器在運行）
echo "🌐 檢查 API 端點..."
echo ""

API_AVAILABLE=false

if curl -s -f http://localhost:3000/api/products > /dev/null 2>&1; then
    echo -e "${GREEN}✓${NC} API 端點可訪問: /api/products"
    API_AVAILABLE=true
else
    echo -e "${YELLOW}⚠${NC} API 端點不可訪問（開發服務器可能未運行）"
    echo "   執行: npm run dev"
fi

echo ""

# 6. 檢查測試配置
echo "🧪 檢查測試配置..."
echo ""

if [ -f "vitest.config.ts" ]; then
    echo -e "${GREEN}✓${NC} Vitest 配置存在"
else
    echo -e "${YELLOW}⚠${NC} Vitest 配置不存在"
fi

if [ -f "playwright.config.ts" ]; then
    echo -e "${GREEN}✓${NC} Playwright 配置存在"
else
    echo -e "${YELLOW}⚠${NC} Playwright 配置不存在"
fi

if [ -d "tests" ]; then
    echo -e "${GREEN}✓${NC} tests 目錄存在"
    
    if [ -d "tests/unit" ]; then
        echo -e "${GREEN}✓${NC} tests/unit 目錄存在"
    fi
    
    if [ -d "tests/e2e" ]; then
        echo -e "${GREEN}✓${NC} tests/e2e 目錄存在"
    fi
else
    echo -e "${YELLOW}⚠${NC} tests 目錄不存在"
fi

echo ""

# 7. 執行快速測試
echo "🏃 執行快速測試..."
echo ""

# 檢查 Lint
if npm run lint > /dev/null 2>&1; then
    echo -e "${GREEN}✓${NC} ESLint 檢查通過"
else
    echo -e "${YELLOW}⚠${NC} ESLint 檢查有警告（這不影響開發）"
fi

# 檢查單元測試（快速運行）
if [ -d "tests/unit" ]; then
    echo -e "${YELLOW}ℹ${NC} 跳過單元測試（使用 'npm test' 手動運行）"
fi

echo ""

# 8. 總結
echo "================================"
echo "✨ 驗證完成！"
echo ""

if [ "$REQUIREMENTS_MET" = true ] && [ "$PROJECT_STRUCTURE_OK" = true ] && [ "$PACKAGES_OK" = true ]; then
    echo -e "${GREEN}✓ 環境設置完成，可以開始開發！${NC}"
    echo ""
    echo "下一步："
    echo "  1. 啟動開發服務器: npm run dev"
    echo "  2. 執行單元測試: npm test"
    echo "  3. 執行 E2E 測試: npm run test:e2e"
    echo "  4. 查看 Nuxt DevTools: 打開瀏覽器並按 Shift + Alt + D"
    echo ""
    
    if [ "$API_AVAILABLE" = false ]; then
        echo -e "${YELLOW}提醒: 開發服務器尚未運行${NC}"
        echo "執行: npm run dev"
        echo ""
    fi
    
    exit 0
else
    echo -e "${YELLOW}⚠ 環境設置不完整，但可能仍可工作${NC}"
    echo ""
    echo "建議："
    echo "  - 執行 'npm install' 安裝所有依賴"
    echo "  - 檢查缺失的目錄和文件"
    echo "  - 參考 quickstart.md 文檔"
    echo ""
    exit 1
fi
