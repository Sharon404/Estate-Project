# TAUSI LOGO INSTALLATION INSTRUCTIONS

## Logo Files Required

You need to save the Tausi logo in two versions:

### 1. Dark Version (for light backgrounds)
- **File name**: `tausi-logo.png`
- **Location**: `/public/assets/tausi/tausi-logo.png`
- **Usage**: Headers, light-colored sections
- **Recommended size**: 180px wide, transparent background

### 2. Light Version (for dark backgrounds)
- **File name**: `tausi-logo-white.png`
- **Location**: `/public/assets/tausi/tausi-logo-white.png`
- **Usage**: Footer, dark sections
- **Recommended size**: 180px wide, transparent background

## Installation Steps

### Step 1: Save the Logo Files
Save the provided Tausi logo image to your computer, then upload both versions:

```bash
# Navigate to the project directory
cd "C:\Users\Admin\Desktop\Estate Project"

# Logo files should be placed here:
# public/assets/tausi/tausi-logo.png
# public/assets/tausi/tausi-logo-white.png
```

### Step 2: Verify Logo Display
After uploading, visit these pages to verify the logo displays correctly:
- Home page (dark logo in header)
- Footer (light logo)
- All other pages with the header

### Step 3: Generate Favicon (Optional)
Create a favicon from the Tausi logo:
1. Use an online favicon generator (e.g., favicon.io)
2. Upload your Tausi logo
3. Download the generated favicon.ico
4. Place it at: `/public/assets/frontend/images/favicon.ico`

## Current Logo Configuration

The CSS is already configured to automatically use your logo files:

```css
/* Dark logo for headers */
.logo-dark, .logo-black,
img[src*="logo-black"] {
  content: url('/assets/tausi/tausi-logo.png') !important;
}

/* Light logo for footer */
.logo-light, .logo-white,
img[src*="logo-white"] {
  content: url('/assets/tausi/tausi-logo-white.png') !important;
}
```

## Logo Specifications

### Format:
- PNG (preferred for transparency)
- SVG (alternative for scalability)

### Dimensions:
- Width: 180px (desktop), scales down to 140px (mobile)
- Height: Auto (maintains aspect ratio)
- Transparent background required

### Color Guidelines:
- **Dark version**: Use purple (#652482) or dark gray (#222222)
- **Light version**: Use white (#ffffff) or light beige (#decfbc)

## Troubleshooting

### Logo not displaying?
1. Check file names match exactly: `tausi-logo.png` and `tausi-logo-white.png`
2. Verify files are in: `/public/assets/tausi/`
3. Clear browser cache (Ctrl+F5)
4. Check file permissions (should be readable)

### Logo too large/small?
Adjust in `/public/assets/tausi/tausi-brand.css`:

```css
.navbar-brand img,
.logo {
  max-width: 180px !important;  /* Adjust this value */
  height: auto !important;
}
```

## Quick Upload Command (PowerShell)

If you have the logo saved on your desktop:

```powershell
# Copy dark logo
Copy-Item "C:\Users\Admin\Desktop\tausi-logo.png" `
          "C:\Users\Admin\Desktop\Estate Project\public\assets\tausi\tausi-logo.png"

# Copy light logo
Copy-Item "C:\Users\Admin\Desktop\tausi-logo-white.png" `
          "C:\Users\Admin\Desktop\Estate Project\public\assets\tausi\tausi-logo-white.png"
```

---

**Status**: Logo files are ready to be uploaded. The CSS configuration is complete.
