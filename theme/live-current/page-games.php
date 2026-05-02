<?php
/**
 * Template Name: Games
 */
get_header();
?>

<section class="pb"><div class="wrap">
  <div class="pill" style="justify-content:center"><span class="pill-dot"></span><?php echo date('Y'); ?> Season</div>
  <h1><?php $gh = get_field('games_heading') ?: 'Games &amp; Contests'; echo wp_kses_post($gh); ?></h1>
  <p>Fixtures, results, and the annual events that define our season.</p>
</div></section>

<?php
// ── NEXT GAME FULL-WIDTH BANNER ───────────────────────
$next = new WP_Query(['post_type'=>'stingers_game','post_status'=>'publish','posts_per_page'=>1,'meta_key'=>'game_date','orderby'=>'meta_value','order'=>'ASC','meta_query'=>[['key'=>'game_result','value'=>'upcoming']]]);
if ($next->have_posts()) : $next->the_post();
  $ng_raw        = get_post_meta(get_the_ID(), 'game_date', true);
  $ng_disp       = $ng_raw ? date('l j F Y', strtotime($ng_raw)) : get_field('game_date');
  $ng_opp        = get_field('game_opponent');
  $ng_venue      = get_field('game_venue');
  $ng_time       = get_field('game_time') ?: '';
  $ng_title      = get_field('game_title');
  $ng_time_clean = $ng_time ? preg_replace('/[^0-9:]/', '', $ng_time) : '14:00';
  $ng_iso        = $ng_raw ? date('Y-m-d', strtotime($ng_raw)) . 'T' . $ng_time_clean . ':00' : '';
  $ng_bg         = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : '';
  $ng_logo       = get_field('game_banner_logo') ? wp_get_attachment_image_url(get_field('game_banner_logo'), 'medium') : stingers_logo_url();
  wp_reset_postdata();
?>
<div class="ng-banner" data-date="<?php echo esc_attr($ng_iso); ?>"
     <?php if ($ng_bg) echo 'style="background-image:url(' . esc_url($ng_bg) . ')"'; ?>>
  <div class="ng-banner-overlay"></div>
  <div class="wrap ng-banner-inner">
    <div class="ng-logo">
      <img src="<?php echo esc_url($ng_logo); ?>" alt="<?php echo esc_attr($ng_opp ?: 'Next Game'); ?>"/>
    </div>
    <div class="ng-banner-left">
      <div class="ng-label"><span class="pill-dot"></span>Next Game</div>
      <?php if ($ng_title) : ?><div class="ng-title"><?php echo esc_html($ng_title); ?></div><?php endif; ?>
      <div class="ng-match">Stingers <span class="fvs-vs">vs</span> <?php echo esc_html($ng_opp); ?></div>
      <div class="ng-meta">
        <?php if ($ng_disp) : ?><span><?php echo esc_html($ng_disp); ?><?php if ($ng_time) echo ' &middot; ' . esc_html($ng_time); ?></span><?php endif; ?>
        <?php if ($ng_venue) : ?><span><?php echo esc_html($ng_venue); ?></span><?php endif; ?>
      </div>
    </div>
    <div class="ng-countdown-wrap">
      <div class="countdown">—</div>
      <div class="ng-cd-label">until kickoff</div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- UPCOMING -->
<section class="section" style="padding-top:50px"><div class="wrap">

  <h2 class="st" style="font-size:clamp(15px,1.8vw,20px);margin-bottom:20px;color:var(--or)">Upcoming <em>Games</em></h2>
  <?php
  $upcoming = new WP_Query([
    'post_type'      => 'stingers_game',
    'post_status'    => 'publish',
    'posts_per_page' => 20,
    'meta_key'       => 'game_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_query'     => [['key' => 'game_result', 'value' => 'upcoming']],
  ]);
  if ($upcoming->have_posts()) : ?>
  <div class="gtable-wrap"><table class="ftb gtable"><thead><tr>
    <th style="min-width:110px">Date</th>
    <th style="min-width:240px">Match</th>
    <th style="min-width:180px">Venue</th>
    <th style="min-width:80px">Time</th>
    <th style="min-width:100px">Status</th>
  </tr></thead><tbody>
    <?php while ($upcoming->have_posts()) : $upcoming->the_post();
      $gtitle    = get_field('game_title');
      $opponent  = get_field('game_opponent');
      $is_home   = get_field('game_home');
      $match_str = $is_home
        ? 'Cairns Stingers <span class="fvs-vs">vs</span> ' . esc_html($opponent)
        : esc_html($opponent) . ' <span class="fvs-vs">vs</span> Cairns Stingers';
    ?>
    <tr>
      <td class="gtd-date"><?php echo esc_html(get_field('game_date')); ?></td>
      <td>
        <?php if ($gtitle) : ?><div class="gtitle"><?php echo esc_html($gtitle); ?></div><?php endif; ?>
        <div class="fvs"><?php echo $match_str; ?></div>
      </td>
      <td class="gtd-venue"><?php echo esc_html(get_field('game_venue')); ?></td>
      <td class="gtd-time"><?php echo esc_html(get_field('game_time')); ?></td>
      <td><span class="fr u">Upcoming</span></td>
    </tr>
    <?php endwhile; wp_reset_postdata(); ?>
  </tbody></table></div>
  <?php else : ?>
    <p style="color:var(--dim);padding:40px 0">No upcoming games scheduled yet.</p>
  <?php endif; ?>
</div></section>

<!-- RESULTS -->
<section class="section" style="padding-top:0"><div class="wrap">
  <?php
  // Season record summary
  $all_results = new WP_Query(['post_type'=>'stingers_game','post_status'=>'publish','posts_per_page'=>-1,'meta_query'=>[['key'=>'game_result','value'=>'upcoming','compare'=>'!=']]]);
  $wins=0;$losses=0;$draws=0;
  if($all_results->have_posts()) while($all_results->have_posts()){$all_results->the_post();$r=get_field('game_result');if($r==='win')$wins++;elseif($r==='loss')$losses++;elseif($r==='draw')$draws++;}wp_reset_postdata();
  ?>
  <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:28px">
    <h2 class="st" style="font-size:clamp(15px,1.8vw,20px);margin:0;color:var(--or)">Recent <em>Results</em></h2>
    <?php if($wins+$losses+$draws>0):?>
    <div style="display:flex;gap:8px;align-items:center;margin-left:4px">
      <span style="background:rgba(0,212,224,.1);color:var(--cy);font-family:var(--df);font-weight:700;font-size:13px;padding:4px 12px;border-radius:var(--rx)"><?php echo $wins?>W</span>
      <span style="background:rgba(255,255,255,.06);color:var(--dim);font-family:var(--df);font-weight:700;font-size:13px;padding:4px 12px;border-radius:var(--rx)"><?php echo $losses?>L</span>
      <?php if($draws>0):?><span style="background:rgba(255,255,255,.06);color:var(--dim);font-family:var(--df);font-weight:700;font-size:13px;padding:4px 12px;border-radius:var(--rx)"><?php echo $draws?>D</span><?php endif;?>
    </div>
    <?php endif;?>
  </div>
  <?php
  $results = new WP_Query([
    'post_type'      => 'stingers_game',
    'post_status'    => 'publish',
    'posts_per_page' => 20,
    'meta_key'       => 'game_date',
    'orderby'        => 'meta_value',
    'order'          => 'DESC',
    'meta_query'     => [['key' => 'game_result', 'value' => 'upcoming', 'compare' => '!=']],
  ]);
  if ($results->have_posts()) : ?>
  <div class="gtable-wrap"><table class="ftb gtable"><thead><tr>
    <th style="min-width:120px">Date</th>
    <th style="min-width:220px">Match</th>
    <th style="min-width:160px">Venue</th>
    <th style="min-width:120px">Score</th>
    <th style="min-width:90px">Result</th>
  </tr></thead><tbody>
    <?php while ($results->have_posts()) : $results->the_post();
      $result  = get_field('game_result');
      $gtitle  = get_field('game_title');
      $venue   = get_field('game_venue');
      // Get date with year — raw meta is stored as Ymd
      $raw     = get_post_meta(get_the_ID(), 'game_date', true);
      $display_date = $raw ? date('j M Y', strtotime($raw)) : get_field('game_date');
    ?>
    <tr>
      <td class="gtd-date"><?php echo esc_html($display_date); ?></td>
      <td>
        <?php if ($gtitle) : ?><div class="gtitle"><?php echo esc_html($gtitle); ?></div><?php endif; ?>
        <div class="fvs">Stingers <span class="fvs-vs">vs</span> <?php echo esc_html(get_field('game_opponent')); ?></div>
      </td>
      <td class="gtd-venue"><?php echo esc_html($venue); ?></td>
      <td class="gtd-score"><?php echo esc_html(get_field('game_score_us')); ?> <span style="color:var(--dim)">—</span> <?php echo esc_html(get_field('game_score_them')); ?></td>
      <td><span class="fr <?php echo $result === 'win' ? 'w' : ($result === 'loss' ? 'l' : ''); ?>"><?php echo ucfirst($result); ?></span></td>
    </tr>
    <?php endwhile; wp_reset_postdata(); ?>
  </tbody></table></div>
  <?php else : ?>
    <p style="color:var(--dim);padding:40px 0">No results yet.</p>
  <?php endif; ?>
</div></section>

<!-- CONTESTS -->
<section class="section" style="padding-top:20px"><div class="wrap">
  <div style="text-align:center;margin-bottom:72px">
    <div class="pill" style="justify-content:center"><span class="pill-dot"></span>Annual Events</div>
    <h2 class="st">Our Yearly <em>Contests</em></h2>
  </div>
  <?php
  $contests = new WP_Query(['post_type' => 'stingers_contest', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC']);
  if ($contests->have_posts()) : ?>
  <div class="cgrid">
    <?php while ($contests->have_posts()) : $contests->the_post(); ?>
    <div class="cc">
      <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('contest-card', ['class' => 'cc-img']); ?>
      <?php else : ?>
        <img class="cc-img" src="<?php echo get_template_directory_uri(); ?>/assets/images/player-action.png" alt="<?php the_title(); ?>"/>
      <?php endif; ?>
      <div class="cc-body">
        <h3><?php the_title(); ?></h3>
        <?php the_content(); ?>
      </div>
    </div>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
  <?php else : ?>
    <p style="text-align:center;color:var(--dim);padding:40px 0">Contest details will appear here once added.</p>
  <?php endif; ?>
</div></section>

<?php get_footer();
