<?php
/**
 * Template Name: Players
 */
get_header();
$edit_player = stingers_get_edit_player();
$playhq = stingers_playhq_url();
?>

<section class="pb"><div class="wrap">
  <div class="pill" style="justify-content:center"><span class="pill-dot"></span><?php echo date('Y'); ?> Season</div>
  <h1><?php echo esc_html(get_field("players_heading") ?: "Cairns Stingers"); ?><br><span><?php echo esc_html(get_field("players_subheading") ?: "Masters Squad"); ?></span></h1>
  <p><?php echo esc_html(get_field("players_desc") ?: "Players, administration, and legends of the Cairns Stingers."); ?></p>
</div></section>

<section class="section" style="padding-top:28px"><div class="wrap">

  <div class="tab-row">
    <div class="tabs-wrap">
      <div class="tabs" id="tabs">
        <span class="tab-pill" id="tab-pill"></span>
        <button class="tab on" onclick="stab('players',this)">
          <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><circle cx="8" cy="5.5" r="2.8"/><path d="M2.5 14c0-3 2.5-5 5.5-5s5.5 2 5.5 5"/></svg>
          Players
        </button>
        <button class="tab" onclick="stab('admin',this)">
          <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M11 3H5a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V5a2 2 0 00-2-2z"/><path d="M6 7h4M6 10h2"/></svg>
          Admin
        </button>
        <button class="tab" onclick="stab('retired',this)">
          <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><circle cx="8" cy="8" r="5.5"/><path d="M8 5.5V8l2 1.5"/></svg>
          Retired
        </button>
        <button class="tab" onclick="stab('honour',this)">
          <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2l1.5 4h4.2l-3.4 2.5 1.3 4L8 10 4.4 12.5l1.3-4L2.3 6H6.5L8 2z"/></svg>
          Honour Roll
        </button>
      </div>
      <div class="tabs-btns">
        <button class="btn btn-gh tab-edit-btn" onclick="document.getElementById('signup-modal').classList.add('open')">
          <svg viewBox="0 0 16 16" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><circle cx="8" cy="6" r="3.5"/><path d="M1.5 14c0-3.6 2.9-6.5 6.5-6.5s6.5 2.9 6.5 6.5"/><path d="M11 3.5l4 4" stroke-width="1.6"/><path d="M13 3.5h2v2" stroke-width="1.6"/></svg>
          Register Profile
        </button>
        <button class="btn btn-gh tab-edit-btn" onclick="document.getElementById('login-modal').classList.add('open')">
          <svg viewBox="0 0 16 16" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M11 2l3 3-8 8H3v-3l8-8z"/></svg>
          Edit Profile
        </button>
      </div>
    </div>
  </div>

  <!-- PLAYERS TAB -->
  <div id="tab-players" class="tc on">
    <!-- Alphabet Quick Nav (mobile) -->
    <div class="alpha-nav" id="alpha-nav"></div>
    <div class="pgrid-wrap">
    <div class="pgrid">
      <?php
      $players = stingers_get_players('Active');
      if ($players->have_posts()) :
        $current_letter = '';
        while ($players->have_posts()) : $players->the_post();
          $surname = get_field('player_surname') ?: '';
          $letter = strtoupper(substr($surname, 0, 1));
          // Letter heading spans full grid width
          if ($letter && $letter !== $current_letter) {
            $current_letter = $letter;
          }
          get_template_part('template-parts/player-card');
        endwhile;
        wp_reset_postdata();
      else :
      ?>
        <p style="grid-column:1/-1;text-align:center;color:var(--dim);padding:60px 0;">No active players yet. <a href="#" onclick="event.preventDefault();document.getElementById('signup-modal').classList.add('open')" style="color:var(--or)">Be the first to register!</a></p>
      <?php endif; ?>
    </div><!-- /pgrid -->
    </div><!-- /pgrid-wrap -->
  </div><!-- /tab-players -->

  <!-- ADMINISTRATION TAB -->
  <div id="tab-admin" class="tc">
    <div class="agrid">
      <?php
      $admins = stingers_get_players('Administration');
      if ($admins->have_posts()) : while ($admins->have_posts()) : $admins->the_post();
        $role = get_field('player_admin_role') ?: get_field('player_position');
      ?>
        <div class="ac">
          <div class="aav"><?php if (has_post_thumbnail()) : ?><?php the_post_thumbnail('thumbnail', ['style' => 'width:100%;height:100%;object-fit:cover;border-radius:50%']); ?><?php else : ?><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--cy)" stroke-width="1.5"><circle cx="12" cy="7" r="4"/><path d="M5.5 21c0-3.6 2.9-6.5 6.5-6.5s6.5 2.9 6.5 6.5"/></svg><?php endif; ?></div>
          <div><h3><?php the_title(); ?></h3><div class="rl"><?php echo esc_html($role); ?></div></div>
        </div>
      <?php endwhile; wp_reset_postdata(); else : ?>
        <p style="color:var(--dim);">No administration members added yet.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- RETIRED TAB -->
  <div id="tab-retired" class="tc">
    <div class="agrid">
      <?php
      $retired = stingers_get_players('Retired');
      if ($retired->have_posts()) : while ($retired->have_posts()) : $retired->the_post();
        $role = get_field('player_position');
        $years = get_field('player_debut');
        $bio = get_field('player_bio');
      ?>
        <div class="ac ret">
          <div class="aav"><?php if (has_post_thumbnail()) : ?><?php the_post_thumbnail('thumbnail', ['style' => 'width:100%;height:100%;object-fit:cover;border-radius:50%']); ?><?php else : ?><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--or)" stroke-width="1.5"><circle cx="12" cy="7" r="4"/><path d="M5.5 21c0-3.6 2.9-6.5 6.5-6.5s6.5 2.9 6.5 6.5"/></svg><?php endif; ?></div>
          <div>
            <h3><?php the_title(); ?></h3>
            <div class="rl"><?php echo esc_html($role); ?> <?php if ($years) echo '— ' . esc_html($years); ?></div>
            <?php if ($bio) : ?><div class="desc"><?php echo esc_html($bio); ?></div><?php endif; ?>
          </div>
        </div>
      <?php endwhile; wp_reset_postdata(); else : ?>
        <p style="color:var(--dim);">No retired players added yet.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- HONOUR ROLL TAB -->
  <div id="tab-honour" class="tc">
    <div style="text-align:left;margin-bottom:36px">
      <h2 class="st" style="font-size:clamp(22px,2.8vw,32px);color:var(--or)">Club Honour Roll</h2>
      <p style="color:var(--dim);font-size:15px">The people who have led the Stingers through the years.</p>
    </div>
    <?php if (have_rows('honour_rows')) : ?>
    <div style="overflow-x:auto"><table class="htb"><thead>
      <tr><th>Year</th><th>President</th><th>Secretary</th><th>Treasurer</th><th>Coach</th></tr>
    </thead><tbody>
      <?php while (have_rows('honour_rows')) : the_row(); ?>
      <tr>
        <td><?php echo esc_html(get_sub_field('year')); ?></td>
        <td><?php echo esc_html(get_sub_field('president')); ?></td>
        <td><?php echo esc_html(get_sub_field('secretary')); ?></td>
        <td><?php echo esc_html(get_sub_field('treasurer')); ?></td>
        <td><?php echo esc_html(get_sub_field('coach')); ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody></table></div>
    <?php else : ?>
      <p style="text-align:center;color:var(--dim);">Honour roll entries will appear here once added in the backend.</p>
    <?php endif; ?>
  </div>

</div></section>

<!-- SIGNUP MODAL -->
<div class="modal-bg" id="signup-modal" onclick="if(event.target===this)this.classList.remove('open')"><div class="modal">
  <button class="modal-x" onclick="document.getElementById('signup-modal').classList.remove('open')">&times;</button>
  <div style="text-align:center;margin-bottom:24px">
    <img src="<?php echo esc_url(stingers_logo_url()); ?>" alt="Stingers" style="height:50px;margin:0 auto 14px"/>
    <h3 style="font-family:var(--df);font-weight:800;font-size:26px">Register Your Profile</h3>
    <p style="color:var(--dim);font-size:13px;margin-top:5px">Submit your details and we'll add you to the squad page.</p>
  </div>
  <div id="signup-form">
    <div class="fg"><label>Full Name *</label><input type="text" id="reg-name" placeholder="Your full name"/></div>
    <div class="fg"><label>Email Address *</label><input type="email" id="reg-email" placeholder="yourname@email.com"/></div>
    <button class="btn btn-or" style="width:100%;justify-content:center" onclick="registerPlayer()">Submit Registration</button>
    <div id="reg-msg" style="margin-top:14px;text-align:center;font-size:14px;display:none"></div>
  </div>
</div></div>

<!-- LOGIN MODAL -->
<div class="modal-bg" id="login-modal" onclick="if(event.target===this)this.classList.remove('open')"><div class="modal">
  <button class="modal-x" onclick="document.getElementById('login-modal').classList.remove('open')">&times;</button>
  <div style="text-align:center;margin-bottom:24px">
    <img src="<?php echo esc_url(stingers_logo_url()); ?>" alt="Stingers" style="height:50px;margin:0 auto 14px"/>
    <h3 style="font-family:var(--df);font-weight:800;font-size:26px">Player Login</h3>
    <p style="color:var(--dim);font-size:13px;margin-top:5px">Enter your email and we'll send you an edit link.</p>
  </div>
  <div id="login-form">
    <div class="fg"><label>Email Address</label><input type="email" id="login-email" placeholder="yourname@email.com"/></div>
    <button class="btn btn-or" style="width:100%;justify-content:center" onclick="loginPlayer()">Send Edit Link</button>
    <div id="login-msg" style="margin-top:14px;text-align:center;font-size:14px;display:none"></div>
  </div>
</div></div>

<!-- EDIT FORM (shown when player clicks magic link) -->
<?php if ($edit_player) : ?>
<div class="modal-bg open" id="edit-modal"><div class="modal" style="max-width:540px">
  <button class="modal-x" onclick="document.getElementById('edit-modal').classList.remove('open')">&times;</button>
  <div style="text-align:center;margin-bottom:24px">
    <h3 style="font-family:var(--df);font-weight:800;font-size:26px">Edit Your Profile</h3>
    <p style="color:var(--dim);font-size:13px"><?php echo esc_html($edit_player->post_title); ?></p>
  </div>
  <form id="edit-form" enctype="multipart/form-data">
    <input type="hidden" name="player_id" value="<?php echo $edit_player->ID; ?>"/>
    <input type="hidden" name="token" value="<?php echo esc_attr($_GET['token']); ?>"/>
    <div class="fg"><label>Nickname</label><input type="text" name="nickname" value="<?php echo esc_attr(get_field('player_nickname', $edit_player->ID)); ?>"/></div>
    <div class="fg"><label>Position</label><input type="text" name="position" value="<?php echo esc_attr(get_field('player_position', $edit_player->ID)); ?>"/></div>
    <div class="frow">
      <div class="fg"><label>Age</label><input type="number" name="age" value="<?php echo esc_attr(get_field('player_age', $edit_player->ID)); ?>"/></div>
      <div class="fg"><label>Height</label><input type="text" name="height" value="<?php echo esc_attr(get_field('player_height', $edit_player->ID)); ?>"/></div>
    </div>
    <div class="fg"><label>Former Clubs</label><input type="text" name="clubs" value="<?php echo esc_attr(get_field('player_clubs', $edit_player->ID)); ?>"/></div>
    <div class="fg"><label>Bio</label><textarea name="bio" rows="3"><?php echo esc_textarea(get_field('player_bio', $edit_player->ID)); ?></textarea></div>
    <div class="fg"><label>Profile Photo</label><input type="file" name="photo" accept="image/*" style="color:var(--dim)"/></div>
    <button type="submit" class="btn btn-or" style="width:100%;justify-content:center">Save Changes</button>
    <div id="edit-msg" style="margin-top:14px;text-align:center;font-size:14px;display:none"></div>
  </form>
</div></div>
<?php endif; ?>

<script>
// ── SLIDING PILL TABS ────────────────────────────────
function stab(id, btn) {
  document.querySelectorAll('.tc').forEach(c => c.classList.remove('on'));
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('on'));
  document.getElementById('tab-' + id).classList.add('on');
  btn.classList.add('on');
  movePill(btn);
}
function movePill(btn) {
  var pill = document.getElementById('tab-pill');
  if (!pill || !btn) return;
  // Use offsetLeft/offsetWidth — relative to the scroll container,
  // not viewport, so it works correctly when tabs are scrolled on mobile
  pill.style.width  = btn.offsetWidth  + 'px';
  pill.style.height = btn.offsetHeight + 'px';
  pill.style.left   = btn.offsetLeft   + 'px';
  pill.style.top    = btn.offsetTop    + 'px';
}
// Init pill position on load
document.addEventListener('DOMContentLoaded', function() {
  var active = document.querySelector('.tab.on');
  if (active) movePill(active);
  // Delay slightly so fonts are loaded and line heights are accurate
  setTimeout(initExpandButtons, 200);
});
window.addEventListener('resize', function() {
  var active = document.querySelector('.tab.on');
  if (active) movePill(active);
});

// ── CAROUSEL ────────────────────────────────────────
function pcarMove(btn, dir) {
  var container = btn.closest('.pimg') || btn.closest('.pmodal-img');
  var car = container.querySelector('.pcarousel');
  var slides = car.querySelectorAll('.pcar-slide');
  var dots   = container.querySelectorAll('.pcar-dot');
  var cur = 0;
  slides.forEach(function(s, i) { if (s.classList.contains('active')) cur = i; });
  slides[cur].classList.remove('active');
  if (dots[cur]) dots[cur].classList.remove('on');
  cur = (cur + dir + slides.length) % slides.length;
  slides[cur].classList.add('active');
  if (dots[cur]) dots[cur].classList.add('on');
}

// Touch swipe for mobile carousel
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.pimg-carousel,.pmodal-img.pimg-carousel').forEach(function(pimg) {
    var startX = 0;
    pimg.addEventListener('touchstart', function(e) { startX = e.touches[0].clientX; }, {passive:true});
    pimg.addEventListener('touchend', function(e) {
      var dx = e.changedTouches[0].clientX - startX;
      if (Math.abs(dx) > 40) {
        var fakeBtn = pimg.querySelector(dx < 0 ? '.pcar-next' : '.pcar-prev');
        if (fakeBtn) pcarMove(fakeBtn, dx < 0 ? 1 : -1);
      }
    }, {passive:true});
  });
});

// ── PLAYER MODAL ─────────────────────────────────────
function openPlayerModal(id) {
  var modal = document.getElementById(id);
  if (!modal) return;
  modal.classList.add('open');
  document.body.style.overflow = 'hidden';
  // Re-run expand button check for this modal
  setTimeout(function() {
    modal.querySelectorAll('.pe-clubs').forEach(function(el) {
      var btn = el.closest('.pd-exp') ? el.closest('.pd-exp').querySelector('.pe-clubs-btn') : null;
      if (btn && el.scrollHeight > el.clientHeight + 2) btn.style.display = 'inline-flex';
    });
    modal.querySelectorAll('.pe-bio').forEach(function(el) {
      var wrap = el.closest('.pbio-wrap');
      var btn = wrap ? wrap.querySelector('.pe-bio-btn') : null;
      if (btn && el.scrollHeight > el.clientHeight + 2) btn.style.display = 'inline-flex';
    });
  }, 50);
}
function closePlayerModal(bg) {
  bg.classList.remove('open');
  document.body.style.overflow = '';
}
// Close on Escape
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    document.querySelectorAll('.pmodal-bg.open').forEach(function(m) { closePlayerModal(m); });
  }
});
function peToggle(btn) {
  var isClubs = btn.classList.contains('pe-clubs-btn');
  var text = isClubs
    ? btn.closest('.pd-exp').querySelector('.pe-text')
    : btn.closest('.pbio-wrap').querySelector('.pe-text');
  var expanded = text.classList.contains('expanded');
  text.classList.toggle('expanded', !expanded);
  btn.classList.toggle('open', !expanded);
}

// Show expand buttons only when content actually overflows its clamp
function initExpandButtons() {
  // Clubs: show button only if clamped to more than 2 lines
  document.querySelectorAll('.pe-clubs').forEach(function(el) {
    var btn = el.closest('.pd-exp') ? el.closest('.pd-exp').querySelector('.pe-clubs-btn') : null;
    if (!btn) return;
    if (el.scrollHeight > el.clientHeight + 2) {
      btn.style.display = 'inline-flex';
    }
  });
  // Bio: show button only if clamped to more than 3 lines
  document.querySelectorAll('.pe-bio').forEach(function(el) {
    var wrap = el.closest('.pbio-wrap');
    var btn = wrap ? wrap.querySelector('.pe-bio-btn') : null;
    if (!btn) return;
    if (el.scrollHeight > el.clientHeight + 2) {
      btn.style.display = 'inline-flex';
    }
  });
}

// ── REGISTER / LOGIN ────────────────────────────────
function registerPlayer() {
  var name = document.getElementById('reg-name').value;
  var email = document.getElementById('reg-email').value;
  var msg = document.getElementById('reg-msg');
  if (!name || !email) { msg.style.display='block'; msg.style.color='var(--or)'; msg.textContent='Please fill in both fields.'; return; }
  var fd = new FormData(); fd.append('action','stingers_register_player'); fd.append('nonce',stingersAjax.nonce); fd.append('name',name); fd.append('email',email);
  fetch(stingersAjax.url,{method:'POST',body:fd}).then(r=>r.json()).then(d=>{
    msg.style.display='block'; msg.style.color=d.success?'var(--cy)':'var(--or)'; msg.textContent=d.data;
  });
}
function loginPlayer() {
  var email = document.getElementById('login-email').value;
  var msg = document.getElementById('login-msg');
  if (!email) { msg.style.display='block'; msg.style.color='var(--or)'; msg.textContent='Please enter your email.'; return; }
  var fd = new FormData(); fd.append('action','stingers_player_login'); fd.append('nonce',stingersAjax.nonce); fd.append('email',email);
  fetch(stingersAjax.url,{method:'POST',body:fd}).then(r=>r.json()).then(d=>{
    msg.style.display='block'; msg.style.color=d.success?'var(--cy)':'var(--or)'; msg.textContent=d.data;
  });
}

<?php if ($edit_player) : ?>
document.getElementById('edit-form').addEventListener('submit', function(e) {
  e.preventDefault();
  var fd = new FormData(this); fd.append('action','stingers_update_player'); fd.append('nonce',stingersAjax.nonce);
  var msg = document.getElementById('edit-msg');
  fetch(stingersAjax.url,{method:'POST',body:fd}).then(r=>r.json()).then(d=>{
    msg.style.display='block'; msg.style.color=d.success?'var(--cy)':'var(--or)'; msg.textContent=d.data;
    if (d.success) setTimeout(()=>location.href=location.pathname, 1500);
  });
});
<?php endif; ?>

// ── ALPHABET NAV ─────────────────────────────────────
(function() {
  var nav = document.getElementById('alpha-nav');
  if (!nav) return;
  var cards = document.querySelectorAll('#tab-players .pcard[data-surname]');
  var letters = {};
  cards.forEach(function(c) { var l = c.getAttribute('data-surname'); if (l) letters[l] = true; });
  var abc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  for (var i = 0; i < abc.length; i++) {
    var a = document.createElement('a');
    a.textContent = abc[i]; a.href = '#';
    if (!letters[abc[i]]) { a.className = 'disabled'; }
    else {
      a.setAttribute('data-letter', abc[i]);
      a.addEventListener('click', function(e) {
        e.preventDefault();
        var target = document.querySelector('#tab-players .pcard[data-surname="' + this.getAttribute('data-letter') + '"]');
        if (target) target.scrollIntoView({behavior:'smooth', block:'center'});
        nav.querySelectorAll('a').forEach(function(x) { x.classList.remove('active'); });
        this.classList.add('active');
      });
    }
    nav.appendChild(a);
  }
})();
</script>

<?php get_footer();
