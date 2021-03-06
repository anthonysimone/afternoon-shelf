<?php

$acquia_site_name = 'paragon';
if (file_exists('/var/www/site-php')) {
  require("/var/www/site-php/{$acquia_site_name}/{$acquia_site_name}-settings.inc");
}

$settings['file_private_path'] = "/mnt/files/{$acquia_site_name}.{$_ENV['AH_SITE_ENVIRONMENT']}/files-private";
$config['system.file']['path']['temporary'] = "/mnt/tmp/{$acquia_site_name}.{$_ENV['AH_SITE_ENVIRONMENT']}";

//# https://docs.acquia.com/article/drupal-8-cache-backend
//$settings['cache']['default'] = 'cache.backend.memcache';
//
//# Force common chainedfast bins to use database.
//$settings['cache']['bins']['discovery'] = 'cache.backend.memcache';
//$settings['cache']['bins']['bootstrap'] = 'cache.backend.memcache';
//$settings['cache']['bins']['render'] = 'cache.backend.memcache';
//$settings['cache']['bins']['data'] = 'cache.backend.memcache';
//$settings['cache']['bins']['config'] = 'cache.backend.memcache';

// Enable CSS and JS preprocessing
$config['system.performance']['css']['preprocess'] = TRUE;
$config['system.performance']['js']['preprocess'] = TRUE;

/**
 * Environment Indicator Settings
 *
 * This should be configured per environment.
 *
 * Drupal VM | #005093
 *
 * For environment with canon DB:
 * <Environment> [Master DB] | #000000
 *
 * For environment with dispensable DB:
 * <Environment> | #930007
 *
 * Available environments include:
 *
 * Acquia dev
 * Acquia test
 * Acquia prod
 */
$config['environment_indicator.indicator']['bg_color'] = '#930007';
$config['environment_indicator.indicator']['fg_color'] = '#ffffff';
$config['environment_indicator.indicator']['name'] = 'Acquia ' . $_ENV['AH_SITE_ENVIRONMENT'];

// Set trusted host pattern for the acquia paragon site. We need to set this because we cannot add additional
// aliases to a free acquia account. This can be deleted for any new project created from paragon.
$settings['trusted_host_patterns'][] = 'paragonbhybefranz.devcloud.acquia-sites.com';
$settings['trusted_host_patterns'][] = 'paragono6pncnthxe.devcloud.acquia-sites.com';

/**
 * Set default config_readonly status to TRUE on all Acquia environments.
 */
//if (PHP_SAPI !== 'cli') {
//  $settings['config_readonly'] = FALSE;
//}

switch ($_ENV['AH_SITE_ENVIRONMENT']) {
  case 'dev':
    // Configure shield for dev environment.
    $config['shield.settings']['user'] = 'paragon';
    $config['shield.settings']['pass'] = '3ditParagon';
  
    /**
     * Master DB and Config Read-Only settings
     *
     * Set the environment indicator for the environment with the Master DB.  This should never be on more than one DB.
     * If non-developers are allowed to modify configuration on the master environment, add the following line:
     * 
     * $settings['config_readonly'] = FALSE;
     *
     * NOTE: If set to FALSE, caution should be used when merging in config changes.  
     * All Master DB config must be merged into the master branch before merging new config from VCS.
     *
     */
    $config['environment_indicator.indicator']['name'] = 'Acquia ' . $_ENV['AH_SITE_ENVIRONMENT'] . ' [Master DB]';
    $config['environment_indicator.indicator']['bg_color'] = '#000000';
    $config['google_analytics.settings']['account'] = '';
    break;
  case 'test':
    // Configure shield for test environment.
    $config['shield.settings']['user'] = 'paragon';
    $config['shield.settings']['pass'] = '3ditParagon';
    $config['google_analytics.settings']['account'] = '';
    break;
  case 'prod':

    // Set logging level on production.
    $config['system.logging']['error_level'] = 'hide';

    // Add Google Analytics settings.
    $config['google_analytics.settings']['account'] = '';
    break;
}

