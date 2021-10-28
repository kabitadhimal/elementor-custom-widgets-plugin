<?php
class App_Post_Filter_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'App-post-filter';
    }

    public function get_title() {
        return __( 'App Post Filter', 'app-elementor-post-filter' );
    }

    public function get_icon() {
        return 'eicon-filter';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'app-elementor-post-filter' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'filter_block_title',
            [
                'label' => __( 'Filter Block Title', 'app-elementor-post-filter' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => "",
                'title' => __( 'Enter text for recent post title', 'app-elementor-post-filter' ),
            ]
        );

        $this->end_controls_section();
    }

     /*
     * Reading time of the article
     */
    protected function estimated_reading_time( $content = '', $wpm = 200 ) {
        $clean_content = strip_shortcodes( $content );
        $clean_content = strip_tags( $clean_content );
        $word_count = str_word_count( $clean_content );
        $time = ceil( $word_count / $wpm );

        return $time;
    }

    /*
     * Pagination
     */

    /**
     * @param string $numpages
     * @param string $pagerange
     * @param string $paged
     * http://callmenick.com/post/custom-wordpress-loop-with-pagination
     */
    protected function custom_pagination($numpages = '', $pagerange = '', $paged='', $base = '', $type='') {

        if ($pagerange) {
            $pagerange = 2;
        }

        /**
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
            if(!$numpages) {
                $numpages = 1;
            }
        }

        $base = ($base) ? : get_pagenum_link(1);

        /**
         * We construct the pagination arguments to enter into our paginate_links
         * function.
         */
        $pagination_args = array(
            'base'            => $base.'%_%',
            'format'          => 'page/%#%',
            'total'           => $numpages,
            'current'         => $paged,
            'show_all'        => False,
            'end_size'        => 2,
            'mid_size'        => $pagerange,
            'prev_next'       => True,
            'prev_text'       => __(''),
            'next_text'       => __(''),
            'type'      => 'list',
            'add_args'        => false,
            'add_fragment'    => '',
        );

        $paginate_links = paginate_links($pagination_args);
        $paginate_links = str_replace('<li><span aria-current="page" class="page-numbers current">','<li class="active"><span aria-current="page" class="page-numbers current">',$paginate_links);
        echo $paginate_links;
    }

    protected function render() {
        // generate the final HTML on the frontend using PHP
        $settings = $this->get_settings_for_display();
        $pagePermalink = get_permalink();
        include 'post/post-query.php';
        include 'post/category-filter.php';
   /*     $numberOfPosts =  isset( $settings['number_of_posts'] ) ? (int) $settings['number_of_posts'] : 3;
            $query = new WP_Query(['post_type' => 'post',
                'post_status' => 'publish', 'posts_per_page' => $numberOfPosts]);*/
            if ($query->have_posts()):
                include 'post/post-list.php';
            ?>
        <?php
            else:
                echo "No Post Found";
            endif;
            ?>
        <script>
            (function ($) {
                $('input[type=radio]').on('change', function() {
                    $(this).closest("form").submit();
                });
            })(jQuery);
        </script>
<?php
    }
}
