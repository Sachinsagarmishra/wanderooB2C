# Nano Banana Pro — Admin Dashboard Design System

> [!IMPORTANT]
> **AI Instruction**: These rules apply ONLY to the **Admin Dashboard (`admin.php`, `admin.html`, and related admin components)**. Do NOT apply these tokens or styles to the frontend landing pages (`index.html`) or the Figma plugin UI unless explicitly told. Use the CSS variables defined here specifically for creating or modifying admin-side pages.

This document outlines the design tokens, components, and layout specifications used in the Nano Banana Pro Admin Dashboard.

## 🎨 Color Palette

### Dark Theme (Default)
| Token | Hex Value | Usage |
| :--- | :--- | :--- |
| `--bg` | `#09090b` | Main background |
| `--bg2` | `#0a0a0f` | Card & Sidebar background |
| `--bg3` | `#18181b` | Secondary elements/hover states |
| `--fg` | `#fafafa` | Primary text |
| `--fg2` | `#a1a1aa` | Secondary text |
| `--fg3` | `#71717a` | Muted text / Labels |
| `--border` | `#27272a` | Borders |
| `--accent` | `#f5c518` | Primary accent (Yellow) |
| `--accent2` | `#e6a800` | Darker accent |
| `--success` | `#22c55e` | Success states |
| `--danger` | `#ef4444` | Errors / Deletion |
| `--info` | `#3b82f6` | Informational links / Active page |

### Light Theme
- Equivalent tokens with inverted brightness (e.g., `--bg: #f8f8fa`, `--fg: #09090b`).

## 🔡 Typography
- **Primary Font**: `'Inter'`, -apple-system, sans-serif
- **Smoothing**: `-webkit-font-smoothing: antialiased`

### Font Sizes
| Type | Size | Weight | Usage |
| :--- | :--- | :--- | :--- |
| **H1** | `24px` | `700` | Page Titles |
| **H2** | `18px` | `700` | Section / Modal Titles |
| **Card Header**| `15px` | `600` | Table Headers / Card Titles |
| **Body** | `13px` | `400/500` | Sidebar Menu, Table Rows, Inputs |
| **Small** | `11px` | `500/600` | Labels, Subtexts, Stats Labels |
| **Tiny** | `10px` | `600` | Badges, Helper text |

## 📐 Layout & Spacing

### Shell
- **Sidebar Width**: `260px`
- **Main Shell Padding**: `32px` (reduced to `16px` on mobile)
- **Sidebar Padding**: `24px 16px`
- **Navigation item Gap**: `2px` (Vertical margin)

### Component Patterns
- **Card Padding**: `20px` (Internal)
- **Table Component**:
    - **Cell Padding**: `12px 20px`
    - **Header Padding**: `10px 20px`
    - **Heading Style**: `11px`, Uppercase, `600` weight, letter-spacing `0.5px`, color `var(--fg3)`
    - **Row Hover**: `background: rgba(255, 255, 255, 0.02)`
    - **Border**: `1px solid var(--border)` (Bottom only)
- **Input Padding**: `10px 14px`
- **Gap between Stats**: `16px`
- **Gap between Sections**: `32px`

## 📐 Responsive Strategy
- **Grid Layouts**: Always use `grid-template-columns: repeat(auto-fit, minmax(300px, 1fr))` for variable widths.
- **Mobile Breakpoint**: `768px`.
- **Mobile Behavior**:
    - Sidebar should be hidden/toggled (standard admin pattern).
    - Grid columns should stack to `1fr`.
    - Main padding should reduce to `16px` or `20px`.


## 🧱 UI Elements

### Borders & Radius
- **Main Radius**: `12px` (Cards, Stat cards)
- **Interactive Radius**: `8px` (Buttons, Inputs, Nav items)
- **Login Card Radius**: `16px`
- **Border Thickness**: `1px` (Solid)

### Buttons
- **Primary**: Background `var(--accent)`, Color `#000`
- **Secondary/Default**: Background `var(--bg3)`, Border `var(--border)`
- **Success/Danger**: Transparent backgrounds with colored borders and text hover states.

### Forms
- **Input Background**: `rgba(255, 255, 255, 0.03)`
- **Focus State**: `border-color: var(--accent)`, `background: rgba(255, 255, 255, 0.05)`
- **Transition**: `all 0.2s` for interactive states.

## ✨ Effects & Animations
- **Glassmorphism**: Backdrop blur `12px` used for modal overlays and masks.
- **Micro-interactions**: Color/Background transitions are typically `0.15s` to `0.2s`.
- **Transitions**:
    - Hover on Table Rows: `rgba(255, 255, 255, 0.02)`
    - Sidebar active link: `rgba(245, 197, 24, 0.1)` (10% opacity accent)
