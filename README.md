# Smart Bears API

Laravel backend for the [Smart Bears Expo application](https://github.com/vcastroglez/smartbears). It provides a simple workflow for uploading drawing-task images and retrieving them from the mobile client.

## Features

- Validated image uploads
- Task-image listing endpoint
- Individual image retrieval with cache headers
- Numeric ordering of uploaded task images
- Laravel Sanctum-ready user endpoint
- Pest-based test setup

## API endpoints

| Method | Endpoint | Purpose |
| --- | --- | --- |
| `GET` | `/api/tasks/images` | List available task images |
| `GET` | `/api/tasks/images/{filename}` | Retrieve an image |
| `GET` | `/api/user` | Return the authenticated user |

The authenticated user endpoint uses the `auth:sanctum` middleware.

## Web endpoints

| Method | Endpoint | Purpose |
| --- | --- | --- |
| `GET` | `/task/upload` | Display the upload form |
| `POST` | `/task/upload` | Validate and store uploaded images |

## Development

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

The project targets PHP 8.2+ and Laravel 11.

## Status

Personal project and companion backend for Smart Bears.
