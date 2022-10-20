<?php

/**
 * Custom Post Type Helper
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 *
 * # Usage
 *
 * ## Create Custom Post Type
 *
 * ` $project = new Helper_Custom_Post_Type( 'Project' );
 *
 * ## Create Meta Boxes
 *
 * ` $project->add_meta_box(
 * ` 'Client Info',
 * ` array(
 * `   'Client Name'     => 'text',
 * `   'Description'     => 'textarea',
 * `   'New Client'      => 'checkbox',
 * `   'Client Logo'     => 'file',
 * `   'Client Industry' => array( 'select', array( 'entertainment', 'government', 'education' ) )
 * ` )
 * `);
 *
 */
class GROWP_Custom_Post_Type
{
    public $post_type_name;

    public $post_type_args;

    public $post_type_labels;

    /**
     * construct
     *
     * @param string $name
     * @param array $args
     * @param array $labels
     */

    public function __construct($name, $args = array(), $labels = array())
    {
        // Set some important variables
        $this->post_type_name   = strtolower(str_replace(' ', '_', $name));
        $this->post_type_args   = $args;
        $this->post_type_labels = $labels;

        // Add action to register the post type, if the post type doesnt exist
        if ( ! post_type_exists($this->post_type_name)) {
            add_action('init', array(&$this, 'register_post_type'));
        }

        // Listen for the save post hook
        $this->save();
    }

    /**
     * Register post type
     *
     * @return void
     */

    public function register_post_type()
    {
        $name   = ucwords(str_replace('_', ' ', $this->post_type_name));
        $plural = $name . 's';

        /**
         * Set post type labels.
         *
         * @var array
         */
        $labels = array_merge(

            array(
                'name'               => sprintf(__('%s', 'growp'), $plural),
                'singular_name'      => sprintf(__('%s', 'growp'), $name),
                'add_new'            => sprintf(__('Add New %s', 'growp'), strtolower($name)),
                'add_new_item'       => sprintf(__('Add New %s', 'growp'), $name),
                'edit_item'          => sprintf(__('Edit %s', 'growp'), $name),
                'new_item'           => sprintf(__('New %s', 'growp'), $name),
                'all_items'          => sprintf(__('All %s', 'growp'), $plural),
                'view_item'          => sprintf(__('View %s', 'growp'), $name),
                'search_items'       => sprintf(__('Search %s', 'growp'), $plural),
                'not_found'          => sprintf(__('No %s found', 'growp'), strtolower($plural)),
                'not_found_in_trash' => sprintf(__('No %s found in Trash', 'growp'), strtolower($plural)),
                'parent_item_colon'  => '',
                'menu_name'          => sprintf(__('%s', 'growp'), $plural),
            ),

            // Given labels
            $this->post_type_labels

        );


        // Same principle as the labels. We set some default and overwite them with the given arguments.
        $args = array_merge(

            array(
                'label'             => $plural,
                'labels'            => $labels,
                'public'            => true,
                'show_ui'           => true,
                'supports'          => array('title', 'editor', 'thumbnail'),
                'show_in_nav_menus' => true,
                '_builtin'          => false,
            ),

            // Given args
            $this->post_type_args

        );

        // Register the post type
        register_post_type($this->post_type_name, $args);

    }

    /**
     * Register taxonomy
     *
     * @param string $name : taxonomy slug
     * @param array $args
     * @param array $labels
     */

    public function add_taxonomy($name, $args = array(), $labels = array())
    {
        if ( ! empty($name)) {
            // We need to know the post type name, so the new taxonomy can be attached to it.
            $post_type_name = $this->post_type_name;

            // Taxonomy properties
            $taxonomy_name   = strtolower(str_replace(' ', '_', $name));
            $taxonomy_labels = $labels;
            $taxonomy_args   = $args;

            if ( ! taxonomy_exists($taxonomy_name)) {
                //Capitilize the words and make it plural
                $name   = ucwords(str_replace('_', ' ', $name));
                $plural = $name . 's';

                // Default labels, overwrite them with the given labels.
                $labels = array_merge(

                // Default
                    array(
                        'name'              => _x($plural, 'taxonomy general name'),
                        'singular_name'     => _x($name, 'taxonomy singular name'),
                        'search_items'      => __('Search ' . $plural),
                        'all_items'         => sprintf(__('All %s', 'growp'), $name),
                        'parent_item'       => sprintf(__('Parent %s', 'growp'), $name),
                        'parent_item_colon' => sprintf(__('Parent: %s', 'growp'), $name),
                        'edit_item'         => sprintf(__('Edit: %s', 'growp'), $name),
                        'update_item'       => sprintf(__('Update %s', 'growp'), $name),
                        'add_new_item'      => sprintf(__('Add new %s', 'growp'), $name),
                        'new_item_name'     => sprintf(__('New %s Name', 'growp'), $name),
                        'menu_name'         => sprintf(__('%s', 'growp'), $name),
                    ),

                    // Given labels
                    $taxonomy_labels

                );

                // Default arguments, overwitten with the given arguments
                $args = array_merge(

                // Default
                    array(
                        'label'             => $plural,
                        'labels'            => $labels,
                        'public'            => true,
                        'show_ui'           => true,
                        'show_in_nav_menus' => true,
                        '_builtin'          => false,
                    ),

                    // Given
                    $taxonomy_args

                );

                // Add the taxonomy to the post type
                add_action('init', function () use ($taxonomy_name, $post_type_name, $args) {
                    register_taxonomy($taxonomy_name, $post_type_name, $args);
                }
                );

            } else {

                add_action('init', function () use ($taxonomy_name, $post_type_name) {
                    register_taxonomy_for_object_type($taxonomy_name, $post_type_name);
                }
                );

            }
        }
    }

    /**
     * Register custom field meta box
     *
     * @param string $title
     * @param array $fields
     * @param string $context
     * @param string $priority
     */
    public function add_meta_box($title, $fields = array(), $context = 'normal', $priority = 'default')
    {
        if ( ! empty($title)) {
            // We need to know the Post Type name again
            $post_type_name = $this->post_type_name;

            // Meta variables
            $box_id       = strtolower(str_replace(' ', '_', $title));
            $box_title    = ucwords(str_replace('_', ' ', $title));
            $box_context  = $context;
            $box_priority = $priority;

            // Make the fields global
            global $custom_fields;
            $custom_fields[$title] = $fields;

            add_action('admin_init',
                function () use ($box_id, $box_title, $post_type_name, $box_context, $box_priority, $fields) {
                    add_meta_box(
                        $box_id,
                        $box_title,
                        function ($post, $data) {

                            global $post;

                            // Nonce field for some validation
                            wp_nonce_field(plugin_basename(__FILE__), 'custom_post_type');

                            // Get all inputs from $data
                            $custom_fields = $data['args'][0];

                            // Get the saved values
                            $meta = get_post_custom($post->ID);

                            if ( ! empty($custom_fields)) {

                                foreach ($custom_fields as $label => $type) {

                                    $field_id_name = strtolower(str_replace(' ', '_',
                                            $data['id'])) . '_' . strtolower(str_replace(' ', '_', $label));
                                    echo '<label for="' . $field_id_name . '">' . $label . '</label><input type="text" name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '" value="' . $meta[$field_id_name][0] . '" />';

                                }

                            }

                        },
                        $post_type_name,
                        $box_context,
                        $box_priority,
                        array($fields)
                    );
                }
            );
        }

    }

    /**
     * Save custom field meta
     *
     * @return void
     */

    public function save()
    {
        // Need the post type name again
        $post_type_name = $this->post_type_name;

        add_action('save_post',
            function () use ($post_type_name) {
                // Deny the wordpress autosave function
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                    return;
                }

                if ( ! wp_verify_nonce($_POST['custom_post_type'], plugin_basename(__FILE__))) {
                    return;
                }

                global $post;

                if (isset($_POST) && isset($post->ID) && get_post_type($post->ID) == $post_type_name) {
                    global $custom_fields;

                    foreach ($custom_fields as $title => $fields) {
                        foreach ($fields as $label => $type) {

                            $field_id_name = strtolower(str_replace(' ', '_',
                                    $title)) . '_' . strtolower(str_replace(' ', '_', $label));
                            update_post_meta($post->ID, $field_id_name, $_POST['custom_meta'][$field_id_name]);

                        }
                    }
                }
            }
        );
    }
}
