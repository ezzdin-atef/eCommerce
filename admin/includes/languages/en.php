<?php 

    function lang($phase) {
        static $lang = array(
        	'DASHBOARD' => 'Dashboard',
        	'HOME_PAGE' => 'Home',
        	'CATEGORIES' => 'Categories',
        	'ITEMS' => 'Items',
        	'MEMBERS' => 'Members',
            'COMMENTS' => 'Comments',
        	'STATISTICS' => 'Statistics',
        	'LOGS' => 'Logs',
        	'EDIT_PROFILE' => 'Edit Profile',
        	'SETTING' => 'Setting',
        	'LOGOUT' => 'Logout'
        );

        return $lang[$phase];
    }

?>