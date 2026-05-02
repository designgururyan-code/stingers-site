<?php
/**
 * Template Name: Gallery
 */
get_header();
$gallery_images = get_field('gallery_images');
?>

<section class="pb"><div class="wrap">
  <div class="pill" style="justify-content:center"><span class="pill-dot"></span>Memories</div>
  <h1><?php echo esc_html(get_field("gallery_heading") ?: "Photo Gallery"); ?></h1>
  <p>Moments from the field, the rooms, and everything in between.</p>
</div></section>

<section class="section" style="padding-top:50px"><div class="wrap">
  <?php if ($gallery_images) : ?>
  <div class="ggrid">
    <?php foreach ($gallery_images as $img) : ?>
      <?php $cap = $img['caption'] ?: $img['alt'] ?: ($img['title'] ?? ''); ?>
      <div class="gi" data-cap="<?php echo esc_attr($cap); ?>">
        <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt']); ?>"/>
        <div class="gi-cap"><?php echo esc_html($cap); ?></div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php else : ?>
  <div class="ggrid">
    <?php
    // Fallback: show any images from media library tagged with gallery
    $fallback = get_template_directory_uri() . '/assets/images/';
    $placeholders = ['player-action.png', 'player-portrait.webp', 'player-dark.webp'];
    foreach ($placeholders as $p) :
      for ($i = 0; $i < 3; $i++) : ?>
        <div class="gi"><img src="<?php echo $fallback . $p; ?>" alt="Gallery"/></div>
      <?php endfor;
    endforeach; ?>
  </div>
  <?php endif; ?>
</div></section>

<!-- GALLERY MODAL -->
<div class="gmodal" id="gmodal" onclick="if(event.target===this||event.target.id==='gmodal')this.classList.remove('open')">
  <button class="gmodal-x" onclick="document.getElementById('gmodal').classList.remove('open')">&times;</button>
  <div class="gmodal-inner">
    <img id="gimg" src="" alt="Gallery"/>
    <div class="gmodal-cap" id="gmodal-cap"></div>
  </div>
</div>

<script>
document.querySelectorAll('.gi').forEach(function(gi){
  gi.addEventListener('click',function(){
    var img = gi.querySelector('img');
    document.getElementById('gimg').src = img ? img.src : '';
    document.getElementById('gmodal-cap').textContent = gi.getAttribute('data-cap') || '';
    document.getElementById('gmodal').classList.add('open');
  });
});
</script>

<?php get_footer();
