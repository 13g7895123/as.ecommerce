# E-Commerce Backend Project

基于 CodeIgniter 4 和 Docker 的电商后端系统。

## 项目结构

```
.
├── backend/                # CodeIgniter 4 应用程序
│   ├── app/               # 应用代码
│   ├── public/            # Web 根目录
│   └── ...
├── docker/                # Docker 配置
│   ├── envs/             # 环境配置文件
│   ├── nginx/            # Nginx 配置
│   ├── php/              # PHP Dockerfile
│   └── docker-compose.yml
└── scripts/              # 管理脚本
    ├── deploy.sh         # 部署脚本
    ├── migrate.sh        # 数据库迁移
    └── seed.sh           # 数据填充
```

## 快速开始

### 1. 启动开发环境

```bash
# 启动所有服务
./scripts/deploy.sh

# 运行数据库迁移
./scripts/migrate.sh

# 填充测试数据
./scripts/seed.sh
```

### 2. 访问服务

- **Web 应用**: http://localhost:8080
- **PhpMyAdmin**: http://localhost:8082
- **MySQL**: localhost:3307

默认数据库连接：
- 用户名: `root`
- 密码: `root`
- 数据库: `ecommerce`

## 常用命令

### 服务管理

```bash
./scripts/deploy.sh                 # 启动开发环境
./scripts/deploy.sh production      # 启动生产环境
./scripts/deploy.sh down            # 停止服务
./scripts/deploy.sh logs            # 查看日志
./scripts/deploy.sh ps              # 查看服务状态
./scripts/deploy.sh restart         # 重启服务
```

### 数据库管理

```bash
# 迁移
./scripts/migrate.sh                # 运行迁移
./scripts/migrate.sh rollback       # 回滚迁移
./scripts/migrate.sh status         # 查看迁移状态

# 填充数据
./scripts/seed.sh                   # 运行默认填充器
./scripts/seed.sh UserSeeder        # 运行指定填充器
```

## 环境配置

环境配置文件位于 `docker/envs/`：

- `development.env` - 开发环境
- `production.env` - 生产环境

### 修改端口

编辑对应环境的配置文件：

```env
# =====================================
# External Ports (对外端口)
# =====================================
NGINX_PORT=8080
PHPMYADMIN_PORT=8082
MYSQL_PORT=3307
```

## 开发工作流

### 创建新功能

```bash
# 1. 确保服务运行
./scripts/deploy.sh

# 2. 创建迁移文件
docker exec -it $(docker compose -f docker/docker-compose.yml ps -q php) \
  php spark make:migration CreateUsersTable

# 3. 编辑迁移文件
# backend/app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateUsersTable.php

# 4. 运行迁移
./scripts/migrate.sh

# 5. 创建填充器（可选）
docker exec -it $(docker compose -f docker/docker-compose.yml ps -q php) \
  php spark make:seeder UserSeeder

# 6. 运行填充器
./scripts/seed.sh UserSeeder
```

### 重置数据库

```bash
# 回滚所有迁移并重新运行
./scripts/migrate.sh refresh

# 重新填充数据
./scripts/seed.sh
```

## 生产环境部署

```bash
# 1. 编辑生产环境配置
nano docker/envs/production.env

# 2. 启动生产环境
./scripts/deploy.sh production

# 3. 运行迁移
./scripts/migrate.sh production

# 4. （可选）填充初始数据
./scripts/seed.sh production InitialSeeder
```

## 技术栈

- **PHP**: 8.2
- **Framework**: CodeIgniter 4.7
- **Web Server**: Nginx (Alpine)
- **Database**: MySQL 8.0
- **Container**: Docker & Docker Compose

## 文档

详细文档请参阅：

- [Docker 配置文档](docker/README.md)
- [脚本使用说明](scripts/README.md)
- [CodeIgniter 4 文档](https://codeigniter.com/user_guide/)

## 故障排除

### 端口冲突

如果遇到端口被占用的错误，编辑环境配置文件修改端口：

```bash
nano docker/envs/development.env
```

### 容器未启动

```bash
# 查看服务状态
./scripts/deploy.sh ps

# 查看日志
./scripts/deploy.sh logs

# 重启服务
./scripts/deploy.sh restart
```

### 权限问题

```bash
# 给脚本添加执行权限
chmod +x scripts/*.sh
```

## License

MIT License
