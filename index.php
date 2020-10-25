<?php

include 'header.php';

$page = makeKeyWord($_GET['page'] ?? _MI_WIKIMOD_WIKIHOME);
$op = $_GET['op'] ?? '';
if (!empty($_POST)) {
    extract($_POST);
}
$mayEdit = $xoopsModuleConfig['anonymous_edit'] || !!$xoopsUser;

if (('' != $op) && !$mayEdit) {
    redirect_header("index.php?page=$page", 2, _NOPERM);

    exit();
}

if (('insert' == $op) && isset($id)) {
    if ((int)$id == getCurrentId($page)) {
        $success = addRevision($page, $title, $body, $uid);

        redirect_header("index.php?page=$page", 2, ($success) ? _MD_WIKIMOD_DBUPDATED : _MD_WIKIMOD_ERRORINSERT);
    } else {
        redirect_header("index.php?page=$page", 2, _MD_WIKIMOD_EDITCONFLICT);
    }

    exit();
}

if (('preview' == $op) && isset($id)) {
    $result = (int)$id > 0;

    $title = $myts->stripSlashesGPC($title);

    $body = $myts->stripSlashesGPC($body);
} elseif ($result = getPage($page)) {
    [$title, $body, $lastmodified, $uid] = $xoopsDB->fetchRow($result);
} else {
    $title = $body = '';

    $op = 'edit';
}

switch ($op) {
    case 'edit':
    case 'preview':
        $GLOBALS['xoopsOption']['template_main'] = 'wikimod_edit.html';
        require XOOPS_ROOT_PATH . '/header.php';

        if ('preview' == $op) {
            $xoopsTpl->assign('wikimod', ['keyword' => $page, 'title' => $title, 'body' => wikiDisplay($body)]);

            $xoopsTpl->assign(['_MD_WIKIMOD_PAGE' => _MD_WIKIMOD_PAGE, '_MD_WIKIMOD_PREVIEW' => _MD_WIKIMOD_PREVIEW]);
        }

        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);
        $body = htmlspecialchars($body, ENT_QUOTES | ENT_HTML5);
        $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : 0;

        $form = new XoopsThemeForm(_MD_WIKIMOD_EDITPAGE . ": $page", 'wikimodform', 'index.php');
        $btn_tray = new XoopsFormElementTray('', ' ');

        if ($mayEdit) {
            $form->addElement(new XoopsFormHidden('op', 'insert'));

            $form->addElement(new XoopsFormHidden('page', $page));

            $form->addElement(new XoopsFormHidden('id', getCurrentId($page)));

            $form->addElement(new XoopsFormHidden('uid', $uid));

            $form->addElement(new XoopsFormText(_MD_WIKIMOD_TITLE, 'title', 80, 250, $title));

            $form->addElement(new XoopsFormTextArea(_MD_WIKIMOD_BODY, 'body', $body, 20, 80));

            $btn_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

            $preview_btn = new XoopsFormButton('', 'preview', _PREVIEW, 'button');

            $preview_btn->setExtra("onclick='document.forms.wikimodform.op.value=\"preview\"; document.forms.wikimodform.submit.click();'");

            $btn_tray->addElement($preview_btn);
        }

        $cancel_btn = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
        $cancel_btn->setExtra("onclick='" . (('edit' == $op) ? 'history.back();' : 'document.location.href="index.php' . (($result) ? "?page=$page" : '') . '";') . "'");
        $btn_tray->addElement($cancel_btn);
        if (!$result) {
            $btn_tray->addElement(new XoopsFormLabel('', ' - <strong>' . _MD_WIKIMOD_PAGENOTFOUND . '</strong>'));
        }
        $form->addElement($btn_tray);

        $form->assign($xoopsTpl);
        break;
    default:
        $GLOBALS['xoopsOption']['template_main'] = 'wikimod_view.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $xoopsTpl->assign('wikimod', ['keyword' => $page, 'title' => $title, 'body' => wikiDisplay($body), 'lastmodified' => date($xoopsModuleConfig['date_format'], strtotime($lastmodified)), 'author' => getUserName($uid), 'mayEdit' => $mayEdit]);
        $xoopsTpl->assign(['_MD_WIKIMOD_PAGE' => _MD_WIKIMOD_PAGE, '_MD_WIKIMOD_LASTMODIFIED' => _MD_WIKIMOD_LASTMODIFIED, '_MD_WIKIMOD_BY' => _MD_WIKIMOD_BY, '_EDIT' => _EDIT]);
        break;
}

require XOOPS_ROOT_PATH . '/footer.php';
