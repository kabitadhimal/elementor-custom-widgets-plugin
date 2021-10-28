<?php
class App_Featured_Post_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'App-featured-post-block';
    }

    public function get_title() {
        return __( 'Featured Posts', 'app-elementor-featured-post' );
    }

    public function get_icon() {
        return 'eicon-post';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'app-elementor-featured-post' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'featured_title',
            [
                'label' => __( 'Featured Post Title', 'app-elementor-featured-post' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'title' => __( 'Enter text for featured post title', 'app-elementor-featured-post' ),
            ]
        );


        $this->add_control(
            'featured_posts',
            [
                'label' => __( 'featured_posts', 'elementor-pro' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT2,
                //'default' => [ 'date', 'comments' ],
                'multiple' => true,
                'options' => $this->getBlogPosts(),
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

    }

    protected function getBlogPosts(){

        $blogPosts = get_posts( [
                'posts_per_page' => -1,
                'post_status' =>'publish'
            ]
        );

        $blogPostsArray = [];
        foreach( $blogPosts as $post) {
            $blogPostsArray[$post->ID] = $post->post_title;
        }

        return $blogPostsArray;
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

    protected function render() {
        // generate the final HTML on the frontend using PHP
        $settings = $this->get_settings_for_display();

        if ( $settings['featured_posts'] ) {

            $query = new WP_Query(['post_type' => 'post',
                'post_status' => 'publish', 'posts_per_page' => 5]);
            if ($query->have_posts()):
            ?>
            <section class="blog-carousel">
                <div class="container">
                    <div class="content-slider">
                <?php
                while ($query->have_posts()):
                    $query->the_post();
                    $contentReadTime = $this->estimated_reading_time(get_the_content());
                    $image = wp_get_attachment_url( get_post_thumbnail_id() );
                ?>
                        <div class="banner-content">
                            <?php
                                if( empty( $image ) ) {
                                    echo ' <div class="image-wrap no-image"></div>';
                                }

                            if( isset( $image ) && !empty( $image ) ) { ?>
                                    <div class="image-wrap">
                                        <img src="<?=$image?>">
                                    </div>
                            <?php } ?>
                            <div class="slider-content">
                                <?php
                                 $featuredPostTitle = ( isset( $settings['featured_title'] ) ) ? $settings['featured_title'] : "";
                                if( $featuredPostTitle ) echo "<h3>".$featuredPostTitle."</h3>";
                                 ?>
                                <div class="sub-header">
                                    <div class="header-date">
                                        <p><?=get_the_date( 'F j, Y' )?></p>
                                    </div>
                                    <div class="read-time">
                                        <p><?=$contentReadTime?> min read</p>
                                    </div>
                                </div>
                                <?php the_title("<h4>","</h4>"); ?>
                            </div>
                        </div>

                <?php endwhile;  wp_reset_query(); ?>
                    </div>
                </div>
            </section>
        <?php
            endif;
        }
    }
}
