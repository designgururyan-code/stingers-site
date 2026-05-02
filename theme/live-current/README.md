# Cairns Stingers WordPress Theme

## Installation

1. Upload the `cairns-stingers` folder to `wp-content/themes/`
2. Install and activate **ACF Pro** plugin
3. Activate the **Cairns Stingers** theme in Appearance → Themes
4. Theme activation will auto-create player statuses (Active, Retired, Administration) and sponsor tiers (Bronze, Silver, Gold)

## Create Pages

Create these pages in wp-admin → Pages, and assign the matching template:

| Page Title    | Page Slug   | Template       |
|--------------|-------------|----------------|
| Home         | home        | (set as Front Page in Settings → Reading) |
| Players      | players     | Players        |
| Gallery      | gallery     | Gallery        |
| Club Song    | club-song   | Club Song      |
| Games        | games       | Games          |
| Sponsors     | sponsors    | Sponsors       |

## Global Settings

Go to **Stingers Settings** in the wp-admin sidebar to set:
- Site Logo
- Facebook / Instagram URLs
- PlayHQ Registration URL
- Registration Fee ($90)
- Contact Email

## Adding Content

### Players
- Go to **Players → Add New**
- Set title as full name
- Fill in ACF fields: Number, Position, Nickname, Age, Height, etc.
- Set **Surname** field for alphabetical sorting
- Assign **Player Status**: Active, Retired, or Administration
- Set **Player Email** to allow that player to self-edit
- Set Featured Image for player photo

### Games
- Go to **Games → Add New**
- Fill in: Date, Opponent, Venue, Time
- Set Result: Upcoming, Win, Loss, or Draw
- Add scores after the game

### Contests (Annual Events)
- Go to **Contests → Add New**
- Add title, description, and featured image
- Use drag ordering in the admin list

### Sponsors
- Go to **Sponsors → Add New**
- Add sponsor name, logo (Featured Image), website URL
- Assign tier: Bronze, Silver, or Gold

### Gallery
- Edit the Gallery page
- Use the ACF Gallery field to upload/select images

### Club Song
- Edit the Club Song page
- Add lyrics via the WYSIWYG editor
- Upload audio file

### Honour Roll
- Edit the Players page
- Use the Honour Roll repeater to add Year, President, Secretary, Treasurer, Coach

### Home Page
- Edit the Home page (set as Front Page)
- Hero: heading, subheading, background image, video URL
- About: heading, text, image
- Training: day, time, location
- Feature Images: repeater with image, title, description
- CTA: heading, text

## Player Self-Service

### How it works:
1. Player visits the Players page and clicks **"Register Your Profile"**
2. They submit their name and email
3. A **pending** player post is created — admin gets email notification
4. Admin approves the post in wp-admin (Publish it)
5. Player can then click **"Edit Profile"** on the Players page
6. They enter their email → receive a **magic link** via email
7. Clicking the link opens an edit form where they can update their details and photo
8. Link expires after 1 hour

### Admin controls:
- You can assign/change the email on any player post
- You have full access to edit all player details in wp-admin
- Pending registrations show in Players → All Players (filter by Pending)

## Theme Structure

```
cairns-stingers/
├── style.css              (theme header)
├── functions.php          (CPTs, ACF fields, enqueue, helpers)
├── header.php             (nav)
├── footer.php             (footer)
├── front-page.php         (home page)
├── page-players.php       (players + tabs + modals)
├── page-games.php         (fixtures + contests)
├── page-sponsors.php      (tiers + form + partner grid)
├── page-gallery.php       (photo grid + lightbox)
├── page-club-song.php     (lyrics + audio)
├── page.php               (generic page fallback)
├── index.php              (fallback)
├── inc/
│   └── player-frontend.php (AJAX handlers for registration/login/edit)
├── template-parts/
│   └── player-card.php    (reusable player card component)
└── assets/
    ├── css/theme.css      (all styles)
    ├── js/motion-init.js  (animations)
    ├── js/player-frontend.js
    └── images/            (logo, placeholder images)
```
