<?php namespace ColdTurkey\ProgramPage;

if (!defined('ABSPATH')) exit; // Exit if accessed directly.

// Composer autoloader
require_once PROGRAM_PAGE_PLUGIN_PATH . 'assets/vendor/autoload.php';

class ProgramPage
{
    private $dir;
    private $file;
    private $assets_dir;
    private $assets_url;
    private $template_path;
    private $token;
    private $home_url;
    private $crm;

    /**
     * Basic constructor for the Program Page class
     *
     * @param string $file
     * @param PlatformCRM $crm
     */
    public function __construct($file, PlatformCRM $crm)
    {
        global $wpdb;
        $this->dir = dirname($file);
        $this->file = $file;
        $this->assets_dir = trailingslashit($this->dir) . 'assets';
        $this->assets_url = esc_url(trailingslashit(plugins_url('/assets/', $file)));
        $this->template_path = trailingslashit($this->dir) . 'templates/';
        $this->home_url = trailingslashit(home_url());
        $this->token = 'pf_program_page';
        $this->crm = $crm;
        $this->table_name = $wpdb->base_prefix . $this->token;

        // Register 'pf_program_page' post type
        add_action('init', [$this, 'register_post_type']);

        // Use built-in templates for landing pages
        add_action('template_redirect', [$this, 'page_templates'], 20);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 900);

        // Handle form submissions
        add_action('wp_ajax_' . $this->token . '_submit_form', [$this, 'process_submission']);
        add_action('wp_ajax_nopriv_' . $this->token . '_submit_form', [$this, 'process_submission']);

        if (is_admin()) {
            add_action('admin_menu', [$this, 'meta_box_setup'], 20);
            add_action('save_post', [$this, 'meta_box_save']);
            add_filter('post_updated_messages', [$this, 'updated_messages']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_styles'], 10);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts'], 10);
            add_filter('manage_edit-' . $this->token . '_columns', [
                $this,
                'register_custom_column_headings'
            ], 10, 1);
            add_filter('enter_title_here', [$this, 'change_default_title']);
        }

        // Flush rewrite rules on plugin activation
        register_activation_hook($file, [$this, 'rewrite_flush']);
    }

    /**
     * Functions to be called when the plugin is
     * deactivated and then reactivated.
     *
     */
    public function rewrite_flush()
    {
        $this->register_post_type();
        flush_rewrite_rules();
    }

    /**
     * Registers the House Hunter custom post type
     * with WordPress, used for our pages.
     *
     */
    public function register_post_type()
    {
        $labels = [
            'name' => _x('Program Page', 'post type general name', $this->token),
            'singular_name' => _x('Program Page', 'post type singular name', $this->token),
            'add_new' => _x('Add New', $this->token, $this->token),
            'add_new_item' => sprintf(__('Add New %s', $this->token), __('Program Page', $this->token)),
            'edit_item' => sprintf(__('Edit %s', $this->token), __('Program Page', $this->token)),
            'new_item' => sprintf(__('New %s', $this->token), __('Program Page', $this->token)),
            'all_items' => sprintf(__('All %s', $this->token), __('Program Page', $this->token)),
            'view_item' => sprintf(__('View %s', $this->token), __('Program Page', $this->token)),
            'search_items' => sprintf(__('Search %a', $this->token), __('Program Page', $this->token)),
            'not_found' => sprintf(__('No %s Found', $this->token), __('Program Page', $this->token)),
            'not_found_in_trash' => sprintf(__('No %s Found In Trash', $this->token), __('Program Page', $this->token)),
            'parent_item_colon' => '',
            'menu_name' => __('Program Page', $this->token)
        ];

        $slug = __('program-page', $this->token);
        $custom_slug = get_option($this->token . '_slug');
        if ($custom_slug && strlen($custom_slug) > 0 && $custom_slug != '')
            $slug = $custom_slug;

        $args = [
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => $slug],
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'supports' => ['title', 'thumbnail'],
            'menu_position' => 5,
            'menu_icon' => 'dashicons-admin-calendar'
        ];

        register_post_type($this->token, $args);
    }

    /**
     * Register the headings for our defined custom columns
     *
     * @param array $defaults
     *
     * @return array
     */
    public function register_custom_column_headings($defaults)
    {
        $new_columns = ['permalink' => __('Link', $this->token)];
        $last_item = '';

        if (count($defaults) > 2) {
            $last_item = array_slice($defaults, -1);

            array_pop($defaults);
        }
        $defaults = array_merge($defaults, $new_columns);

        if ($last_item != '') {
            foreach ($last_item as $k => $v) {
                $defaults[$k] = $v;
                break;
            }
        }

        return $defaults;
    }

    /**
     * Define the strings that will be displayed
     * for users based on different actions they
     * perform with the plugin in the dashboard.
     *
     * @param array $messages
     *
     * @return array
     */
    public function updated_messages($messages)
    {
        global $post, $post_ID;

        $messages[$this->token] = [
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf(__('Page updated. %sView page%s.', $this->token), '<a href="' . esc_url(get_permalink($post_ID)) . '">', '</a>'),
            4 => __('Program Page updated.', $this->token),
            /* translators: %s: date and time of the revision */
            5 => isset($_GET['revision']) ? sprintf(__('Program Page restored to revision from %s.', $this->token), wp_post_revision_title((int)$_GET['revision'], false)) : false,
            6 => sprintf(__('Program Page published. %sView Page%s.', $this->token), '<a href="' . esc_url(get_permalink($post_ID)) . '">', '</a>'),
            7 => __('Program Page saved.', $this->token),
            8 => sprintf(__('Program Page submitted. %sPreview Page%s.', $this->token), '<a target="_blank" href="' . esc_url(add_query_arg('preview', 'true', get_permalink($post_ID))) . '">', '</a>'),
            9 => sprintf(__('Program Page scheduled for: %1$s. %2$sPreview Page%3$s.', $this->token), '<strong>' . date_i18n(__('M j, Y @ G:i', $this->token), strtotime($post->post_date)) . '</strong>', '<a target="_blank" href="' . esc_url(get_permalink($post_ID)) . '">', '</a>'),
            10 => sprintf(__('Program Page draft updated. %sPreview Page%s.', $this->token), '<a target="_blank" href="' . esc_url(add_query_arg('preview', 'true', get_permalink($post_ID))) . '">', '</a>'),
        ];

        return $messages;
    }

    /**
     * Build the meta box containing our custom fields
     * for our Program Page post type creator & editor.
     *
     */
    public function meta_box_setup()
    {
        add_meta_box($this->token . '-data', __('Basic Details', $this->token), [
            $this,
            'meta_box_content'
        ], $this->token, 'normal', 'high', ['type' => 'basic']);

        add_meta_box($this->token . '-marketing', __('Marketing Details', $this->token), [
            $this,
            'meta_box_content'
        ], $this->token, 'normal', 'high', ['type' => 'marketing']);

        do_action($this->token . '_meta_boxes');
    }

    /**
     * Build the custom fields that will be displayed
     * in the meta box for our Program Page post type.
     *
     * @param $post
     * @param $meta
     */
    public function meta_box_content($post, $meta)
    {
        global $post_id;
        $fields = get_post_custom($post_id);
        $field_data = $this->get_custom_fields_settings($meta['args']['type']);

        $html = '';

        if ($meta['args']['type'] == 'basic')
            $html .= '<input type="hidden" name="' . $this->token . '_nonce" id="' . $this->token . '_nonce" value="' . wp_create_nonce(plugin_basename($this->dir)) . '">';

        if (0 < count($field_data)) {
            $html .= '<table class="form-table">' . "\n";
            $html .= '<tbody>' . "\n";

            $html .= '<input id="' . $this->token . '_post_id" type="hidden" value="' . $post_id . '" />';

            foreach ($field_data as $k => $v) {
                $data = $v['default'];
                $placeholder = $v['placeholder'];
                $type = $v['type'];
                if (isset($fields[$k]) && isset($fields[$k][0]))
                    $data = $fields[$k][0];

                if ($type == 'text') {
                    $html .= '<tr valign="top"><th scope="row"><label for="' . esc_attr($k) . '">' . $v['name'] . '</label></th><td>';
                    $html .= '<input style="width:100%" name="' . esc_attr($k) . '" id="' . esc_attr($k) . '" placeholder="' . esc_attr($placeholder) . '" type="text" value="' . esc_attr($data) . '" />';
                    $html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
                    $html .= '</td><tr/>' . "\n";
                } elseif ($type == 'posts') {
                    $html .= '<tr valign="top"><th scope="row"><label for="' . esc_attr($k) . '">' . $v['name'] . '</label></th><td>';
                    $html .= '<select style="width:100%" name="' . esc_attr($k) . '" id="' . esc_attr($k) . '">';
                    $html .= '<option value="">Select a Page to Use</option>';

                    // Query posts
                    global $post;
                    $args = [
                        'posts_per_page' => 20,
                        'post_type' => $v['default'],
                        'post_status' => 'publish'
                    ];
                    $custom_posts = get_posts($args);
                    foreach ($custom_posts as $post) : setup_postdata($post);
                        $link = str_replace(home_url(), '', get_permalink());
                        $selected = '';
                        if ($link == $data)
                            $selected = 'selected';

                        $html .= '<option value="' . $link . '" ' . $selected . '>' . get_the_title() . '</option>';
                    endforeach;
                    wp_reset_postdata();

                    $html .= '</select><p class="description">' . $v['description'] . '</p>' . "\n";
                    $html .= '</td><tr/>' . "\n";
                } elseif ($type == 'select') {
                    $html .= '<tr valign="top"><th scope="row"><label for="' . esc_attr($k) . '">' . $v['name'] . '</label></th><td>';
                    $html .= '<select style="width:100%" name="' . esc_attr($k) . '" id="' . esc_attr($k) . '">';
                    foreach ($v['options'] as $option) {
                        $selected = '';
                        if ($option == $data)
                            $selected = 'selected';

                        $html .= '<option value="' . $option . '" ' . $selected . '>' . ucfirst($option) . '</option>';
                    }
                    $html .= '</select>';
                    if ($k == 'area') {
                        $area_custom_val = '';
                        if (isset($fields['area_custom'])) {
                            $area_custom_val = 'value="' . esc_attr($fields['area_custom'][0]) . '"';
                        }
                        $html .= '<input type="text" name="area_custom" id="area_custom" ' . $area_custom_val . ' placeholder="Your Custom Area" style="width:100%;display:none;">';
                    }
                    $html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
                    $html .= '</td><tr/>' . "\n";
                } elseif ($type == 'url') {
                    $html .= '<tr valign="top"><th scope="row"><label for="' . esc_attr($k) . '">' . $v['name'] . '</label></th><td><input type="button" class="button" id="upload_media_file_button" value="' . __('Upload Image', $this->token) . '" data-uploader_title="Choose an image" data-uploader_button_text="Insert image file" /><input name="' . esc_attr($k) . '" type="text" id="upload_media_file" class="regular-text" value="' . esc_attr($data) . '" />' . "\n";
                    $html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
                    $html .= '</td><tr/>' . "\n";
                } else {
                    $default_color = '';
                    $html .= '<tr valign="top"><th scope="row"><label for="' . esc_attr($k) . '">' . $v['name'] . '</label></th><td>';
                    $html .= '<input name="' . esc_attr($k) . '" id="primary_color" class="pf-color"  type="text" value="' . esc_attr($data) . '"' . $default_color . ' />';
                    $html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
                    $html .= '</td><tr/>' . "\n";
                }

                $html .= '</td><tr/>' . "\n";
            }

            $html .= '</tbody>' . "\n";
            $html .= '</table>' . "\n";
        }

        echo $html;
    }

    /**
     * Save the data entered by the user using
     * the custom fields for our Program Page post type.
     *
     * @param integer $post_id
     *
     * @return int
     */
    public function meta_box_save($post_id)
    {
        // Verify
        if ((get_post_type() != $this->token) || !wp_verify_nonce($_POST[$this->token . '_nonce'], plugin_basename($this->dir)))
            return $post_id;

        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id))
                return $post_id;
        } else {
            if (!current_user_can('edit_post', $post_id))
                return $post_id;
        }

        $field_data = $this->get_custom_fields_settings('all');
        $fields = array_keys($field_data);

        foreach ($fields as $f) {

            if (isset($_POST[$f]))
                ${$f} = strip_tags(trim($_POST[$f]));

            // Escape the URLs.
            if ('url' == $field_data[$f]['type'])
                ${$f} = esc_url(${$f});

            if (${$f} == '') {
                delete_post_meta($post_id, $f, get_post_meta($post_id, $f, true));
            } else {
                update_post_meta($post_id, $f, ${$f});
            }
        }
    }

    /**
     * Register the stylesheets that will be
     * used for our scripts in the dashboard.
     *
     */
    public function enqueue_admin_styles()
    {
        wp_enqueue_style('wp-color-picker');
    }

    /**
     * Register the Javascript files that will be
     * used for our scripts in the dashboard.
     */
    public function enqueue_admin_scripts()
    {
        // Admin JS
        wp_register_script($this->token . '-admin', esc_url($this->assets_url . 'js/admin.js'), [
            'jquery',
            'wp-color-picker'
        ]);
        wp_enqueue_script($this->token . '-admin');
    }

    /**
     * Register the Javascript files that will be
     * used for our templates.
     */
    public function enqueue_scripts()
    {
        if (is_singular($this->token)) {
            wp_register_style($this->token, esc_url($this->assets_url . 'css/programpage.css'), [], PROGRAM_PAGE_PLUGIN_VERSION);
            wp_register_style('animate', esc_url($this->assets_url . 'css/animate.css'), [], PROGRAM_PAGE_PLUGIN_VERSION);
            wp_register_style('roboto', 'https://fonts.googleapis.com/css?family=Roboto:400,400italic,500,500italic,700,700italic,900,900italic,300italic,300');
            wp_register_style('robo-slab', 'https://fonts.googleapis.com/css?family=Roboto+Slab:400,700,300,100');
            wp_enqueue_style($this->token);
            wp_enqueue_style('animate');
            wp_enqueue_style('roboto');
            wp_enqueue_style('roboto-slab');

            wp_register_script($this->token . '-js', esc_url($this->assets_url . 'js/scripts.js'), [
                'jquery'
            ], PROGRAM_PAGE_PLUGIN_VERSION);
            wp_enqueue_script($this->token . '-js');

            $localize = [
                'ajaxurl' => admin_url('admin-ajax.php'),
            ];
            wp_localize_script($this->token . '-js', 'ProgramPage', $localize);
        }

    }

    /**
     * Define the custom fields that will
     * be displayed and used for our
     * Program Page post type.
     *
     * @param $meta_box
     *
     * @return mixed
     */
    public function get_custom_fields_settings($meta_box)
    {
        $fields = [];

        if ($meta_box == 'basic' || $meta_box == 'all') {
            $fields['headline'] = [
                'name' => __('Headline', $this->token),
                'description' => __('The headline for your page.', $this->token),
                'placeholder' => __('Listing Calculator', $this->token),
                'type' => 'text',
                'default' => 'Listing Calculator',
                'section' => 'info'
            ];

            $fields['subheadline'] = [
                'name' => __('Sub-Headline', $this->token),
                'description' => __('The sub-headline for your page.', $this->token),
                'placeholder' => __('How long will it take to sell my home?', $this->token),
                'type' => 'text',
                'default' => 'How long will it take to sell my home?',
                'section' => 'info'
            ];

            $fields['call_to_action'] = [
                'name' => __('Your Call To Action', $this->token),
                'description' => __('The call to action for users to give you their contact information.', $this->token),
                'placeholder' => __('Get My Results!', $this->token),
                'type' => 'text',
                'default' => 'Get My Results!',
                'section' => 'info'
            ];

            $fields['modal_title'] = [
                'name' => __('Modal Title', $this->token),
                'description' => __('The title for the modal shown after CTA button is clicked.', $this->token),
                'placeholder' => __('Get My Results!', $this->token),
                'type' => 'text',
                'default' => 'Where should we send your results?',
                'section' => 'info'
            ];

            $fields['modal_subtitle'] = [
                'name' => __('Modal Subtitle', $this->token),
                'description' => __('The subtitle for the modal shown after CTA button is clicked.', $this->token),
                'placeholder' => '',
                'type' => 'text',
                'default' => 'How long will it take to sell a <span id="sq_ft-answer"></span> square foot <span id="type-answer"></span> (<span id="num_beds-answer"></span> bedrooms, <span id="num_baths-answer"></span> bathrooms) located in <span id="location-answer"></span>.',
                'section' => 'info'
            ];

            $fields['modal_button'] = [
                'name' => __('Modal Button', $this->token),
                'description' => __('The submit button text for the modal shown after CTA button is clicked.', $this->token),
                'placeholder' => __('See My Results', $this->token),
                'type' => 'text',
                'default' => 'See My Results',
                'section' => 'info'
            ];

            $fields['show_sqft'] = [
                'name' => __('Show Square Footage Field', $this->token),
                'description' => __('If set to no, the square footage field will not be shown', $this->token),
                'placeholder' => '',
                'type' => 'select',
                'default' => 'yes',
                'options' => ['no', 'yes'],
                'section' => 'info'
            ];

            $fields['city_placeholder'] = [
                'name' => __('City Input Placeholder', $this->token),
                'description' => __('The placeholder to be shown in the City field before a user enters any text.', $this->token),
                'placeholder' => __('Chicago', $this->token),
                'type' => 'text',
                'default' => 'Chicago',
                'section' => 'info'
            ];

            $fields['home_valuator'] = [
                'name' => __('Link To Home Valuator', $this->token),
                'description' => __('The last step of the funnel allows you to link the user to your Home Valuator. Enter the link for the funnel here.', $this->token),
                'placeholder' => '',
                'type' => 'posts',
                'default' => 'pf_valuator',
                'section' => 'info'
            ];

            $fields['legal_broker'] = [
                'name' => __('Your Legal Broker', $this->token),
                'description' => __('This will be displayed on the bottom of the page.', $this->token),
                'placeholder' => '',
                'type' => 'text',
                'default' => '',
                'section' => 'info'
            ];

            $fields['name'] = [
                'name' => __('Your Name', $this->token),
                'description' => __('Your name for introducing you at the end of the funnel..', $this->token),
                'placeholder' => '',
                'type' => 'text',
                'default' => '',
                'section' => 'info'
            ];

            $fields['photo'] = [
                'name' => __('Your Photo', $this->token),
                'description' => __('A photo of you for the thank you page of the funnel.', $this->token),
                'placeholder' => '',
                'type' => 'url',
                'default' => '',
                'section' => 'info'
            ];

            $fields['primary_color'] = [
                'name' => __('Primary Color', $this->token),
                'description' => __('Change the primary color of the funnel page.', $this->token),
                'placeholder' => '',
                'type' => 'color',
                'default' => '',
                'section' => 'info'
            ];

            $fields['hover_color'] = [
                'name' => __('Hover Color', $this->token),
                'description' => __('Change the button hover color of the funnel page.', $this->token),
                'placeholder' => '',
                'type' => 'color',
                'default' => '',
                'section' => 'info'
            ];
        }

        if ($meta_box == 'marketing' || $meta_box == 'all') {
            // Step before opt-in (after clicking button, before opt-in)
            $fields['retargeting'] = [
                'name' => __('Facebook Pixel - Retargeting (optional)', $this->token),
                'description' => __('Facebook Pixel to allow retargeting of people that view this page.', $this->token),
                'placeholder' => __('Ex: 4123423454', $this->token),
                'type' => 'text',
                'default' => '',
                'section' => 'info'
            ];

            // After opt-in
            $fields['conversion'] = [
                'name' => __('Facebook Pixel - Conversion (optional)', $this->token),
                'description' => __('Facebook Pixel to allow conversion tracking of people that submit this page.', $this->token),
                'placeholder' => __('Ex: 170432123454', $this->token),
                'type' => 'text',
                'default' => '',
                'section' => 'info'
            ];
        }

        return apply_filters($this->token . '_meta_fields', $fields);
    }

    /**
     * Define the custom templates that
     * are used for our plugin.
     *
     */
    public function page_templates()
    {
        // Single house hunter page template
        if (is_single() && get_post_type() == $this->token) {
            if (!defined('PLATFORM_FUNNEL'))
                define('PLATFORM_FUNNEL', 'PROGRAM_PAGE');

            include($this->template_path . 'single-page.php');
            exit;
        }
    }

    /**
     * Get the optional media file selected for
     * a defined Program Page funnel.
     *
     * @param integer $pageID
     *
     * @return bool|string
     */
    public function get_media_file($pageID)
    {
        if ($pageID) {
            $file = get_post_meta($pageID, 'media_file', true);

            if (preg_match('/(\.jpg|\.png|\.bmp|\.gif)$/', $file))
                return '<img src="' . $file . '" style="margin-left:auto;margin-right:auto;margin-bottom:0px;display:block;" class="img-responsive img-thumbnail">';
        }

        return false;
    }

    /**
     * Process the form submission from the user.
     *
     */
    public function process_submission()
    {
        if (isset($_POST[$this->token . '_nonce']) && wp_verify_nonce($_POST[$this->token . '_nonce'], $this->token . '_submit_form')) {
            global $wpdb;
            $sq_ft = 0;
            $blog_id = get_current_blog_id();
            $page_id = sanitize_text_field($_POST['page_id']);

            echo json_encode(['status' => 'success']);
            die();
        }
    }

    /**
     * Change the post title placeholder text
     * for the custom post editor.
     *
     * @param $title
     *
     * @return string
     */
    public function change_default_title($title)
    {
        $screen = get_current_screen();

        if ($this->token == $screen->post_type) {
            $title = 'Enter a title for your Program Page';
        }

        return $title;
    }
}
