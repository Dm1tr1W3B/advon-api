# advon new backend

## Installation

```
git clone URL .
git pull
```
after run composer
```
composer update
```
after that configure `.env`

generate secret key
```
php artisan jwt:secret
```

open api

after that configure `..editorconfig`
regenerate docs
```
php artisan l5-swagger:generate
```
see docs base_url/api/documentation

