<p align="center">
  <img src="https://github.com/kadirertandev/tall-ecommerce/blob/a72ae69ee23f2074911ca29c0b59fbc9c5c46ffe/showcase/logo.png" />
</p>

<details>
  <summary>🖼️ Click to view home, single product, category and brand pages screenshots</summary>

  <table>
    <tr>
      <td valign="top">
        <p align="center"><strong>Home</strong></p>
        <img src="https://github.com/kadirertandev/tall-ecommerce/blob/6f3ce261c0251e0810807399db1cce8fad381fa5/showcase/landing/home.png" width="100%" />
      </td>
      <td valign="top">
        <p align="center"><strong>Single Product</strong></p>
        <img src="https://github.com/kadirertandev/tall-ecommerce/blob/6f3ce261c0251e0810807399db1cce8fad381fa5/showcase/landing/product.png" width="100%" />
      </td>
    </tr>
    <tr>
      <td valign="top">
        <p align="center"><strong>Category</strong></p>
        <img src="https://github.com/kadirertandev/tall-ecommerce/blob/6f3ce261c0251e0810807399db1cce8fad381fa5/showcase/landing/category.png" width="100%" />
      </td>
      <td valign="top">
        <p align="center"><strong>Brand</strong></p>
        <img src="https://github.com/kadirertandev/tall-ecommerce/blob/6f3ce261c0251e0810807399db1cce8fad381fa5/showcase/landing/brand.png" width="100%" />
      </td>
    </tr>
  </table>

</details>

### Clone project and navigate into project folder

```sh
git clone https://github.com/kadirertandev/tall-ecommerce.git
cd tall-ecommerce
```

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
