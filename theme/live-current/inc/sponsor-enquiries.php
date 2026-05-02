<?php
/**
 * Sponsor Enquiry System
 * Saves submissions to a custom post type AND emails admin
 */

// Register CPT for enquiries
add_action('init', function() {
    register_post_type('stingers_enquiry', [
        'labels' => [
            'name' => 'Enquiries',
            'singular_name' => 'Enquiry',
            'all_items' => 'All Enquiries',
            'menu_name' => 'Enquiries',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-email-alt',
        'supports' => ['title'],
        'capability_type' => 'post',
    ]);
});

// Handle form submission
add_action('template_redirect', function() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['sponsor_enquiry_submit'])) return;

    $name = sanitize_text_field($_POST['name'] ?? '');
    $business = sanitize_text_field($_POST['business'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $tier = sanitize_text_field($_POST['tier'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    if (empty($business) || empty($email)) return;

    // Save to database
    $post_id = wp_insert_post([
        'post_type' => 'stingers_enquiry',
        'post_title' => $business . ' — ' . $name,
        'post_status' => 'publish',
    ]);

    if ($post_id) {
        update_post_meta($post_id, '_enquiry_name', $name);
        update_post_meta($post_id, '_enquiry_business', $business);
        update_post_meta($post_id, '_enquiry_email', $email);
        update_post_meta($post_id, '_enquiry_phone', $phone);
        update_post_meta($post_id, '_enquiry_tier', $tier);
        update_post_meta($post_id, '_enquiry_message', $message);
        update_post_meta($post_id, '_enquiry_date', current_time('mysql'));
    }

    // Email admin
    $to = get_option('admin_email');
    $subject = 'New Sponsorship Enquiry — ' . $business;
    $body = "New sponsorship enquiry received:\n\n";
    $body .= "Name: {$name}\n";
    $body .= "Business: {$business}\n";
    $body .= "Email: {$email}\n";
    $body .= "Phone: {$phone}\n";
    $body .= "Tier: {$tier}\n";
    $body .= "Message: {$message}\n\n";
    $body .= "View in admin: " . admin_url("edit.php?post_type=stingers_enquiry");
    wp_mail($to, $subject, $body, ['Reply-To: ' . $email]);

    // Redirect back with success flag
    wp_redirect(add_query_arg('enquiry', 'sent', wp_get_referer() ?: home_url('/sponsors/#form')));
    exit;
});

// Show enquiry details in admin columns
add_filter('manage_stingers_enquiry_posts_columns', function($cols) {
    return [
        'cb' => $cols['cb'],
        'title' => 'Business / Name',
        'enquiry_email' => 'Email',
        'enquiry_phone' => 'Phone',
        'enquiry_tier' => 'Tier',
        'date' => 'Date',
    ];
});

add_action('manage_stingers_enquiry_posts_custom_column', function($col, $id) {
    switch ($col) {
        case 'enquiry_email': echo esc_html(get_post_meta($id, '_enquiry_email', true)); break;
        case 'enquiry_phone': echo esc_html(get_post_meta($id, '_enquiry_phone', true)); break;
        case 'enquiry_tier': echo esc_html(get_post_meta($id, '_enquiry_tier', true)); break;
    }
}, 10, 2);

// Show full details when editing an enquiry
add_action('add_meta_boxes', function() {
    add_meta_box('enquiry_details', 'Enquiry Details', function($post) {
        $fields = [
            'Name' => get_post_meta($post->ID, '_enquiry_name', true),
            'Business' => get_post_meta($post->ID, '_enquiry_business', true),
            'Email' => get_post_meta($post->ID, '_enquiry_email', true),
            'Phone' => get_post_meta($post->ID, '_enquiry_phone', true),
            'Tier' => get_post_meta($post->ID, '_enquiry_tier', true),
            'Message' => get_post_meta($post->ID, '_enquiry_message', true),
            'Submitted' => get_post_meta($post->ID, '_enquiry_date', true),
        ];
        echo '<table style="width:100%;border-collapse:collapse">';
        foreach ($fields as $label => $val) {
            $val = esc_html($val ?: '—');
            if ($label === 'Email') $val = '<a href="mailto:' . $val . '">' . $val . '</a>';
            echo "<tr><td style='padding:8px 12px;font-weight:bold;width:120px;border-bottom:1px solid #eee'>{$label}</td><td style='padding:8px 12px;border-bottom:1px solid #eee'>{$val}</td></tr>";
        }
        echo '</table>';
    }, 'stingers_enquiry', 'normal', 'high');
});

// Remove unnecessary meta boxes from enquiry edit screen
add_action('admin_menu', function() {
    remove_meta_box('submitdiv', 'stingers_enquiry', 'side');
});

// Add unread count to admin menu
add_action('admin_menu', function() {
    $count = wp_count_posts('stingers_enquiry');
    $pending = isset($count->publish) ? $count->publish : 0;
    // We'll just show total as a badge — in practice you'd track read/unread
});
