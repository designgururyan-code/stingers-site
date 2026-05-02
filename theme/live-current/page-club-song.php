<?php
/**
 * Template Name: Club Song
 */
get_header();
$lyrics = get_field('song_lyrics');
$audio = get_field('song_audio');
$playhq = stingers_playhq_url();
?>

<section class="pb"><div class="wrap">
  <div class="pill" style="justify-content:center"><span class="pill-dot"></span>Pride &amp; Passion</div>
  <h1>Club <span>Song</span></h1>
  <p>Sing it loud, sing it proud.</p>
</div></section>

<section class="section"><div class="wrap"><div style="max-width:760px;margin:0 auto;text-align:center">

  <?php if ($audio) : ?>
  <div class="song-audio">
    <audio controls>
      <source src="<?php echo esc_url($audio); ?>"/>
      Your browser does not support audio playback.
    </audio>
  </div>
  <?php else : ?>
  <div class="song-audio" style="background:var(--c1);border:1px solid var(--bd);border-radius:var(--rl);padding:24px;display:flex;align-items:center;gap:18px;text-align:left;margin-bottom:32px">
    <div style="width:54px;height:54px;border-radius:50%;background:var(--cyg);display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="22" height="22" viewBox="0 0 24 24" fill="var(--cy)"><polygon points="9 5 19 12 9 19"/></svg></div>
    <div><div style="font-family:var(--df);font-weight:700;font-size:18px;margin-bottom:3px">Listen to Our Club Song</div><div style="font-size:14px;color:var(--dim);line-height:1.5">We play this after every home game win — upload an MP3 in wp-admin to add it here.</div></div>
  </div>
  <?php endif; ?>

  <?php if ($lyrics) : ?>
  <div class="song"><?php echo wp_kses_post($lyrics); ?></div>
  <?php else : ?>
  <div class="song">
    <p class="ch">Oh we're the Stingers, the mighty Stingers!</p>
    <p>From the tropics we come to play,</p>
    <p>With teal and orange blazing,</p>
    <p>We'll sting you on the way!</p><br/>
    <p>Through the heat and through the rain,</p>
    <p>We'll always give our best,</p>
    <p class="ch">For the Cairns Stingers, the mighty Stingers,</p>
    <p>We're tougher than the rest!</p>
    <p style="font-size:14px;color:var(--dim);font-weight:400;margin-top:18px"><em>Placeholder — add lyrics in wp-admin</em></p>
  </div>
  <?php endif; ?>
</div></div></section>

<section style="padding-bottom:80px"><div class="wrap"><div class="cta">
  <h2 class="st" style="font-size:clamp(28px,4vw,44px)">Want to Sing It After a <em>Win</em>?</h2>
  <p>Join the Stingers and belt it out with the boys.</p>
  <div class="cta-btns"><a href="<?php echo esc_url($playhq); ?>" target="_blank" class="btn btn-or">Register on PlayHQ</a></div>
</div></div></section>

<?php get_footer();
