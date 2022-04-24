//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if (!\defined('\IPS\SUITE_UNIQUE_KEY')) {
    exit;
}

abstract class axenserverlist_hook_loadJSandCSS extends _HOOK_CLASS_
{
    public static function baseCss()
    {
        try {
            parent::baseCss();
            \IPS\Output::i()->cssFiles = array_merge(\IPS\Output::i()->cssFiles, \IPS\Theme::i()->css('widget.css', 'axenserverlist', 'front'));
        } catch (\RuntimeException$e) {
            if (method_exists(get_parent_class(), __FUNCTION__)) {
                return \call_user_func_array('parent::' . __FUNCTION__, \func_get_args());
            } else {
                throw $e;
            }
        }
    }
}
