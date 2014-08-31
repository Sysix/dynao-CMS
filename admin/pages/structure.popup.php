<?php
$langId = type::super('lang', 'int', lang::getLangId());
type::addSession('backend-lang', $langId);

ajax::addReturn(pageMisc::getTreeStructurePagePopup(0, $langId));
?>
