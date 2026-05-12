# BizTrack

BizTrack is a SaaS consultancy management platform for Zimbabwean SME consultancies.

## Stack

- Laravel 11
- Filament 3 (admin panel)
- Laravel Sanctum (token auth for API)
- Swagger UI (OpenAPI spec)
- dompdf (PDF generation)

## Setup (local)

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

## Admin panel

- **URL**: `/admin`

## REST API

- **Base URL**: `/api/v1`
- **Auth**: `Authorization: Bearer {token}` (Sanctum personal access token)

## Swagger / OpenAPI docs

- **Swagger UI**: `/api/documentation`
- **OpenAPI spec**: `public/openapi/v1.yaml`

## Demo account (planned seed)

```
demo@biztrack.app / password
```

# dealflow
