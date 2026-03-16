# Prexta

## Requirements

- Docker installed and running
- PHP, Composer, and Node.js installed

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
