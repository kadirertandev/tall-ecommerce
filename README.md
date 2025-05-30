### Install dependencies

```sh
composer install
```

### Copy environment file

```sh
cp .env.example .env
```

**Change the database name using .env file**

**For a quick test, queue connection is set to database and default mail sender is set to log.**

### Generate application key

```sh
php artisan key:generate
```

### Seed the database

```sh
php artisan migrate:fresh --seed
```

### Link storage to public for file access

```sh
php artisan storage:link
```

### Start Laravel queue worker

```sh
php artisan queue:work
```

### Serve the application

```sh
php artisan serve
```

### Install frontend dependencies

```sh
npm install
```

### Compile and Hot-Reload for Development

```sh
npm run dev
```
