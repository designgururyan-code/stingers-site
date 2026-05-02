<?php
/**
 * Cairns Stingers Theme Functions
 * Requires: ACF Pro
 */

// ── THEME SETUP ──────────────────────────────────────
add_action('after_setup_theme', function() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_image_size('player-card', 600, 500, true);
    add_image_size('contest-card', 800, 400, true);
    add_image_size('sponsor-logo', 300, 200, false);
    register_nav_menus(['primary' => 'Primary Navigation']);
});

// ── ENQUEUE ──────────────────────────────────────────
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap', [], null);
    wp_enqueue_style('stingers-theme', get_template_directory_uri() . '/assets/css/theme.css', ['google-fonts'], '1.0');
    wp_enqueue_script('stingers-motion', get_template_directory_uri() . '/assets/js/motion-init.js', [], '3.0', true);
    wp_enqueue_script('stingers-player', get_template_directory_uri() . '/assets/js/player-frontend.js', [], '1.0', true);
    wp_localize_script('stingers-player', 'stingersAjax', [
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('stingers_player_nonce'),
    ]);
});

// ── CUSTOM POST TYPES ────────────────────────────────
add_action('init', function() {

    // PLAYERS
    register_post_type('stingers_player', [
        'labels' => [
            'name' => 'Players',
            'singular_name' => 'Player',
            'add_new_item' => 'Add New Player',
            'edit_item' => 'Edit Player',
        ],
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'thumbnail'],
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'player'],
    ]);

    // GAMES
    register_post_type('stingers_game', [
        'labels' => [
            'name' => 'Games',
            'singular_name' => 'Game',
            'add_new_item' => 'Add New Game',
            'edit_item' => 'Edit Game',
        ],
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title', 'custom-fields'],
        'show_in_rest' => true,
        'capability_type' => 'post',
        'map_meta_cap' => true,
    ]);

    // CONTESTS (Annual Events)
    register_post_type('stingers_contest', [
        'labels' => [
            'name' => 'Contests',
            'singular_name' => 'Contest',
        ],
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-awards',
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);

    // SPONSORS
    register_post_type('stingers_sponsor', [
        'labels' => [
            'name' => 'Sponsors',
            'singular_name' => 'Sponsor',
        ],
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-money-alt',
        'supports' => ['title', 'thumbnail'],
        'show_in_rest' => true,
    ]);

    // PLAYER TAXONOMY — Status (Active, Retired, Admin)
    register_taxonomy('player_status', 'stingers_player', [
        'labels' => [
            'name' => 'Player Status',
            'singular_name' => 'Status',
        ],
        'hierarchical' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'status'],
    ]);

    // SPONSOR TAXONOMY — Tier
    register_taxonomy('sponsor_tier', 'stingers_sponsor', [
        'labels' => ['name' => 'Sponsor Tier', 'singular_name' => 'Tier'],
        'hierarchical' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
    ]);
});

// Create default taxonomies on theme activation
add_action('after_switch_theme', function() {
    // Player statuses
    $statuses = ['Active', 'Retired', 'Administration'];
    foreach ($statuses as $s) {
        if (!term_exists($s, 'player_status')) {
            wp_insert_term($s, 'player_status');
        }
    }
    // Sponsor tiers
    $tiers = ['Bronze', 'Silver', 'Gold'];
    foreach ($tiers as $t) {
        if (!term_exists($t, 'sponsor_tier')) {
            wp_insert_term($t, 'sponsor_tier');
        }
    }
    flush_rewrite_rules();
});

// ── ACF FIELD GROUPS (registered via PHP) ────────────
if (function_exists('acf_add_local_field_group')) {
    add_action('acf/init', 'stingers_register_acf_fields');
}

function stingers_register_acf_fields() {

    // PLAYER FIELDS
    acf_add_local_field_group([
        'key' => 'group_player_details',
        'title' => 'Player Details',
        'fields' => [
            ['key' => 'field_player_number', 'label' => 'Number', 'name' => 'player_number', 'type' => 'number'],
            ['key' => 'field_player_position', 'label' => 'Position', 'name' => 'player_position', 'type' => 'text'],
            ['key' => 'field_player_nickname', 'label' => 'Nickname', 'name' => 'player_nickname', 'type' => 'text'],
            ['key' => 'field_player_age', 'label' => 'Age', 'name' => 'player_age', 'type' => 'number'],
            ['key' => 'field_player_height', 'label' => 'Height', 'name' => 'player_height', 'type' => 'text'],
            ['key' => 'field_player_years', 'label' => 'Years Played', 'name' => 'player_years', 'type' => 'number'],
            ['key' => 'field_player_debut', 'label' => 'Debut Year', 'name' => 'player_debut', 'type' => 'text'],
            ['key' => 'field_player_clubs', 'label' => 'Former Clubs', 'name' => 'player_clubs', 'type' => 'text'],
            ['key' => 'field_player_awards', 'label' => 'Awards', 'name' => 'player_awards', 'type' => 'text'],
            ['key' => 'field_player_bio', 'label' => 'Bio', 'name' => 'player_bio', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_player_admin_role', 'label' => 'Admin Role (if applicable)', 'name' => 'player_admin_role', 'type' => 'text', 'instructions' => 'e.g. President, Secretary. Only shows in Administration tab.'],
            ['key' => 'field_player_email', 'label' => 'Player Email (for self-edit access)', 'name' => 'player_email', 'type' => 'email', 'instructions' => 'Assign an email so this player can edit their own profile from the frontend.'],
            ['key' => 'field_player_images', 'label' => 'Additional Photos', 'name' => 'player_images', 'type' => 'gallery', 'return_format' => 'array', 'preview_size' => 'thumbnail'],
            ['key' => 'field_player_surname', 'label' => 'Surname (for sorting)', 'name' => 'player_surname', 'type' => 'text'],
        ],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'stingers_player']]],
        'menu_order' => 0,
    ]);

    // GAME FIELDS
    acf_add_local_field_group([
        'key' => 'group_game_details',
        'title' => 'Game Details',
        'fields' => [
            ['key' => 'field_game_title', 'label' => 'Game Title (optional)', 'name' => 'game_title', 'type' => 'text', 'instructions' => 'Optional display title, e.g. "Grand Final" or "Round 4". Leave blank to auto-generate from opponent.', 'required' => 0],
            ['key' => 'field_game_banner_logo', 'label' => 'Banner Logo / Side-by-Side Graphic', 'name' => 'game_banner_logo', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'instructions' => 'Optional logo/graphic shown on the left of the Next Game banner. Upload a side-vs-side graphic or opponent logo. Defaults to the club logo if left blank.'],
            ['key' => 'field_game_date', 'label' => 'Date', 'name' => 'game_date', 'type' => 'date_picker', 'display_format' => 'D j M Y', 'return_format' => 'D j M'],
            ['key' => 'field_game_opponent', 'label' => 'Opponent', 'name' => 'game_opponent', 'type' => 'text'],
            ['key' => 'field_game_venue', 'label' => 'Venue', 'name' => 'game_venue', 'type' => 'text'],
            ['key' => 'field_game_time', 'label' => 'Time', 'name' => 'game_time', 'type' => 'text'],
            ['key' => 'field_game_score_us', 'label' => 'Our Score', 'name' => 'game_score_us', 'type' => 'text', 'instructions' => 'e.g. 12.8 (80)'],
            ['key' => 'field_game_score_them', 'label' => 'Their Score', 'name' => 'game_score_them', 'type' => 'text'],
            ['key' => 'field_game_result', 'label' => 'Result', 'name' => 'game_result', 'type' => 'select', 'choices' => ['upcoming' => 'Upcoming', 'win' => 'Win', 'loss' => 'Loss', 'draw' => 'Draw']],
            ['key' => 'field_game_home', 'label' => 'Home Game?', 'name' => 'game_home', 'type' => 'true_false'],
        ],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'stingers_game']]],
    ]);

    // SPONSOR FIELDS
    acf_add_local_field_group([
        'key' => 'group_sponsor_details',
        'title' => 'Sponsor Details',
        'fields' => [
            ['key' => 'field_sponsor_url', 'label' => 'Website URL', 'name' => 'sponsor_url', 'type' => 'url'],
        ],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'stingers_sponsor']]],
    ]);

    // HOME PAGE FIELDS
    acf_add_local_field_group([
        'key' => 'group_home_page',
        'title' => 'Home Page Settings',
        'fields' => [
            ['key' => 'field_hero_heading', 'label' => 'Hero Heading', 'name' => 'hero_heading', 'type' => 'text', 'default_value' => 'Cairns Stingers'],
            ['key' => 'field_hero_subheading', 'label' => 'Hero Subheading', 'name' => 'hero_subheading', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_hero_bg', 'label' => 'Hero Background Image', 'name' => 'hero_bg', 'type' => 'image', 'return_format' => 'url'],
            ['key' => 'field_hero_video_url', 'label' => 'Hero Video URL (YouTube/Vimeo)', 'name' => 'hero_video_url', 'type' => 'url'],
            ['key' => 'field_about_heading', 'label' => 'About Heading', 'name' => 'about_heading', 'type' => 'text', 'default_value' => 'More Than A Team'],
            ['key' => 'field_about_text', 'label' => 'About Text', 'name' => 'about_text', 'type' => 'wysiwyg', 'media_upload' => 0],
            ['key' => 'field_about_image', 'label' => 'About Image', 'name' => 'about_image', 'type' => 'image', 'return_format' => 'url'],
            ['key' => 'field_train_day', 'label' => 'Training Day', 'name' => 'train_day', 'type' => 'text', 'default_value' => 'Wednesday'],
            ['key' => 'field_train_time', 'label' => 'Training Time', 'name' => 'train_time', 'type' => 'text', 'default_value' => '5:00 PM'],
            ['key' => 'field_train_location', 'label' => 'Training Location', 'name' => 'train_location', 'type' => 'text', 'default_value' => "Watson's Oval #2"],
            ['key' => 'field_train_sublocation', 'label' => 'Training Sub-location', 'name' => 'train_sublocation', 'type' => 'text', 'default_value' => 'Near PCYC'],
            ['key' => 'field_feature_images', 'label' => 'Feature Image Cards', 'name' => 'feature_images', 'type' => 'repeater', 'sub_fields' => [
                ['key' => 'field_feat_image', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'url'],
                ['key' => 'field_feat_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'],
                ['key' => 'field_feat_desc', 'label' => 'Description', 'name' => 'description', 'type' => 'text'],
            ]],
            ['key' => 'field_cta_heading', 'label' => 'CTA Heading', 'name' => 'cta_heading', 'type' => 'text'],
            ['key' => 'field_cta_text', 'label' => 'CTA Text', 'name' => 'cta_text', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_section_bg_color', 'label' => 'Sections Background Override', 'name' => 'section_bg_color', 'type' => 'color_picker'],
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'front-page.php']]],
    ]);

    // CLUB SONG PAGE FIELDS
    acf_add_local_field_group([
        'key' => 'group_club_song',
        'title' => 'Club Song',
        'fields' => [
            ['key' => 'field_song_lyrics', 'label' => 'Lyrics', 'name' => 'song_lyrics', 'type' => 'wysiwyg', 'media_upload' => 0],
            ['key' => 'field_song_audio', 'label' => 'Audio File', 'name' => 'song_audio', 'type' => 'file', 'return_format' => 'url'],
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-club-song.php']]],
    ]);

    // HONOUR ROLL (repeater)
    acf_add_local_field_group([
        'key' => 'group_honour_roll',
        'title' => 'Honour Roll',
        'fields' => [
            ['key' => 'field_honour_rows', 'label' => 'Honour Roll Entries', 'name' => 'honour_rows', 'type' => 'repeater', 'sub_fields' => [
                ['key' => 'field_hr_year', 'label' => 'Year', 'name' => 'year', 'type' => 'text'],
                ['key' => 'field_hr_president', 'label' => 'President', 'name' => 'president', 'type' => 'text'],
                ['key' => 'field_hr_secretary', 'label' => 'Secretary', 'name' => 'secretary', 'type' => 'text'],
                ['key' => 'field_hr_treasurer', 'label' => 'Treasurer', 'name' => 'treasurer', 'type' => 'text'],
                ['key' => 'field_hr_coach', 'label' => 'Coach', 'name' => 'coach', 'type' => 'text'],
            ]],
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-players.php']]],
    ]);

    // GLOBAL OPTIONS (requires ACF Pro options page)
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => 'Stingers Settings',
            'menu_title' => 'Stingers Settings',
            'menu_slug' => 'stingers-settings',
            'capability' => 'manage_options',
            'icon_url' => 'dashicons-shield',
        ]);

        acf_add_local_field_group([
            'key' => 'group_global_settings',
            'title' => 'Global Settings',
            'fields' => [
                ['key' => 'field_logo', 'label' => 'Site Logo', 'name' => 'site_logo', 'type' => 'image', 'return_format' => 'url'],
                ['key' => 'field_facebook_url', 'label' => 'Facebook URL', 'name' => 'facebook_url', 'type' => 'url'],
                ['key' => 'field_instagram_url', 'label' => 'Instagram URL', 'name' => 'instagram_url', 'type' => 'url'],
                ['key' => 'field_playhq_url', 'label' => 'PlayHQ Registration URL', 'name' => 'playhq_url', 'type' => 'url'],
                ['key' => 'field_registration_fee', 'label' => 'Registration Fee', 'name' => 'registration_fee', 'type' => 'text', 'default_value' => '$90'],
                ['key' => 'field_contact_email', 'label' => 'Contact Email', 'name' => 'contact_email', 'type' => 'email'],
            ],
            'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'stingers-settings']]],
        ]);
    }
}

// ── PLAYER FRONTEND SYSTEM ───────────────────────────
require_once get_template_directory() . '/inc/player-frontend.php';

// ── HELPERS ──────────────────────────────────────────
function stingers_logo_url() {
    $acf_logo = function_exists('get_field') ? get_field('site_logo', 'option') : false;
    return $acf_logo ?: get_template_directory_uri() . '/assets/images/logo.png';
}

function stingers_playhq_url() {
    $url = function_exists('get_field') ? get_field('playhq_url', 'option') : false;
    return $url ?: 'https://www.playhq.com';
}

function stingers_fb_url() {
    $url = function_exists('get_field') ? get_field('facebook_url', 'option') : false;
    return $url ?: '#';
}

// Sort players by surname
function stingers_get_players($status = 'Active') {
    $term = get_term_by('name', $status, 'player_status');
    $args = [
        'post_type' => 'stingers_player',
        'posts_per_page' => -1,
        'meta_key' => 'player_surname',
        'orderby' => 'meta_value',
        'order' => 'ASC',
    ];
    if ($term) {
        $args['tax_query'] = [['taxonomy' => 'player_status', 'field' => 'term_id', 'terms' => $term->term_id]];
    }
    return new WP_Query($args);
}

// Gallery Page ACF Fields
add_action('acf/init', function() {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key' => 'group_gallery',
        'title' => 'Gallery Images',
        'fields' => [
            ['key' => 'field_gallery_images', 'label' => 'Gallery Images', 'name' => 'gallery_images', 'type' => 'gallery', 'return_format' => 'array', 'preview_size' => 'medium', 'instructions' => 'Upload photos for the gallery grid.'],
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-gallery.php']]],
    ]);
});

// Sponsor enquiry form handler
add_action('init', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['business']) && !empty($_POST['email'])) {
        $to = get_option('admin_email');
        $subject = 'Sponsorship Enquiry — ' . sanitize_text_field($_POST['business']);
        $body = sprintf(
            "Name: %s\nBusiness: %s\nEmail: %s\nPhone: %s\nTier: %s\nMessage: %s",
            sanitize_text_field($_POST['name'] ?? ''),
            sanitize_text_field($_POST['business']),
            sanitize_email($_POST['email']),
            sanitize_text_field($_POST['phone'] ?? ''),
            sanitize_text_field($_POST['tier'] ?? ''),
            sanitize_textarea_field($_POST['message'] ?? '')
        );
        wp_mail($to, $subject, $body);
    }
});

// ── DESIGN CONTROLS ──────────────────────────────────
require_once get_template_directory() . '/inc/design-controls.php';

// ── SPONSOR ENQUIRIES ─────────────────────────────
require_once get_template_directory() . '/inc/sponsor-enquiries.php';

// ── DEMO CONTENT INSTALLER ───────────────────────
require_once get_template_directory() . '/inc/demo-content.php';

// ── ADMIN COLUMN: Show player status names instead of IDs ──
add_filter('manage_stingers_player_posts_columns', function($cols) {
    $new = [];
    foreach ($cols as $k => $v) {
        $new[$k] = $v;
        if ($k === 'title') {
            $new['player_number'] = '#';
            $new['player_position'] = 'Position';
        }
    }
    // Replace taxonomy column with custom one
    if (isset($new['taxonomy-player_status'])) {
        $new['taxonomy-player_status'] = 'Status';
    }
    return $new;
});

add_action('manage_stingers_player_posts_custom_column', function($col, $id) {
    switch ($col) {
        case 'player_number':
            echo esc_html(get_post_meta($id, 'player_number', true));
            break;
        case 'player_position':
            $pos = get_post_meta($id, 'player_position', true);
            $role = get_post_meta($id, 'player_admin_role', true);
            echo esc_html($pos);
            if ($role) echo ' <span style="color:#00D4E0;font-size:11px">(' . esc_html($role) . ')</span>';
            break;
    }
}, 10, 2);

// ── ACF CONDITIONAL: Show admin_role only when Administration status is selected ──
add_action('acf/init', function() {
    if (!function_exists('acf_add_local_field_group')) return;
    
    // Override the admin_role field to add conditional logic
    // This runs after the main field registration, so it modifies the existing field
    add_filter('acf/load_field/key=field_player_admin_role', function($field) {
        $field['conditional_logic'] = [
            [
                [
                    'field' => 'field_player_position', // Always visible - we'll use JS instead
                    'operator' => '!=empty',
                ]
            ]
        ];
        return $field;
    });
});

// ── JS to show/hide admin_role based on taxonomy checkbox ──
add_action('admin_footer-post.php', function() {
    global $post;
    if (!$post || $post->post_type !== 'stingers_player') return;
    ?>
    <script>
    (function(){
        function toggleAdminRole() {
            var adminField = document.querySelector('[data-key="field_player_admin_role"]');
            if (!adminField) return;
            var checked = false;
            document.querySelectorAll('#player_statuschecklist input[type="checkbox"]').forEach(function(cb) {
                var label = cb.parentNode.textContent.trim();
                if (label === 'Administration' && cb.checked) checked = true;
            });
            adminField.style.display = checked ? '' : 'none';
        }
        // Run on load and on checkbox change
        document.addEventListener('DOMContentLoaded', function() {
            toggleAdminRole();
            var checklist = document.getElementById('player_statuschecklist');
            if (checklist) {
                checklist.addEventListener('change', toggleAdminRole);
            }
        });
        // Also run after a short delay for ACF loading
        setTimeout(toggleAdminRole, 1000);
    })();
    </script>
    <?php
});
add_action('admin_footer-post-new.php', function() {
    global $post;
    if (!$post || $post->post_type !== 'stingers_player') return;
    // Same script as above
    ?>
    <script>
    (function(){
        function toggleAdminRole() {
            var adminField = document.querySelector('[data-key="field_player_admin_role"]');
            if (!adminField) return;
            var checked = false;
            document.querySelectorAll('#player_statuschecklist input[type="checkbox"]').forEach(function(cb) {
                var label = cb.parentNode.textContent.trim();
                if (label === 'Administration' && cb.checked) checked = true;
            });
            adminField.style.display = checked ? '' : 'none';
        }
        document.addEventListener('DOMContentLoaded', function() {
            toggleAdminRole();
            var checklist = document.getElementById('player_statuschecklist');
            if (checklist) checklist.addEventListener('change', toggleAdminRole);
        });
        setTimeout(toggleAdminRole, 1000);
    })();
    </script>
    <?php
});

// ── Fix stingers-demo page permissions ──
add_action('admin_menu', function() {
    // Remove the broken submenu and re-add with correct parent
    remove_submenu_page('stingers-settings', 'stingers-demo');
    add_management_page('Reinstall Demo', 'Stingers Demo', 'manage_options', 'stingers-demo', function() {
        echo '<div class="wrap"><h1>Reinstall Demo Content</h1>';
        if (isset($_GET['stingers_reinstall']) && $_GET['stingers_reinstall'] === '1') {
            check_admin_referer('stingers_reinstall_nonce');
            delete_option('stingers_demo_installed');
            $file = get_template_directory() . '/inc/demo-content.php';
            if (file_exists($file)) {
                require_once $file;
                if (function_exists('stingers_install_demo_content')) {
                    stingers_install_demo_content();
                    echo '<div class="notice notice-success"><p>Demo content reinstalled.</p></div>';
                }
            }
        }
        $url = wp_nonce_url(admin_url('tools.php?page=stingers-demo&stingers_reinstall=1'), 'stingers_reinstall_nonce');
        echo '<p>This will recreate sample players, games, contests, and sponsors.</p>';
        echo '<a href="' . esc_url($url) . '" class="button button-primary" onclick="return confirm(\'Reinstall demo content?\')">Reinstall Demo Content</a>';
        echo '</div>';
    });
}, 99);

// ── FORCE GAMES TO PUBLISH — never get stuck as "Scheduled" ─────────────────
// WordPress re-sets status to 'future' if post_date > now even after we change status.
// Fix: also reset post_date to current time so WordPress never sees a future date.
add_filter('wp_insert_post_data', function($data) {
    if ($data['post_type'] === 'stingers_game' && in_array($data['post_status'], ['future', 'draft'])) {
        if ($data['post_status'] === 'future') {
            $now = current_time('mysql');
            $now_gmt = current_time('mysql', 1);
            $data['post_status']   = 'publish';
            $data['post_date']     = $now;
            $data['post_date_gmt'] = $now_gmt;
        }
    }
    return $data;
}, 10, 1);

// Gutenberg / REST API: force publish on REST saves too
add_filter('rest_pre_insert_stingers_game', function($prepared_post, $request) {
    $params = $request->get_params();
    // Only force if user is explicitly trying to publish or schedule
    if (!empty($params['status']) && in_array($params['status'], ['future', 'publish'])) {
        $prepared_post->post_status   = 'publish';
        $prepared_post->post_date     = current_time('mysql');
        $prepared_post->post_date_gmt = current_time('mysql', 1);
    }
    return $prepared_post;
}, 10, 2);

// Fallback: fix any that slip through on save
add_action('save_post_stingers_game', function($post_id, $post) {
    if ($post->post_status === 'future') {
        remove_action('save_post_stingers_game', __FUNCTION__, 10);
        wp_update_post(['ID' => $post_id, 'post_status' => 'publish',
            'post_date' => current_time('mysql'), 'post_date_gmt' => current_time('mysql', 1)]);
    }
}, 10, 2);

// Hide the WordPress date picker in game editor — game_date ACF field handles the date
add_action('admin_head', function() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'stingers_game') {
        echo '<style>.edit-post-post-schedule,.editor-post-schedule,.components-datetime{display:none!important}</style>';
    }
});
