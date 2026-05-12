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

after('deploy:vendors', function () {
    run('mkdir -p {{release_path}}/public/vendor/swagger-api/swagger-ui/dist');
    run('cp -r {{release_path}}/vendor/swagger-api/swagger-ui/dist/* {{release_path}}/public/vendor/swagger-api/swagger-ui/dist/');
});

after('deploy:failed', 'deploy:unlock');
