# Products API

## Configuration

1. The application uses [Laravel Sanctum](https://laravel.com/docs/8.x/sanctum) SPA authentication. The frontend and backend must use the same domain. Edit `/etc/hosts` as bellow.

```console
127.0.0.1   local.test
127.0.0.1   api.local.test
```

The backend (this application) is hosted at `http://api.local.test:8001` and the frontend at `http://local.test:8080`.
