<?php
/**
 * Design Controls ACF Fields
 * Adds backend-editable design options for colors, backgrounds, headings, buttons, etc.
 */

add_action('acf/init', 'stingers_design_acf_fields');

function stingers_design_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) return;

    // ── GLOBAL DESIGN OPTIONS (Options page) ──
    acf_add_local_field_group([
        'key' => 'group_design_options',
        'title' => 'Design & Colors',
        'fields' => [
            // Colors
            ['key' => 'field_color_primary', 'label' => 'Primary Accent Color (Orange)', 'name' => 'color_primary', 'type' => 'color_picker', 'default_value' => '#E35926', 'instructions' => 'Main accent — buttons, highlights, headings.'],
            ['key' => 'field_color_secondary', 'label' => 'Secondary Accent Color (Cyan)', 'name' => 'color_secondary', 'type' => 'color_picker', 'default_value' => '#00D4E0', 'instructions' => 'Secondary accent — stats, links, badges.'],
            ['key' => 'field_color_bg', 'label' => 'Site Background Color', 'name' => 'color_bg', 'type' => 'color_picker', 'default_value' => '#0A1628'],
            ['key' => 'field_color_card', 'label' => 'Card Background Color', 'name' => 'color_card', 'type' => 'color_picker', 'default_value' => '#112040'],
            ['key' => 'field_color_nav', 'label' => 'Nav Background Color', 'name' => 'color_nav', 'type' => 'color_picker', 'default_value' => '#0A1628'],

            // Footer
            ['key' => 'field_footer_desc', 'label' => 'Footer Description', 'name' => 'footer_desc', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Proud AFL Masters team representing Far North Queensland.'],

            // Buttons
            ['key' => 'field_btn_register', 'label' => 'Register Button Text', 'name' => 'btn_register', 'type' => 'text', 'default_value' => 'Register'],
            ['key' => 'field_btn_playhq', 'label' => 'PlayHQ Button Text', 'name' => 'btn_playhq', 'type' => 'text', 'default_value' => 'Register on PlayHQ'],
        ],
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'stingers-settings']]],
        'menu_order' => 10,
    ]);

    // ── HOME PAGE — extra design controls ──
    acf_add_local_field_group([
        'key' => 'group_home_design',
        'title' => 'Home Page — Section Controls',
        'fields' => [
            // Stats
            ['key' => 'field_stats', 'label' => 'Stat Cards', 'name' => 'stat_cards', 'type' => 'repeater', 'max' => 4, 'button_label' => 'Add Stat', 'sub_fields' => [
                ['key' => 'field_stat_val', 'label' => 'Value', 'name' => 'value', 'type' => 'text'],
                ['key' => 'field_stat_lbl', 'label' => 'Label', 'name' => 'label', 'type' => 'text'],
            ], 'instructions' => 'Leave empty for defaults (2026/30+/FNQ/AFL).'],

            // Section toggles
            ['key' => 'field_show_stats', 'label' => 'Show Stats Section', 'name' => 'show_stats', 'type' => 'true_false', 'default_value' => 1],
            ['key' => 'field_show_train', 'label' => 'Show Training Section', 'name' => 'show_training', 'type' => 'true_false', 'default_value' => 1],
            ['key' => 'field_show_about', 'label' => 'Show About Section', 'name' => 'show_about', 'type' => 'true_false', 'default_value' => 1],
            ['key' => 'field_show_features', 'label' => 'Show Feature Images', 'name' => 'show_features', 'type' => 'true_false', 'default_value' => 1],
            ['key' => 'field_show_cta', 'label' => 'Show CTA Section', 'name' => 'show_cta', 'type' => 'true_false', 'default_value' => 1],
            ['key' => 'field_show_sponsors_home', 'label' => 'Show Sponsors Marquee', 'name' => 'show_sponsors_home', 'type' => 'true_false', 'default_value' => 1],

            // Section backgrounds
            ['key' => 'field_about_bg_color', 'label' => 'About Section Background Color', 'name' => 'about_bg_color', 'type' => 'color_picker'],
            ['key' => 'field_about_bg_image', 'label' => 'About Section Background Image', 'name' => 'about_bg_image', 'type' => 'image', 'return_format' => 'url'],
            ['key' => 'field_cta_bg_color', 'label' => 'CTA Section Background Color', 'name' => 'cta_bg_color', 'type' => 'color_picker'],
            ['key' => 'field_cta_bg_image', 'label' => 'CTA Section Background Image', 'name' => 'cta_bg_image', 'type' => 'image', 'return_format' => 'url'],

            // Sponsors section heading
            ['key' => 'field_sponsors_home_heading', 'label' => 'Sponsors Section Heading', 'name' => 'sponsors_home_heading', 'type' => 'text', 'default_value' => 'Proudly Supported By'],

            // Hero button labels
            ['key' => 'field_hero_btn1', 'label' => 'Hero Button 1 Text', 'name' => 'hero_btn1', 'type' => 'text', 'default_value' => 'Meet the Squad'],
            ['key' => 'field_hero_btn2', 'label' => 'Hero Button 2 Text', 'name' => 'hero_btn2', 'type' => 'text', 'default_value' => 'Register to Play'],
            ['key' => 'field_hero_badge_text', 'label' => 'Hero Badge Text', 'name' => 'hero_badge_text', 'type' => 'text', 'default_value' => 'AFL Masters — Cairns, QLD'],
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'front-page.php']]],
        'menu_order' => 20,
    ]);

    // ── PLAYERS PAGE — design controls ──
    acf_add_local_field_group([
        'key' => 'group_players_design',
        'title' => 'Players Page — Design',
        'fields' => [
            ['key' => 'field_players_heading', 'label' => 'Page Heading', 'name' => 'players_heading', 'type' => 'text', 'default_value' => 'Cairns Stingers'],
            ['key' => 'field_players_subheading', 'label' => 'Page Subheading', 'name' => 'players_subheading', 'type' => 'text', 'default_value' => 'Masters Squad'],
            ['key' => 'field_players_desc', 'label' => 'Page Description', 'name' => 'players_desc', 'type' => 'text', 'default_value' => 'Players, administration, and legends of the Cairns Stingers.'],
            ['key' => 'field_players_bg_color', 'label' => 'Page Background Color Override', 'name' => 'players_bg_color', 'type' => 'color_picker'],
            ['key' => 'field_players_banner_bg', 'label' => 'Banner Background Image', 'name' => 'players_banner_bg', 'type' => 'image', 'return_format' => 'url'],
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-players.php']]],
        'menu_order' => 20,
    ]);

    // ── GAMES PAGE — design controls ──
    acf_add_local_field_group([
        'key' => 'group_games_design',
        'title' => 'Games Page — Design',
        'fields' => [
            ['key' => 'field_games_heading', 'label' => 'Page Heading', 'name' => 'games_heading', 'type' => 'text', 'default_value' => 'Games &amp; Contests'],
            ['key' => 'field_games_desc', 'label' => 'Page Description', 'name' => 'games_desc', 'type' => 'text'],
            ['key' => 'field_games_banner_bg', 'label' => 'Banner Background Image', 'name' => 'games_banner_bg', 'type' => 'image', 'return_format' => 'url'],
            ['key' => 'field_contests_heading', 'label' => 'Contests Section Heading', 'name' => 'contests_heading', 'type' => 'text', 'default_value' => 'Our Yearly Contests'],
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-games.php']]],
        'menu_order' => 20,
    ]);

    // ── SPONSORS PAGE — design + editable tiers ──
    acf_add_local_field_group([
        'key' => 'group_sponsors_design',
        'title' => 'Sponsors Page — Design & Content',
        'fields' => [
            ['key' => 'field_sponsors_heading', 'label' => 'Page Heading', 'name' => 'sponsors_heading', 'type' => 'text', 'default_value' => 'Sponsorship Packages'],
            ['key' => 'field_sponsors_why_text', 'label' => 'Why Sponsor Text', 'name' => 'sponsors_why_text', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_sponsors_banner_bg', 'label' => 'Banner Background Image', 'name' => 'sponsors_banner_bg', 'type' => 'image', 'return_format' => 'url'],

            // Benefits
            ['key' => 'field_sponsor_benefits', 'label' => 'Sponsor Benefits', 'name' => 'sponsor_benefits', 'type' => 'repeater', 'max' => 4, 'button_label' => 'Add Benefit', 'sub_fields' => [
                ['key' => 'field_sb_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'],
                ['key' => 'field_sb_desc', 'label' => 'Description', 'name' => 'description', 'type' => 'text'],
                ['key' => 'field_sb_icon', 'label' => 'Icon (SVG code or dashicon)', 'name' => 'icon', 'type' => 'text', 'instructions' => 'Leave empty for default icons.'],
            ]],

            // Tiers
            ['key' => 'field_sponsor_tiers', 'label' => 'Sponsorship Tiers', 'name' => 'sponsor_tiers', 'type' => 'repeater', 'max' => 4, 'button_label' => 'Add Tier', 'sub_fields' => [
                ['key' => 'field_st_name', 'label' => 'Tier Name', 'name' => 'name', 'type' => 'text'],
                ['key' => 'field_st_price', 'label' => 'Price', 'name' => 'price', 'type' => 'text'],
                ['key' => 'field_st_period', 'label' => 'Period', 'name' => 'period', 'type' => 'text', 'default_value' => 'per season'],
                ['key' => 'field_st_color', 'label' => 'Accent Color', 'name' => 'color', 'type' => 'color_picker', 'instructions' => 'Gold=#FFD700, Silver=#C0C0C0, Bronze=#CD7F32'],
                ['key' => 'field_st_features', 'label' => 'Features (one per line)', 'name' => 'features', 'type' => 'textarea', 'rows' => 6],
                ['key' => 'field_st_btn_text', 'label' => 'Button Text', 'name' => 'btn_text', 'type' => 'text', 'default_value' => 'Get Started'],
                ['key' => 'field_st_featured', 'label' => 'Featured (primary button)', 'name' => 'featured', 'type' => 'true_false'],
            ]],

            // Partners heading
            ['key' => 'field_partners_heading', 'label' => 'Partners Section Heading', 'name' => 'partners_heading', 'type' => 'text', 'default_value' => 'Our Current Partners'],
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-sponsors.php']]],
        'menu_order' => 20,
    ]);

    // ── GALLERY PAGE — design ──
    acf_add_local_field_group([
        'key' => 'group_gallery_design',
        'title' => 'Gallery Page — Design',
        'fields' => [
            ['key' => 'field_gallery_heading', 'label' => 'Page Heading', 'name' => 'gallery_heading', 'type' => 'text', 'default_value' => 'Photo Gallery'],
            ['key' => 'field_gallery_desc', 'label' => 'Page Description', 'name' => 'gallery_desc', 'type' => 'text', 'default_value' => 'Moments from the field, the rooms, and everything in between.'],
            ['key' => 'field_gallery_banner_bg', 'label' => 'Banner Background Image', 'name' => 'gallery_banner_bg', 'type' => 'image', 'return_format' => 'url'],
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-gallery.php']]],
        'menu_order' => 20,
    ]);

    // ── CLUB SONG PAGE — design ──
    acf_add_local_field_group([
        'key' => 'group_song_design',
        'title' => 'Club Song Page — Design',
        'fields' => [
            ['key' => 'field_song_heading', 'label' => 'Page Heading', 'name' => 'song_heading', 'type' => 'text', 'default_value' => 'The Stingers Song'],
            ['key' => 'field_song_subtitle', 'label' => 'Subtitle', 'name' => 'song_subtitle', 'type' => 'text', 'default_value' => 'Sung after every victory — or any time the boys feel like it.'],
            ['key' => 'field_song_cta_heading', 'label' => 'CTA Heading', 'name' => 'song_cta_heading', 'type' => 'text', 'default_value' => 'Want to Sing It After a Win?'],
            ['key' => 'field_song_cta_text', 'label' => 'CTA Text', 'name' => 'song_cta_text', 'type' => 'text', 'default_value' => 'Join the Stingers and belt it out with the boys.'],
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-club-song.php']]],
        'menu_order' => 20,
    ]);
}

/**
 * Output custom CSS variables from ACF design options
 */
add_action('wp_head', function() {
    if (!function_exists('get_field')) return;

    $primary = get_field('color_primary', 'option') ?: '#E35926';
    $secondary = get_field('color_secondary', 'option') ?: '#00D4E0';
    $bg = get_field('color_bg', 'option') ?: '#0A1628';
    $card = get_field('color_card', 'option') ?: '#112040';
    $nav_bg = get_field('color_nav', 'option') ?: '#0A1628';

    echo "<style>:root{";
    echo "--or:{$primary};--ord:" . stingers_darken($primary, 15) . ";--orl:" . stingers_lighten($primary, 20) . ";--org:" . stingers_alpha($primary, 0.1) . ";--bo:" . stingers_alpha($primary, 0.2) . ";";
    echo "--cy:{$secondary};--cyd:" . stingers_darken($secondary, 15) . ";--cyg:" . stingers_alpha($secondary, 0.07) . ";";
    echo "--bg:{$bg};--c1:{$card};";
    echo "}";
    echo ".nav,.nav.sc{background:" . stingers_alpha($nav_bg, 0.9) . "}";
    echo "body{background:{$bg}}";
    echo "</style>";
});

// Color helpers
function stingers_darken($hex, $percent) {
    $hex = ltrim($hex, '#');
    $r = max(0, hexdec(substr($hex, 0, 2)) - (255 * $percent / 100));
    $g = max(0, hexdec(substr($hex, 2, 2)) - (255 * $percent / 100));
    $b = max(0, hexdec(substr($hex, 4, 2)) - (255 * $percent / 100));
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}

function stingers_lighten($hex, $percent) {
    $hex = ltrim($hex, '#');
    $r = min(255, hexdec(substr($hex, 0, 2)) + (255 * $percent / 100));
    $g = min(255, hexdec(substr($hex, 2, 2)) + (255 * $percent / 100));
    $b = min(255, hexdec(substr($hex, 4, 2)) + (255 * $percent / 100));
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}

function stingers_alpha($hex, $alpha) {
    $hex = ltrim($hex, '#');
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return "rgba({$r},{$g},{$b},{$alpha})";
}
