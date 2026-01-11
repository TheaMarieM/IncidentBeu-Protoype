# Quick Command Reference

## Initial Setup
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

## Database Setup
```bash
# Edit .env first with your database credentials
php artisan migrate
php artisan db:seed
```

## Development
```bash
# Build assets
npm run build

# Or watch for changes (recommended during development)
npm run dev

# Start server
php artisan serve
```

## Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Notifications
```bash
# Process pending notifications manually
php artisan notifications:process

# Run scheduler (for automatic processing)
php artisan schedule:work
```

## Database Management
```bash
# Fresh migration (WARNING: deletes all data)
php artisan migrate:fresh --seed

# Rollback last migration
php artisan migrate:rollback

# Check migration status
php artisan migrate:status
```

## Useful Laravel Commands
```bash
# Create new controller
php artisan make:controller ControllerName

# Create new model with migration
php artisan make:model ModelName -m

# Create new migration
php artisan make:migration create_table_name

# Create new seeder
php artisan make:seeder SeederName

# Run specific seeder
php artisan db:seed --class=SeederName
```

## Troubleshooting
```bash
# If routes not working
php artisan route:clear
php artisan route:list

# If views not updating
php artisan view:clear

# If config not loading
php artisan config:clear

# Check for errors
php artisan about
```

## Production Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Create storage link for file uploads
php artisan storage:link
```

## Git (Optional)
```bash
# Initialize git (if not already done)
git init
git add .
git commit -m "Initial BEU Incident System implementation"
```

## Access URLs
```
Development Server: http://localhost:8000
Dashboard: http://localhost:8000/dashboard
Incidents: http://localhost:8000/incidents
Students: http://localhost:8000/students
Parents: http://localhost:8000/parents
```

## Default Login
```
Email: discipline@spup.edu.ph
Password: password
```
