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

> Then set `DB_HOST=127.0.0.1` in your `.env` file.

## Run

```bash
./vendor/bin/sail up -d mysql
composer run dev
```

---

## Deploy with Docker (Production & Client Deliverable)

The application includes a fully automated, production-ready Docker deployment using Apache, PHP 8.4, and an integrated Node build phase. It dynamically links to a MySQL database and automatically runs required caching and migrations on boot.

### Run the App

Instead of installing PHP, Composer, or NPM locally, simply build for the first time by adding `--build` flag and start the containers using Docker Compose:

```bash
docker compose -f docker-compose.prod.yml up -d #--build
```

The application will be available at **http://localhost:8000** once the database fully initializes (usually takes 15-30 seconds on first boot).

To stop the containers safely:
```bash
docker compose -f docker-compose.prod.yml down
```

### Exposing with Ngrok (Optional)

If you need to instantly expose the local application over the internet securely (like demonstrating it live), we have provided a separate `docker-compose.ngrok.yml` overlay.

Ensure you have your token in your `.env` file (`NGROK_AUTHTOKEN=your_token_here`).

Then simply attach the Ngrok composer to your running production environment:
```bash
docker compose -f docker-compose.prod.yml -f docker-compose.ngrok.yml up -d
```

Visit the Ngrok dashboard at **http://localhost:4040** to retrieve your public forwarding URL.
