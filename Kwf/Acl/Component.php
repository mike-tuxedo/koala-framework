<?php
class Kwf_Acl_Component extends Kwf_Acl
{
    public function __construct()
    {
        parent::__construct();

        $this->addRole(new Kwf_Acl_Role('superuser', trlKwf('Superuser')));
        $this->addRole(new Kwf_Acl_Role('preview', trlKwf('Preview')));
        $this->add(new Kwf_Acl_Resource_EditRole('edit_role_superuser', 'superuser'), 'edit_role');
        $this->add(new Kwf_Acl_Resource_EditRole('edit_role_preview', 'preview'), 'edit_role');

        $this->add(new Zend_Acl_Resource('kwf_debug_class-tree'), 'kwf_debug');
        $this->add(new Zend_Acl_Resource('kwf_debug_logs'), 'kwf_debug');
        $this->add(new Zend_Acl_Resource('kwf_debug_logs-form'), 'kwf_debug_logs');
        $this->add(new Zend_Acl_Resource('kwf_component_web'));
        $this->add(new Zend_Acl_Resource('kwf_component_media'));
        $this->add(new Zend_Acl_Resource('kwf_component_benchmark'));
        $this->add(new Zend_Acl_Resource('kwf_component_show-component'));
        $this->add(new Kwf_Acl_Resource_MenuUrl('kwf_component_pages',
            array('text'=>trlKwfStatic('Page tree'), 'icon'=>'application_side_tree.png')));
            $this->add(new Zend_Acl_Resource('kwf_component_page'), 'kwf_component_pages');
            $this->add(new Zend_Acl_Resource('kwf_component_components'),
                                'kwf_component_pages'); // für /component/show
            $this->add(new Zend_Acl_Resource('kwf_component'),
                                'kwf_component_pages'); // für /component/edit
            $this->add(new Zend_Acl_Resource('kwf_component_preview'), 'kwf_component_pages');

        $this->add(new Zend_Acl_Resource('kwf_component_root')); //Komponenten können hier resourcen anhängen

        $this->addResource(
            new Kwf_Acl_Resource_MenuDropdown(
                'kwf_enquiries_dropdown', array('text'=>trlKwfStatic('Enquiries'), 'icon'=>'email.png')
            )
        );

        $this->add(new Kwf_Acl_Resource_MenuUrl('kwf_enquiries_enquiries',
                array('text'=>trlKwfStatic('All Enquiries'), 'icon'=>'email.png')), 'kwf_enquiries_dropdown');

        $this->add(new Kwf_Acl_Resource_MenuDropdown('settings',
                    array('text'=>trlKwf('Toolbox'), 'icon'=>'wrench.png')));
            $this->add(new Kwf_Acl_Resource_MenuUrl('kwf_user_users',
                    array('text'=>trlKwf('Useradministration'), 'icon'=>'user.png')), 'settings');
                $this->add(new Zend_Acl_Resource('kwf_user_user'), 'kwf_user_users');
                $this->add(new Zend_Acl_Resource('kwf_user_log'), 'kwf_user_users');
                $this->add(new Zend_Acl_Resource('kwf_user_comments'), 'kwf_user_users');
            $this->add(new Kwf_Acl_Resource_MenuUrl('kwf_component_clear-cache',
                    array('text'=>trlKwf('Clear Cache'), 'icon'=>'database.png')), 'settings');
            $this->add(new Kwf_Acl_Resource_MenuUrl('kwf_redirects_redirects',
                    array('text'=>trlKwf('Redirects'), 'icon'=>'page_white_go.png')), 'settings');
                $this->add(new Zend_Acl_Resource('kwf_redirects_redirect'), 'kwf_redirects_redirects');
                    $this->add(new Zend_Acl_Resource('kwf_redirects_pages'), 'kwf_redirects_redirect');



        $this->add(new Zend_Acl_Resource('kwc_structure')); // Create Structure Resource for all classes
        foreach(Kwc_Abstract::getComponentClasses() as $class) {
            $this->add(new Kwf_Acl_Resource_Component_Structure($class), 'kwc_structure');
        }

        $this->allow('admin', 'kwc_structure');
        $this->allow('superuser', 'kwc_structure');

        $this->allow(null, 'kwf_component_web');
        $this->allow(null, 'kwf_component_media');
        $this->allow('admin', 'kwf_component');
        $this->allow('superuser', 'kwf_component');
        $this->allow('superuser', 'edit_role_superuser');
        $this->allow('superuser', 'edit_role_preview');

        $this->allow('admin', null);
        $this->allow('superuser', 'settings');
        //$this->allow('superuser', 'kwf_enquiries_enquiries');
        $this->deny('superuser', 'kwf_component_clear-cache');

        $this->allow('admin', 'kwf_component_show-component');
        $this->allow('admin', 'kwf_component_pages');
        $this->allow('admin', 'kwf_component_benchmark');
        $this->allow('superuser', 'kwf_component_show-component');
        $this->allow('superuser', 'kwf_component_pages');
        $this->allow('preview', 'kwf_component_preview');

        $this->allow(null, 'kwf_component_root');

        $this->deny('guest', 'kwf_welcome_welcome');
        $this->deny('guest', 'kwf_component_pages');

        // Kwf_Component_Acl nicht vergessen für Komponentenrechte!
    }
}
