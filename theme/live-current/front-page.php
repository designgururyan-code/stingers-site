<?php
/**
 * Template Name: Home Page
 */
get_header();

// Fields with rich defaults matching original design
$hero_heading = get_field('hero_heading') ?: 'Cairns Stingers';
$hero_sub = get_field('hero_subheading') ?: 'Proud AFL Masters team from Far North Queensland. Built on mateship, competition, and a love of the game.';
$hero_bg = get_field('hero_bg') ?: get_template_directory_uri() . '/assets/images/player-action.png';
$hero_badge = get_field('hero_badge_text') ?: 'AFL Masters — Cairns, QLD';
$hero_btn1 = get_field('hero_btn1') ?: 'Meet the Squad';
$hero_btn2 = get_field('hero_btn2') ?: 'Register to Play';
$video_url = get_field('hero_video_url');

$about_heading = get_field('about_heading') ?: 'More Than a Team';
$about_text = get_field('about_text');
$about_img = get_field('about_image') ?: get_template_directory_uri() . '/assets/images/player-portrait.webp';
$about_bg = get_field('about_bg_color');

$train_day = get_field('train_day') ?: 'Wednesday';
$train_time = get_field('train_time') ?: '5:00 PM';
$train_loc = get_field('train_location') ?: "Watson's Oval #2";
$train_sub = get_field('train_sublocation') ?: 'Near PCYC';

$cta_heading = get_field('cta_heading') ?: 'Ready to Pull On The Stingers Jersey?';
$cta_text = get_field('cta_text') ?: 'Register via PlayHQ for the 2026 season.';
$cta_bg = get_field('cta_bg_color');

$sponsors_heading = get_field('sponsors_home_heading') ?: 'Proudly Supported By';
$fee = function_exists('get_field') ? (get_field('registration_fee', 'option') ?: '$90') : '$90';
$playhq = stingers_playhq_url();
$logo = stingers_logo_url();
$playhq_btn = function_exists('get_field') ? (get_field('btn_playhq', 'option') ?: 'Register on PlayHQ') : 'Register on PlayHQ';

$show_stats = get_field('show_stats') !== null ? get_field('show_stats') : true;
$show_train = get_field('show_training') !== null ? get_field('show_training') : true;
$show_about = get_field('show_about') !== null ? get_field('show_about') : true;
$show_features = get_field('show_features') !== null ? get_field('show_features') : true;
$show_cta = get_field('show_cta') !== null ? get_field('show_cta') : true;
$show_sponsors = get_field('show_sponsors_home') !== null ? get_field('show_sponsors_home') : true;

// Default about text
if (!$about_text) {
    $about_text = '<p>The Cairns Stingers are a competitive AFL Masters team based in Far North Queensland. We bring together players of all backgrounds, ages, and skill levels united by a shared passion for Australian Rules Football.</p>
    <p>Whether you\'re a seasoned veteran or picking up the footy for the first time in years, the Stingers offer a welcoming, competitive environment to get back on the park and represent Cairns at the highest masters level.</p>';
}

// Default feature images
$has_features = have_rows('feature_images');
$default_features = [
    ['image' => get_template_directory_uri() . '/assets/images/player-action.png', 'title' => 'Game Day', 'description' => 'Compete at the highest masters level across FNQ and beyond.'],
    ['image' => get_template_directory_uri() . '/assets/images/player-portrait.webp', 'title' => 'Community', 'description' => 'More than a team — a brotherhood built on mateship.'],
    ['image' => get_template_directory_uri() . '/assets/images/player-dark.webp', 'title' => 'Compete', 'description' => 'Carnivals, challenges, and weekly fixtures all season long.'],
    ['image' => get_template_directory_uri() . '/assets/images/player-action.png', 'title' => 'Join Us', 'description' => 'All skill levels welcome. Lace up and get amongst it.'],
];
?>

<!-- HERO -->
<header class="hero">
  <img src="<?php echo esc_url($hero_bg); ?>" alt="Stingers" class="hero-bg-img"/>
  <div class="hero-bg"></div>
  <div class="hero-ct">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center">
      <div>
        <div class="hero-badge"><?php echo esc_html($hero_badge); ?></div>
        <h1><?php
          $parts = explode(' ', $hero_heading, 2);
          echo esc_html($parts[0]);
          if (isset($parts[1])) echo '<br><span class="or">' . esc_html($parts[1]) . '</span>';
        ?></h1>
        <p class="hero-sub"><?php echo esc_html($hero_sub); ?></p>
        <div class="hero-btns">
          <a href="<?php echo home_url('/players/'); ?>" class="btn btn-or"><?php echo esc_html($hero_btn1); ?> <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 6h10M7 2l4 4-4 4"/></svg></a>
          <a href="<?php echo esc_url($playhq); ?>" target="_blank" class="btn btn-ol"><?php echo esc_html($hero_btn2); ?></a>
        </div>
      </div>
      <div style="aspect-ratio:4/5;width:100%;background:rgba(10,22,40,.6);border:1px solid var(--bd);border-radius:var(--rl);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;overflow:hidden;position:relative;backdrop-filter:blur(4px)">
        <?php if ($video_url) : ?>
          <iframe src="<?php echo esc_url($video_url); ?>" style="position:absolute;inset:0;width:100%;height:100%;border:none;" allowfullscreen></iframe>
        <?php else : ?>
          <div style="width:60px;height:60px;border-radius:50%;background:var(--org);border:2px solid var(--bo);display:flex;align-items:center;justify-content:center;z-index:2">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="#fff"><polygon points="9 5 19 12 9 19"/></svg>
          </div>
          <p style="font-size:13px;color:var(--dim);z-index:2;letter-spacing:.06em;text-transform:uppercase;font-weight:600">Watch Highlights</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>

<?php if ($show_stats) : ?>
<!-- STATS -->
<div class="wrap">
  <div class="stats-row">
    <?php if (have_rows('stat_cards')) : while (have_rows('stat_cards')) : the_row(); ?>
      <div class="stat-card"><h3><?php echo esc_html(get_sub_field('value')); ?></h3><p><?php echo esc_html(get_sub_field('label')); ?></p></div>
    <?php endwhile; else : ?>
      <div class="stat-card"><h3><?php echo date('Y'); ?></h3><p>Season</p></div>
      <div class="stat-card"><h3>32+</h3><p>Registered Players</p></div>
      <div class="stat-card"><h3>FNQ</h3><p>Cairns Based</p></div>
      <div class="stat-card"><h3>AFL</h3><p>Masters</p></div>
    <?php endif; ?>
  </div>
</div>
<?php endif; ?>

<?php if ($show_train) : ?>
<!-- TRAINING -->
<section class="train"><div class="wrap"><div class="train-inner">
  <div style="display:flex;align-items:center;gap:20px">
    <img src="<?php echo esc_url($logo); ?>" alt="Stingers" style="height:120px;flex-shrink:0"/>
    <div>
      <h2>Come and Train</h2>
      <p style="color:var(--dim);font-size:16px;margin-top:10px;max-width:380px">New players always welcome. Lace up the boots and get amongst it.</p>
    </div>
  </div>
  <div class="train-dets">
    <div class="train-d"><div class="v"><?php echo esc_html($train_day); ?></div><div class="l">Day</div></div>
    <div class="train-d"><div class="v"><?php echo esc_html($train_time); ?></div><div class="l">Time</div></div>
    <div class="train-d"><div class="v"><?php echo esc_html($train_loc); ?></div><div class="l"><?php echo esc_html($train_sub); ?></div></div>
  </div>
</div></div></section>
<?php endif; ?>

<?php if ($show_about) : ?>
<!-- ABOUT -->
<section class="section"<?php if ($about_bg) echo ' style="background:' . esc_attr($about_bg) . '"'; ?>>
  <div class="wrap"><div class="about-grid">
    <div class="about-img"><img src="<?php echo esc_url($about_img); ?>" alt="About"/></div>
    <div>
      <div class="pill"><span class="pill-dot"></span>About the Club</div>
      <h2 class="st"><?php
        $about_heading_display = str_replace('Than A ', 'Than a ', $about_heading);
        $ah = explode(' ', $about_heading_display);
        $last = array_pop($ah);
        echo esc_html(implode(' ', $ah)) . ' <em>' . esc_html($last) . '</em>';
      ?></h2>
      <div class="about-text"><?php echo wp_kses_post($about_text); ?></div>
      <a href="<?php echo home_url('/players/'); ?>" class="btn btn-or">View Squad <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 6h10M7 2l4 4-4 4"/></svg></a>
    </div>
  </div></div>
</section>
<?php endif; ?>

<?php if ($show_features) : ?>
<!-- FEATURE IMAGES -->
<section style="padding:0 0 40px"><div class="wrap">
  <div class="img-grid">
    <?php if ($has_features) :
      while (have_rows('feature_images')) : the_row(); ?>
        <div class="img-hover-card" style="background-image:url('<?php echo esc_url(get_sub_field("image")); ?>');background-size:cover;background-position:top center">
          <div class="img-hover-card-thumb" style="background-image:url('<?php echo esc_url(get_sub_field("image")); ?>')"></div>
          <div class="img-hover-overlay">
            <h3><?php echo esc_html(get_sub_field('title')); ?></h3>
            <p><?php echo esc_html(get_sub_field('description')); ?></p>
          </div>
        </div>
      <?php endwhile;
    else :
      foreach ($default_features as $feat) : ?>
        <div class="img-hover-card" style="<?php echo 'background-image:url(' . esc_url($feat['image']) . ');background-size:cover;background-position:top center'; ?>">
          <div class="img-hover-card-thumb" style="<?php echo 'background-image:url(' . esc_url($feat['image']) . ')'; ?>"></div>
          <div class="img-hover-overlay">
            <h3><?php echo esc_html($feat['title']); ?></h3>
            <p><?php echo esc_html($feat['description']); ?></p>
          </div>
        </div>
      <?php endforeach;
    endif; ?>
  </div>
</div></section>
<?php endif; ?>

<?php if ($show_cta) : ?>
<!-- CTA -->
<section class="section" style="padding-top:40px"><div class="wrap">
  <div class="cta"<?php if ($cta_bg) echo ' style="background:' . esc_attr($cta_bg) . '"'; ?>>
    <div class="pill" style="justify-content:center"><span class="pill-dot"></span><?php echo date('Y'); ?> Season</div>
    <h2 class="st" style="font-size:clamp(30px,4vw,48px)"><?php echo esc_html($cta_heading); ?></h2>
    <p><?php echo esc_html($cta_text); ?> Registration fee is <strong style="color:var(--or)"><?php echo esc_html($fee); ?></strong>. All skill levels welcome.</p>
    <div class="cta-btns">
      <a href="<?php echo esc_url($playhq); ?>" target="_blank" class="btn btn-or"><?php echo esc_html($playhq_btn); ?> <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 6h10M7 2l4 4-4 4"/></svg></a>
      <a href="<?php echo home_url('/sponsors/'); ?>" class="btn btn-ol">Become a Sponsor</a>
    </div>
  </div>
</div></section>
<?php endif; ?>

<?php if ($show_sponsors) : ?>
<!-- SPONSORS MARQUEE -->
<section class="section" style="padding-top:20px;text-align:center"><div class="wrap">
  <div class="pill" style="justify-content:center"><span class="pill-dot"></span>Our Partners</div>
  <h2 class="st" style="margin-bottom:56px;font-size:clamp(22px,3vw,32px);color:#fff"><?php echo esc_html($sponsors_heading); ?></h2>
  <div class="marquee-wrap" style="padding:12px 0">
    <div class="marquee-track">
      <?php
      $sponsors = new WP_Query(['post_type' => 'stingers_sponsor', 'posts_per_page' => -1]);
      $sponsor_items = [];
      if ($sponsors->have_posts()) :
        while ($sponsors->have_posts()) : $sponsors->the_post();
          ob_start();
          if (has_post_thumbnail()) the_post_thumbnail('sponsor-logo');
          else echo '<span style="font-size:10px;color:var(--dim);text-align:center;font-weight:600">Your Business<br>Here</span>';
          $sponsor_items[] = ob_get_clean();
        endwhile;
        wp_reset_postdata();
        // Output twice for seamless loop
        foreach ($sponsor_items as $item) echo '<div class="marquee-item">' . $item . '</div>';
        foreach ($sponsor_items as $item) echo '<div class="marquee-item">' . $item . '</div>';
      else :
        for ($i = 0; $i < 10; $i++) : ?>
          <div class="marquee-item"><span style="font-size:10px;color:var(--dim);text-align:center;font-weight:600">Your Business<br>Here</span></div>
        <?php endfor;
      endif; ?>
    </div>
  </div>
  <a href="<?php echo home_url('/sponsors/'); ?>" class="btn btn-gh" style="margin-top:64px">View Sponsorship Packages</a>
</div></section>
<?php endif; ?>

<?php get_footer();
