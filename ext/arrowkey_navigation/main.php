<?php
/**
 * Name: Arrow Key Navigation
 * Author: Drudex Software <support@drudexsoftware.com>
 * Link: http://www.drudexsoftware.com/
 * License: GPLv2
 * Description: Allows viewers no navigate between images using the left & right arrow keys.
 * Documentation:
 *  Simply enable this extention in the extention manager to enable arrow key navigation.
 */
class arrowkey_navigation extends Extension {  
    # Adds functionality for post/view on images
    public function onDisplayingImage(DisplayingImageEvent $event) { 
        $prev_url = make_http(make_link("post/prev/".$event->image->id));
        $next_url = make_http(make_link("post/next/".$event->image->id));
        $this->add_arrowkeys_code($prev_url, $next_url);
    }  
    
    # Adds functionality for post/list
    public function onPageRequest(PageRequestEvent $event) {
        if($event->page_matches("post/list")) {
            $pageinfo = $this->get_list_pageinfo($event);
            $prev_url = make_http(make_link("post/list/".$pageinfo["prev"]));
            $next_url = make_http(make_link("post/list/".$pageinfo["next"]));
            $this->add_arrowkeys_code($prev_url, $next_url);
        }
    }
    
    # adds the javascript to the page with the given urls
    private function add_arrowkeys_code($prev_url, $next_url) {
        global $page;

        $page->add_html_header("<script type=\"text/javascript\">
            document.onkeyup=checkKeycode;
            function checkKeycode(e)
            {
                var keycode;
                if(window.event) keycode=window.event.keyCode;
                else if(e) keycode=e.which;

                if (e.srcElement.tagName != \"INPUT\")
                {
                    if(keycode==\"37\") window.location.href='$prev_url';
                    else if(keycode==\"39\") window.location.href='$next_url';
                }
            }
            </script>");
    }
    
    # returns info about the current page number
    private function get_list_pageinfo($event) {
        global $config, $database;
  
        // determine if post/list with tag, add prefix if needed
        $prefix = "";
        $page_number = (int)$event->get_arg(0);
        if ($page_number == 0) {
            $prefix = $event->get_arg(0)."/";
            $page_number = (int)$event->get_arg(1);
        }
        
        // if the page number is still invalid, set it to 1
        if(is_null($page_number) || $page_number <= 0)
            $page_number = 1;

        // Determine the amount of pages
        $images_per_page = $config->get_int('index_images');
        $total_pages = ceil($database->get_one("SELECT COUNT(*) FROM images") / $images_per_page);

        // creates previous & next values     
        // When previous first page, go to last page
        if ($page_number <= 1) $prev = $total_pages;
        else $prev = $page_number-1;
        if ($page_number >= $total_pages) $next = 1;
        else $next = $page_number+1;

        // Create return array
        $pageinfo = array(
            "prev" => $prefix.$prev,
            "next" => $prefix.$next,
        );

        return $pageinfo;
    }
}
?>
