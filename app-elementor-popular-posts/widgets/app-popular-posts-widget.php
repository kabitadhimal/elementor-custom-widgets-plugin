<?php
class App_Popular_Posts_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'App-popular-posts';
    }

    public function get_title() {
        return __( 'Popular Posts', 'app-elementor-popular-post' );
    }

    public function get_icon() {
        return 'eicon-info-box';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'app-elementor-popular-post' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'popular_post_title',
            [
                'label' => __( 'Popular Post Title', 'app-elementor-popular-post' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => "",
                'title' => __( 'Enter text for popular post title', 'app-elementor-popular-post' ),
            ]
        );



        $this->add_control(
            'number_of_posts',
            [
                'label' => __( 'Number of Posts to display', 'app-elementor-popular-post' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 3,
                'title' => __( 'Enter number of posts to display', 'app-elementor-popular-post' ),
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


    protected function render() {
        // generate the final HTML on the frontend using PHP
        $settings = $this->get_settings_for_display();
        $numberOfPosts =  isset( $settings['number_of_posts'] ) ? (int) $settings['number_of_posts'] : 3;
            $query = new WP_Query(['post_type' => 'post',
                                    'post_status' => 'publish',
                                    'posts_per_page' => $numberOfPosts,
                                    'orderby'   => 'meta_value_num',
                                    'meta_key'  => 'post_views_count'
                                    ]
                                );
            if ($query->have_posts()):
            ?>

                <section class="blog-feature pop-articles">
                    <div class="container">
                        <div class="blog-wrap">
                            <div class="blog-post-wrap">
                                <div class="d-flex flex-wrap align-items-center justify-content-between">

                                    <?php
                                    $PopularPostTitle = ( isset( $settings['popular_post_title'] ) ) ? $settings['popular_post_title'] : "";
                                    if( $PopularPostTitle ) echo "<h2>".$PopularPostTitle."</h2>";
                                    ?>
                                </div>
                                <div class="blog-news">
                                    <div class="feature-post similar-post d-flex flex-wrap">

                                        <?php
                                        while ($query->have_posts()):
                                        $query->the_post();
                                        $contentReadTime = $this->estimated_reading_time(get_the_content());
                                        $image = wp_get_attachment_url( get_post_thumbnail_id() );
                                        ?>
                                        <div class="post-count">
                                            <?php

                                            if (empty( $image ) ) {
                                                echo ' <div class="image-wrap no-image"></div>';
                                            }

                                            if( isset( $image ) && !empty( $image ) ) { ?>
                                                <div class="post-image">
                                                    <img src="<?=$image?>" alt="image">
                                                </div>
                                            <?php } ?>
                                            <div class="slide-content post-content">
                                                <div class="slide-subtitle">
                                                    <div class="tag-date">
                                                        <div class="sub-header">
                                                            <div class="header-date">
                                                                <p><?=get_the_date( 'F j, Y' )?></p>
                                                            </div>
                                                            <div class="read-time">
                                                                <p><?=$contentReadTime?> min read</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                                    <?php the_excerpt(); ?>
                                                </div>
                                                <div class="blog-items-footer">
                                                    <a href="<?php the_permalink(); ?>" class="btn btn-primary">Read More </a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endwhile;  wp_reset_query(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <?php
            endif;
    }
}
