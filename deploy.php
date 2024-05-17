<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'https://github.com/TransitTracker/regio.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('5966edc.online-server.cloud')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/regio');

// Hooks

after('deploy:failed', 'deploy:unlock');
