[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/curder/laravel-tdd-driven-demo/run-tests?label=tests)](https://github.com/curder/laravel-tdd-driven-demo/actions?query=workflow%3Arun-tests%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/curder/laravel-tdd-driven-demo/Check%20&%20fix%20styling?label=code%20style)](https://github.com/curder/laravel-tdd-driven-demo/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)

## 介绍

Laravel TDD 单元测试。

## 安装

```bash
git clone https://github.com/curder/laravel-tdd-driven-demo && cd laravel-tdd-driven-demo

cp .env.example .env

touch database/database.sqlite
composer install
php artisan key:generate
```

## 配置

打开 `.env` 文件，修改里面的数据库配置信息。

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

修改为

```dotenv
DB_CONNECTION=sqlite
```

修改完之后使用数据库迁移文件安装所需的数据表。

```bash
php artisan migrate
```

具体视频内容查看[这里](https://www.youtube.com/watch?v=0Rjsuw1ScXg&list=PLpzy7FIRqpGAbkfdxo1MwOS9xjG3O3z1y)
