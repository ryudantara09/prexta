# Prexta

## Requirements

**PHP 8.4+**
```bash
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.4)"
```

**Composer**
```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'c8b085408188070d5f52bcfe4ecfbee5f727afa458b2573b8eaaf77b3419b0bf2768dc67c86944da1544f06fa544fd47') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
php composer-setup.php --install-dir=bin
php -r "unlink('composer-setup.php');"
```
**LARAVEL**
```bash
composer global require laravel/installer
```
**Node.js 20+**
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

**Docker**
```bash
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER
```
> Log out and back in after installing Docker for the group change to take effect.

## Install (first time)

```bash
composer run setup
```

## Run (Local Development)

Create a local environment override file:
```bash
cp .env.local.example .env.local
```

> Edit `.env.local` and set your local MySQL credentials and copy `APP_KEY` from `.env`.

Start the database and dev server:
```bash
./vendor/bin/sail up -d mysql
APP_ENV=local php artisan serve
```

In a separate terminal, start Vite:
```bash
npm run dev
```

The `APP_ENV=local` prefix tells Laravel to load `.env.local` instead of `.env`, keeping the Docker configuration untouched.

---

## Exposing with Ngrok (Local Dev)

To expose your locally running app over the internet using the ngrok Docker image:

```bash
docker run --net=host -it -e NGROK_AUTHTOKEN=1kcdAWlZ1NskoO5k5FORoy1AgN6_5rnaAnKSDKnh4ueRF9yim ngrok/ngrok:latest http --url=nongenerically-brideless-tempie.ngrok-free.dev 8000
```

> Replace `8000` with whichever port your local server is running on.

Visit the Ngrok dashboard at **http://localhost:4040** to retrieve your public forwarding URL.

---

## Deploy with Docker (Production & Client Deliverable)

The application includes a fully automated, production-ready Docker deployment using Apache, PHP 8.4, and an integrated Node build phase. It dynamically links to a MySQL database and automatically runs required caching and migrations on boot.

### Run the App

Build and start the containers (use `--build` on first run or after code changes):

```bash
docker compose -f docker-compose.prod.yml up -d --build
```

On subsequent starts (no code changes), you can omit `--build`:
```bash
docker compose -f docker-compose.prod.yml up -d
```

The application will be available at **http://localhost:8000** once the database fully initializes (usually takes 15-30 seconds on first boot).

To stop the containers safely:
```bash
docker compose -f docker-compose.prod.yml down
```

### Exposing with Ngrok (Optional)

If you need to instantly expose the production Docker stack over the internet, use the ngrok compose overlay. Ensure `NGROK_AUTHTOKEN` is set in your `.env` file, then start everything together:

```bash
docker compose -f docker-compose.prod.yml -f docker-compose.ngrok.yml up -d --build
```

Visit the Ngrok dashboard at **http://localhost:4040** to retrieve your public forwarding URL.
