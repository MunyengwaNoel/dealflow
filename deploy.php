<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github.com:MunyengwaNoel/dealflow.git');
set('branch', 'main');
set('keep_releases', 5);

add('shared_files', ['.env']);
add('shared_dirs', ['storage']);
add('writable_dirs', [
    'bootstrap/cache',
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
]);

// Hosts

host('dealflow.softurf.co.zw')
    ->set('hostname', '109.199.110.62')
    ->set('remote_user', 'softurf-dealflow')
    ->set('site_domain', 'dealflow.softurf.co.zw')
    ->set('deploy_path', '/home/{{remote_user}}/htdocs/{{site_domain}}');

// Hooks

desc('Install Node dependencies (Vite, Tailwind, etc.)');
task('npm:install', function () {
    // npm ci needs package-lock.json; fall back to npm install if lockfile is missing
    run('cd {{release_path}} && (npm ci --no-audit --no-fund || npm install --no-audit --no-fund)');
});

desc('Build frontend assets (public/build/manifest.json)');
task('npm:build', function () {
    run('cd {{release_path}} && npm run build');
});

desc('Publish Livewire JS/CSS to public/vendor/livewire (avoids /livewire/livewire.js 404 on hosts that do not route that path to Laravel)');
task('livewire:assets', function () {
    run('cd {{release_path}} && php artisan livewire:publish --assets --no-interaction');
});

after('deploy:vendors', 'npm:install');
after('deploy:vendors', 'livewire:assets');
after('npm:install', 'npm:build');

after('npm:build', function () {
    run('mkdir -p {{release_path}}/public/vendor/swagger-api/swagger-ui/dist');
    run('cp -r {{release_path}}/vendor/swagger-api/swagger-ui/dist/* {{release_path}}/public/vendor/swagger-api/swagger-ui/dist/');
});

after('deploy:failed', 'deploy:unlock');
