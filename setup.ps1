# ACME Donations - Setup Script
Write-Host "Setting up ACME Donations Platform..." -ForegroundColor Green

# Set paths
$HERD_PHP = "C:\Users\catal\.config\herd\bin\php.bat"
$HERD_COMPOSER = "C:\Users\catal\.config\herd\bin\composer.bat"

# Install PHP dependencies
Write-Host "`nInstalling PHP dependencies..." -ForegroundColor Yellow
& $HERD_COMPOSER install

# Copy .env file
Write-Host "`nSetting up environment..." -ForegroundColor Yellow
if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
}

# Generate app key
Write-Host "`nGenerating application key..." -ForegroundColor Yellow
& $HERD_PHP artisan key:generate

# Create SQLite database
Write-Host "`nCreating SQLite database..." -ForegroundColor Yellow
New-Item -Path "database\database.sqlite" -ItemType File -Force

# Install Breeze
Write-Host "`nInstalling Laravel Breeze..." -ForegroundColor Yellow
& $HERD_COMPOSER require laravel/breeze --dev
& $HERD_PHP artisan breeze:install vue --dark

# Install Pest
Write-Host "`nInstalling Pest..." -ForegroundColor Yellow
& $HERD_COMPOSER require pestphp/pest --dev --with-all-dependencies
& $HERD_COMPOSER require pestphp/pest-plugin-laravel --dev
& $HERD_PHP artisan pest:install

# Install PHPStan/Larastan
Write-Host "`nInstalling PHPStan..." -ForegroundColor Yellow
& $HERD_COMPOSER require phpstan/phpstan larastan/larastan --dev

# Install NPM dependencies
Write-Host "`nInstalling NPM dependencies..." -ForegroundColor Yellow
npm install

# Run migrations
Write-Host "`nRunning migrations..." -ForegroundColor Yellow
& $HERD_PHP artisan migrate

Write-Host ""
Write-Host "Setup complete!" -ForegroundColor Green
Write-Host ""
Write-Host "To start development:"
Write-Host "  1. Run: npm run dev" -ForegroundColor Cyan
Write-Host "  2. Run: php artisan serve" -ForegroundColor Cyan
