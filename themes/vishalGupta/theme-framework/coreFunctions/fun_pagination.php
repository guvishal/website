<?php
function custom_pagination($url, $numpages = '', $pagerange = '', $paged = '') {
    if (empty($pagerange)) {
        $pagerange = 2;
    }    /**
     * This first part of our function is a fallback
     * for custom pagination inside a regular loop that
     * uses the global $paged and global $wp_query variables.
     *
     * It's good because we can now override default pagination
     * in our theme, and use this function in default quries
     * and custom queries.
     */
    global $paged;
    if (empty($paged)) {
        $paged = 1;
    }
    if ($numpages == '') {
        global $wp_query;
        $numpages = $wp_query->max_num_pages;
        if (!$numpages) {
            $numpages = 1;
        }
    }    /**
     * We construct the pagination arguments to enter into our paginate_links
     * function.
     */
    $pagination_args = array(
        'base'         => $url.'%_%',
        'format'       => 'page/%#%',
        'total'        => $numpages,
        'current'      => $paged,
        'show_all'     => True,
        'end_size'     => 1,
        'mid_size'     => $pagerange,
        'prev_next'    => True,
        'prev_text'    => __('&lt;'),
        'next_text'    => __('&gt;'),
        'type'         => 'list',
        'add_args'     => false,
        'add_fragment' => '',
    );    $paginate_links = paginate_links($pagination_args);    //Store your html into $html variable.
    if ($paginate_links) {
        $html = $paginate_links;        $dom = new DOMDocument();
        $dom->loadHTML($html);        //Evaluate Anchor tag in HTML
        $xpath = new DOMXPath($dom);
        $hrefs = $xpath->evaluate("//ul");        for ($i = 0; $i < $hrefs->length; $i++) {
            $href     = $hrefs->item($i);
            $getClass = $href->getAttribute('class');
            //remove and set target attribute
            $class = $getClass.' pagination';
            $href->setAttribute("class", $class);
        }        // save html
        $html = $dom->saveHTML();        echo "<nav class='custom-pagination'>".$html."</nav>";
    }
}

?>