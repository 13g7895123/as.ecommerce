# Docker Deployment Guide

## 目录结构

```
.
├── backend/                 # CodeIgniter 4 应用程序
├── docker/                  # Docker 配置文件夹
│   ├── nginx/              # Nginx 配置
│   │   └── default.conf
│   ├── php/                # PHP Dockerfile
│   │   └── Dockerfile
│   ├── envs/               # 环境配置模板
│   │   ├── development.env # 开发环境配置
│   │   └── production.env  # 生产环境配置
│   ├── .env.example        # 环境配置示例
│   ├── .env                # 当前使用的环境配置 (自动生成，不要提交到Git)
│   └── docker-compose.yml  # Docker Compose 配置
└── scripts/                # 部署和管理脚本
    ├── deploy.sh           # 部署脚本
    ├── migrate.sh          # 数据库迁移脚本
    └── seed.sh             # 数据库填充脚本
```

## 快速开始

### 1. 启动开发环境

```bash
./scripts/deploy.sh
# 或
./scripts/deploy.sh development
```

### 2. 启动生产环境

```bash
./scripts/deploy.sh production
```

### 3. 运行数据库迁移

```bash
./scripts/migrate.sh development
```

### 4. 填充测试数据

```bash
./scripts/seed.sh development
```

## 部署脚本使用方法

### deploy.sh - 服务部署

```bash
./scripts/deploy.sh [environment] [action]
```

**注意：** 如果只提供环境名，默认会执行 `up` 动作（启动服务）

### 可用环境

- `development` - 开发环境（默认）
- `production` - 生产环境

### 可用操作

- `up` - 启动服务（默认）
- `down` - 停止并删除服务
- `restart` - 重启服务
- `logs` - 查看日志
- `ps` - 列出服务状态
- `build` - 构建或重建服务

### 使用示例

``scripts/deploy.sh

# 启动开发环境（指定环境名）
./scripts/deploy.sh development

# 启动生产环境
./scripts/deploy.sh production

# 重启开发环境
./scripts/deploy.sh restart
# 或
./scripts/deploy.sh development restart

# 查看生产环境日志
./scripts/deploy.sh production logs

# 停止开发环境
./scripts/deploy.sh down

# 停止生产环境
./scripts/deploy.sh production down

# 重新构建服务
./scripts/deploy.sh build
# 或
./scripts/deploy.sh production build
```

### migrate.sh - 数据库迁移

```bash
./scripts/migrate.sh [environment] [action]
```

**可用操作：**
- `migrate` - 运行所有迁移（默认）
- `rollback` - 回滚最后一次迁移
- `refresh` - 回滚所有迁移并重新运行
- `status` - 检查迁移状态

**示例：**
```bash
# 运行开发环境迁移
./scripts/migrate.sh

# 运行生产环境迁移
./scripts/migrate.sh production

# 回滚最后一次迁移
./scripts/migrate.sh develo

### 端口配置

所有对外端口统一配置在环境文件的开头：

```env
# =====================================
# External Ports (对外端口)
# =====================================
NGINX_PORT=8080
PHPMYADMIN_PORT=8082
MYSQL_PORT=3307
```pment rollback

# 检查迁移状态
./scripts/migrate.sh production status

# 刷新所有迁移
./scripts/migrate.sh development refresh
```

### seed.sh - 数据库填充

```bash
./scripts/seed.sh [environment] [seeder_name]
```

**示例：**
```bash
# 运行默认填充器
./scripts/seed.sh

# 运行生产环境默认填充器
./scripts/seed.sh production

# 运行指定填充器
./scripts/seed.sh development UserSeeder

# 运行多个填充器（按顺序执行）
./scripts/seed.sh development UserSeeder
./scripts/seed.sh development ProductSeeder
# 或
./deploy.sh production build
```

## 服务访问地址

### 开发环境

- Web应用: http://localhost:8080
- PhpMyAdmin: http://localhost:8082
- MySQL: localhost:3307

### 生产环境

- Web应用: http://localhost:80
- PhpMyAdmin: http://localhost:8082
- MySQL: localhost:3306

## 环境配置

环境配置文件位于 `docker/envs/` 目录：

- `development.env` - 开发环境配置
- `production.env` - 生产环境配置

首次运行时，脚本会自动从 `docker/envs/` 复制对应的配置文件到 `docker/.env`

### 修改配置

1. 编辑 `docker/envs/` 中的对应环境文件
2. 删除 `docker/.env` 文件
3. 重新运行部署脚本

或者直接编辑 `docker/.env` 文件（不推荐，因为这个文件不会被提交到Git）

## Docker Compose 特性

- ✅ 使用新版 Docker Compose（无 `version` 字段）
- ✅ 容器名称由 `COMPOSE_PROJECT_NAME` 自动生成
- ✅ 所有配置通过环境变量管理
- ✅ 支持多环境部署

## 注意事项

1. `docker/.env` 文件不会被提交到 Git（已添加到 .gitignore）
2. 生产环境请务必修改 `docker/envs/production.env` 中的密码
3. 首次运行会自动构建 PHP 镜像，可能需要几分钟
4. 数据库数据保存在 Docker volume 中，即使删除容器也不会丢失

## 故障排除

### 端口被占用

如果遇到端口冲突，编辑对应的环境配置文件修改端口号：

``scripts/deploy.sh development ps
```

### 查看日志

```bash
./scripts/deploy.sh development logs
```

### 重建服务

```bash
./scripts/deploy.sh development build
./scripts/deploy.sh development up
```

### 数据库操作

```bash
# 创建新的迁移文件
docker exec -it $(docker compose -f docker/docker-compose.yml ps -q php) php spark make:migration CreateUsersTable

# 创建新的填充器
docker exec -it $(docker compose -f docker/docker-compose.yml ps -q php) php spark make:seeder UserSeeder

# 运行迁移
./scripts/migrate.sh development

# 填充数据
./scripts/seed.sh development
```

### 查看日志

```bash
./deploy.sh development logs
```

### 重建服务

```bash
./deploy.sh development build
./deploy.sh development up
```

## 手动使用 Docker Compose

如果需要手动使用 Docker Compose：

```bash
cd docker
docker compose --env-file .env up -d
docker compose --env-file .env down
docker compose --env-file .env logs -f
```
