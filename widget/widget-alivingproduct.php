<?php
if (class_exists('Elementor\Widget_Base')) {
    class Elementor_AlivingProduct_Widget extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'alivingproduct_widget';
        }

        public function get_title()
        {
            return __('Aliving Product', 'plugin-name');
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
                    'label' => __('Content', 'plugin-name'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'ranking',
                [
                    'label' => __('Ranking', 'plugin-name'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Best Overall', 'plugin-name'),
                ]
            );

            $this->add_control(
                'product_name',
                [
                    'label' => __('Product Name', 'plugin-name'),
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

            echo '<div class="alivingproduct-list">';

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $pricing = get_post_meta(get_the_ID(), '_alivingproduct_pricing', true);
                    $productlink = get_post_meta(get_the_ID(), '_alivingproduct_link', true);
                    $productranking = get_post_meta(get_the_ID(), '_alivingproduct_ranking', true);
                    $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');

                    echo '<div class="alivingproduct-item">';
                    echo '<div class="productranking"><h1>' . esc_html($productranking) . '</h1></div>';
                    echo '<div class="alivingproduct-cover"><img src="' . esc_url($thumbnail) . '" alt=""></div>';
                    echo '<div class="productinfo">';
                    echo '<h2>' . esc_html(get_the_title()) . '</h2>';
                    echo '<p>$' . esc_html(number_format($pricing)) . '</p>';
                    echo '<h3>' . esc_html(get_the_excerpt()) . '</h3>';
                    echo '<a href="' . esc_url($productlink) . '" target:"_blank">Buy Now<span class="material-symbols-outlined">arrow_forward</span></a>';
                    echo '</div>';
                    echo '</div>';
                }
                wp_reset_postdata();
            } else {
                echo '<p>No products found' . (!empty($settings['product_name']) ? ' with the name "' . esc_html($settings['product_name']) . '"' : ' with the ranking of "' . esc_html($settings['ranking']) . '"') . '.</p>';
            }

            echo '</div>';
        }

        protected function _content_template()
        {
        }
    }

    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor_AlivingProduct_Widget());
}
