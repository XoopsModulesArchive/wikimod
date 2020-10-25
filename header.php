<?php

require_once '../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once 'common/functions.php';

if (file_exists('language/' . $xoopsConfig['language'] . '/modinfo.php')) {
    require_once 'language/' . $xoopsConfig['language'] . '/modinfo.php';
} else {
    require_once 'language/english/modinfo.php';
}

$myts = MyTextSanitizer::getInstance();
