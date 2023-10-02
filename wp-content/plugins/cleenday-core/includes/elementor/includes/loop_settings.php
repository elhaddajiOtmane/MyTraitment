<?php
namespace WglAddons\Includes;

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;

if (!class_exists('Wgl_Loop_Settings')) {
    /**
     * WGL Elementor Loop Settings
     *
     *
     * @category Class
     * @package cleenday-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class Wgl_Loop_Settings
    {
        private static $instance;

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public static function buildQuery($query)
        {
            return (new Wgl_Query_Builder($query))->build();
        }

        /**
         * Cache Query
         */
        public static function cache_query($args = [])
        {
            $cache_key = http_build_query($args);
            $custom_query = wp_cache_get($cache_key, 'cleenday_theme_cache');
            if (false === $custom_query) {
                $custom_query = new \WP_Query($args);
                if (!is_wp_error($custom_query) && $custom_query->have_posts()) {
                    wp_cache_set($cache_key, $custom_query, 'cleenday_theme_cache');
                }
            }

            return $custom_query;
        }

        public static function init($self, $atts = [])
        {
            if (!$self) {
                return;
            }

            $self->start_controls_section(
                'query_section',
                [
                    'label' => esc_html__('Query', 'cleenday-core'),
                    'tab' => Controls_Manager::TAB_SETTINGS
                ]
            );

            $self->add_control(
                'number_of_posts',
                [
                    'label' => esc_html__('Posts amount', 'cleenday-core'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'default' => 12,
                ]
            );

            $self->add_control(
                'order_by',
                [
                    'label' => esc_html__('Order by', 'cleenday-core'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'date' => esc_html__('Date', 'cleenday-core'),
                        'title' => esc_html__('Title', 'cleenday-core'),
                        'author' => esc_html__('Author', 'cleenday-core'),
                        'modified' => esc_html__('Modified', 'cleenday-core'),
                        'rand' => esc_html__('Random', 'cleenday-core'),
                        'comment_count' => esc_html__('Comments', 'cleenday-core'),
                        'menu_order' => esc_html__('Menu Order', 'cleenday-core'),
                    ],
                    'default' => 'date',
                ]
            );

            $self->add_control(
                'order',
                [
                    'label' => esc_html__('Order', 'cleenday-core'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'DESC' => esc_html__('Descending', 'cleenday-core'),
                        'ASC' => esc_html__('Ascending', 'cleenday-core'),
                    ],
                    'default' => 'DESC',
                ]
            );

            if (!isset($atts['hide_cats'])) {
                $self->add_control(
                    'categories',
                    [
                        'label' => esc_html__('Categories', 'cleenday-core'),
                        'type' => Controls_Manager::SELECT2,
                        'description' => esc_html__('Filter by category slug', 'cleenday-core'),
                        'separator' => 'before',
                        'label_block' => true,
                        'multiple' => true,
                        'options' => self::categories_suggester(),
                    ]
                );

                $self->add_control(
                    'exclude_categories',
                    [
                        'label' => esc_html__('Exclude Selected Categories', 'cleenday-core'),
                        'type' => Controls_Manager::SWITCHER,
                        'description' => esc_html__('Leave empty for all', 'cleenday-core'),
                    ]
                );
            }

            if (!isset($atts['hide_tags'])) {
                $self->add_control(
                    'tags',
                    [
                        'label' => esc_html__('Tags', 'cleenday-core'),
                        'type' => Controls_Manager::SELECT2,
                        'description' => esc_html__('Filter by tags slug', 'cleenday-core'),
                        'separator' => 'before',
                        'multiple' => true,
                        'label_block' => true,
                        'options' => self::tags_suggester(),
                    ]
                );

                $self->add_control(
                    'exclude_tags',
                    [
                        'label' => esc_html__('Exclude Selected Tags', 'cleenday-core'),
                        'type' => Controls_Manager::SWITCHER,
                        'description' => esc_html__('Leave empty for all', 'cleenday-core'),
                    ]
                );
            }

            $self->add_control(
                'taxonomies',
                [
                    'label' => esc_html__('Taxonomies', 'cleenday-core'),
                    'type' => Controls_Manager::SELECT2,
                    'description' => esc_html__('Filter by custom taxonomies.', 'cleenday-core'),
                    'separator' => 'before',
                    'multiple' => true,
                    'label_block' => true,
                    'options' => self::taxonomies_suggester($atts),
                ]
            );

            $self->add_control(
                'exclude_taxonomies',
                [
                    'label' => esc_html__('Exclude Selected Taxonomies', 'cleenday-core'),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );

            if (!isset($atts['hide_individual_posts'])) {
                $self->add_control(
                    'hr_posts',
                    ['type' => Controls_Manager::DIVIDER]
                );

                $self->add_control(
                    'by_posts',
                    [
                        'label' => esc_html__('Individual Post/Page/Post Types', 'cleenday-core'),
                        'type' => Controls_Manager::SELECT2,
                        'description' => esc_html__('Filter by individual posts, pages or custom post type', 'cleenday-core'),
                        'multiple' => true,
                        'label_block' => true,
                        'options' => self::by_posts_suggester($atts),
                    ]
                );

                $self->add_control(
                    'exclude_any',
                    [
                        'label' => esc_html__('Exclude Selected', 'cleenday-core'),
                        'type' => Controls_Manager::SWITCHER,
                        'description' => esc_html__('Leave empty for all', 'cleenday-core'),
                    ]
                );
            }

            $self->add_control(
                'author',
                [
                    'label' => esc_html__('Author', 'cleenday-core'),
                    'type' => Controls_Manager::SELECT2,
                    'description' => esc_html__('Filter by author names', 'cleenday-core'),
                    'separator' => 'before',
                    'multiple' => true,
                    'label_block' => true,
                    'options' => self::by_author_suggester(),
                ]
            );

            $self->add_control(
                'exclude_author',
                [
                    'label' => esc_html__('Exclude Selected Authors', 'cleenday-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'description' => esc_html__('Leave empty for all', 'cleenday-core'),
                ]
            );

            $self->end_controls_section();
        }

        public static function get_term_parents_list($term_id, $taxonomy, $args = [])
        {
            $term = get_term($term_id, $taxonomy);

            if (
                !$term
                || is_wp_error($term)
            ) {
                return '';
            }

            $term_id = $term->term_id;

            $defaults = [
                'format' => 'name',
                'separator' => '/',
                'inclusive' => true,
            ];

            $args = wp_parse_args($args, $defaults);

            foreach (['inclusive'] as $bool) {
                $args[$bool] = wp_validate_boolean($args[$bool]);
            }

            $parents = get_ancestors($term_id, $taxonomy, 'taxonomy');

            if ($args['inclusive']) {
                array_unshift($parents, $term_id);
            }

            $a = count($parents) - 1;
            $list = '';
            foreach (array_reverse($parents) as $index => $term_id) {
                $parent = get_term($term_id, $taxonomy);
                $temp_sep = $args['separator'];
                $lastElement = reset($parents);

                if ($index == $a - 1) {
                    $temp_sep = '';
                }

                if ($term_id != $lastElement) {
                    $name = $parent->name;
                    $list .= $name . $temp_sep;
                }
            }

            return $list ?? '';
        }

        public static function categories_suggester()
        {
            foreach (get_categories() as $cat) {
                $parent = self::get_term_parents_list($cat->cat_ID, 'category');

                $content[(string) $cat->slug] = $cat->cat_name
                    . (!empty($parent) ? esc_html__(' (Parent categories: (', 'cleenday-core') . $parent . '))' : '');
            }

            return $content ?? [];
        }

        public static function tags_suggester()
        {
            foreach (get_tags() as $tag) {
                $content[(string) $tag->slug] = $tag->name;
            }

            return $content ?? [];
        }

        public static function getTaxonomies()
        {
            $taxonomy_exclude = (array) apply_filters('get_categories_taxonomy', 'category');
            $taxonomy_exclude[] = 'post_tag';
            $taxonomies = [];

            foreach (get_taxonomies() as $taxonomy) {
                if (!in_array($taxonomy, $taxonomy_exclude)) {
                    $taxonomies[] = $taxonomy;
                }
            }

            return $taxonomies;
        }

        public static function taxonomies_suggester($atts)
        {
            $taxonomies = self::getTaxonomies();

            if (isset($atts['post_type'])) {
                $type = $atts['post_type'];

                $taxonomies = array_reduce($taxonomies, function ($arr, $item) use ($type) {
                    if (strpos($item, $type) !== false) {
                        $arr[] = $item;
                    }

                    return $arr;
                });
            }

            $terms_arr = get_terms(['taxonomy' => $taxonomies]);

            if ($terms_arr) foreach ($terms_arr as $tag) {
                $args = [
                    'separator' => ' > ',
                    'format' => 'name',
                ];
                $parent = self::get_term_parents_list($tag->term_id, $tag->taxonomy, $args);

                $content[$tag->taxonomy . ":" . $tag->slug] = $tag->name
                    . ' (' . $tag->taxonomy . ')'
                    . (!empty($parent) ? esc_html__(' (Parent categories: (', 'cleenday-core') . $parent . '))' : '');
            }

            return $content ?? [];
        }

        public static function by_posts_suggester($atts)
        {
            $arguments['post_type'] = $atts['post_type'] ?? 'any';
            $arguments['numberposts'] = -1;

            foreach (get_posts($arguments) as $post) {
                $content[$post->post_name] = $post->post_title;
            }

            return $content ?? [];
        }

        public static function by_author_suggester()
        {
            foreach (get_users() as $user) {
                $content[(string) $user->ID] = (string) $user->data->user_nicename;
            }

            return $content ?? [];
        }
    }
    new Wgl_Loop_Settings();
}

if (!class_exists('Wgl_Query_Builder')) {
    /**
     * WGL Query Builder
     *
     *
     * @category Class
     * @package cleenday-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class Wgl_Query_Builder
    {
        /**
         * @since   1.0.0
         * @var     array
         */
        private $args = [
            'post_status' => 'publish', // show only published posts #1098
        ];

        private $data_attr = [];
        private static $instance = null;

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        function __construct($data)
        {
            // Include Item
            foreach ($data as $key => $value) {
                $method = 'parse_' . $key;
                if (
                    stripos($key, 'exclude_') === false
                    && method_exists($this, $method)
                    && !empty($value)
                ) {
                    $this->$method($value);
                }
            }

            // Exclude Item
            foreach ($data as $k => $v) {
                $method = 'parse_' . $k;
                if (
                    stripos($k, 'exclude_') !== false
                    && method_exists($this, $method)
                    && !empty($v)
                ) {
                    $this->$method($v);
                }
            }
        }

        /**
         * Pages count
         */
        protected function parse_number_of_posts($value)
        {
            $this->args['posts_per_page'] = 'All' === $value ? -1 : (int) $value;
        }

        /**
         * Sorting field
         */
        protected function parse_order_by($value)
        {
            $this->args['orderby'] = $value;
        }

        /**
         * Sorting order
         */
        protected function parse_order($value)
        {
            $this->args['order'] = $value;
        }

        /**
         * By author
         */
        protected function parse_author($value)
        {
            $value = implode(',', $value);
            $this->data_attr['author_id'] = $value;
            $this->args['author'] = $value;
        }

        /**
         * Exclude author
         */
        protected function parse_exclude_author($value)
        {
            if (!isset($this->data_attr['author_id'])) {
                return;
            }
            if (isset($this->args['author'])) {
                unset($this->args['author']);
            }
            $author_id = [];
            $author_id[] = $this->data_attr['author_id'];
            $this->args['author__not_in'] = $author_id;
        }

        /**
         * By categories
         */
        protected function parse_categories($value)
        {
            if (empty($value)) {
                return;
            }

            $this->args['category_name'] = implode(', ', (array) $value);
        }

        /**
         * Exclude categories
         */
        protected function parse_exclude_categories($value)
        {
            if (!isset($this->args['category_name'])) {
                return;
            }

            $list = explode(', ', $this->args['category_name']);

            $id_list = [];
            foreach ($list as $key => $value) {
                $idObj = get_category_by_slug($value);
                $id_list[] = '-' . $idObj->term_id;
            }
            $id_list = implode(',', $id_list);
            $this->args['cat'] = $id_list;
            unset($this->args['category_name']);
        }

        /**
         * Get Taxonomies
         */
        private function get_tax($value)
        {
            $terms = (array) $value;
            $this->args['tax_query'] = ['relation' => 'AND'];

            $item = $id_list = [];

            $taxonomies = get_terms(Wgl_Loop_Settings::getTaxonomies());
            foreach ($terms as $key => $value) {
                $item_t = explode(":", $value);
                if (isset($item_t[1])) {
                    $idObj = get_term_by('slug', $item_t[1], $item_t[0]);
                    $id_list[] = $idObj->term_id;
                }
            }

            $terms = get_terms(
                Wgl_Loop_Settings::getTaxonomies(),
                ['include' => array_map('abs', $id_list)]
            );
            foreach ($terms as $t) {
                $item[$t->taxonomy][] = $t->slug;
            }

            return $item;
        }

        /**
         * By taxonomies
         */
        protected function parse_taxonomies($value)
        {
            if (empty($value)) {
                return;
            }

            $this->data_attr['taxonomies'] = $value;

            $item = $this->get_tax($value);

            foreach ($item as $taxonomy => $terms) {
                $this->args['tax_query'][] = [
                    'field' => 'slug',
                    'taxonomy' => $taxonomy,
                    'terms' => $terms,
                    'operator' => 'IN',
                ];
            }
        }

        /**
         * Exclude tax slugs
         */
        protected function parse_exclude_taxonomies()
        {
            if (!isset($this->data_attr['taxonomies'])) {
                return;
            }

            if (isset($this->args['tax_query'])) {
                unset($this->args['tax_query']);
            }

            $value = $this->data_attr['taxonomies'];

            $item = $this->get_tax($value);

            foreach ($item as $taxonomy => $terms) {
                $this->args['tax_query'][] = [
                    'field' => 'slug',
                    'taxonomy' => $taxonomy,
                    'terms' => $terms,
                    'operator' => 'NOT IN',
                ];
            }
        }

        /**
         * By tags slugs
         */
        protected function parse_tags($value)
        {
            if (empty($value)) {
                return;
            }

            $this->data_attr['tags'] = $value;
            $in = $not_in = [];
            $tags_slugs = $value;
            foreach ($tags_slugs as $tag) {
                $in[] = $tag;
            }
            $this->args['tag_slug__in'] = $in;
        }

        /**
         * Exclude tags slugs
         *
         * @since 1.0
         *
         * @param $value
         */
        protected function parse_exclude_tags($value)
        {
            if (!isset($this->data_attr['tags'])) {
                return;
            }

            $list = $this->data_attr['tags'];
            $id_list = [];
            foreach ($list as $value) {
                $idObj = get_term_by('slug', $value, 'post_tag');
                $id_list[] = (int) $idObj->term_id;
            }

            $this->args['tag__not_in'] = $id_list;

            unset($this->args['tag_slug__in']);
        }

        /**
         * By posts slugs
         */
        protected function parse_by_posts($value)
        {
            $in = [];
            $this->data_attr['posts_in'] = $value;
            $slugs = $value;
            if (!empty($slugs)) {
                foreach ($slugs as $slug) {
                    $in[] = $slug;
                }
                $this->args['post_name__in'] = $in;
            }
        }

        /**
         * Exclude posts slugs
         */
        protected function parse_exclude_any($value)
        {
            if (!isset($this->data_attr['posts_in'])) {
                return;
            }

            if (isset($this->args['post_name__in'])) {
                unset($this->args['post_name__in']);
            }

            $options = [];
            $value = $this->data_attr['posts_in'];

            $list = new \WP_Query([
                'post_type' => 'any',
                'post_name__in' => $value,
            ]);
            foreach ($list->posts as $obj) {
                $options[] = $obj->ID;
            }
            $this->args['post__not_in'] = $options;
        }

        public function excludeId($id)
        {
            if (!isset($this->args['post__not_in'])) {
                $this->args['post__not_in'] = [];
            }
            if (is_array($id)) {
                $this->args['post__not_in'] = array_merge($this->args['post__not_in'], $id);
            } else {
                $this->args['post__not_in'][] = $id;
            }
        }

        public function filter_where($where = '')
        {
            return $where . ' AND ' . $this->args['where'];
        }

        public function build()
        {
            if (isset($this->args['where'])) {
                add_filter('posts_where', [$this, 'filter_where']);
            }

            $output = [$this->args, new \WP_Query($this->args)];

            if (isset($this->args['where'])) {
                remove_filter('posts_where', [$this, 'filter_where']);
            }

            return $output;
        }
    }
}
