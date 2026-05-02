<?php
/**
 * Template Name: Sponsors
 */
get_header();
?>

<section class="pb"><div class="wrap">
  <div class="pill" style="justify-content:center"><span class="pill-dot"></span>Partner With Us</div>
  <h1><?php echo esc_html(get_field("sponsors_heading") ?: "Sponsorship Packages"); ?></h1>
  <p>Support grassroots AFL in FNQ and get your brand in front of the Stingers community.</p>
</div></section>

<section class="section" style="padding-top:50px"><div class="wrap">
  <div style="text-align:center;max-width:660px;margin:0 auto 0">
    <h2 class="st" style="font-size:clamp(22px,3vw,34px);color:#fff">Why Sponsor the Stingers?</h2>
    <p style="color:var(--dim);font-size:16px;line-height:1.7">Every sponsorship dollar goes directly into the club — jerseys, equipment, travel, and ground fees.</p>
  </div>

  <div class="sponsor-feats">
    <div class="sponsor-feat">
      <div class="sponsor-feat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg></div>
      <h4>500+ Facebook Members</h4>
      <p>Direct exposure to our engaged community across social media.</p>
    </div>
    <div class="sponsor-feat">
      <div class="sponsor-feat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20.38 3.46L16 2 12 5.5 8 2 3.62 3.46a2 2 0 00-1.34 2.23l.58 3.47a1 1 0 00.99.84H6l-1 8h14l-1-8h2.15a1 1 0 00.99-.84l.58-3.47a2 2 0 00-1.34-2.23z"/></svg></div>
      <h4>Logo on Club Merch</h4>
      <p>Feature your logo on club merchandise, training gear, and playing apparel.</p>
    </div>
    <div class="sponsor-feat">
      <div class="sponsor-feat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
      <h4>Game Day Exposure</h4>
      <p>Brand exposure on banners, signage, and printed media at all home games.</p>
    </div>
  </div>

  <!-- TIER CARDS -->
  <div class="tgrid">
    <div class="tier bronze"><div class="tier-lbl">Bronze Sponsor</div><div class="tier-price">$250</div><div class="tier-per">per season</div>
      <ul class="tier-feat"><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>Logo on club website</li><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>Social media shoutout quarterly</li><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>Name in match-day programs</li><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>Club supporter certificate</li></ul>
      <a href="#form" class="btn btn-ol" style="width:100%;justify-content:center">Get Started</a></div>
    <div class="tier silver"><div class="tier-lbl">Silver Sponsor</div><div class="tier-price">$500</div><div class="tier-per">per season</div>
      <ul class="tier-feat"><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>Everything in Bronze, plus:</li><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>Logo on training apparel</li><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>Featured social media monthly</li><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>Ground signage at home games</li><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>Invite to end-of-season awards</li></ul>
      <a href="#form" class="btn btn-ol" style="width:100%;justify-content:center">Get Started</a></div>
    <div class="tier gold"><div class="tier-lbl">Gold Sponsor</div><div class="tier-price">$1,000+</div><div class="tier-per">per season</div>
      <ul class="tier-feat"><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>Everything in Silver, plus:</li><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>Logo on playing jerseys</li><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>Premium website placement</li><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>Named sponsor of a game day</li><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>Priority digital content</li><li><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6l3 3 5-6"/></svg>VIP invite to all club events</li></ul>
      <a href="#form" class="btn btn-or" style="width:100%;justify-content:center">Become Gold Sponsor</a></div>
  </div>
</div></section>

<!-- ENQUIRY FORM -->
<section class="section" id="form"><div class="wrap">
  <div style="text-align:center;max-width:560px;margin:0 auto 48px">
    <h2 class="st sponsor-pitch" style="font-size:clamp(24px,3vw,36px)">Ready to get your brand in front of <em>Cairns?</em></h2>
    <p style="color:var(--dim);font-size:16px;line-height:1.7">Fill in the form below and we'll be in touch within 48 hours to talk through the best package for your business.</p>
  </div>
  <div class="fcard">
    <div style="text-align:center;margin-bottom:32px">
      <div class="pill" style="justify-content:center"><span class="pill-dot"></span>Get In Touch</div>
      <h2 class="st" style="font-size:clamp(26px,3.5vw,40px)">Sponsorship Enquiry</h2>
    </div>
    <form method="post">
      <div class="frow"><div class="fg"><label>Your Name *</label><input type="text" name="name" required/></div><div class="fg"><label>Business Name *</label><input type="text" name="business" required/></div></div>
      <div class="frow"><div class="fg"><label>Email *</label><input type="email" name="email" required/></div><div class="fg"><label>Phone</label><input type="tel" name="phone"/></div></div>
      <div class="fg"><label>Sponsorship Level</label><select name="tier"><option value="" disabled selected>Select a tier...</option><option>Bronze — $250</option><option>Silver — $500</option><option>Gold — $1,000+</option><option>Not sure yet</option></select></div>
      <div class="fg"><label>Message</label><textarea name="message"></textarea></div>
      <input type="hidden" name="sponsor_enquiry_submit" value="1"/>
      <button type="submit" class="btn btn-or" style="width:100%;justify-content:center">Submit Enquiry <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 6h10M7 2l4 4-4 4"/></svg></button>
    </form>
    <?php if (isset($_GET['enquiry']) && $_GET['enquiry'] === 'sent') : ?>
    <div style="text-align:center;padding:20px;margin-top:24px;background:var(--cyg);border:1px solid var(--cy);border-radius:var(--r);color:var(--cy);font-weight:600">
      Thanks! Your enquiry has been submitted. We'll be in touch shortly.
    </div>
    <?php endif; ?>
  </div>
</div></section>

<!-- CURRENT PARTNERS -->
<section class="section" style="padding-top:0;text-align:center"><div class="wrap">
  <div class="pill" style="justify-content:center"><span class="pill-dot"></span>Thank You</div>
  <h2 class="st" style="margin-bottom:36px;font-size:clamp(22px,3vw,34px)">Our Current <em>Partners</em></h2>

  <?php
  // Build sponsor items array using get_the_post_thumbnail (returns HTML, no ob_start needed)
  $sponsor_items = [];
  $sponsors = new WP_Query(['post_type' => 'stingers_sponsor', 'posts_per_page' => -1, 'post_status' => 'publish']);
  if ($sponsors->have_posts()) :
    while ($sponsors->have_posts()) : $sponsors->the_post();
      $url = get_field('sponsor_url');
      $img = get_the_post_thumbnail(null, 'sponsor-logo', ['style' => 'max-width:100%;max-height:50px;object-fit:contain']);
      if ($img) {
        $item = $url ? '<a href="' . esc_url($url) . '" target="_blank">' . $img . '</a>' : $img;
      } else {
        $item = '<span style="font-size:10px;color:var(--dim);font-weight:600">Your Business<br>Here</span>';
      }
      $sponsor_items[] = $item;
    endwhile;
    wp_reset_postdata();
  endif;

  // Fallback placeholder items if no sponsors yet
  if (empty($sponsor_items)) {
    for ($i = 0; $i < 12; $i++) {
      $sponsor_items[] = '<span style="font-size:10px;color:var(--dim);font-weight:600">Your Business<br>Here</span>';
    }
  }

  // Split into two rows for velocity marquee
  $half = max(ceil(count($sponsor_items) / 2), 1);
  $row1 = array_slice($sponsor_items, 0, $half);
  $row2 = array_slice($sponsor_items, $half);
  if (empty($row2)) $row2 = $row1;
  // Duplicate each row for seamless infinite loop
  $row1_loop = array_merge($row1, $row1);
  $row2_loop = array_merge($row2, $row2);
  ?>

  <div class="vel-marquee-wrap">
    <div class="vel-track vel-fwd">
      <?php foreach ($row1_loop as $item) : ?><div class="vel-item"><?php echo $item; ?></div><?php endforeach; ?>
    </div>
    <div class="vel-track vel-rev">
      <?php foreach ($row2_loop as $item) : ?><div class="vel-item"><?php echo $item; ?></div><?php endforeach; ?>
    </div>
  </div>

</div></section>

<?php get_footer();
