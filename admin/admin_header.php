<?php

require_once '../../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/kernel/module.php';
require_once XOOPS_ROOT_PATH . '/include/cp_functions.php';
require_once '../common/functions.php';

if (file_exists('../language/' . $xoopsConfig['language'] . '/modinfo.php')) {
    require_once '../language/' . $xoopsConfig['language'] . '/modinfo.php';
} else {
    require_once '../language/english/modinfo.php';
}

if (file_exists('../language/' . $xoopsConfig['language'] . '/admin.php')) {
    require_once '../language/' . $xoopsConfig['language'] . '/admin.php';
} else {
    require_once '../language/english/admin.php';
}

if (file_exists('../language/' . $xoopsConfig['language'] . '/main.php')) {
    require_once '../language/' . $xoopsConfig['language'] . '/main.php';
} else {
    require_once '../language/english/main.php';
}

if ($xoopsUser) {
    $xoopsModule = XoopsModule::getByDirname(_MI_WIKIMOD_DIRNAME);

    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);

        exit();
    }
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);

    exit();
}

$configHandler = xoops_getHandler('config');
$xoopsModuleConfig = &$configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

$myts = MyTextSanitizer::getInstance();
