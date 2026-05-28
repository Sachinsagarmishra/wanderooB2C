# Wanderoo Package Import Playbook

Use this document when the user gives a competitor package URL and asks to add a similar package to Wanderoo.

The goal is to add a complete package into the live Wanderoo website with:

- SEO rewritten package content
- destination mapping
- main package record
- exactly 2 package tags
- highlights
- day-wise itinerary
- inclusions and exclusions
- main gallery images
- day-wise images
- GitHub push when project files/assets change
- remote MySQL update

Keep credit usage low. Do not over-browse, do not re-read unrelated files, and do not ask unnecessary questions.

## Current Project Facts

- Repo path:
  `/Users/sachinsagar/Desktop/Clients/wanderoo b2c/wanderoo2.0-da627cc1a529add89aab4a0c8a6edeee529fb899`
- Git remote:
  `https://github.com/Sachinsagarmishra/wanderooB2C.git`
- Main branch:
  `main`
- Live site:
  `https://wanderoo.world`
- Hostinger deploys from GitHub, so local file changes must be committed and pushed.
- Remote MySQL database is already configured in Hostinger Remote MySQL.
- Database name:
  `u829703776_world`
- DB credentials are in `config.php`.
- For local/agent remote MySQL access use host:
  `82.25.121.32`
- On Hostinger/server runtime `DB_HOST` may remain `localhost`.
- Local machine may not have `php` or `mysql` CLI installed.
- Use temporary Node scripts with `mysql2` from `/private/tmp/wanderoo-mysql-check` when needed.

## Important Tables

Main package tables:

- `destinations`
- `tour_packages`
- `package_tags`
- `package_highlights`
- `package_days`
- `package_inclusions`
- `package_photos`
- `package_day_images`

Core table meanings:

- `destinations`: destination page data. `slug` must match package `destination`.
- `tour_packages`: one row per package.
- `package_tags`: chips shown on package cards/detail page.
- `package_highlights`: bullet highlights on detail page.
- `package_days`: day-wise itinerary text.
- `package_inclusions`: both inclusions and exclusions using `type`.
- `package_photos`: gallery images.
- `package_day_images`: images under each itinerary day.

## Package URL Shape

Wanderoo package detail URL:

```text
https://wanderoo.world/{destination_slug}/{package_slug}
```

Example:

```text
https://wanderoo.world/thailand/romantic-thailand-escape-krabi-phi-phi-phuket
```

## Low-Credit Workflow

Follow this order. It avoids repeated context and repeated network calls.

1. Read only these local files if needed:
   - `config.php`
   - `database.sql`
   - `package-detail.php`
   - `admin/save-package.php` only if schema/form behavior is unclear
2. Fetch competitor HTML once with `curl -L`.
3. Extract text and image URLs from that saved HTML locally.
4. Query DB once for destinations and existing package slugs.
5. Build a single upsert script that inserts/updates package and child tables in one transaction.
6. Download required images only once into `uploads/packages/{package_id}/`.
7. Commit and push image/code changes only if local files changed.
8. Verify live URL and one or two image URLs with `curl -I` or `curl -L`.
9. After GitHub push and live-site verification, remove the local working-copy image files from the Mac without committing those deletions. This keeps the Mac clean while GitHub/Hostinger still have the images.

Avoid:

- repeated browser screenshots unless layout is broken
- full-site crawling
- reading all admin files
- using a browser if `curl` is enough
- manually clicking admin panel
- asking for confirmation when the user already asked to proceed

## Rights And Image Handling

If the user asks for competitor images:

- Do not assume rights by default.
- If the user confirms they have permission or says the competitor uses copyright-free images and asks to proceed, treat that as user-provided permission.
- Prefer extracting original/CDN image URLs from the competitor page HTML.
- Download images into the Wanderoo repo under:

```text
uploads/packages/{package_id}/
```

Use clean filenames:

```text
hong-island.webp
wat-kaew-krabi.webp
day-01-krabi-cha-da.webp
day-02-khlong-nam-sai-lagoon.webp
```

Then update DB paths to local relative paths:

```text
uploads/packages/{package_id}/hong-island.webp
```

Do not leave important package images pointing to competitor CDN if the user wants the images "in my website/database".

## Local Image Cleanup Rule

The user does not want downloaded package images to remain on the Mac after they are safely pushed and visible on the website.

For every package image import:

1. Download images into `uploads/packages/{package_id}/`.
2. Commit and push those images to GitHub.
3. Verify the live site serves at least the hero image with `HTTP/2 200`.
4. Then remove the local working-copy image files from the Mac.

Important: do not commit the local deletions. The images must stay in GitHub and on Hostinger.

Recommended cleanup approach after successful push and live verification:

```bash
git update-index --skip-worktree uploads/packages/{package_id}/*
rm uploads/packages/{package_id}/*
```

Why:

- GitHub keeps the committed image files.
- Hostinger keeps/deploys the image files from GitHub.
- The local Mac frees the image space.
- `skip-worktree` prevents `git status` from showing local deletions as pending deletes.

If an agent later needs to replace or edit those local images, first undo skip-worktree:

```bash
git update-index --no-skip-worktree uploads/packages/{package_id}/*
git checkout -- uploads/packages/{package_id}
```

Never run a broad cleanup like `rm -rf uploads/packages` because older live package assets may be affected locally and create confusing Git state.

## Content Rewrite Rules

Do not copy competitor text verbatim.

Rewrite in Wanderoo style:

- premium but simple
- clear for Indian travelers
- couple/honeymoon friendly when relevant
- SEO optimized without keyword stuffing
- descriptive enough for Google
- clean day-wise structure
- natural CTAs are already handled by the site, so do not add CTA spam inside content
- inclusions and exclusions must be short, simple, and easy English

Keep the same package concept, route, duration, price, major places, and activities when requested.

Recommended fields:

- `title`: clear package name, not identical to competitor if possible
- `slug`: concise SEO slug
- `meta_title`: under around 60 chars if possible
- `meta_description`: around 150-160 chars if possible
- `focus_keywords`: comma-separated target queries
- `description`: short summary paragraph
- `overview`: longer planning paragraph
- `duration`: e.g. `8 days & 7 nights`
- `old_price`, `price`, `save_text`: preserve competitor values only if user wants same pricing
- `rating`, `rating_count`: can mirror visible values if user asked to recreate package

## Inclusions And Exclusions Rule

Write inclusions and exclusions in very simple English.

The target reader may not be comfortable with long travel/legal wording. Each point should be quick to read and easy to understand.

Rules:

- Use short points only.
- Keep each point ideally under 6-8 words.
- Use plain words.
- Avoid legal/travel jargon.
- Avoid long sentences.
- Avoid commas where possible.
- Avoid words like `quotation`, `applicable`, `explicitly`, `surcharge`, `itinerary customisation`, `payable directly`.
- Do not write full paragraphs.
- Do not combine too many things in one point.
- Use 6-8 inclusions and 6-8 exclusions unless the user asks otherwise.
- Each point should fit cleanly in the package detail section without looking heavy.

Good inclusion examples:

```text
Airport pickup and drop
Hotel stay included
Daily breakfast
Sightseeing transfers
Activity tickets included
Local guide support
Ferry tickets included
Trip expert support
```

Good exclusion examples:

```text
Flights not included
Visa fee not included
Travel insurance extra
Personal shopping extra
Lunch and dinner extra
Tips not included
Hotel taxes extra
Room upgrades extra
```

Bad inclusion examples:

```text
Private and shared transfers in air-conditioned vehicles
Accommodation in Bangkok, Chiang Mai and Krabi as per selected hotel category
Professional local guides for included tours, wherever applicable
Lunch on selected activity days as specified in the final voucher
Wanderoo destination expert support for itinerary customisation
```

Bad exclusion examples:

```text
International and domestic flights unless specifically added to the quotation
Thailand visa charges, travel insurance and passport-related expenses
Hotel city taxes, tourism fees or local charges payable directly at the property
Peak-season, public-holiday or last-minute surcharges, if applicable
Room, flight or activity upgrades requested after confirmation
```

When rewriting competitor inclusions/exclusions:

- Convert meaning, not wording.
- Split complex points into shorter simple points only if needed.
- If a point becomes too long, shorten it aggressively.
- Prefer user-friendly wording over exact supplier wording.

## Package Tag Rule

Use exactly 2 tags per imported package. More tags create spacing/card-height issues on destination cards.

Rules:

- Always insert only 2 rows into `package_tags`.
- Tags should be SEO useful and visible-card friendly.
- Prefer one broad intent tag and one destination/route/theme tag.
- Keep tags short, ideally 1-3 words.
- Do not add city lists as separate tags.
- Do not add generic filler tags if they do not help SEO or card scanning.

Good examples:

```text
Fully Customizable
Honeymoon
```

```text
Fully Customizable
Offbeat Thailand
```

```text
Family Friendly
Singapore
```

Bad examples:

```text
Fully Customizable
Couples
Offbeat
Bangkok
Chiang Mai
Krabi
Culture & Nature
```

When choosing tags:

- Romantic/couple package: `Fully Customizable`, `Honeymoon`
- Offbeat/culture package: `Fully Customizable`, `Offbeat Thailand`
- Family package: `Family Friendly`, destination name
- Luxury package: `Luxury Escape`, destination name

## SEO Template

For a package like Thailand couples itinerary:

```text
Title:
Romantic Thailand Escape: Krabi, Phi Phi & Phuket

Slug:
romantic-thailand-escape-krabi-phi-phi-phuket

Meta title:
Romantic Thailand Package for Couples | 7 Nights Krabi, Phi Phi & Phuket

Meta description:
Book a 7-night romantic Thailand package for couples covering Krabi, Phi Phi Island and Phuket with island tours, lagoon kayaking, private transfers, beach time and curated honeymoon experiences.

Focus keywords:
Thailand package for couples, Thailand honeymoon package, Krabi Phi Phi Phuket itinerary, romantic Thailand tour package, 7 nights Thailand package, Phuket honeymoon package, Krabi honeymoon package
```

Adapt by destination and package theme.

## DB Insert/Update Strategy

Use an upsert-like transaction.

1. Check destination exists:

```sql
SELECT id FROM destinations WHERE slug = ?;
```

If not found:

- If the destination is clearly missing and enough source info exists, insert a basic destination row.
- Otherwise ask user whether to create the destination.

2. Check package by slug:

```sql
SELECT id FROM tour_packages WHERE slug = ?;
```

3. If exists, update `tour_packages`.
4. If not exists, insert `tour_packages` and capture `insertId`.
5. Replace child rows for that package:

```sql
DELETE FROM package_tags WHERE package_id = ?;
DELETE FROM package_highlights WHERE package_id = ?;
DELETE FROM package_days WHERE package_id = ?;
DELETE FROM package_inclusions WHERE package_id = ?;
DELETE FROM package_photos WHERE package_id = ?;
DELETE FROM package_day_images WHERE package_id = ?;
```

6. Insert fresh child rows.
7. Commit.

This makes reruns safe and avoids duplicated highlights/days/images.

When reinserting `package_tags`, insert only the final 2 selected tags.

## Temporary Node Setup

If `mysql2` is not available:

```bash
npm install mysql2 --prefix /private/tmp/wanderoo-mysql-check
```

Use network escalation if sandbox blocks npm.

When using Node scripts:

- Store scripts in `/private/tmp/wanderoo-mysql-check`.
- Do not commit temp scripts.
- Read DB credentials from `config.php`.
- Use host `82.25.121.32` for remote MySQL.

Credential parser pattern:

```js
const fs = require('fs');
const cfg = fs.readFileSync('/absolute/path/to/config.php', 'utf8');
const val = (key) => (cfg.match(new RegExp("define\\('" + key + "',\\s*'([^']*)'\\)")) || [])[1];
```

Connection:

```js
const mysql = require('mysql2/promise');
const conn = await mysql.createConnection({
  host: '82.25.121.32',
  user: val('DB_USER'),
  password: val('DB_PASS'),
  database: val('DB_NAME'),
  connectTimeout: 12000,
});
```

## Competitor Page Extraction

Fetch once:

```bash
curl -L -o /private/tmp/wanderoo-mysql-check/source.html COMPETITOR_URL
```

Extract image URLs:

```bash
node -e "const fs=require('fs'); const html=fs.readFileSync('/private/tmp/wanderoo-mysql-check/source.html','utf8'); const urls=[...html.matchAll(/https:\\/\\/[^\\\"'<> )]+\\.(?:webp|jpg|jpeg|png|svg)/gi)].map(m=>m[0]); console.log([...new Set(urls)].join('\\n'));"
```

For Webflow pages, useful image URLs often look like:

```text
https://cdn.prod.website-files.com/.../*.webp
```

Extract day-wise image mapping by searching around "Day 01", "Trip Details", or image `alt` text.

Use `rg` on saved HTML:

```bash
rg -n "Day 01|Day 02|Trip Details|img src|webp" /private/tmp/wanderoo-mysql-check/source.html
```

## Image Download Script Pattern

Use a small Node downloader. This avoids shell quoting issues with spaces and encoded URLs.

```js
const fs = require('fs');
const https = require('https');
const path = require('path');

const outDir = '/absolute/repo/uploads/packages/PACKAGE_ID';
const images = [
  ['hero.webp', 'https://example.com/source-image.webp'],
  ['day-01.webp', 'https://example.com/day-01.webp'],
];

function download(url, file) {
  return new Promise((resolve, reject) => {
    https.get(url, (response) => {
      if (response.statusCode !== 200) {
        response.resume();
        reject(new Error(`${response.statusCode} ${url}`));
        return;
      }
      const stream = fs.createWriteStream(file);
      response.pipe(stream);
      stream.on('finish', () => stream.close(resolve));
      stream.on('error', reject);
    }).on('error', reject);
  });
}

(async () => {
  fs.mkdirSync(outDir, { recursive: true });
  for (const [filename, url] of images) {
    await download(url, path.join(outDir, filename));
  }
})();
```

After download, stage/commit/push the `uploads/packages/{package_id}` folder. Once live image URLs are verified, clean local image files using the Local Image Cleanup Rule above.

## DB Image Mapping

Set hero image:

```sql
UPDATE tour_packages
SET hero_image = ?, hero_image_alt = ?
WHERE id = ?;
```

Gallery:

```sql
INSERT INTO package_photos
  (package_id, image_path, alt_text, sort_order)
VALUES (?, ?, ?, ?);
```

Day images:

```sql
INSERT INTO package_day_images
  (package_id, day_number, image_path, alt_text, sort_order)
VALUES (?, ?, ?, ?, ?);
```

Use relative paths:

```text
uploads/packages/10/hong-island.webp
```

Do not use absolute file paths in DB.

## Example: Thailand Package Mapping

Competitor URL:

```text
https://www.30sundays.club/packages/thailand-for-the-romantics-one-with-enough-us-time
```

Wanderoo package created:

```text
https://wanderoo.world/thailand/romantic-thailand-escape-krabi-phi-phi-phuket
```

Package:

- Destination: `thailand`
- Title: `Romantic Thailand Escape: Krabi, Phi Phi & Phuket`
- Slug: `romantic-thailand-escape-krabi-phi-phi-phuket`
- Duration: `8 days & 7 nights`
- Price: `₹43,299`
- Old price: `₹55,299`
- Save text: `SAVE ₹12,000`
- Rating: `4.8`
- Rating count: `165`

Gallery images:

- `hong-island.webp`
- `wat-kaew-krabi.webp`
- `khlong-nam-sai-lagoon-gallery.webp`
- `phuket-beach.webp`
- `freedom-beach.webp`
- `chalong-temple.webp`
- `phi-phi-don.webp`

Day-wise images:

- Day 01: `day-01-krabi-cha-da.webp`
- Day 02: `day-02-khlong-nam-sai-lagoon.webp`
- Day 03: `day-03-plankton-snorkeling.webp`
- Day 04: `day-04-phi-phi-island.webp`
- Day 05: `day-05-viking-cave.webp`
- Day 06: `day-06-chanlai-hill-side-phuket.webp`
- Day 07: `day-07-old-phuket-town.webp`
- Day 08: `day-08-departure-bangkok.webp`

## Code Notes

`package-detail.php` supports external and local image URLs through `package_image_url()`.

Important behavior:

- External image URLs must not get a leading `/`.
- Local image paths should render as `SITE_PATH . '/' . path`.
- Gallery and day images render from DB.

If images are broken, inspect:

- `tour_packages.hero_image`
- `package_photos.image_path`
- `package_day_images.image_path`
- whether files exist in repo and on live site
- whether Hostinger has deployed latest GitHub commit

## Verification Checklist

After DB update and GitHub push:

1. Check package route:

```bash
curl -I https://wanderoo.world/{destination}/{slug}
```

Expected:

```text
HTTP/2 200
```

2. Check rendered HTML references local image paths:

```bash
curl -L https://wanderoo.world/{destination}/{slug}
```

Look for:

```text
/uploads/packages/{package_id}/...
```

3. Check at least one image:

```bash
curl -I https://wanderoo.world/uploads/packages/{package_id}/hero.webp
```

Expected:

```text
HTTP/2 200
content-type: image/webp
```

4. Check DB row counts if needed:

- package exists
- gallery count correct
- day image count correct
- day count correct
- tag count is exactly 2

5. Clean local image files after live verification if images were downloaded into the repo.

## Git Workflow

Only commit files you changed.

For image imports:

```bash
git status --short
git add uploads/packages/{package_id}
git commit -m "Add {destination} package images"
git push origin main
```

After successful live verification:

```bash
git update-index --skip-worktree uploads/packages/{package_id}/*
rm uploads/packages/{package_id}/*
```

Do not commit the local image deletion.

If code was changed:

```bash
git add package-detail.php
git commit -m "Fix external package hero images"
git push origin main
```

Do not commit `/private/tmp` scripts.

## Final Response Template

Keep final response short.

Mention:

- package live URL
- package ID
- counts inserted/updated
- images downloaded and pushed
- DB updated
- GitHub push done if applicable
- verification status

Example:

```text
Done. Package add ho gaya:
https://wanderoo.world/thailand/romantic-thailand-escape-krabi-phi-phi-phuket

DB mein package ID 10 update hua:
- 7 gallery images
- 8 day-wise images
- 8 itinerary days
- 2 package tags
- SEO title/meta/keywords

Images GitHub/Hostinger par push ho gayi, live image URL 200 OK hai, aur local Mac copy cleanup ho gaya.
```

## Common Problems

Problem: `mysql` CLI missing.

Solution: use Node + `mysql2`.

Problem: `php` CLI missing.

Solution: verify with live `curl` and DB queries instead of PHP lint.

Problem: sandbox blocks npm/network/MySQL.

Solution: rerun with escalation and concise justification.

Problem: competitor image URLs contain `%20`, `%2520`, spaces, or parentheses.

Solution: use Node downloader array strings, not shell `curl` one-liners.

Problem: package detail page shows `/https://...`.

Solution: use or preserve `package_image_url()` behavior in `package-detail.php`.

Problem: DB updated before images deployed.

Solution: push images to GitHub first, then update DB paths, or accept a short deployment gap.

Problem: too many tags make destination cards tall or uneven.

Solution: keep exactly 2 tags per imported package.

Problem: local Mac storage grows because downloaded images remain in the repo working copy.

Solution: after GitHub push and live verification, mark package image files `skip-worktree` and remove the local working-copy files. Do not commit deletion.

Problem: duplicate content risk.

Solution: rewrite all copy. Preserve facts/route/pricing only when requested.

## Minimal Agent Prompt For Future Use

The user can say:

```text
Read packageimplementation.md. Add this competitor package to Wanderoo with rewritten SEO content and matching images: COMPETITOR_URL
```

Agent should then execute the workflow without asking extra questions unless:

- destination does not exist and cannot be inferred
- user has not given image reuse permission and wants exact competitor images
- DB connection fails after retry
- Git push fails due auth/remote issue
