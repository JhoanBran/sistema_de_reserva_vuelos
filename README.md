[![Deploy to Render](https://render.com/images/deploy-to-render-button.svg)](https://render.com/deploy?repo=https://github.com/JhoanBran/sistema_de_reserva_vuelos)

# Sistema de Reserva de Vuelos

Flight Reservation System - A web application for managing and reserving flights.

## Quick Deploy

Click the button above to deploy to Render immediately!

## Manual Deploy to Render

1. Go to [render.com](https://render.com)
2. Sign in with GitHub
3. Create a new "Web Service"
4. Connect your GitHub repository
5. Configure:
   - **Build Command:** `composer install`
   - **Start Command:** `vendor/bin/heroku-php-apache2 public/`
   - **Plan:** Free

## Local Setup

```bash
composer install
php -S localhost:8000 -t public/
```

## Project Structure

- `public/` - Web server root
- `src/` - Application code (controllers, models, config)
- `sql/` - Database schema
- `composer.json` - PHP dependencies
