# tournament-manager

для запуска веб-приложения необходимо:
1) docker-compose build
2) docker-compose up -d
3) docker-compose exec php-fpm sh и пишем composer install
4) php bin/console doctrine:migrations:migrate
5) yarn install (если нет yarn, то npm install -g yarn, а потом ещё раз yarn install)
6) yarn encore dev
7) копируем файл .env (пишем .env.local) и вставляем туда данные БД (нужные данные находятся в docker-compose.yml)
