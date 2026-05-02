<?php
/**
 * Player Frontend System
 * - Registration form (creates pending player post)
 * - Email-based login for self-editing
 * - AJAX handlers
 */

// ── PLAYER REGISTRATION ──
add_action('wp_ajax_nopriv_stingers_register_player', 'stingers_register_player');
add_action('wp_ajax_stingers_register_player', 'stingers_register_player');

function stingers_register_player() {
    check_ajax_referer('stingers_player_nonce', 'nonce');

    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');

    if (empty($name) || empty($email)) {
        wp_send_json_error('Name and email are required.');
    }

    // Check if email already exists
    $existing = get_posts([
        'post_type' => 'stingers_player',
        'meta_key' => 'player_email',
        'meta_value' => $email,
        'posts_per_page' => 1,
    ]);

    if ($existing) {
        wp_send_json_error('A player with this email already exists.');
    }

    // Create pending player post
    $post_id = wp_insert_post([
        'post_type' => 'stingers_player',
        'post_title' => $name,
        'post_status' => 'pending',
    ]);

    if ($post_id) {
        update_post_meta($post_id, 'player_email', $email); update_post_meta($post_id, '_player_email', 'field_player_email');

        $parts = explode(' ', $name);
        $surname = end($parts);
        update_post_meta($post_id, 'player_surname', $surname);
        update_post_meta($post_id, '_player_surname', 'field_player_surname');

        // Set as Active status
        $term = get_term_by('name', 'Active', 'player_status');
        if ($term) wp_set_object_terms($post_id, $term->term_id, 'player_status');

        // Notify admin
        $admin_email = get_option('admin_email');
        wp_mail($admin_email, 'New Player Registration — Cairns Stingers',
            "New player registration:\n\nName: {$name}\nEmail: {$email}\n\nApprove in wp-admin → Players.");

        wp_send_json_success('Registration submitted! An admin will approve your profile shortly.');
    }

    wp_send_json_error('Something went wrong. Please try again.');
}

// ── PLAYER LOGIN (email verification) ──
add_action('wp_ajax_nopriv_stingers_player_login', 'stingers_player_login');
add_action('wp_ajax_stingers_player_login', 'stingers_player_login');

function stingers_player_login() {
    check_ajax_referer('stingers_player_nonce', 'nonce');

    $email = sanitize_email($_POST['email'] ?? '');
    if (empty($email)) wp_send_json_error('Email is required.');

    // Find player by email
    $players = get_posts([
        'post_type' => 'stingers_player',
        'meta_key' => 'player_email',
        'meta_value' => $email,
        'posts_per_page' => 1,
        'post_status' => 'publish',
    ]);

    if (empty($players)) {
        wp_send_json_error('No player found with that email.');
    }

    $player = $players[0];
    $token = wp_generate_password(32, false);
    update_post_meta($player->ID, '_edit_token', $token);
    update_post_meta($player->ID, '_edit_token_expiry', time() + 3600); // 1hr

    // Send magic link
    $edit_url = add_query_arg([
        'edit_player' => $player->ID,
        'token' => $token,
    ], get_permalink(get_page_by_path('players')));

    wp_mail($email, 'Edit Your Stingers Profile',
        "Hi {$player->post_title},\n\nClick this link to edit your player profile:\n{$edit_url}\n\nThis link expires in 1 hour.\n\n— Cairns Stingers");

    wp_send_json_success('Check your email — we sent you an edit link.');
}

// ── PLAYER PROFILE UPDATE ──
add_action('wp_ajax_nopriv_stingers_update_player', 'stingers_update_player');
add_action('wp_ajax_stingers_update_player', 'stingers_update_player');

function stingers_update_player() {
    check_ajax_referer('stingers_player_nonce', 'nonce');

    $player_id = intval($_POST['player_id'] ?? 0);
    $token = sanitize_text_field($_POST['token'] ?? '');

    if (!$player_id || !$token) wp_send_json_error('Invalid request.');

    // Verify token
    $stored_token = get_post_meta($player_id, '_edit_token', true);
    $expiry = get_post_meta($player_id, '_edit_token_expiry', true);

    if ($token !== $stored_token || time() > intval($expiry)) {
        wp_send_json_error('Edit link has expired. Please request a new one.');
    }

    // Update fields
    $fields = [
        'player_nickname' => sanitize_text_field($_POST['nickname'] ?? ''),
        'player_position' => sanitize_text_field($_POST['position'] ?? ''),
        'player_age' => intval($_POST['age'] ?? 0),
        'player_height' => sanitize_text_field($_POST['height'] ?? ''),
        'player_clubs' => sanitize_text_field($_POST['clubs'] ?? ''),
        'player_bio' => sanitize_textarea_field($_POST['bio'] ?? ''),
    ];

    foreach ($fields as $key => $val) {
        if (!empty($val)) update_field($key, $val, $player_id);
    }

    // Handle photo upload
    if (!empty($_FILES['photo'])) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $attach_id = media_handle_upload('photo', $player_id);
        if (!is_wp_error($attach_id)) {
            set_post_thumbnail($player_id, $attach_id);
        }
    }

    // Clear token after use
    delete_post_meta($player_id, '_edit_token');
    delete_post_meta($player_id, '_edit_token_expiry');

    wp_send_json_success('Profile updated!');
}

// ── CHECK FOR EDIT MODE ──
function stingers_get_edit_player() {
    if (empty($_GET['edit_player']) || empty($_GET['token'])) return false;

    $player_id = intval($_GET['edit_player']);
    $token = sanitize_text_field($_GET['token']);
    $stored = get_post_meta($player_id, '_edit_token', true);
    $expiry = get_post_meta($player_id, '_edit_token_expiry', true);

    if ($token === $stored && time() < intval($expiry)) {
        return get_post($player_id);
    }
    return false;
}
