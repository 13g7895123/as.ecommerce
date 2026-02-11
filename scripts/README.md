# Scripts 使用说明

本目录包含用于管理 Docker 环境和数据库的脚本。

## 可用脚本

### 1. deploy.sh - 服务部署管理

用于启动、停止和管理 Docker Compose 服务。

```bash
./scripts/deploy.sh [environment] [action]
```

**常用命令：**
```bash
./scripts/deploy.sh                 # 启动开发环境
./scripts/deploy.sh production      # 启动生产环境
./scripts/deploy.sh down            # 停止开发环境
./scripts/deploy.sh logs            # 查看开发环境日志
./scripts/deploy.sh ps              # 查看服务状态
```

### 2. migrate.sh - 数据库迁移

用于运行 CodeIgniter 4 数据库迁移。

```bash
./scripts/migrate.sh [environment] [action]
```

**常用命令：**
```bash
./scripts/migrate.sh                    # 运行开发环境迁移
./scripts/migrate.sh production         # 运行生产环境迁移
./scripts/migrate.sh rollback           # 回滚最后一次迁移
./scripts/migrate.sh status             # 查看迁移状态
./scripts/migrate.sh refresh            # 刷新所有迁移
```

### 3. seed.sh - 数据库填充

用于运行 CodeIgniter 4 数据库填充器（Seeder）。

```bash
./scripts/seed.sh [environment] [seeder_name]
```

**常用命令：**
```bash
./scripts/seed.sh                       # 运行默认填充器
./scripts/seed.sh production            # 在生产环境运行默认填充器
./scripts/seed.sh UserSeeder            # 运行指定填充器
./scripts/seed.sh production UserSeeder # 在生产环境运行指定填充器
```

## 完整工作流程示例

### 开发环境设置

```bash
# 1. 启动服务
./scripts/deploy.sh

# 2. 运行数据库迁移
./scripts/migrate.sh

# 3. 填充测试数据
./scripts/seed.sh

# 4. 查看服务状态
./scripts/deploy.sh ps
```

### 生产环境部署

```bash
# 1. 启动生产环境
./scripts/deploy.sh production

# 2. 运行生产环境迁移
./scripts/migrate.sh production

# 3. 填充生产数据（谨慎使用）
./scripts/seed.sh production ProductionSeeder

# 4. 查看日志
./scripts/deploy.sh production logs
```

### 开发流程

```bash
# 创建新的迁移
docker exec -it $(docker compose -f ../docker/docker-compose.yml ps -q php) \
  php spark make:migration CreateProductsTable

# 运行迁移
./scripts/migrate.sh

# 创建填充器
docker exec -it $(docker compose -f ../docker/docker-compose.yml ps -q php) \
  php spark make:seeder ProductSeeder

# 运行填充器
./scripts/seed.sh ProductSeeder

# 如果需要重置数据库
./scripts/migrate.sh refresh
./scripts/seed.sh
```

## 注意事项

1. **所有脚本必须从项目根目录执行**
2. **生产环境操作要特别谨慎**，尤其是 `migrate refresh` 和 `seed` 操作
3. **确保服务已启动**后再运行 migrate 和 seed 脚本
4. **环境配置文件**位于 `docker/envs/` 目录

## 故障排除

### 容器未运行

如果看到 "PHP container is not running" 错误：

```bash
# 先启动服务
./scripts/deploy.sh development
```

### 权限问题

如果脚本无法执行：

```bash
chmod +x scripts/*.sh
```

### 路径问题

确保从项目根目录执行脚本：

```bash
# 正确
cd /path/to/project
./scripts/deploy.sh

# 错误
cd /path/to/project/scripts
./deploy.sh
```
