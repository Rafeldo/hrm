<?php

class Menu
{

    public function dynamicMenu()
    {

        $CI = &get_instance();

        $user_id = $CI->session->userdata('designations_id');


        $user_type = $CI->session->userdata('user_type');

        if ($user_type != 1) {// query for employee user role
            $user_menu = mysql_query("SELECT tbl_user_role.*,tbl_menu.*
                                        FROM tbl_user_role
                                        INNER JOIN tbl_menu
                                        ON tbl_user_role.menu_id=tbl_menu.menu_id
                                        WHERE tbl_user_role.designations_id=$user_id
                                        ORDER BY sort;");
        } else { // get all menu for admin
            $user_menu = mysql_query("SELECT menu_id, label, link, icon, parent FROM  tbl_menu WHERE status=1 ORDER BY sort,time");
        }
        // Create a multidimensional array to conatin a list of items and parents
        $menu = array(
            'items' => array(),
            'parents' => array()
        );

        // Builds the array lists with data from the menu table
        while ($items = mysql_fetch_assoc($user_menu)) {
            // Creates entry into items array with current menu item id ie. $menu['items'][1]
            $menu['items'][$items['menu_id']] = $items;
            // Creates entry into parents array. Parents array contains a list of all items with children
            $menu['parents'][$items['parent']][] = $items['menu_id'];
        }
        return $output = $this->buildMenu(0, $menu);
    }

    public function buildMenu($parent, $menu, $sub = NULL)
    {
        $html = "";
        if (isset($menu['parents'][$parent])) {
            if (!empty($sub)) {
                $html .= "<ul id=" . $sub . " class='nav sidebar-subnav collapse'><li class=\"sidebar-subnav-header\">" . lang($sub) . "</li>\n";
            } else {
                $html .= "<ul class='nav'>\n";
            }
            foreach ($menu['parents'][$parent] as $itemId) {
                $result = $this->active_menu_id($menu['items'][$itemId]['menu_id']);
                if ($result) {
                    $active = 'active';
                } else {
                    $active = '';
                }

                if (!isset($menu['parents'][$itemId])) { //if condition is false only view menu
                    $html .= "<li class='" . $active . "' >\n  <a title='" . lang($menu['items'][$itemId]['label']) . "' href='" . base_url() . $menu['items'][$itemId]['link'] . "'>\n <em class='" . $menu['items'][$itemId]['icon'] . "'></em><span>" . lang($menu['items'][$itemId]['label']) . "</span></a>\n</li> \n";
                }

                if (isset($menu['parents'][$itemId])) { //if condition is true show with submenu
                    $html .= "<li class='sub-menu " . $active . "'>\n  <a data-toggle='collapse' href='#" . $menu['items'][$itemId]['label'] . "'> <em class='" . $menu['items'][$itemId]['icon'] . "'></em><span>" . lang($menu['items'][$itemId]['label']) . "</span></a>\n";
                    $html .= self::buildMenu($itemId, $menu, $menu['items'][$itemId]['label']);
                    $html .= "</li> \n";
                }
            }
            $html .= "</ul> \n";
        }
        return $html;
    }

    public function active_menu_id($id)
    {
        $CI = &get_instance();
        $activeId = $CI->session->userdata('menu_active_id');

        if (!empty($activeId)) {
            foreach ($activeId as $v_activeId) {
                if ($id == $v_activeId) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

}
