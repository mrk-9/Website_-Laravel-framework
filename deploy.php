<?php

require 'recipes.php';

task('prod_config', function () {
    set('shared_dirs', array_merge(get('shared_dirs'), ['public/file/medias', 'public/img']));
});

task('deploy_preprod', [
    'deploy:prepare',
    'deploy:release',
    'deploy:upload_code',
    'deploy:shared',
    'deploy:down',
    'deploy:reset_db',
    'deploy:symlink',
    'cleanup'
])->desc('Deploy your project in preproduction');

task('deploy_prod', [
    'prod_config',
    'deploy:prepare',
    'deploy:release',
    'deploy:upload_code',
    'deploy:shared',
    'deploy:down',
    'deploy:migrate_db',
    'deploy:symlink',
    'cleanup'
])->desc('Deploy your project in production');

server('preprod', getenv('PREPROD_HOST'))
    ->user(getenv('PREPROD_USERNAME'))
    ->password(getenv('PREPROD_PASSWORD'))
    ->env('deploy_path', '~');

server('prepreprod', getenv('PREPREPROD_HOST'))
    ->user(getenv('PREPREPROD_USERNAME'))
    ->password(getenv('PREPREPROD_PASSWORD'))
    ->env('deploy_path', '~');

server('prod', getenv('PROD_HOST'))
    ->user(getenv('PROD_USERNAME'))
    ->password(getenv('PROD_PASSWORD'))
    ->env('deploy_path', '~');
