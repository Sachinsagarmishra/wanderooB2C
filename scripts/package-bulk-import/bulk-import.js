#!/usr/bin/env node

const fs = require('fs');
const path = require('path');
const https = require('https');
const http = require('http');

const repoRoot = path.resolve(__dirname, '..', '..');
const tmpRoot = '/private/tmp/wanderoo-batch';
const dbHost = '82.25.121.32';

function readJson(file) {
  return JSON.parse(fs.readFileSync(file, 'utf8'));
}

function writeJson(file, data) {
  fs.mkdirSync(path.dirname(file), { recursive: true });
  fs.writeFileSync(file, `${JSON.stringify(data, null, 2)}\n`);
}

function configValue(key) {
  const cfg = fs.readFileSync(path.join(repoRoot, 'config.php'), 'utf8');
  const match = cfg.match(new RegExp("define\\('" + key + "',\\s*'([^']*)'\\)"));
  return match ? match[1] : '';
}

function loadMysql() {
  const candidates = [
    process.env.MYSQL2_PATH,
    '/private/tmp/wanderoo-mysql-check/node_modules/mysql2/promise',
    path.join(repoRoot, 'node_modules/mysql2/promise'),
  ].filter(Boolean);

  for (const candidate of candidates) {
    try {
      return require(candidate);
    } catch (_) {
      // Try next candidate.
    }
  }

  throw new Error('mysql2 not found. Install once in /private/tmp/wanderoo-mysql-check or set MYSQL2_PATH.');
}

function requestBuffer(url, redirects = 0) {
  return new Promise((resolve, reject) => {
    const lib = url.startsWith('https:') ? https : http;
    const request = lib.get(url, { headers: { 'user-agent': 'Mozilla/5.0 WanderooBulkImporter/1.0' } }, (response) => {
      if (response.statusCode >= 300 && response.statusCode < 400 && response.headers.location && redirects < 5) {
        response.resume();
        const nextUrl = new URL(response.headers.location, url).toString();
        requestBuffer(nextUrl, redirects + 1).then(resolve, reject);
        return;
      }
      if (response.statusCode !== 200) {
        response.resume();
        reject(new Error(`${response.statusCode} ${url}`));
        return;
      }
      const chunks = [];
      response.on('data', (chunk) => chunks.push(chunk));
      response.on('end', () => resolve(Buffer.concat(chunks)));
    });
    request.on('error', reject);
  });
}

async function download(url, file) {
  const bytes = await requestBuffer(url);
  fs.mkdirSync(path.dirname(file), { recursive: true });
  fs.writeFileSync(file, bytes);
  return bytes.length;
}

function slugFromUrl(url) {
  const parsed = new URL(url);
  return parsed.pathname.split('/').filter(Boolean).pop() || 'package';
}

function decodeEntities(value) {
  return value
    .replace(/&nbsp;|&#xA0;/g, ' ')
    .replace(/&#x27;/g, "'")
    .replace(/&amp;/g, '&')
    .replace(/&gt;/g, '>')
    .replace(/&lt;/g, '<')
    .replace(/&quot;/g, '"');
}

function htmlToText(html) {
  return decodeEntities(
    html
      .replace(/<script[\s\S]*?<\/script>/gi, ' ')
      .replace(/<style[\s\S]*?<\/style>/gi, ' ')
      .replace(/<[^>]+>/g, ' ')
      .replace(/\s+/g, ' ')
      .trim()
  );
}

function imageUrls(html) {
  return [
    ...new Set(
      [...html.matchAll(/https:\/\/cdn\.prod\.website-files\.com\/[^"' <>)]+/g)]
        .map((match) => match[0].replace(/\\u0026/g, '&'))
        .filter((url) => /\.(webp|jpg|jpeg|png)(\?|$)/i.test(url))
        .filter((url) => !/favicon|logo|Google|Star|Call|Compress|laurel|Icon|bus|building|Ticket|Arrow|-p-500|-p-800|-p-1080|-p-1600/i.test(url))
    ),
  ];
}

function extractPage(url, html) {
  const text = htmlToText(html);
  const title = decodeEntities((html.match(/<title>(.*?)<\/title>/is) || [])[1] || '');
  const price = (text.match(/Starting From (₹[\d,]+)/) || [])[1] || '';
  const ratingMatch = text.match(/\/Person ([0-9.]+) \((\d+)\)/);
  const routeMatch = text.match(/Fully Customizable ([\s\S]*?) Starting From/);
  const detailsStart = text.indexOf('Trip Details');
  const detailsEnd = text.indexOf('What’s Included');
  const highlightsStart = text.indexOf('Trip Highlights');
  const highlightsEnd = text.indexOf('Trip Details');

  return {
    source_url: url,
    source_slug: slugFromUrl(url),
    title,
    price,
    rating: ratingMatch ? Number(ratingMatch[1]) : null,
    rating_count: ratingMatch ? Number(ratingMatch[2]) : null,
    route_text: routeMatch ? routeMatch[1].replace(title, '').trim() : '',
    highlights_text: highlightsStart >= 0 && highlightsEnd > highlightsStart ? text.slice(highlightsStart, highlightsEnd).trim() : '',
    trip_details_text: detailsStart >= 0 ? text.slice(detailsStart, detailsEnd > detailsStart ? detailsEnd : detailsStart + 12000).trim() : '',
    inclusions_exclusions_text: detailsEnd >= 0 ? text.slice(detailsEnd, detailsEnd + 2500).trim() : '',
    images: imageUrls(html),
  };
}

async function fetchBatch(inputFile) {
  const input = readJson(inputFile);
  fs.mkdirSync(path.join(tmpRoot, 'pages'), { recursive: true });

  const extracted = {
    destination: input.destination || null,
    fetched_at: new Date().toISOString(),
    pages: [],
  };

  for (const url of input.urls || []) {
    const slug = slugFromUrl(url);
    const htmlFile = path.join(tmpRoot, 'pages', `${slug}.html`);
    const html = (await requestBuffer(url)).toString('utf8');
    fs.writeFileSync(htmlFile, html);
    extracted.pages.push({ ...extractPage(url, html), html_file: htmlFile });
    console.log(`fetched ${slug}`);
  }

  writeJson(path.join(tmpRoot, 'extracted.json'), extracted);
  console.log(`wrote ${path.join(tmpRoot, 'extracted.json')}`);
}

function validatePackage(pkg) {
  const required = ['title', 'slug', 'meta_title', 'meta_description', 'description', 'overview', 'duration', 'old_price', 'price', 'save_text'];
  for (const field of required) {
    if (!pkg[field]) throw new Error(`Missing ${field} for package ${pkg.slug || pkg.title || 'unknown'}`);
  }
  if (!Array.isArray(pkg.tags) || pkg.tags.length !== 2) throw new Error(`${pkg.slug} must have exactly 2 tags`);
  if (!Array.isArray(pkg.gallery) || pkg.gallery.length === 0) throw new Error(`${pkg.slug} must have gallery images`);
  if (!Array.isArray(pkg.day_images) || pkg.day_images.length === 0) throw new Error(`${pkg.slug} must have day images`);
  if (!Array.isArray(pkg.days) || pkg.days.length === 0) throw new Error(`${pkg.slug} must have days`);
}

async function upsertDestination(conn, destination) {
  if (!destination || !destination.slug) return;

  await conn.query(
    `INSERT INTO destinations
      (slug, name, title, meta_title, meta_description, focus_keywords, breadcrumb, hero_bg, hero_bg_alt, dropdown_icon, dropdown_icon_alt, description, sort_order)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, COALESCE(?, 0))
     ON DUPLICATE KEY UPDATE
      name=VALUES(name), title=VALUES(title), meta_title=VALUES(meta_title), meta_description=VALUES(meta_description),
      focus_keywords=VALUES(focus_keywords), breadcrumb=VALUES(breadcrumb), hero_bg=VALUES(hero_bg),
      hero_bg_alt=VALUES(hero_bg_alt), dropdown_icon=VALUES(dropdown_icon), dropdown_icon_alt=VALUES(dropdown_icon_alt),
      description=VALUES(description), updated_at=NOW()`,
    [
      destination.slug,
      destination.name,
      destination.title,
      destination.meta_title || null,
      destination.meta_description || null,
      destination.focus_keywords || null,
      destination.breadcrumb || destination.name,
      destination.hero_bg || null,
      destination.hero_bg_alt || null,
      destination.dropdown_icon || null,
      destination.dropdown_icon_alt || null,
      destination.description || '',
      destination.sort_order || 0,
    ]
  );
}

async function upsertPackage(conn, destinationSlug, pkg) {
  validatePackage(pkg);

  const heroImage = pkg.gallery[0].url;
  const heroAlt = pkg.hero_image_alt || pkg.gallery[0].alt;
  const [existing] = await conn.query('SELECT id FROM tour_packages WHERE slug = ?', [pkg.slug]);
  let packageId;

  if (existing.length) {
    packageId = existing[0].id;
    await conn.query(
      `UPDATE tour_packages
       SET destination=?, title=?, meta_title=?, meta_description=?, focus_keywords=?, description=?, overview=?,
           duration=?, old_price=?, price=?, save_text=?, rating=?, rating_count=?, hero_image=?, hero_image_alt=?, status='active'
       WHERE id=?`,
      [
        destinationSlug,
        pkg.title,
        pkg.meta_title,
        pkg.meta_description,
        pkg.focus_keywords || '',
        pkg.description,
        pkg.overview,
        pkg.duration,
        pkg.old_price,
        pkg.price,
        pkg.save_text,
        pkg.rating || 4.8,
        pkg.rating_count || 100,
        heroImage,
        heroAlt,
        packageId,
      ]
    );
  } else {
    const [result] = await conn.query(
      `INSERT INTO tour_packages
        (destination, title, slug, meta_title, meta_description, focus_keywords, description, overview,
         duration, old_price, price, save_text, rating, rating_count, hero_image, hero_image_alt, status)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')`,
      [
        destinationSlug,
        pkg.title,
        pkg.slug,
        pkg.meta_title,
        pkg.meta_description,
        pkg.focus_keywords || '',
        pkg.description,
        pkg.overview,
        pkg.duration,
        pkg.old_price,
        pkg.price,
        pkg.save_text,
        pkg.rating || 4.8,
        pkg.rating_count || 100,
        heroImage,
        heroAlt,
      ]
    );
    packageId = result.insertId;
  }

  for (const table of ['package_tags', 'package_highlights', 'package_days', 'package_inclusions', 'package_photos', 'package_day_images']) {
    await conn.query(`DELETE FROM ${table} WHERE package_id = ?`, [packageId]);
  }

  for (const tag of pkg.tags) await conn.query('INSERT INTO package_tags (package_id, tag_name) VALUES (?, ?)', [packageId, tag]);
  for (const [index, highlight] of pkg.highlights.entries()) await conn.query('INSERT INTO package_highlights (package_id, highlight_text, sort_order) VALUES (?, ?, ?)', [packageId, highlight, index + 1]);
  for (const day of pkg.days) await conn.query('INSERT INTO package_days (package_id, day_number, day_title, day_content, accommodation, meals) VALUES (?, ?, ?, ?, ?, ?)', [packageId, day.number, day.title, day.content, day.accommodation || '', day.meals || '']);
  for (const [index, item] of pkg.inclusions.entries()) await conn.query("INSERT INTO package_inclusions (package_id, type, item_text, sort_order) VALUES (?, 'inclusion', ?, ?)", [packageId, item, index + 1]);
  for (const [index, item] of pkg.exclusions.entries()) await conn.query("INSERT INTO package_inclusions (package_id, type, item_text, sort_order) VALUES (?, 'exclusion', ?, ?)", [packageId, item, index + 1]);
  for (const [index, item] of pkg.gallery.entries()) await conn.query('INSERT INTO package_photos (package_id, image_path, alt_text, sort_order) VALUES (?, ?, ?, ?)', [packageId, item.url, item.alt, index + 1]);
  for (const item of pkg.day_images) await conn.query('INSERT INTO package_day_images (package_id, day_number, image_path, alt_text, sort_order) VALUES (?, ?, ?, ?, 1)', [packageId, item.day, item.url, item.alt]);

  return packageId;
}

async function updateLocalImagePaths(conn, packageId, pkg) {
  const base = `uploads/packages/${packageId}/`;
  await conn.query('UPDATE tour_packages SET hero_image = ?, hero_image_alt = ? WHERE id = ?', [base + pkg.gallery[0].filename, pkg.hero_image_alt || pkg.gallery[0].alt, packageId]);

  await conn.query('DELETE FROM package_photos WHERE package_id = ?', [packageId]);
  for (const [index, item] of pkg.gallery.entries()) {
    await conn.query('INSERT INTO package_photos (package_id, image_path, alt_text, sort_order) VALUES (?, ?, ?, ?)', [packageId, base + item.filename, item.alt, index + 1]);
  }

  await conn.query('DELETE FROM package_day_images WHERE package_id = ?', [packageId]);
  for (const item of pkg.day_images) {
    await conn.query('INSERT INTO package_day_images (package_id, day_number, image_path, alt_text, sort_order) VALUES (?, ?, ?, ?, 1)', [packageId, item.day, base + item.filename, item.alt]);
  }
}

async function importBatch(packageFile) {
  const input = readJson(packageFile);
  const mysql = loadMysql();
  const conn = await mysql.createConnection({
    host: dbHost,
    user: configValue('DB_USER'),
    password: configValue('DB_PASS'),
    database: configValue('DB_NAME'),
    connectTimeout: 12000,
  });

  const destinationSlug = input.destination.slug;
  const imported = [];

  try {
    await conn.beginTransaction();
    await upsertDestination(conn, input.destination);

    for (const pkg of input.packages) {
      const packageId = await upsertPackage(conn, destinationSlug, pkg);
      imported.push({ packageId, slug: pkg.slug, title: pkg.title });
    }

    await conn.commit();
  } catch (error) {
    await conn.rollback();
    await conn.end();
    throw error;
  }

  try {
    for (const item of imported) {
      const pkg = input.packages.find((candidate) => candidate.slug === item.slug);
      const outDir = path.join(repoRoot, 'uploads', 'packages', String(item.packageId));
      fs.mkdirSync(outDir, { recursive: true });
      for (const image of [...pkg.gallery, ...pkg.day_images]) {
        const size = await download(image.url, path.join(outDir, image.filename));
        console.log(`downloaded ${item.packageId}/${image.filename} ${size}`);
      }
    }

    await conn.beginTransaction();
    for (const item of imported) {
      const pkg = input.packages.find((candidate) => candidate.slug === item.slug);
      await updateLocalImagePaths(conn, item.packageId, pkg);
    }
    await conn.commit();
  } catch (error) {
    await conn.rollback();
    await conn.end();
    throw error;
  }

  await conn.end();
  writeJson(path.join(tmpRoot, 'import-result.json'), { ok: true, destination: destinationSlug, imported });
  console.log(JSON.stringify({ ok: true, destination: destinationSlug, imported }, null, 2));
  console.log('Next: git add changed files, commit once, push once, sample verify, then cleanup local image files.');
}

async function main() {
  const [command, file] = process.argv.slice(2);
  if (!command || !file || !['fetch', 'import'].includes(command)) {
    console.error('Usage: node scripts/package-bulk-import/bulk-import.js fetch input.json');
    console.error('   or: node scripts/package-bulk-import/bulk-import.js import packages.json');
    process.exit(1);
  }

  if (command === 'fetch') await fetchBatch(file);
  if (command === 'import') await importBatch(file);
}

main().catch((error) => {
  console.error(error.message);
  process.exit(1);
});
