<?php

namespace Fatherly\Nav;

class Walker extends \Walker_Nav_Menu
{
    public function start_lvl(&$output, $depth = 0, $args = array())
    {

//      $indent = str_repeat( "\t", $depth );
//      $output .= "\n$indent\n";
    }

    public function end_lvl(&$output, $depth = 0, $args = array())
    {
        $output .= "</ul></div>";
        //      $indent = str_repeat( "\t", $depth );
//      $output .= "$indent\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        if ($item->menu_order === 1) {
            $output .= sprintf('<div class="nav__main-menu--slideout-vertical">
                <h4 class="nav__main-menu--slideout-section-name mobile-visible">
                    Sections</h4>');
        }
        if ($depth === 0 && $item->menu_order !==1) {
            $output .= sprintf('<div class="nav__main-menu--slideout-vertical">');
        }
        if ($depth === 0 && in_array('menu-item-has-children', $item->classes)) {
            $output .= sprintf(' <a href="%1$s" data-ev-loc="Header" data-ev-name="Navigation Menu Link" data-ev-val="%2$s"><h4
                            class="nav__main-menu--slideout-vertical-name">%2$s</h4></a><ul>', $item->url, $item->title);
        } else if ($depth === 0) {
            $output .= sprintf(' <a href="%1$s" data-ev-loc="Header" data-ev-name="Navigation Menu Link" data-ev-val="%2$s"><h4
                            class="nav__main-menu--slideout-vertical-name">%2$s</h4></a><div class="xfinity-menu">sponsored by <div class="xfinity-menu-logo"> 
                            <img src="/wp-content/themes/grandfather/images/xfinity-logo.png">
                            </div></div></div>', $item->url, $item->title);
        } else {
            $output .= sprintf('<li><a href="%1$s" data-ev-loc="Header" data-ev-name="Navigation Menu Link" data-ev-val="%2$s">%2$s</a></li>', $item->url, $item->title);
        }
        //      $indent      = ( $depth ) ? str_repeat( "\t", $depth ) : '';
//      $class_names = $value = '';
//      $classes     = empty( $item->classes ) ? array() : (array) $item->classes;
//      $classes[]   = 'menu-item-' . $item->ID;
//      $class_names = join( ' ',
//          apply_filters( 'nav_menu_css_class', array_filter( $classes ),
//              $item, $args ) );
//      $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
//      $id          = apply_filters( 'nav_menu_item_id',
//          'menu-item-' . $item->ID, $item, $args );
//      $id          = $id ? ' id="' . esc_attr( $id ) . '"' : '';
//      $output      .= $indent . '';
//      $attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
//      $attributes  .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
//      $attributes  .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
//      $attributes  .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
//      $item_output = $args->before;
//      $item_output .= '<a' . $attributes . '>';
//      $item_output .= $args->link_before . apply_filters( 'the_title',
//              $item->title, $item->ID ) . $args->link_after;
//      $item_output .= '</a>';
//      $item_output .= $args->after;
//      $output      .= apply_filters( 'walker_nav_menu_start_el', $item_output,
//          $item, $depth, $args );
    }

    public function end_el(&$output, $item, $depth = 0, $args = array())
    {
        //      $output .= "\n";
    }
}

class gaWalker extends \Walker_Nav_Menu
{
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
    {
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
        $menu_class_checks = ['header-nav-links-bottom', 'header-nav-second-top'];
        $data_ev_name = in_array($args->menu_class, $menu_class_checks) ? $item->type_label : ($args->container_class === 'nav__main-menu--secondary-verticals' ? 'Category' : 'Navigation Menu');
        $output .= sprintf('<li %1$s %2$s><a href="%3$s" data-ev-loc="Header" data-ev-name="%4$s Link" data-ev-val="%5$s">%5$s</a></li>', $id, $class_names, $item->url, $data_ev_name, $item->title);
    }
}
