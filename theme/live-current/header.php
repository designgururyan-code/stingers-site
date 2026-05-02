<?php
/**
 * Theme Header
 */
$logo = stingers_logo_url();
$playhq = stingers_playhq_url();
$fb = stingers_fb_url();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<nav class="nav" id="nav"><div class="nav-inner">
  <a href="<?php echo home_url(); ?>" class="nav-logo"><img src="<?php echo esc_url($logo); ?>" alt="<?php bloginfo('name'); ?>"/></a>
  <div class="nav-links">
    <a href="<?php echo home_url('/players/'); ?>"<?php if (is_page('players')) echo ' class="on"'; ?>>Players</a>
    <a href="<?php echo home_url('/games/'); ?>"<?php if (is_page('games')) echo ' class="on"'; ?>>Games</a>
    <a href="<?php echo home_url('/gallery/'); ?>"<?php if (is_page('gallery')) echo ' class="on"'; ?>>Gallery</a>
    <a href="<?php echo home_url('/club-song/'); ?>"<?php if (is_page('club-song')) echo ' class="on"'; ?>>Club Song</a>
    <a href="<?php echo home_url('/sponsors/'); ?>"<?php if (is_page('sponsors')) echo ' class="on"'; ?>>Sponsors</a>
    <a href="<?php echo ($fb && $fb !== '#') ? esc_url($fb) : '#'; ?>" <?php if ($fb && $fb !== '#') echo 'target="_blank"'; ?> class="nav-social-link"><svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" style="margin-right:4px;vertical-align:-1px"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>Connect on Social</a>
  </div>
  <div class="nav-end">
    <?php if ($fb && $fb !== '#') : ?>
    <a href="<?php echo esc_url($fb); ?>" target="_blank" class="nav-social" style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;border:1px solid rgba(255,255,255,.15);margin-right:10px;transition:all .25s"><svg viewBox="0 0 24 24" width="15" height="15" fill="#fff"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg></a>
    <?php endif; ?>
    <a href="<?php echo esc_url($playhq); ?>" target="_blank" class="nav-cta"><?php echo esc_html(function_exists("get_field") ? get_field("btn_register", "option") : "Register"); ?></a>
  </div>
  <button class="hb" onclick="document.getElementById('mm').classList.toggle('open')"><span></span><span></span><span></span></button>
</div></nav>

<div class="mm" id="mm">
  <a href="<?php echo home_url(); ?>"<?php if (is_front_page()) echo ' class="on"'; ?>>Home</a>
  <a href="<?php echo home_url('/players/'); ?>"<?php if (is_page('players')) echo ' class="on"'; ?>>Players</a>
  <a href="<?php echo home_url('/games/'); ?>"<?php if (is_page('games')) echo ' class="on"'; ?>>Games</a>
  <a href="<?php echo home_url('/gallery/'); ?>"<?php if (is_page('gallery')) echo ' class="on"'; ?>>Gallery</a>
  <a href="<?php echo home_url('/club-song/'); ?>"<?php if (is_page('club-song')) echo ' class="on"'; ?>>Club Song</a>
  <a href="<?php echo home_url('/sponsors/'); ?>"<?php if (is_page('sponsors')) echo ' class="on"'; ?>>Sponsors</a>
  <a href="<?php echo ($fb && $fb !== '#') ? esc_url($fb) : '#'; ?>" <?php if ($fb && $fb !== '#') echo 'target="_blank"'; ?> class="mm-social"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" style="margin-right:6px;vertical-align:-2px"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>Connect on Social</a>
  <a href="<?php echo esc_url($playhq); ?>" target="_blank"><?php echo esc_html(function_exists("get_field") ? get_field("btn_register", "option") : "Register"); ?></a>
</div>
