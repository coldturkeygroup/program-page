<?php

namespace ColdTurkey\ProgramPage;

if (!defined('ABSPATH')) exit; // Exit if accessed directly.

class ProgramPage_Admin
{
    private $dir;
    private $file;
    private $assets_dir;
    private $assets_url;
    private $home_url;
    private $token;

    /**
     * Basic constructor for the Days on Market Admin class
     *
     * @param string $file
     */
    public function __construct($file)
    {
        $this->dir = dirname($file);
        $this->file = $file;
        $this->assets_dir = trailingslashit($this->dir) . 'assets';
        $this->assets_url = esc_url(trailingslashit(plugins_url('/assets/', $file)));
        $this->home_url = trailingslashit(home_url());
        $this->token = 'pf_program_page';

        // Register house hunter settings
        add_action('admin_init', [$this, 'register_settings']);

        // Add settings page to menu
        add_action('admin_menu', [$this, 'add_menu_item']);

        // Add settings link to plugins page
        add_filter('plugin_action_links_' . plugin_basename($this->file), [$this, 'add_settings_link']);

        // Load scripts for posts
        add_action('admin_print_scripts-post-new.php', [$this, 'enqueue_admin_styles'], 10);
        add_action('admin_print_scripts-post.php', [$this, 'enqueue_admin_styles'], 10);
        add_action('admin_print_scripts-post-new.php', [$this, 'enqueue_admin_scripts'], 10);
        add_action('admin_print_scripts-post.php', [$this, 'enqueue_admin_scripts'], 10);

        // Display notices in the WP admin
        add_action('admin_notices', [$this, 'admin_notices'], 10);
    }

    /**
     * Add the menu links for the plugin
     *
     */
    public function add_menu_item()
    {
        add_submenu_page('edit.php?post_type=' . $this->token, 'Program Page Settings', 'Settings', 'manage_options', $this->token . '_settings', [
            $this,
            'settings_page'
        ]);
    }

    /**
     * Add the link to our Settings page
     * from the plugins page
     *
     * @param array $links
     *
     * @return array
     */
    public function add_settings_link($links)
    {
        $settings_link = '<a href="edit.php?post_type=' . $this->token . '&page=' . $this->token . '_settings">Settings</a>';
        array_push($links, $settings_link);

        return $links;
    }

    /**
     * Register the stylesheets that will be
     * used for our scripts in the dashboard.
     *
     */
    public function enqueue_admin_styles()
    {
        global $post_type;
        if ($post_type == $this->token) {
            wp_enqueue_style('wp-color-picker');
        }
    }

    /**
     * Register the Javascript files used by
     * the plugin in the WordPress dashboard
     *
     */
    public function enqueue_admin_scripts()
    {
        global $post_type;
        if ($post_type == $this->token) {
            wp_register_script($this->token . '-admin', esc_url($this->assets_url . 'js/admin.js'), [
                'jquery',
                'wp-color-picker'
            ]);
            wp_enqueue_script($this->token . '-admin');
        }
    }

    /**
     * Define different notices that can be
     * displayed to the user in the dashboard
     *
     */
    public function admin_notices()
    {
        global $wp_version;

        // Version notice
        if ($wp_version < 3.5) {
            ?>
            <div class="error">
                <p><?php printf(__('%1$sProgram Page%2$s requires WordPress 3.5 or above in order to function correctly. You are running v%3$s - please update now.', $this->token), '<strong>', '</strong>', $wp_version); ?></p>
            </div>
            <?php
        }
    }

    /**
     * Register the different settings available
     * to customize the plugin.
     *
     */
    public function register_settings()
    {
        // Add settings section
        add_settings_section('customize', __('Basic Settings', $this->token), [
            $this,
            'main_settings'
        ], $this->token);

        // Add settings fields
        add_settings_field($this->token . '_slug', __('URL slug for Program Page funnels:', $this->token), [
            $this,
            'slug_field'
        ], $this->token, 'customize');
        add_settings_field($this->token . '_frontdesk_key', __('Platform CRM API key:', $this->token), [
            $this,
            'frontdesk_key_field'
        ], $this->token, 'customize');

        // Register settings fields
        register_setting($this->token, $this->token . '_slug', [$this, 'validate_slug']);
        register_setting($this->token, $this->token . '_frontdesk_key');

        // Allow plugins to add more settings fields
        do_action($this->token . '_settings_fields');

    }

    /**
     * Define the main description string
     * for the Settings page.
     *
     */
    public function main_settings()
    {
        echo '<p>' . __('These are a few simple settings for setting up your Program Page funnels.', $this->token) . '</p>';
    }

    /**
     * Create the slug field for the Settings page.
     * The slug field allows users to choose which
     * subdirectory their house hunter pages are nested in.
     *
     */
    public function slug_field()
    {
        $option = get_option($this->token . '_slug');

        $data = 'program-page';
        if ($option && strlen($option) > 0 && $option != '')
            $data = $option;

        echo '<input id="slug" type="text" name="' . $this->token . '_slug" value="' . $data . '"/>
				<label for="slug"><span class="description">' . sprintf(__('Provide a custom URL slug for the Program Page funnels.', $this->token)) . '</span></label>';
    }

    /**
     * Validates that a slug has been defined,
     * and formats it properly as a URL
     *
     * @param string $slug
     *
     * @return string
     */
    public function validate_slug($slug)
    {
        if ($slug && strlen($slug) > 0 && $slug != '')
            $slug = urlencode(strtolower(str_replace(' ', '-', $slug)));

        return $slug;
    }

    /**
     * Create the FrontDesk key field for the Settings page.
     * The FrontDesk key field allows users to define their
     * API key to be used in all FrontDesk requests.
     */
    public function frontdesk_key_field()
    {
        $option = get_option($this->token . '_frontdesk_key');

        $data = get_option('pf_frontdesk_key', '');
        if ($option && strlen($option) > 0 && $option != '')
            $data = $option;

        echo '<input id="frontdesk_key" type="text" name="' . $this->token . '_frontdesk_key" value="' . $data . '"/>
					<label for="frontdesk_key"><span class="description">' . __('Enter your API key generated by Platform CRM. To access your API key, visit <a href="https://platformcrm.com/account/api" target="_blank">https://platformcrm.com/account/api</a>.', $this->token) . '</span></label>';

    }

    /**
     * Create the actual HTML structure
     * for the Settings page for the plugin
     *
     */
    public function settings_page()
    {
        if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == true) {
            flush_rewrite_rules();
            echo '<div class="updated"><p>Successfully updated.</p></div>';
        }

        echo '<div class="wrap" id="' . $this->token . '_settings">
					<h1>' . __('Program Page Settings', $this->token) . '</h1>
					<form method="post" action="options.php" enctype="multipart/form-data">
						<div class="clear"></div>';

        settings_fields($this->token);
        do_settings_sections($this->token);

        echo '<p class="submit">
							<input name="Submit" type="submit" class="button-primary" value="' . esc_attr(__('Save Settings', $this->token)) . '" />
						</p>
					</form>
			  </div>';
    }
}