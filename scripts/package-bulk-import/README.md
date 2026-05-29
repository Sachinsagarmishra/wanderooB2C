# Package Bulk Import

Use this folder for low-credit package imports.

The goal is to process a whole destination batch in one flow:

1. Fetch all competitor package pages once.
2. Extract visible package text and image URLs into JSON.
3. Rewrite package content using `packageimplementation.md`.
4. Import all packages into remote MySQL in one transaction.
5. Download all package images once.
6. Update DB image paths to `uploads/packages/{package_id}/...`.
7. Commit and push once.
8. Verify only sample pages/images.
9. Clean local image copies after live verification.

## Input File

Create a batch input JSON:

```json
{
  "destination": {
    "slug": "sri-lanka",
    "name": "Sri Lanka",
    "title": "Sri Lanka Packages",
    "breadcrumb": "Sri Lanka",
    "description": "SEO-friendly destination description",
    "hero_bg": "https://example.com/hero.webp",
    "hero_bg_alt": "Sri Lanka beach and hills",
    "dropdown_icon": "assets/img/SriLanka.svg"
  },
  "urls": [
    "https://www.30sundays.club/packages/example-package"
  ]
}
```

## Commands

Fetch and extract all competitor pages:

```bash
node scripts/package-bulk-import/bulk-import.js fetch /private/tmp/wanderoo-batch/input.json
```

This writes:

```text
/private/tmp/wanderoo-batch/extracted.json
/private/tmp/wanderoo-batch/pages/*.html
```

Then create rewritten package data:

```text
/private/tmp/wanderoo-batch/packages.json
```

Import all packages and images:

```bash
node scripts/package-bulk-import/bulk-import.js import /private/tmp/wanderoo-batch/packages.json
```

## Required Package JSON Shape

Each package must contain fully rewritten Wanderoo content:

```json
{
  "destination": {
    "slug": "sri-lanka",
    "name": "Sri Lanka",
    "title": "Sri Lanka Packages",
    "breadcrumb": "Sri Lanka",
    "description": "SEO-friendly destination description",
    "hero_bg": "https://example.com/hero.webp",
    "hero_bg_alt": "Sri Lanka beach and hills",
    "dropdown_icon": "assets/img/SriLanka.svg"
  },
  "packages": [
    {
      "title": "SEO package title",
      "slug": "seo-package-slug",
      "meta_title": "Meta title",
      "meta_description": "Meta description",
      "focus_keywords": "keyword one, keyword two",
      "description": "Short summary",
      "overview": "Longer summary",
      "duration": "7 days & 6 nights",
      "old_price": "₹93,701",
      "price": "₹65,399",
      "save_text": "SAVE ₹28,302",
      "rating": 4.8,
      "rating_count": 188,
      "hero_image_alt": "Descriptive hero alt",
      "tags": ["Offbeat Trip", "South Coast"],
      "highlights": ["Short rewritten highlight"],
      "days": [
        {
          "number": 1,
          "title": "Arrival",
          "content": "Rewritten easy day content",
          "accommodation": "Hotel stay",
          "meals": ""
        }
      ],
      "inclusions": ["Hotel stay included"],
      "exclusions": ["Flights not included"],
      "gallery": [
        {
          "filename": "hero.webp",
          "url": "https://cdn.example.com/hero.webp",
          "alt": "Descriptive alt"
        }
      ],
      "day_images": [
        {
          "day": 1,
          "filename": "day-01-arrival.webp",
          "url": "https://cdn.example.com/day-01.webp",
          "alt": "Arrival image alt"
        }
      ]
    }
  ]
}
```

## Low-Credit Rules

- Do one batch per destination.
- Do one DB import for all packages.
- Do one image download pass.
- Do one commit and one push.
- Verify only sample URLs unless there is an error.
- Do not use browser screenshots unless layout is broken.
- Keep all package content and all package images complete.
