<?php

if (class_exists('\Elementor\Widget_Base')) {

    class Elementor_Full_AlivingProduct_Widget extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'full_alivingproduct_widget';
        }

        public function get_title()
        {
            return __('Full Aliving Product', 'essential-elementor-widget');
        }

        public function get_icon()
        {
            return 'eicon-posts-grid';
        }

        public function get_categories()
        {
            return ['general'];
        }

        protected function _register_controls()
        {
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Content', 'essential-elementor-widget'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'ranking',
                [
                    'label' => __('Ranking', 'essential-elementor-widget'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Best Overall', 'essential-elementor-widget'),
                ]
            );

            $this->add_control(
                'product_name',
                [
                    'label' => __('Product Name', 'essential-elementor-widget'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                ]
            );

            $this->end_controls_section();
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();

            $args = [
                'post_type' => 'alivingproduct',
                'posts_per_page' => 1,
            ];

            if (!empty($settings['product_name'])) {
                $args['s'] = $settings['product_name'];
            } else {
                $args['meta_query'] = [
                    [
                        'key' => '_alivingproduct_ranking',
                        'value' => $settings['ranking'],
                        'compare' => '='
                    ]
                ];
            }

            $query = new \WP_Query($args);

            if ($query->have_posts()) {
                echo '<div class="fullwidth_alivingproduct">';
                while ($query->have_posts()) {
                    $query->the_post();
                    $pricing = get_post_meta(get_the_ID(), '_alivingproduct_pricing', true);
                    $productlink = get_post_meta(get_the_ID(), '_alivingproduct_link', true);
                    $productranking = get_post_meta(get_the_ID(), '_alivingproduct_ranking', true);
                    $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');

                    echo '<div class="alivingproduct-item">';
                    echo '<div class="alivingproduct-cover"><img src="' . esc_url($thumbnail) . '" alt=""></div>';
                    echo '<div class="productinfo">';
                    echo '<div class="wraprank">';
                    echo '<h2>' . esc_html(get_the_title()) . '</h2>';
                    echo '<div class="productranking"><h1>' . esc_html($productranking) . '</h1></div>';
                    echo '</div>';
                    echo '<p>$' . esc_html(number_format($pricing)) . '</p>';
                    echo '<h3>' . esc_html(get_the_excerpt()) . '</h3>';
                    echo '<a href="' . esc_url($productlink) . '">Buy Now<span class="material-symbols-outlined">arrow_forward</span></a>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
                wp_reset_postdata();
            } else {
                echo '<p>No products found' . (!empty($settings['product_name']) ? ' with the name "' . esc_html($settings['product_name']) . '"' : ' with the ranking of "' . esc_html($settings['ranking']) . '"') . '.</p>';
            }
        }

        protected function _content_template()
        {
        }
    }
}
