<?php
class Kwc_Directories_List_ViewAjax_Component extends Kwc_Directories_List_View_Component
{
    public static function getSettings()
    {
        $ret = parent::getSettings();

        $ret['generators']['child']['component']['paging'] = 'Kwc_Directories_List_ViewAjax_Paging_Component';

        $ret['assets']['dep'][] = 'KwfAutoGrid'; //TODO: less dep
        $ret['assets']['dep'][] = 'KwfHistoryState';
        $ret['assets']['dep'][] = 'KwfStatistics';

        $ret['loadMoreBufferPx'] = 700; //if false infinite scrolling is disabled, you still can call loadMore() manually
        $ret['loadDetailAjax'] = true; //true by default - the detail will be loaded via ajax
        $ret['partialClass'] = 'Kwf_Component_Partial_Id';

        return $ret;
    }

    public function getTemplateVars()
    {
        $ret = parent::getTemplateVars();
        if ($this->getData()->parent
            ->getComponent()
            ->getItemDirectory()
            ->getChildComponent('-view')
            ->componentClass
            != $this->getData()->componentClass
        ) {
//             throw new Kwf_Exception('Invalid View: must be the same as the one used for the directory itself if using ViewAjax');
        }

        $cfg = Kwf_Component_Abstract_ExtConfig_Abstract::getInstance($this->getData()->componentClass);
        $ret['config'] = array(
            'controllerUrl' => $cfg->getControllerUrl('View'),
            'directoryViewComponentId' => $this->getData()->parent->getComponent()->getItemDirectory()->getChildComponent('-view')->componentId,
            'viewUrl' => $this->getData()->url,
            'directoryUrl' => $this->getData()->parent->getComponent()->getItemDirectory()->url,
            'componentId' => $this->getData()->componentId,
            'directoryComponentId' => $this->getData()->parent->getComponent()->getItemDirectory()->componentId,
            'searchFormComponentId' => $this->_getSearchForm() ? $this->_getSearchForm()->componentId : null,
            'placeholder' => array(
                'noEntriesFound' => $this->_getPlaceholder('noEntriesFound')
            ),
            'loadMoreBufferPx' => $this->_getSetting('loadMoreBufferPx'),
            'loadDetailAjax' => $this->_getSetting('loadDetailAjax'),
        );
        return $ret;
    }

    //public for ViewController
    public final function getSelect()
    {
        return $this->_getSelect();
    }

    public function getPartialParams()
    {
        $ret = parent::getPartialParams();
        $ret['tpl'] = '<div class="kwfViewAjaxItem {id}">{content}</div>'."\n";
        return $ret;
    }
}
