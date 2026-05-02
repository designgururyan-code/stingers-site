<?php
/**
 * Demo Content Installer
 * Creates sample players, games, contests, sponsors, honour roll, and pages on theme activation.
 * Only runs once — checks for a flag in options table.
 */

// Run on admin_init so ACF is fully loaded
add_action('admin_init', function() {
    if (get_option('stingers_demo_installed')) return;
    // Delay until ACF is ready
    // Using update_post_meta directly — no ACF dependency for data creation
    stingers_install_demo_content();
});

function stingers_install_demo_content() {
    // Only run once
    if (get_option('stingers_demo_installed')) return;

    // ── CREATE PAGES ──
    $pages = [
        'Home' => ['slug' => 'home', 'template' => 'front-page.php'],
        'Players' => ['slug' => 'players', 'template' => 'page-players.php'],
        'Gallery' => ['slug' => 'gallery', 'template' => 'page-gallery.php'],
        'Club Song' => ['slug' => 'club-song', 'template' => 'page-club-song.php'],
        'Games' => ['slug' => 'games', 'template' => 'page-games.php'],
        'Sponsors' => ['slug' => 'sponsors', 'template' => 'page-sponsors.php'],
    ];

    $home_id = 0;
    foreach ($pages as $title => $data) {
        if (get_page_by_path($data['slug'])) continue;
        $id = wp_insert_post([
            'post_type' => 'page',
            'post_title' => $title,
            'post_name' => $data['slug'],
            'post_status' => 'publish',
        ]);
        if ($id) {
            update_post_meta($id, '_wp_page_template', $data['template']);
            if ($title === 'Home') $home_id = $id;
        }
    }

    // Set Home as front page
    if ($home_id) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $home_id);
    }

    // ── PLAYER STATUSES ──
    $active_term = term_exists('Active', 'player_status') ?: wp_insert_term('Active', 'player_status');
    $retired_term = term_exists('Retired', 'player_status') ?: wp_insert_term('Retired', 'player_status');
    $admin_term = term_exists('Administration', 'player_status') ?: wp_insert_term('Administration', 'player_status');

    $active_id = is_array($active_term) ? $active_term['term_id'] : $active_term;
    $retired_id = is_array($retired_term) ? $retired_term['term_id'] : $retired_term;
    $admin_id = is_array($admin_term) ? $admin_term['term_id'] : $admin_term;

    // ── SAMPLE PLAYERS ──
    $players = [
        [
            'name' => 'Michael Barnes', 'surname' => 'Barnes', 'number' => 5,
            'position' => 'Midfielder', 'nickname' => 'Barney', 'age' => 42,
            'height' => '183cm', 'years' => 8, 'debut' => '2018',
            'clubs' => 'Cairns Saints', 'awards' => 'B&F 2023',
            'bio' => 'A fierce competitor who leads by example. Known for elite endurance and football IQ.',
            'status' => [$active_id],
        ],
        [
            'name' => 'Tom Burke', 'surname' => 'Burke', 'number' => 21,
            'position' => 'Ruck', 'nickname' => 'Big Unit', 'age' => 44,
            'height' => '196cm', 'years' => 6, 'debut' => '2020',
            'clubs' => 'Innisfail, Gordonvale', 'awards' => '',
            'bio' => 'Dominant ruckman who controls centre bounces and gives first use to the midfield.',
            'status' => [$active_id],
        ],
        [
            'name' => 'Chris Dixon', 'surname' => 'Dixon', 'number' => 3,
            'position' => 'Small Forward', 'nickname' => 'Zippy', 'age' => 39,
            'height' => '175cm', 'years' => 5, 'debut' => '2021',
            'clubs' => 'Cairns City Lions', 'awards' => 'Rising Star 2022',
            'bio' => 'Electric speed and a nose for goal. Creates havoc with pressure inside 50.',
            'status' => [$active_id],
        ],
        [
            'name' => 'Scott Henderson', 'surname' => 'Henderson', 'number' => 12,
            'position' => 'Full Forward', 'nickname' => 'Hendo', 'age' => 45,
            'height' => '190cm', 'years' => 12, 'debut' => '2014',
            'clubs' => 'Manunda Hawks', 'awards' => 'Leading Goalkicker 2024',
            'bio' => 'Dangerous tall forward with safe hands and a booming left foot.',
            'status' => [$active_id],
        ],
        [
            'name' => 'Dave Mitchell', 'surname' => 'Mitchell', 'number' => 8,
            'position' => 'Half Back Flank', 'nickname' => 'Dasher', 'age' => 40,
            'height' => '181cm', 'years' => 6, 'debut' => '2020',
            'clubs' => 'Edmonton', 'awards' => '',
            'bio' => 'Runs all day off half back. Excellent kick and uses the ball well.',
            'status' => [$active_id],
        ],
        [
            'name' => 'James Murphy', 'surname' => 'Murphy', 'number' => 7,
            'position' => 'Centre Half Back', 'nickname' => 'Murph', 'age' => 38,
            'height' => '186cm', 'years' => 3, 'debut' => '2023',
            'clubs' => 'Port Douglas Crocs', 'awards' => '',
            'bio' => 'Rock solid in defence. Reliable intercept mark who reads the play beautifully.',
            'status' => [$active_id],
        ],
        [
            'name' => 'Ryan Walsh', 'surname' => 'Walsh', 'number' => 18,
            'position' => 'Wing', 'nickname' => 'Walshy', 'age' => 41,
            'height' => '180cm', 'years' => 2, 'debut' => '2024',
            'clubs' => 'Atherton', 'awards' => '',
            'bio' => 'Tireless runner. Link between defence and attack.',
            'status' => [$active_id],
        ],
    ];

    foreach ($players as $p) {
        $id = wp_insert_post([
            'post_type' => 'stingers_player',
            'post_title' => $p['name'],
            'post_status' => 'publish',
        ]);
        if (!$id) continue;
        $fields = [
            'player_number' => $p['number'], 'player_position' => $p['position'],
            'player_nickname' => $p['nickname'], 'player_age' => $p['age'],
            'player_height' => $p['height'], 'player_years' => $p['years'],
            'player_debut' => $p['debut'], 'player_clubs' => $p['clubs'],
            'player_awards' => $p['awards'], 'player_bio' => $p['bio'],
            'player_surname' => $p['surname'],
        ];
        foreach ($fields as $key => $val) {
            update_post_meta($id, $key, $val);
            // Also save ACF reference key
            update_post_meta($id, '_' . $key, 'field_' . $key);
        }
        wp_set_object_terms($id, $p['status'], 'player_status');
    }

    // ── ADMINISTRATION ──
    $admins = [
        ['name' => 'President Name', 'position' => 'Club Member', 'admin_role' => 'President', 'status' => [$admin_id]],
        ['name' => 'Vice President Name', 'position' => 'Club Member', 'admin_role' => 'Vice President', 'status' => [$admin_id]],
        ['name' => 'Secretary Name', 'position' => 'Club Member', 'admin_role' => 'Secretary', 'status' => [$admin_id]],
        ['name' => 'Treasurer Name', 'position' => 'Club Member', 'admin_role' => 'Treasurer', 'status' => [$admin_id]],
        ['name' => 'Coach Name', 'position' => 'Club Member', 'admin_role' => 'Senior Coach', 'status' => [$admin_id]],
        ['name' => 'Manager Name', 'position' => 'Club Member', 'admin_role' => 'Team Manager', 'status' => [$admin_id]],
    ];

    foreach ($admins as $a) {
        $id = wp_insert_post(['post_type' => 'stingers_player', 'post_title' => $a['name'], 'post_status' => 'publish']);
        if (!$id) continue;
        $af = ['player_position' => $a['position'], 'player_admin_role' => $a['admin_role'], 'player_surname' => explode(' ', $a['name'])[0]];
        foreach ($af as $key => $val) { update_post_meta($id, $key, $val); update_post_meta($id, '_' . $key, 'field_' . $key); }
        wp_set_object_terms($id, $a['status'], 'player_status');
    }

    // ── RETIRED PLAYERS ──
    $retired = [
        ['name' => 'Retired Player Name', 'position' => 'Midfielder', 'debut' => '2015–2024', 'bio' => 'A club legend. 10 years of outstanding service. B&F winner 2019 and 2021. Mentored countless younger players.'],
        ['name' => 'Retired Player Name', 'position' => 'Full Back', 'debut' => '2012–2023', 'bio' => 'The ultimate backman. Ferocious tackling, shut down the opposition\'s best. Life member of the club.'],
        ['name' => 'Retired Player Name', 'position' => 'Ruck', 'debut' => '2016–2025', 'bio' => 'Dominated the centre square for nearly a decade. Tap work and around-the-ground play second to none.'],
    ];

    foreach ($retired as $r) {
        $id = wp_insert_post(['post_type' => 'stingers_player', 'post_title' => $r['name'], 'post_status' => 'publish']);
        if (!$id) continue;
        $rf = ['player_position' => $r['position'], 'player_debut' => $r['debut'], 'player_bio' => $r['bio'], 'player_surname' => 'Retired'];
        foreach ($rf as $key => $val) { update_post_meta($id, $key, $val); update_post_meta($id, '_' . $key, 'field_' . $key); }
        wp_set_object_terms($id, [$retired_id], 'player_status');
    }

    // ── SAMPLE GAMES ──
    $games = [
        ['title' => 'Stingers vs Innisfail', 'date' => '20260301', 'opponent' => 'Innisfail', 'venue' => 'Walker Road Oval', 'time' => '2:00 PM', 'us' => '12.8 (80)', 'them' => '7.5 (47)', 'result' => 'win', 'home' => 1],
        ['title' => 'Townsville vs Stingers', 'date' => '20260315', 'opponent' => 'Townsville Bulls', 'venue' => 'Riverway Stadium', 'time' => '1:30 PM', 'us' => '8.6 (54)', 'them' => '9.11 (65)', 'result' => 'loss', 'home' => 0],
        ['title' => 'Stingers vs Cairns City', 'date' => '20260329', 'opponent' => 'Cairns City', 'venue' => 'Cazalys Stadium', 'time' => '2:00 PM', 'us' => '14.12 (96)', 'them' => '5.3 (33)', 'result' => 'win', 'home' => 1],
        ['title' => 'Stingers vs Mackay', 'date' => '20260412', 'opponent' => 'Mackay Magpies', 'venue' => 'Cazalys Stadium', 'time' => '2:00 PM', 'us' => '11.9 (75)', 'them' => '10.7 (67)', 'result' => 'win', 'home' => 1],
        ['title' => 'Stingers vs Townsville', 'date' => '20260426', 'opponent' => 'Townsville Bulls', 'venue' => 'Cazalys Stadium', 'time' => '2:00 PM', 'us' => '', 'them' => '', 'result' => 'upcoming', 'home' => 1],
        ['title' => 'Mackay vs Stingers', 'date' => '20260510', 'opponent' => 'Mackay Magpies', 'venue' => 'Harrup Park', 'time' => '1:30 PM', 'us' => '', 'them' => '', 'result' => 'upcoming', 'home' => 0],
        ['title' => 'Stingers vs Tablelands', 'date' => '20260524', 'opponent' => 'Tablelands Eagles', 'venue' => 'Walker Road Oval', 'time' => '2:00 PM', 'us' => '', 'them' => '', 'result' => 'upcoming', 'home' => 1],
    ];

    foreach ($games as $g) {
        $id = wp_insert_post(['post_type' => 'stingers_game', 'post_title' => $g['title'], 'post_status' => 'publish']);
        if (!$id) continue;
        $gf = ['game_date'=>$g['date'],'game_opponent'=>$g['opponent'],'game_venue'=>$g['venue'],'game_time'=>$g['time'],'game_score_us'=>$g['us'],'game_score_them'=>$g['them'],'game_result'=>$g['result'],'game_home'=>$g['home']];
        foreach ($gf as $key => $val) { update_post_meta($id, $key, $val); update_post_meta($id, '_' . $key, 'field_' . $key); }
    }

    // ── CONTESTS ──
    $contests = [
        ['title' => 'AFL Masters National Carnival', 'content' => 'The biggest event on the Masters calendar. Teams from across Australia converge for a week of footy, mateship, and fierce competition. The Stingers have been represented at every carnival since the club\'s founding.'],
        ['title' => 'North Queensland Challenge', 'content' => 'Annual grudge match against regional rivals. Cairns vs Townsville, Mackay, and Tablelands in a round-robin carnival. Home ground advantage alternates each year, bragging rights fiercely contested.'],
        ['title' => 'Cairns Masters Lightning Carnival', 'content' => 'A one-day, fast-paced carnival with shortened games and a festival atmosphere. Open to masters teams from across Queensland — great day of footy, food, and community.'],
        ['title' => 'FNQ Anzac Day Match', 'content' => 'A commemorative match honouring those who served. Combines our love of footy with respect for the sacrifices of servicemen and women — followed by the traditional two-up.'],
        ['title' => 'Pre-Season Challenge', 'content' => 'First hit-out of the year. A practice match to shake off cobwebs, trial new players, and set the tone for the season ahead. Always played in good spirits.'],
        ['title' => 'End of Season Awards Night', 'content' => 'Celebrating the season\'s achievements. Best & Fairest, Leading Goalkicker, Rising Star. A chance for the whole club to come together and reflect on the year.'],
    ];

    foreach ($contests as $i => $c) {
        wp_insert_post([
            'post_type' => 'stingers_contest',
            'post_title' => $c['title'],
            'post_content' => '<p>' . $c['content'] . '</p>',
            'post_status' => 'publish',
            'menu_order' => $i,
        ]);
    }

    // ── SAMPLE SPONSORS (placeholders) ──
    for ($i = 1; $i <= 6; $i++) {
        $tier = $i <= 2 ? 'Gold' : ($i <= 4 ? 'Silver' : 'Bronze');
        $id = wp_insert_post([
            'post_type' => 'stingers_sponsor',
            'post_title' => 'Sponsor ' . $i,
            'post_status' => 'publish',
        ]);
        if ($id) {
            $term = get_term_by('name', $tier, 'sponsor_tier');
            if ($term) wp_set_object_terms($id, $term->term_id, 'sponsor_tier');
        }
    }

    // ── HONOUR ROLL (saved to Players page) ──
    $players_page = get_page_by_path('players');
    if ($players_page) {
        $honour = [
            ['year' => '2026', 'president' => 'President Name', 'secretary' => 'Secretary Name', 'treasurer' => 'Treasurer Name', 'coach' => 'Coach Name'],
            ['year' => '2025', 'president' => 'President Name', 'secretary' => 'Secretary Name', 'treasurer' => 'Treasurer Name', 'coach' => 'Coach Name'],
            ['year' => '2024', 'president' => 'President Name', 'secretary' => 'Secretary Name', 'treasurer' => 'Treasurer Name', 'coach' => 'Coach Name'],
            ['year' => '2023', 'president' => 'President Name', 'secretary' => 'Secretary Name', 'treasurer' => 'Treasurer Name', 'coach' => 'Coach Name'],
            ['year' => '2022', 'president' => 'President Name', 'secretary' => 'Secretary Name', 'treasurer' => 'Treasurer Name', 'coach' => 'Coach Name'],
        ];
        // Store repeater manually
        update_post_meta($players_page->ID, 'honour_rows', count($honour));
        update_post_meta($players_page->ID, '_honour_rows', 'field_honour_rows');
        foreach ($honour as $i => $row) {
            foreach ($row as $key => $val) {
                update_post_meta($players_page->ID, 'honour_rows_' . $i . '_' . $key, $val);
                update_post_meta($players_page->ID, '_honour_rows_' . $i . '_' . $key, 'field_hr_' . $key);
            }
        }
    }

    // ── CLUB SONG DEFAULTS (saved to Club Song page) ──
    $song_page = get_page_by_path('club-song');
    if ($song_page) {
        $lyrics = "<p style='color:#E35926;font-weight:700;font-size:22px'>Oh we're the Stingers, the mighty Stingers!</p>
<p>From the tropics we come to play,</p>
<p>With teal and orange blazing,</p>
<p>We'll sting you on the way!</p>
<br>
<p>Through the heat and through the rain,</p>
<p>We'll always give our best,</p>
<p style='color:#E35926;font-weight:700;font-size:22px'>For the Cairns Stingers, the mighty Stingers,</p>
<p>We're tougher than the rest!</p>
<br>
<p><em>Placeholder lyrics — replace with your official club song</em></p>";
        update_post_meta($song_page->ID, 'song_lyrics', $lyrics);
        update_post_meta($song_page->ID, '_song_lyrics', 'field_song_lyrics');
    }

    // Mark as installed
    update_option('stingers_demo_installed', true);
}

// ── ADMIN BUTTON TO REINSTALL DEMO CONTENT ──
add_action('admin_notices', function() {
    if (!current_user_can('manage_options')) return;
    if (isset($_GET['stingers_reinstall']) && $_GET['stingers_reinstall'] === '1' && isset($_GET['page']) && $_GET['page'] === 'stingers-demo') {
        delete_option('stingers_demo_installed');
        stingers_install_demo_content();
        echo '<div class="notice notice-success"><p>Demo content reinstalled.</p></div>';
    }
});

// Demo page moved to Tools menu (see functions.php)
