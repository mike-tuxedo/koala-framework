<?php
class Kwc_Menu_DropdownMask_Component extends Kwc_Menu_Dropdown_Component
{
    public static function getSettings()
    {
        $ret = parent::getSettings();
        $ret['cssClass'] .= ' webListNone';
        $ret['assets']['dep'][] = 'jQuery';
        // Define the mask parent node, if parent is not body, parents css position has to be "relative"
        $ret['maskParent'] = 'body';
        return $ret;
    }

    public function getTemplateVars()
    {
        $ret = parent::getTemplateVars();
        $ret['config'] = array(
            'maskParent' => $this->_getSetting('maskParent')
        );
        return $ret;
    }

    protected function _getMenuData($parentData = null, $select = array())
    {
        $ret = parent::_getMenuData($parentData, $select);
        foreach ($ret as $k=>$i) {
            if (count($ret[$k]['data']->getChildPages(array('showInMenu'=>true))) > 0) {
                $ret[$k]['class'] .= ' hasSubmenu';
            }
        }
        return $ret;
    }
}