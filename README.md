## Запуск проекта

### необходимые зависимости для устновки
- Docker
- Composer

### шаги запуска
1. сделать копию файла ```.env.example``` и переименовать в ```.env```

2. Установить зависимости, поднять контейнер, запустить миграции и сиды:
```bash
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
```

3. Документация API:

https://www.postman.com/lunar-module-operator-71390095/workspace/public-share/collection/27651043-3dc4f0ff-cfcd-41a3-901c-88f5a1974ef4?action=share&creator=27651043
