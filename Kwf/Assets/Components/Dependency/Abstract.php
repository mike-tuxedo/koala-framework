<?php
class Kwf_Assets_Components_Dependency_Abstract extends Kwf_Assets_Dependency_Abstract
{
    protected $_componentClass;
    protected $_componentDependencies;
    protected $_isMaster;

    public function __construct($componentClass, array $componentDependencies, $isMaster)
    {
        $this->_componentClass = $componentClass;
        $this->_componentDependencies = $componentDependencies;
        $this->_isMaster = $isMaster;
    }

    private function _getKwcClass()
    {
        $kwcClass = Kwf_Component_Abstract::formatRootElementClass($this->_componentClass, '');
        if ($this->_isMaster) $kwcClass .= 'Master';
        if (Kwf_Config::getValue('application.uniquePrefix')) {
            $kwcClass = str_replace('kwfUp-', Kwf_Config::getValue('application.uniquePrefix').'-', $kwcClass);
        } else {
            $kwcClass = str_replace('kwfUp-', '', $kwcClass);
        }
        return $kwcClass;
    }

    public function getMTime()
    {
        $ret = null;
        foreach ($this->_componentDependencies as $dep) {
            $ret = max($ret, $dep->getMTime());
        }
        return $ret;
    }

    public function getContents($language)
    {
        $ret = '';
        foreach ($this->_componentDependencies as $dep) {
            $c = $dep->getContents($language);
            if (Kwf_Config::getValue('application.uniquePrefix')) {
                $c = str_replace('kwcBem--', $this->_getKwcClass().'--', $c);
                $c = str_replace('kwcBem__', $this->_getKwcClass().'__', $c);
            } else {
                $c = str_replace('kwcBem--', '', $c);
                $c = str_replace('kwcBem__', '', $c);
            }
            $c = str_replace('.kwcClass', '.'.$this->_getKwcClass(), $c);
            $ret .= $c;
        }
        return $ret;
    }

    public function getContentsPacked($language)
    {
        $ret = Kwf_SourceMaps_SourceMap::createEmptyMap('');
        foreach ($this->_componentDependencies as $dep) {
            $c = $dep->getContentsPacked($language);
            if (Kwf_Config::getValue('application.uniquePrefix')) {
                $c->stringReplace('kwcBem--', $this->_getKwcClass().'--');
                $c->stringReplace('kwcBem__', $this->_getKwcClass().'__');
            } else {
                $c->stringReplace('kwcBem--', '');
                $c->stringReplace('kwcBem__', '');
            }
            $c->stringReplace('.kwcClass', '.'.$this->_getKwcClass());
            $ret->concat($c);
        }
        return $ret;
    }
}