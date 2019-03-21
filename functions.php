<?php

// Function to get all child menu items of parent menu item ID
function get_nav_menu_item_children( $parent_id, $nav_menu_items) {
    $nav_menu_item_list = array();
    foreach ( (array) $nav_menu_items as $nav_menu_item ) {
        if ( $nav_menu_item->menu_item_parent == $parent_id ) {
            $nav_menu_item_list[] = $nav_menu_item;
        }
    }
    return $nav_menu_item_list;
}

// Function to display custom menu with nested sub menus
// 2 levels MAX
function gg_custom_menus() {

	$menu_name = 'menu-1'; // specify custom menu slug
	if (($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) {
		$menu = wp_get_nav_menu_object($locations[$menu_name]);
        $menu_items = wp_get_nav_menu_items($menu->term_id);

        $parent_items = array();
        foreach((array) $menu_items as $key => $menu_item) {
            if($menu_item->menu_item_parent == 0) {
                $parent_items[] = $menu_item;
            }
        }

        foreach($parent_items as $parent_item) {

            $child_items = get_nav_menu_item_children($parent_item->ID, $menu_items);

            if($child_items) {
                $menu_list .= '<li><a href="'. $parent_item->url .'">'. $parent_item->title .'</a>';
                $menu_list .= '<ul class="sub-menu">';

                foreach($child_items as $child_item) {
                    $sub_child_items = get_nav_menu_item_children($child_item->ID, $menu_items);

                    if($sub_child_items) {
                        $menu_list .= '<li><a href="'. $child_item->url .'">'. $child_item->title .'</a>';
                        $menu_list .= '<ul class="sub-menu">';

                        foreach($sub_child_items as $sub_child_item) {
                            $menu_list .= '<li><a href="'. $sub_child_item->url .'">'. $sub_child_item->title .'</a></li>';
                        }

                        $menu_list .= '</ul></li>';

                    } else {
                        $menu_list .= '<li><a href="'. $child_item->url .'">'. $child_item->title .'</a></li>';
                    }
                }
                $menu_list .= '</ul></li>';
            } else {
                $menu_list .= '<li><a href="'. $parent_item->url .'">'. $parent_item->title .'</a></li>';
            }

        }

	} else {
		$menu_list = '<!-- no list defined -->';
	}
	echo $menu_list;
}