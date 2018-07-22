<?php

namespace App\Helpers;

/**
 * Pagination Helper
 * 
 * @author Coderberg
 */
class Paginator
{
    
    // Maximum of links left and right
    const MAX_LINKS = 4;


    // Make container for breadcrumbs
    private static function wrapUp(string $items)
    {
        $html = '';
        
        if ($items != '') {
            
            $html = '<nav aria-label="Page navigation">'
                    . '<ul class="pagination">';
            
            $html .= $items;
            
            $html .= '</ul></nav>';
            
        }

        return $html;
    }
    
    // Stylize link for current page
    private static function stylizeCurrent(int $current)
    {

        $html = '<li class="page-item active"><a class="page-link" href="#">';
            
        $html .= $current;
            
        $html .= ' <span class="sr-only">(current)</span></a></li>';
            
        return $html;
    }
    
    // Stylize link for first page
    private static function stylizeFirst( string $uri )
    {

        $html = '<li class="page-item">';
            
        $html .= '<a class="page-link" href="'.$uri.'" title="First page">
                      <span aria-hidden="true">First</span>
                   </a>';
        
        $html .= '</li>';
            
        return $html;
    }
    
    // Stylize link for last page
    private static function stylizeLast( string $uri )
    {

        $html = '<li class="page-item">';
            
        $html .= '<a class="page-link" href="'.$uri.'" title="Last page">
                      <span aria-hidden="true">Last</span>
                   </a>';
        
        $html .= '</li>';
            
        return $html;
    }
    
    // Stylize link for other pages
    private static function stylizeLink( $uri, $text )
    {

        $html = '<li class="page-item">';
            
        $html .= '<a class="page-link" href="'.$uri.'">'.$text.'</a>';
        
        $html .= '</li>';
            
        return $html;
    }

    /**
     * @param array $args 
     *              - int current_page,
     *              - int total_items,
     *              - int per_page,
     *              - string cur_uri
     * @return string
    */
    private static function getPagination( array $args )
    {

        $total_items = intval ($args['total_items']);
        $per_page = intval ($args['per_page']);
        $page = intval ($args['current_page']);
        $сur_uri = strip_tags($args['cur_uri']);
        
        $first_page = '';
        $to_left = ''; 
        $to_right = '';
        $last_page = '';
        
        // Total pages
	$total = ceil($total_items/$per_page);

	for ($i = self::MAX_LINKS; $i >= 1; $i--) {
			
            if ($page-$i > 1) {
             
                $to_left .= self::stylizeLink( $сur_uri.'/page/'.($page-$i), $page-$i );
                
            } elseif ($page-$i == 1) {
           
                $to_left .= self::stylizeLink( $сur_uri, '1' );

            }
	}
		
        for ($i = 1; $i <= self::MAX_LINKS; $i++) {
            
            if ($page+$i <= $total) {

                $to_right .= self::stylizeLink( $сur_uri.'/page/'.($page+$i), $page+$i );
            }
        }

        if ($page != $total) {
            
            // Stylize last page
            $last_page = self::stylizeLast($сur_uri.'/page/'.$total );
        }
        
        if ($page > 1) {

            // Stylize first page
            $first_page = self::stylizeFirst( $сur_uri );
        }

        // Stylize current page
        $current_page = self::stylizeCurrent( $page );
        
        // Concatenate links
        $result = $first_page . $to_left . $current_page . $to_right . $last_page;

        // Make nav container
        $result = self::wrapUp( $result );
        
        return $result;
    }


    /**
     * @param array $args 
     *              - int current_page,
     *              - int total_items,
     *              - int per_page,
     *              - string cur_uri
     * @return string
    */
    public static function get( array $args)
    {
        $result = '';
        
        if (isset ($args['total_items']) && isset ($args['per_page'])) {
            
            $total_items = intval( $args['total_items'] );
            
            $per_page = intval( $args['per_page'] );
            
            if ($per_page > 0 && $per_page < $total_items) {
                
                $result = self::getPagination($args);
            }
        }
        
        return $result;
    }
 
}
