#!/usr/bin/env php
<?php

use Composer\Semver\VersionParser;
require_once dirname(__DIR__) . '/cms/vendor/autoload.php';

$code = 'PD9waHANCg0KLyoqDQogKiBAcHJvcGVydHkgXFNtYXJ0eV9DTVMgJHNtYXJ0eQ0KICogQHByb3BlcnR5IFxDbXNBcHAgJGNtcw0KICogQHByb3BlcnR5IFxjbXNfY29uZmlnICRjb25maWcNCiAqLw0KY2xhc3MgJU1PRFVMRSUgZXh0ZW5kcyBDTVNNb2R1bGUgew0KICAgIC8qKg0KICAgICAqIEBpbmhlcml0ZG9jDQogICAgICovDQogICAgcHVibGljIGZ1bmN0aW9uIEdldFZlcnNpb24oKSB7DQogICAgICAgIHJldHVybiAnJVZFUlNJT04lJzsNCiAgICB9DQoNCiAgICAvKioNCiAgICAgKiBAaW5oZXJpdGRvYw0KICAgICAqLw0KICAgIHB1YmxpYyBmdW5jdGlvbiBHZXRBdXRob3IoKSB7DQogICAgICAgIHJldHVybiAnJUFVVEhPUiUnOw0KICAgIH0NCg0KICAgIC8qKg0KICAgICAqIEBpbmhlcml0ZG9jDQogICAgICovDQogICAgcHVibGljIGZ1bmN0aW9uIEdldEF1dGhvckVtYWlsKCkgew0KICAgICAgICByZXR1cm4gJyVBVVRIT1JfTUFJTCUnOw0KICAgIH0NCg0KICAgIC8qKg0KICAgICAqIEBpbmhlcml0ZG9jDQogICAgICovDQogICAgZnVuY3Rpb24gR2V0RGVwZW5kZW5jaWVzKCkgew0KICAgICAgICByZXR1cm4gW107DQogICAgfQ0KDQogICAgLyoqDQogICAgICogQGluaGVyaXRkb2MNCiAgICAgKi8NCiAgICBwdWJsaWMgZnVuY3Rpb24gU3VwcHJlc3NBZG1pbk91dHB1dCgmJHJlcXVlc3QpIHsNCiAgICAgICAgaWYgKGFycmF5X2tleV9leGlzdHMoJ3N1cHByZXNzJywgJHJlcXVlc3QpIHx8IGFycmF5X2tleV9leGlzdHMoc3ByaW50ZignJXNzdXBwcmVzcycsICR0aGlzLT5BY3Rpb25JZCgpKSwgJHJlcXVlc3QpKSByZXR1cm4gdHJ1ZTsNCiAgICB9DQoNCiAgICAvKioNCiAgICAgKiBAaW5oZXJpdGRvYw0KICAgICAqLw0KICAgIHB1YmxpYyBmdW5jdGlvbiBJbnN0YWxsKCkgew0KICAgICAgICByZXR1cm4gZmFsc2U7DQogICAgfQ0KDQogICAgLyoqDQogICAgICogQGluaGVyaXRkb2MNCiAgICAgKi8NCiAgICBwdWJsaWMgZnVuY3Rpb24gVW5pbnN0YWxsKCkgew0KICAgICAgICByZXR1cm4gZmFsc2U7DQogICAgfQ0KDQogICAgLyoqDQogICAgICogUmV0dXJucyB0aGUgbW9kdWxlIGFjdGlvbiBpZC4NCiAgICAgKg0KICAgICAqIEByZXR1cm4gc3RyaW5nDQogICAgICovDQogICAgcHVibGljIGZ1bmN0aW9uIEFjdGlvbklkKCkgew0KICAgICAgICBpZiAoaXNzZXQoJF9SRVFVRVNUWydtYWN0J10pKSB7DQogICAgICAgICAgICAkdG1wID0gZXhwbG9kZSgnLCcsIGNtc19odG1sZW50aXRpZXMoJF9SRVFVRVNUWydtYWN0J10pLCA0KTsNCiAgICAgICAgICAgICRpZCA9IGlzc2V0KCR0bXBbMV0pID8gJHRtcFsxXSA6ICcnOw0KICAgICAgICAgICAgaWYgKCFlbXB0eSgkaWQpKSByZXR1cm4gJGlkOw0KICAgICAgICB9DQogICAgICAgICRpZCA9ICR0aGlzLT5zbWFydHktPmdldFRlbXBsYXRlVmFycygnYWN0aW9uaWQnKTsNCiAgICAgICAgaWYgKGVtcHR5KCRpZCkpIHsNCiAgICAgICAgICAgIGlmICgkdGhpcy0+Y21zLT50ZXN0X3N0YXRlKFxDbXNBcHA6OlNUQVRFX0FETUlOX1BBR0UpKSAkaWQgPSAnbTFfJzsNCiAgICAgICAgICAgIGVsc2VpZiAoJHRoaXMtPmNtcy0+aXNfZnJvbnRlbmRfcmVxdWVzdCgpKSAkaWQgPSAnY250bnQwMSc7DQogICAgICAgIH0NCiAgICAgICAgcmV0dXJuICRpZDsNCiAgICB9DQp9';

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