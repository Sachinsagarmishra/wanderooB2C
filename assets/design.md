# Wanderoo — Premium Travel Agency Frontend Design System

> [!IMPORTANT]
> **AI Instruction**: These rules apply to the **Frontend user-facing pages (`index.php`, `includes/header.php`, `includes/footer.php`, etc.)**. Use these tokens and rules when creating or modifying any frontend component, page, or style.

This document outlines the design tokens, components, and layout specifications used in the Wanderoo Premium Travel Agency Frontend.

---

## 🎨 Color Palette

| Token | Hex/RGBA Value | Usage |
| :--- | :--- | :--- |
| `--primary` | `#FFDE59` | Primary Yellow Accent (Buttons, Active states, highlights) |
| `--primary-dark` | `#e6c850` | Hover state for Primary components |
| `--text-white` | `#ffffff` | Hero text, Navigation links, Contrast text |
| `--text-dark` | `#1a1a1a` | Body copy, dark headings, primary readable text |
| `--bg-dark` | `#000000` | Full dark background blocks (if used) |
| `--glass` | `rgba(255, 255, 255, 0.1)` | Navigation glassmorphism background |
| `--glass-border` | `rgba(255, 255, 255, 0.2)` | Navigation glassmorphism border |

---

## 🔡 Typography

We employ a distinct mixed-typography style combining a modern geometric sans-serif for structures and body text, with an elegant serif for italic accents.

### Font Families
* **Primary Font**: `'Urbanist'`, sans-serif — used for body text, general structure, and primary headings.
* **Secondary Font**: `'Playfair Display'`, serif — used with `.playfair.italic` classes for premium, elegant accents.
* **Subtitle Font**: `'Inter'`, sans-serif — used for subheadings, captions, and secondary details (e.g. hero subtitle).

### Heading Rules & Sizes

> [!WARNING]
> **Typography Weight Rule**: The font weight of **H1** headings across the frontend must be **`500`** (matching the Hero Title / `h1.hero-title` weight). The font weight of **H2** and **H3** headings must be **`600`**.

| Element / Class | Size (Desktop) | Size (Tablet - 1024px) | Size (Mobile - 768px) | Font Family | Weight | Line Height |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **H1** (`.hero-title`) | `66px` | `50px` | `35px` | `'Urbanist'` / `'Playfair Display'` | `500` | `1.1` |
| **H2** (Section Titles) | `41.4px` | `41.4px` | `40px` | `'Urbanist'` / `'Playfair Display'` | `600` | `1.1` |
| **H3** (Card Titles) | `20px` | `20px` | `20px` | `'Urbanist'` | `600` | `1.3` |
| **H4** (Pricing Highlights)| `28px` | `28px` | `28px` | `'Urbanist'` | `600` | `1.3` |

### Body & Subtitles
* **Body Text**: `13px` / `14px` size, `'Urbanist'` font family, weight `400` / `500`, line-height `1.6`. Color: `#1a1a1a` (`--text-dark`) or `#666` for muted descriptions.
* **Hero Subtitle**: `17px` size, `'Inter'` font family, weight `500`, line-height `1.6`.

---

## 📐 Layout & Spacing

### Shell & Containers
* **Max Width**: `1280px` for main page containers.
* **Padding**: 
  * Large Sections: `80px 40px` (reduced to `40px 20px` on mobile).
  * Medium Sections: `60px 40px` (reduced to `40px 20px` on mobile).

### Flex & Grid Layouts
* **Packages Grid**: 3-column layout (`grid-template-columns: repeat(3, 1fr)`) with `30px` gap on Desktop. Stacks to 2-columns on Tablet, and transitions to a horizontal scroll container (`overflow-x: auto`) with snap alignments on Mobile.

---

## 🧱 UI Elements & Components

### Navigation Bar (Header)
* **Structure**: Absolute positioned at the top of the viewport.
* **Visual style**: Glassmorphic capsule (`.nav-glass`) with `10px` backdrop blur, a light border (`1px solid var(--glass-border)`), and a pill-shaped radius (`50px`).
* **Links**: Font size `15px`, weight `500`, color `#ffffff`. Active or hover states transition smoothly to `--primary` (`#FFDE59`).

### Hero Section
* **Height**: `80vh` for desktop.
* **Margins**: `20px 10px` outer margins.
* **Border Radius**: `20px` for a modern, rounded frame.
* **Overlay**: Disabled (`display: none` for `.hero-overlay`).
* **Visuals**: Full-bleed background image (`object-fit: cover`, `z-index: -1`).

### Buttons (`.btn-enquire`, `.btn-quote`, `.btn-primary`)
* **Font**: `'Urbanist'`, sans-serif.
* **Background**: `#FFDE59` (Primary Yellow).
* **Text Color**: `#000000`.
* **Border**: `2px solid #FFDE59`.
* **Weight**: `500`.
* **Shape**: Pill/Pill-oval shape (`border-radius: 50px`).
* **Shadow**: `0 6px 20px rgba(255, 222, 89, 0.3)`.
* **Hover Interaction**: Translates vertically (`transform: translateY(-3px)`) and changes background to `--primary-dark` (`#e6c850`).

### Package Cards
* **Card Container**: Borderless, transparent background, natural layout width spacing.
* **Card Image**: Height `280px`, rounded corners (`border-radius: 16px`), overflow hidden. Features centered absolute pagination dots overlay at the bottom.
* **Card Meta**: Duration left-aligned; ratings in green (`#10b981`) right-aligned.
* **City Strip Banner**: Background `#fdfaf0` (light cream beige), border-radius `6px`, bold text displaying itinerary summaries.
* **Price Details**: Displays old price (crossed out), green save badge (`background: #e6f7ed`, `color: #10b981`), and current price in large bold format with `/Adult`.
* **Action Buttons**: 
  * Phone Button: Circular/square button with `1.5px solid var(--primary)` border and primary-colored icon.
  * Request Callback Button: Flex-grow block button, filled with `--primary` background, dark text, `8px` border radius, and shadow.

---

## ✨ Effects & Animations
* **Micro-interactions**: Hover movements (`translateY(-3px)`) and scale effects use `0.3s` to `0.5s` ease transitions.
* **Glassmorphism**: Applied on headers (`backdrop-filter: blur(10px)`).
