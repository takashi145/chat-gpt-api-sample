# laravel chat gpt

chatGPTのAPIを使ってみた

<img width="911" alt="chatgpt" src="https://user-images.githubusercontent.com/84027833/234751836-d127d8c4-2846-4f2e-9db7-d8b1ab9777ab.PNG">


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
