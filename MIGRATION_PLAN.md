# Tausi Holiday & Getaway Homes - Website Migration Plan

**Project**: Migrate existing website into Laravel application
**Date**: January 25, 2026
**Status**: In Progress

## 1. CONTENT EXTRACTED FROM SOURCE

### Branding
- **Legal Name**: Tausi Holiday & Getaway Homes
- **Tagline**: AN ENTIRE HOUSE JUST FOR YOU
- **Copyright**: © 2026 Tausi Holiday & Getaway Homes. All rights reserved.
- **Powered By**: Tronis

### Contact Information
- **Phone**: +254 718 756 254 (Call or WhatsApp)
- **Email**: bookings@tausivacations.com
- **Location**: Nanyuki, Kenya

### Pricing
- **Rate**: KES 25,000 PER NIGHT
- **Includes**: Breakfast Included

### Key Content Sections

#### Hero Section
**Headline**: AN ENTIRE HOUSE JUST FOR YOU

#### Booking Form (No POST - JS Redirect)
- Check In (date input)
- Check Out (date input)  
- Home Type (select)
- Button: "CHECK AVAILABILITY" → Routes to /reservation with params

#### Pricing Section
**Title**: SIMPLE PRICING
**Subtitle**: One Flat Rate Entire Home Stay

#### Amenities Section
**Title**: BREAKFAST & HOSPITALITY
**Subtitle**: Simple Comforts That Make a Difference
**Description**: "At Tausi Holiday & Getaway Homes, we focus on the essentials that matter most — privacy, comfort, and a warm hosting experience. Every stay includes breakfast, prepared fresh to help you start your day relaxed and refreshed."

##### Breakfast Included Features
- Freshly prepared daily breakfast
- Served in a calm, private setting
- Included in the nightly rate

##### Home-Style Hosting Features
- Quiet, respectful environment
- Attentive on-request support
- Ideal for families & small groups

#### Testimonials Section
**Title**: GUEST FEEDBACK
**Subtitle**: What Our Guests Say
*(Exact testimonials to be populated)*

#### Contact Form Section
**Title**: SEND US A MESSAGE
**Subtitle**: Get in Touch
- Form with fields (existing in Laravel)
- Buttons: "SEND MESSAGE", "RESET"

#### Contact Details Section
**Title**: Booking & Enquiries
**Description**: "Have a question about availability, pricing, or house options? Reach out and we'll be happy to assist you with your booking."
- Phone: +254 718 756 254
- Email: bookings@tausivacations.com
- Location: Nanyuki, Kenya

#### Footer
- Copyright: © 2026 Tausi Holiday & Getaway Homes. All rights reserved.
- Help text: NEED ASSISTANCE?
- Contact info and social links

## 2. DESIGN SPECIFICATIONS

### Colors (TO BE EXTRACTED FROM CSS)
- Primary: TBD (extracted from DevTools)
- Secondary: TBD
- Accent: TBD
- Background: TBD
- Text: TBD

### Typography (TO BE EXTRACTED FROM CSS)
- Font Family: TBD (primary website uses modern sans-serif)
- Heading Sizes: TBD
- Body Text Size: TBD
- Font Weights: TBD

### Images (TO BE DOWNLOADED)
- Logo
- Favicon
- Hero image
- Property/room images (carousel via Tronis)
- Social media icons (if present)

## 3. ASSETS TO DOWNLOAD

Location: `public/assets/images/`

```
public/assets/
├── images/
│   ├── logo.png
│   ├── favicon.ico
│   ├── hero.jpg
│   ├── property-1.jpg
│   ├── property-2.jpg
│   ├── property-3.jpg
│   └── icons/
│       ├── breakfast.svg
│       ├── hospitality.svg
│       └── social/
```

## 4. BLADE TEMPLATES TO UPDATE

### Files to Modify
1. `resources/views/welcome.blade.php` - Main landing page
2. `resources/views/layouts/app.blade.php` - Main layout with header/footer
3. `resources/views/booking/reservation.blade.php` - Reservation form (preserve JS flow)
4. `resources/views/booking/confirm.blade.php` - Confirmation page (preserve POST, CSRF)
5. Create `resources/views/partials/footer.blade.php` - Footer component
6. Create `resources/views/partials/header.blade.php` - Header/navbar

### CSS Files to Update
1. `resources/css/app.css` - Main styles
2. New: `resources/css/variables.css` - CSS custom properties for colors, fonts
3. New: `resources/css/tausi.css` - Brand-specific styles

## 5. DATABASE & ROUTES (NO CHANGES)

### Routes - PRESERVED
- GET `/reservation` → BookingController@reservationForm
- GET `/reservation/confirm` → BookingController@confirmForm
- POST `/booking/store` → BookingController@store (CSRF protected)
- GET `/payment/{booking}` → Payment logic

### Booking Flow - PRESERVED
1. User visits home → sees hero with booking widget
2. User fills: check-in, check-out, rooms, adults, children (JS only)
3. User clicks "Review & Confirm" → redirects to `/reservation/confirm?...`
4. Confirmation page shows all details in read-only format
5. User clicks "Proceed to Payment" → POST `/booking/store` with CSRF token
6. Server creates booking with PENDING_PAYMENT status
7. Redirects to payment page

**NO CHANGES** to booking logic, database schema, or payment flow.

## 6. UX FIXES TO APPLY

### Hero Section
- [ ] Prevent excessive height on mobile
- [ ] Use proper aspect ratios for images
- [ ] Ensure booking widget is accessible on all screen sizes
- [ ] Test form responsiveness

### Images
- [ ] Apply `object-fit: cover` to property images
- [ ] Normalize image aspect ratios (e.g., 16:9 for hero, 1:1 for testimonials)
- [ ] Set max-widths to prevent over-stretching
- [ ] Optimize for mobile viewing

### Spacing & Layout
- [ ] Normalize section margins/padding (e.g., 2rem top/bottom)
- [ ] Ensure consistent grid alignment across sections
- [ ] Test mobile breakpoints (sm, md, lg)
- [ ] Verify footer doesn't have excessive bottom padding

### Typography
- [ ] Ensure readable line-height (1.5-1.6 for body text)
- [ ] Test font size hierarchy (h1 > h2 > h3 > body)
- [ ] Check contrast ratios (WCAG AA minimum)

## 7. IMPLEMENTATION CHECKLIST

### Phase 1: Content & Branding (CURRENT)
- [x] Extract all text content from source
- [x] Document contact information
- [ ] Download logo, images, favicon
- [ ] Extract exact colors from website CSS
- [ ] Extract exact typography details

### Phase 2: Template Updates
- [ ] Update welcome.blade.php with hero section
- [ ] Create footer.blade.php with contact info
- [ ] Update header/navbar branding
- [ ] Apply content to all sections
- [ ] Verify booking flow still works

### Phase 3: Styling
- [ ] Create CSS variables file with colors
- [ ] Update app.css with Tausi brand colors
- [ ] Fix responsive breakpoints
- [ ] Normalize image aspect ratios
- [ ] Apply spacing fixes

### Phase 4: Assets
- [ ] Move images to public/assets/
- [ ] Update all image paths in Blade templates
- [ ] Test image loading
- [ ] Verify favicon displays

### Phase 5: Testing
- [ ] Test home page load and display
- [ ] Test responsive design (mobile, tablet, desktop)
- [ ] Test /reservation → /confirm → /payment flow
- [ ] Verify CSRF tokens work
- [ ] Verify booking creation works
- [ ] Check all links and buttons work
- [ ] Verify footer displays correctly

### Phase 6: Verification
- [ ] All text matches source exactly (word-for-word)
- [ ] Colors match source branding
- [ ] Images display properly with correct aspect ratios
- [ ] Layout is clean and professional
- [ ] No booking flow disruptions
- [ ] Payment redirect still works

## 8. DESIGN NOTES

### Current State (Laravel Default)
- Clean, minimal landing page with Laravel branding
- Dark/light mode support
- Responsive Tailwind CSS framework

### Target State (Tausi)
- Professional hospitality branding
- Warm, welcoming color scheme
- Clear focus on pricing, amenities, and booking
- Testimonials section for social proof
- Direct contact information (phone, email)
- Location-focused (Nanyuki, Kenya)

### Key Considerations
- Preserve all booking logic and CSRF protection
- No changes to database or migrations
- Keep existing routes and controllers
- Only update views, CSS, and assets
- Maintain mobile responsiveness
- Ensure accessibility standards

## 9. PROGRESS TRACKING

| Task | Status | Notes |
|------|--------|-------|
| Extract content | ✓ Complete | All text documented |
| Download assets | ⏳ Pending | Need DevTools for exact colors |
| Update welcome.blade.php | ⏳ Pending | Hero, pricing, amenities |
| Create footer.blade.php | ⏳ Pending | Contact info |
| Apply CSS variables | ⏳ Pending | Colors and fonts |
| Test booking flow | ⏳ Pending | Verify /reservation → payment |
| Final QA | ⏳ Pending | Word-for-word verification |

---

**Project Lead**: Automated Migration Assistant
**Last Updated**: January 25, 2026
