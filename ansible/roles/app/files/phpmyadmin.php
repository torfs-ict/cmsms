<?php

$i = 0;
$i++;
$cfg['Servers'][$i]['verbose'] = 'CMSMS';
$cfg['Servers'][$i]['host'] = 'localhost';
$cfg['Servers'][$i]['port'] = '';
$cfg['Servers'][$i]['socket'] = '';
$cfg['Servers'][$i]['connect_type'] = 'tcp';
$cfg['Servers'][$i]['auth_type'] = 'config';
$cfg['Servers'][$i]['user'] = 'cmsms';
$cfg['Servers'][$i]['password'] = 'cmsms';
$cfg['Servers'][$i]['only_db'] = array('cmsms');
$cfg['Servers'][$i]['AllowNoPassword'] = true;
$cfg['DefaultLang'] = 'en';
$cfg['ServerDefault'] = 1;
$cfg['UploadDir'] = '';
$cfg['SaveDir'] = '';