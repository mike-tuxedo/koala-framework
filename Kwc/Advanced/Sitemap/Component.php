<?php
class Kwc_Advanced_Sitemap_Component extends Kwc_Abstract
{
    public static function getSettings()
    {
        $ret = parent::getSettings();
        $ret['componentName'] = trlKwfStatic('Sitemap');
        $ret['ownModel'] = 'Kwf_Component_FieldModel';
        $ret['extConfig'] = 'Kwf_Component_Abstract_ExtConfig_Form';
        return $ret;
    }

    public function getTemplateVars(Kwf_Component_Renderer_Abstract $renderer = null)
    {
        $ret = parent::getTemplateVars($renderer);
        $ret['levels'] = $this->getRow()->levels;
        $ret['target'] = Kwf_Component_Data_Root::getInstance()
            ->getComponentByDbId($this->getRow()->target, array('limit'=>1));
        $ret['listHtml'] = '';
        if ($ret['target']) {
            $ret['listHtml'] = $this->_getListHtml($renderer, $ret['target'], 0);
        }
        return $ret;
    }

    //not in template for easier recursion
    private function _getListHtml(Kwf_Component_Renderer_Abstract $renderer, Kwf_Component_Data $c, $level)
    {
        $ret = '';
        $level++;
        $select = new Kwf_Component_Select();
        $select->whereShowInMenu(true);
        $ret .= "<ul>\n";
        foreach ($c->getChildPages($select) as $child) {
            $noChild = '';
            if (!$child->getChildPages($select)) {
                $noChild = 'noChild';
            }
            $ret .= '<li class="' . $noChild . '">';
            $helper = new Kwf_Component_View_Helper_ComponentLink();
            $helper->setRenderer($renderer);
            $ret .= $helper->componentLink($child);
            $ret .= "\n";
            if ($level < $this->getRow()->levels) {
                $ret .= $this->_getListHtml($renderer, $child, $level);
            }
            $ret .= "</li>\n";
        }
        $ret .= "</ul>\n";
        return $ret;
    }
}
