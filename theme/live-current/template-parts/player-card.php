<?php
/**
 * Template Part: Player Card — compact grid card + modal on click
 */
$number    = get_field('player_number');
$position  = get_field('player_position');
$nickname  = get_field('player_nickname');
$age       = get_field('player_age');
$height    = get_field('player_height');
$years     = intval(get_field('player_years'));
$debut     = get_field('player_debut');
$clubs     = get_field('player_clubs');
$awards    = get_field('player_awards');
$bio       = get_field('player_bio');
$has_photo = has_post_thumbnail();
$extra_imgs= get_field('player_images');
$surname   = get_field('player_surname');
if (!$surname) {
    $parts   = explode(' ', get_the_title());
    $surname = end($parts);
}

$all_images = [];
if ($has_photo) $all_images[] = get_the_post_thumbnail_url(null, 'player-card') ?: get_the_post_thumbnail_url(null, 'large');
if (!empty($extra_imgs) && is_array($extra_imgs)) {
    foreach ($extra_imgs as $img) {
        $url = is_array($img) ? ($img['sizes']['player-card'] ?? $img['url']) : wp_get_attachment_image_url($img, 'player-card');
        if ($url) $all_images[] = $url;
    }
}

$multi   = count($all_images) > 1;
$is_vet  = $years >= 10;
$is_new  = $years === 1;
$card_id = 'pm-' . get_the_ID();

// Build image data attr for modal
$img_json = esc_attr(json_encode($all_images));
?>

<!-- COMPACT GRID CARD -->
<div class="pcard" 
     data-surname="<?php echo esc_attr(strtoupper(substr($surname, 0, 1))); ?>"
     data-modal="<?php echo $card_id; ?>"
     onclick="openPlayerModal('<?php echo $card_id; ?>')"
     role="button" tabindex="0"
     onkeydown="if(event.key==='Enter')openPlayerModal('<?php echo $card_id; ?>')">

  <div class="pimg">
    <?php if ($number) : ?><div class="pnum">#<?php echo esc_html($number); ?></div><?php endif; ?>
    <?php if (!empty($all_images)) : ?>
      <img src="<?php echo esc_url($all_images[0]); ?>" alt="<?php the_title(); ?>" loading="lazy"/>
    <?php else : ?>
      <div class="sil">
        <svg viewBox="0 0 100 120" xmlns="http://www.w3.org/2000/svg">
          <circle cx="50" cy="32" r="22" fill="#0A1628"/>
          <path d="M8 115 C8 82 26 68 50 68 C74 68 92 82 92 115Z" fill="#0A1628"/>
        </svg>
      </div>
    <?php endif; ?>
  </div>

  <!-- Only name + position visible on card -->
  <div class="pcard-min">
    <div class="pcard-name"><?php the_title(); ?></div>
    <?php if ($position) : ?><div class="pcard-pos"><?php echo esc_html($position); ?></div><?php endif; ?>
  </div>

</div>

<!-- PLAYER MODAL (hidden) -->
<div class="pmodal-bg" id="<?php echo $card_id; ?>" onclick="if(event.target===this)closePlayerModal(this)" aria-modal="true" role="dialog">
  <div class="pmodal-wrap">
    <button class="pmodal-x" onclick="closePlayerModal(document.getElementById('<?php echo $card_id; ?>'))" aria-label="Close">&#x2715;</button>
    <div class="pmodal">

    <!-- Modal image -->
    <div class="pmodal-img<?php echo $multi ? ' pimg-carousel' : ''; ?>">
      <?php if ($number) : ?><div class="pnum">#<?php echo esc_html($number); ?></div><?php endif; ?>

      <?php if ($is_vet) : ?>
      <div class="pbadge pbadge-vet" title="Club Veteran">
        <div class="vet-stars"><?php for ($s=0;$s<3;$s++): ?><svg viewBox="0 0 24 24"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg><?php endfor; ?></div>
        <span class="vet-label">CLUB<br>VETERAN</span>
      </div>
      <?php elseif ($is_new) : ?>
      <div class="pbadge pbadge-new">ROOKIE LIST</div>
      <?php endif; ?>

      <?php if (!empty($all_images)) : ?>
        <?php if ($multi) : ?>
          <div class="pcarousel">
            <?php foreach ($all_images as $i => $src) : ?>
              <img src="<?php echo esc_url($src); ?>" class="pcar-slide<?php echo $i===0?' active':''; ?>" loading="lazy"/>
            <?php endforeach; ?>
          </div>
          <button class="pcar-btn pcar-prev" onclick="pcarMove(this,-1)" aria-label="Prev"><svg viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 1L3 5l4 4"/></svg></button>
          <button class="pcar-btn pcar-next" onclick="pcarMove(this,1)"  aria-label="Next"><svg viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 1l4 4-4 4"/></svg></button>
          <div class="pcar-dots"><?php foreach ($all_images as $i => $src) : ?><span class="pcar-dot<?php echo $i===0?' on':''; ?>"></span><?php endforeach; ?></div>
        <?php else : ?>
          <img src="<?php echo esc_url($all_images[0]); ?>" alt="<?php the_title(); ?>"/>
        <?php endif; ?>
      <?php else : ?>
        <div class="sil"><svg viewBox="0 0 100 120" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="32" r="22" fill="#0A1628"/><path d="M8 115 C8 82 26 68 50 68 C74 68 92 82 92 115Z" fill="#0A1628"/></svg></div>
      <?php endif; ?>
    </div>

    <!-- Modal body -->
    <div class="pmodal-body">
      <div class="pmodal-title">
        <div>
          <div class="pmodal-name"><?php the_title(); ?></div>
          <?php if ($position) : ?><div class="ppos"><?php echo esc_html($position); ?></div><?php endif; ?>
        </div>
      </div>

      <div class="pdets">
        <?php if ($nickname) : ?><div class="pd"><span class="lb">Nickname:</span><span class="vl"><?php echo esc_html($nickname); ?></span></div><?php endif; ?>
        <div class="pdr">
          <?php if ($age)    : ?><div class="pd"><span class="lb">Age:</span><span class="vl"><?php echo esc_html($age); ?></span></div><?php endif; ?>
          <?php if ($height) : ?><div class="pd"><span class="lb">Height:</span><span class="vl"><?php echo esc_html($height); ?></span></div><?php endif; ?>
        </div>
        <div class="pdr">
          <?php if ($years) : ?><div class="pd"><span class="lb">Years:</span><span class="vl"><?php echo esc_html($years); ?></span></div><?php endif; ?>
          <?php if ($debut) : ?><div class="pd"><span class="lb">Debut:</span><span class="vl"><?php echo esc_html($debut); ?></span></div><?php endif; ?>
        </div>
        <?php if ($clubs) : ?><div class="pd pd-exp">
          <span class="lb">Former Clubs:</span>
          <span class="vl"><?php echo esc_html($clubs); ?></span>
        </div><?php endif; ?>
        <?php if ($awards) : ?><div class="pd"><span class="lb">Awards:</span><span class="vl"><?php echo esc_html($awards); ?></span></div><?php endif; ?>
        <?php if ($bio) : ?><div class="pbio-wrap">
          <div class="pbio"><?php echo esc_html($bio); ?></div>
        </div><?php endif; ?>
      </div>
    </div>
  </div>
  </div><!-- /pmodal-wrap -->
</div>
