<?php

$drush_major_version = 8;
$project_drush_path = '/home/forge/shelf-dev.organyzr.com/vendor/bin/drush';

// Dev env
$aliases['dev'] = array(
  'root' => '/home/forge/shelf-dev.organyzr.com/docroot',
  'uri' => 'shelf-dev.organyzr.com',
  'remote-host' => '67.205.190.2',
  'remote-user' => 'forge',
  'path-aliases' => array(
    '%drush-script' => $project_drush_path,
  )
);
