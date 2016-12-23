#!/usr/bin/env php
<?php

use Composer\Semver\VersionParser;
require_once dirname(__DIR__) . '/cms/vendor/autoload.php';

$code = 'PD9waHANCg0KLyoqDQogKiBAcHJvcGVydHkgXFNtYXJ0eV9DTVMgJHNtYXJ0eQ0KICogQHByb3BlcnR5IFxDbXNBcHAgJGNtcw0KICogQHByb3BlcnR5IFxjbXNfY29uZmlnICRjb25maWcNCiAqLw0KY2xhc3MgJU1PRFVMRSUgZXh0ZW5kcyBDTVNNb2R1bGUgew0KICAgIC8qKg0KICAgICAqIEBpbmhlcml0ZG9jDQogICAgICovDQogICAgcHVibGljIGZ1bmN0aW9uIEdldEF1dGhvcigpIHsNCiAgICAgICAgcmV0dXJuICclQVVUSE9SJSc7DQogICAgfQ0KDQogICAgLyoqDQogICAgICogQGluaGVyaXRkb2MNCiAgICAgKi8NCiAgICBwdWJsaWMgZnVuY3Rpb24gR2V0QXV0aG9yRW1haWwoKSB7DQogICAgICAgIHJldHVybiAnJUFVVEhPUl9NQUlMJSc7DQogICAgfQ0KDQogICAgLyoqDQogICAgICogQGluaGVyaXRkb2MNCiAgICAgKi8NCiAgICBmdW5jdGlvbiBHZXREZXBlbmRlbmNpZXMoKSB7DQogICAgICAgIHJldHVybiBbXTsNCiAgICB9DQoNCiAgICAvKioNCiAgICAgKiBAaW5oZXJpdGRvYw0KICAgICAqLw0KICAgIHB1YmxpYyBmdW5jdGlvbiBTdXBwcmVzc0FkbWluT3V0cHV0KCYkcmVxdWVzdCkgew0KICAgICAgICBpZiAoYXJyYXlfa2V5X2V4aXN0cygnc3VwcHJlc3MnLCAkcmVxdWVzdCkgfHwgYXJyYXlfa2V5X2V4aXN0cyhzcHJpbnRmKCclc3N1cHByZXNzJywgJHRoaXMtPkFjdGlvbklkKCkpLCAkcmVxdWVzdCkpIHJldHVybiB0cnVlOw0KICAgIH0NCg0KICAgIC8qKg0KICAgICAqIEBpbmhlcml0ZG9jDQogICAgICovDQogICAgcHVibGljIGZ1bmN0aW9uIEluc3RhbGwoKSB7DQogICAgICAgIHJldHVybiBmYWxzZTsNCiAgICB9DQoNCiAgICAvKioNCiAgICAgKiBAaW5oZXJpdGRvYw0KICAgICAqLw0KICAgIHB1YmxpYyBmdW5jdGlvbiBVbmluc3RhbGwoKSB7DQogICAgICAgIHJldHVybiBmYWxzZTsNCiAgICB9DQoNCiAgICAvKioNCiAgICAgKiBSZXR1cm5zIHRoZSBtb2R1bGUgYWN0aW9uIGlkLg0KICAgICAqDQogICAgICogQHJldHVybiBzdHJpbmcNCiAgICAgKi8NCiAgICBwdWJsaWMgZnVuY3Rpb24gQWN0aW9uSWQoKSB7DQogICAgICAgIGlmIChpc3NldCgkX1JFUVVFU1RbJ21hY3QnXSkpIHsNCiAgICAgICAgICAgICR0bXAgPSBleHBsb2RlKCcsJywgY21zX2h0bWxlbnRpdGllcygkX1JFUVVFU1RbJ21hY3QnXSksIDQpOw0KICAgICAgICAgICAgJGlkID0gaXNzZXQoJHRtcFsxXSkgPyAkdG1wWzFdIDogJyc7DQogICAgICAgICAgICBpZiAoIWVtcHR5KCRpZCkpIHJldHVybiAkaWQ7DQogICAgICAgIH0NCiAgICAgICAgJGlkID0gJHRoaXMtPnNtYXJ0eS0+Z2V0VGVtcGxhdGVWYXJzKCdhY3Rpb25pZCcpOw0KICAgICAgICBpZiAoZW1wdHkoJGlkKSkgew0KICAgICAgICAgICAgaWYgKCR0aGlzLT5jbXMtPnRlc3Rfc3RhdGUoXENtc0FwcDo6U1RBVEVfQURNSU5fUEFHRSkpICRpZCA9ICdtMV8nOw0KICAgICAgICAgICAgZWxzZWlmICgkdGhpcy0+Y21zLT5pc19mcm9udGVuZF9yZXF1ZXN0KCkpICRpZCA9ICdjbnRudDAxJzsNCiAgICAgICAgfQ0KICAgICAgICByZXR1cm4gJGlkOw0KICAgIH0NCn0=';

$cyan=`tput setaf 6; tput bold`;
$yellow=`tput setaf 3; tput bold`;
$green=`tput setaf 2; tput bold`;
$red=`tput setaf 1 ; tput bold`;
$title=`tput bold ; tput smul`;
$reset=`tput sgr0`;

echo "\n{$title}CMSMS Module generation{$reset}\n\n";

// Check if composer.json exists
echo "Checking for existence of {$yellow}composer.json{$reset}... ";
$result = file_exists('composer.json');
if (!$result) {
    echo "{$red}FAILED!{$reset}\n";
    exit(1);
} else {
    echo "{$green}OK{$green}{$reset}\n";
}

// Parse composer.json
$json = json_decode(file_get_contents('composer.json'), true);
if (!is_array($json)) $json = [];

// Retrieve the author name from composer.json
echo "Detecting {$yellow}author name{$reset}... ";
$name = $json['authors'][0]['name'] ?? null;
if (is_null($name)) {
    echo "{$red}FAILED!{$reset}\n";
    exit(2);
} else {
    echo "{$green}{$name}{$green}{$reset}\n";
}

// Retrieve the author e-mail address from composer.json
echo "Detecting {$yellow}author e-mail address{$reset}... ";
$email = $json['authors'][0]['email'] ?? null;
if (is_null($email)) {
    echo "{$red}FAILED!{$reset}\n";
    exit(2);
} else {
    echo "{$green}{$email}{$green}{$reset}\n";
}

// Retrieve the CMSMS module name from composer.json
echo "Detecting {$yellow}module name{$reset}... ";
$module = $json['extra']['cmsms']['name'] ?? null;
if (is_null($module)) {
    echo "{$red}FAILED!{$reset}\n";
    exit(2);
} else {
    echo "{$green}{$module}{$green}{$reset}\n";
}

// Retrieve the version set in composer.json
echo "Detecting {$yellow}module version{$reset}... ";
$version = null;
if (array_key_exists('version', $json)) {
    $parser = new VersionParser();
    try {
        $parser->normalize($json['version']);
        $version = $json['version'];
    } catch (Exception $e) {
    }
    if (is_null($version)) {
        echo "{$red}FAILED!{$reset}\n";
        exit(3);
    } else {
        echo "{$green}{$version}{$green}{$reset}\n";
    }
}

// Generate module code
echo "\n{$cyan}Generating module code... {$reset}";
$search = ['%MODULE%', '%VERSION%', '%AUTHOR%', '%AUTHOR_MAIL%'];
$replace = [$module, $version, $name, $email];
file_put_contents(sprintf('%s.module.php', $module), str_replace($search, $replace, base64_decode($code)));
echo "{$green}OK{$green}{$reset}\n";

// Initialise Git repository
echo "{$cyan}Initializing Git repository... {$reset}";
`git init`;
echo "{$green}DONE{$green}{$reset}\n";

// Adding temporary repository to CMSMS composer.json
echo "{$cyan}Setting temporary repository in composer.json of the development environment... {$reset}";
$composer = dirname(__DIR__) . '/composer.json';
$json = @json_decode(file_get_contents($composer), true);
if (!is_array($json)) {
    echo "{$red}FAILED!{$reset}\n";
    exit(4);
}
if (!array_key_exists('repositories', $json) || !is_array($json['repositories'])) $json['repositories'] = [];
$json['repositories'][] = ['type' => 'path', 'url' => sprintf('modules/%s', basename(getcwd()))];
file_put_contents($composer, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "{$green}DONE{$green}{$reset}\n";