<?php
/**
 * Theme Footer
 */
$logo = stingers_logo_url();
$fb = stingers_fb_url();
$ig = function_exists('get_field') ? get_field('instagram_url', 'option') : '#';
$playhq = stingers_playhq_url();
$fee = function_exists('get_field') ? get_field('registration_fee', 'option') : '$90';
$train_day = function_exists('get_field') ? get_field('train_day', 'option') : 'Wednesdays';
$train_time = function_exists('get_field') ? get_field('train_time', 'option') : '5pm';
$train_loc = function_exists('get_field') ? get_field('train_location', 'option') : "Watson's Oval #2";
?>

<footer class="footer">
  <div class="wrap">
    <div class="ft-top">
      <div>
        <div class="ft-brand"><img src="<?php echo esc_url($logo); ?>" alt="Cairns Stingers"/><div class="ft-bname">Cairns Stingers</div></div>
        <p class="ft-desc"><?php echo esc_html(function_exists("get_field") ? get_field("footer_desc", "option") : "Proud AFL Masters team representing Far North Queensland."); ?></p>
      </div>
      <div class="ft-col">
        <h4>Navigation</h4>
        <a href="<?php echo home_url(); ?>">Home</a>
        <a href="<?php echo home_url('/players/'); ?>">Players</a>
        <a href="<?php echo home_url('/gallery/'); ?>">Gallery</a>
        <a href="<?php echo home_url('/club-song/'); ?>">Club Song</a>
        <a href="<?php echo home_url('/games/'); ?>">Games</a>
      </div>
      <div class="ft-col">
        <h4>Get Involved</h4>
        <a href="<?php echo esc_url($playhq); ?>" target="_blank">Registration (<?php echo esc_html($fee); ?>)</a>
        <a href="<?php echo home_url('/sponsors/'); ?>">Sponsorship</a>
      </div>
      <div class="ft-col">
        <h4>Training</h4>
        <span><?php echo esc_html($train_day); ?> <?php echo esc_html($train_time); ?></span>
        <span><?php echo esc_html($train_loc); ?></span>
        <span style="color:rgba(255,255,255,.35);font-size:13px">— check socials for updates</span>
      </div>
      <div class="ft-col">
        <h4>Contact</h4>
        <?php
        $contact_name  = function_exists('get_field') ? (get_field('contact_name', 'option')         ?: 'President Greg Lees')      : 'President Greg Lees';
        $contact_email = function_exists('get_field') ? (get_field('contact_email_public', 'option') ?: 'cairnsstingers@gmail.com') : 'cairnsstingers@gmail.com';
        ?>
        <span><?php echo esc_html($contact_name); ?></span>
        <a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a>
      </div>
    </div>
    <div class="ft-bot">
      <p class="ft-copy">&copy; <?php echo date('Y'); ?> Cairns Stingers AFL Masters.</p>
      <div class="ft-soc">
        <?php if ($fb) : ?><a href="<?php echo esc_url($fb); ?>" target="_blank"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg></a><?php endif; ?>
        <?php if ($ig) : ?><a href="<?php echo esc_url($ig); ?>" target="_blank"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a><?php endif; ?>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
