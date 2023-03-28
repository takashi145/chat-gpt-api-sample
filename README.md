# laravel chat gpt

```
git clone git@github.com:takashi145/laravel-mytemplate.git
```

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

```
cp .env.example .env
```

```
DB_HOST=mysql 
OPENAI_API_KEY=OpenAIのAPIキー
```

```
sail up -d
sail php artisan key:generate
sail artisan migrate --seed
```

```
npm install
npm run dev
```