<?php

/**
 * 
 */
class Hospitality_Menu_Manager
{
    /*
     * This is a stub meant to accomodate future functionality. 
     */
    // TODO: be sure to change capability.
    public function configure_menu() {
        add_menu_page(
            'Hospitality Admin',
            'Hospitality',
            'read',
            'hospitiality-admin',
            array(
                $this,
                'hospitality_admin_page'
            )

        );

        // add_submenu_page('admin.php?page=hospitiality-admin', 'Rooms', 'read', 'rooms', '');
    }

    public function hospitality_admin_page() {
        echo '<p>Here it is</p>';
    }

}