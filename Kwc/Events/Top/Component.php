<?php
abstract class Kwc_Events_Top_Component extends Kwc_News_Top_Component
{
    public static function getSettings()
    {
        $ret = parent::getSettings();
        $ret['componentName'] = trlKwfStatic('Events.Top');
        $ret['generators']['child']['component']['view'] = 'Kwc_Events_List_View_Component';
        return $ret;
    }
}
