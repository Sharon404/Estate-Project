<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tausi Holiday & Getaway Homes - Nanyuki, Kenya</title>
    <meta name="description" content="An entire house just for you in Nanyuki, Kenya. KES 25,000 per night with breakfast included.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: 'Inter', system-ui, sans-serif; line-height: 1.6; color: #222222 !important; background: #f8f5f0 !important; }
        
        header { background: white; padding: 1.5rem 2rem; border-bottom: 1px solid #decfbc; position: sticky; top: 0; z-index: 100; }
        .header-container { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 1.5rem; font-weight: 700; color: #222222 !important; }
        
        .nav-links { display: flex; gap: 1.5rem; }
        .nav-links a { color: #222222 !important; text-decoration: none; font-weight: 500; transition: color 0.2s; }
        .nav-links a:hover { color: #652482 !important; }
        .nav-links .auth-link { color: #652482 !important; font-weight: 600; }
        
        .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
        
        /* Hero Section */
        .hero { background: linear-gradient(135deg, #f8f5f0 0%, #ffffff 100%); padding: 3rem 0; min-height: auto; }
        .hero-container { display: flex; align-items: flex-start; gap: 3rem; flex-wrap: wrap-reverse; }
        .hero-text { flex: 1; min-width: 300px; padding-top: 0.5rem; }
        .hero-text h1 { font-size: 2.75rem; font-weight: 700; margin-bottom: 1.5rem; color: #222222 !important; line-height: 1.2; }
        .pricing-badge { background: #fff !important; border: 2px solid #652482 !important; padding: 0.875rem 1.5rem; border-radius: 50px; display: inline-block; font-weight: 600; color: #652482 !important; margin-bottom: 2rem; font-size: 0.95rem; }
        .hero-text > p { font-size: 1.125rem; color: #222222 !important; margin-bottom: 2rem; line-height: 1.8; }
        
        /* Booking Widget */
        .booking-widget { background: white; border-radius: 12px; padding: 2.5rem; box-shadow: 0 8px 24px rgba(0,0,0,0.1); max-width: 380px; width: 100%; flex-shrink: 0; border: 1px solid #decfbc; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.625rem; color: #222222 !important; }
        .form-group input, .form-group select { width: 100%; padding: 0.875rem; border: 1px solid #decfbc; border-radius: 6px; font-size: 1rem; font-family: inherit; transition: border-color 0.2s, box-shadow 0.2s; color: #222222 !important; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #652482 !important; box-shadow: 0 0 0 3px rgba(101, 36, 130, 0.15) !important; }
        .btn-primary { background: #652482 !important; color: white !important; padding: 1rem 1.5rem; border-radius: 6px; border: none !important; font-weight: 600; cursor: pointer; width: 100%; transition: background 0.2s; font-size: 1rem; }
        .btn-primary:hover { background: #4f1c65 !important; }
        .btn-primary:active { transform: scale(0.98); }
        
        /* Sections */
        section { padding: 4.5rem 0; border-bottom: 1px solid #decfbc; }
        section:last-of-type { border-bottom: none; }
        .section-header { text-align: center; margin-bottom: 3.5rem; }
        .section-header h2 { font-size: 2.25rem; font-weight: 700; margin-bottom: 1rem; color: #222222 !important; letter-spacing: -0.5px; }
        .section-header p { font-size: 1.125rem; color: #222222 !important; max-width: 700px; margin: 0 auto; }
        
        /* Features Grid */
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }
        .feature-card { background: white; padding: 2.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #decfbc; }
        .feature-card h3 { font-size: 1.375rem; font-weight: 700; margin-bottom: 1.5rem; color: #222222 !important; }
        .feature-card ul { list-style: none; text-align: left; }
        .feature-card li { padding: 0.625rem 0; color: #222222 !important; font-size: 0.95rem; }
        .feature-card li:before { content: "✓ "; color: #652482 !important; font-weight: bold; margin-right: 0.75rem; }
        
        .pricing-display { background: white; padding: 3rem 2rem; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #decfbc; }
        .pricing-display .price { font-size: 3.5rem; font-weight: 700; color: #652482 !important; margin-bottom: 0.5rem; }
        .pricing-display .period { font-size: 1.25rem; color: #222222 !important; margin-bottom: 1.5rem; }
        .pricing-display .subtitle { color: #222222 !important; font-weight: 600; }
        
        /* Contact Section */
        .contact-info { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 2.5rem; }
        .contact-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); text-align: center; border: 1px solid #decfbc; }
        .contact-card h3 { font-weight: 700; margin-bottom: 1rem; color: #222222 !important; }
        .contact-card a { color: #652482 !important; text-decoration: none; font-weight: 600; font-size: 1.125rem; }
        .contact-card a:hover { text-decoration: underline; }
        .contact-card p { color: #222222 !important; font-size: 1rem; }
        
        /* Footer */
        footer { background: #652482 !important; color: white; padding: 2rem; text-align: center; font-size: 0.875rem; }
        footer a { color: #decfbc !important; text-decoration: none; }
        footer a:hover { text-decoration: underline; color: #ffffff !important; }
        
        /* Testimonial Section */
        .testimonial-card { background: white; padding: 2.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #decfbc; }
        .testimonial-card p:first-child { font-style: italic; color: #222222 !important; margin-bottom: 1.5rem; font-size: 1.05rem; line-height: 1.8; }
        .testimonial-card p:last-child { font-weight: 600; color: #222222 !important; }
        
        /* Image Sizing */
        img { max-width: 33%; height: auto; display: block; }
        section img { max-width: 33%; }
        .hero img { max-width: 30%; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero { padding: 2rem 0; }
            .hero-text h1 { font-size: 2rem; }
            .hero-container { gap: 1.5rem; flex-direction: column-reverse; }
            .booking-widget { max-width: 100%; padding: 1.75rem; }
            section { padding: 2rem 0; }
            .section-header h2 { font-size: 1.75rem; }
            header h1 { font-size: 1.25rem; }
            .nav-links { gap: 1rem; font-size: 0.9rem; }
            .pricing-display .price { font-size: 2.5rem; }
            .form-group { margin-bottom: 1.25rem; }
            .form-group input, .form-group select { padding: 0.75rem; font-size: 0.95rem; }
            .btn-primary { padding: 0.875rem; font-size: 0.95rem; }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Tausi Holiday & Getaway Homes</h1>
            @if (Route::has('login'))
                <div class="nav-links">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="auth-link">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-container">
                <div class="hero-text">
                    <h1>AN ENTIRE HOUSE JUST FOR YOU</h1>
                    <div class="pricing-badge">KES 25,000 PER NIGHT · BREAKFAST INCLUDED</div>
                    <p>Experience privacy, comfort, and warm hospitality in Nanyuki, Kenya. Every stay includes fresh breakfast prepared just for you.</p>
                </div>
                <div class="booking-widget">
                    <form id="bookingForm" onsubmit="goToConfirm(event)">
                        <div class="form-group">
                            <label for="checkin">Check In</label>
                            <input type="date" id="checkin" name="checkin" required>
                        </div>
                        <div class="form-group">
                            <label for="checkout">Check Out</label>
                            <input type="date" id="checkout" name="checkout" required>
                        </div>
                        <div class="form-group">
                            <label for="rooms">Number of Rooms</label>
                            <input type="number" id="rooms" name="rooms" min="1" max="10" value="1" required>
                        </div>
                        <div class="form-group">
                            <label for="adults">Adults</label>
                            <input type="number" id="adults" name="adults" min="1" max="10" value="1" required>
                        </div>
                        <div class="form-group">
                            <label for="children">Children (Optional)</label>
                            <input type="number" id="children" name="children" min="0" max="6" value="0">
                        </div>
                        <button type="submit" class="btn-primary">Review & Confirm Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Simple Pricing Section -->
    <section>
        <div class="container">
            <div class="section-header">
                <h2>SIMPLE PRICING</h2>
                <p>One Flat Rate Entire Home Stay</p>
            </div>
            <div class="pricing-display">
                <div class="price">KES 25,000</div>
                <div class="period">Per Night</div>
                <div class="subtitle">Breakfast & Hospitality Included</div>
            </div>
        </div>
    </section>

    <!-- Breakfast & Hospitality Section -->
    <section>
        <div class="container">
            <div class="section-header">
                <h2>BREAKFAST & HOSPITALITY</h2>
                <p>Simple Comforts That Make a Difference</p>
            </div>
            <p style="text-align: center; color: #666; max-width: 700px; margin: 0 auto 3rem; font-size: 1.125rem; line-height: 1.8;">At Tausi Holiday & Getaway Homes, we focus on the essentials that matter most — privacy, comfort, and a warm hosting experience. Every stay includes breakfast, prepared fresh to help you start your day relaxed and refreshed.</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <h3>Breakfast Included</h3>
                    <ul>
                        <li>Freshly prepared daily breakfast</li>
                        <li>Served in a calm, private setting</li>
                        <li>Included in the nightly rate</li>
                    </ul>
                </div>
                <div class="feature-card">
                    <h3>Home-Style Hosting</h3>
                    <ul>
                        <li>Quiet, respectful environment</li>
                        <li>Attentive on-request support</li>
                        <li>Ideal for families & small groups</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Guest Feedback Section -->
    <section>
        <div class="container">
            <div class="section-header">
                <h2>GUEST FEEDBACK</h2>
                <p>What Our Guests Say</p>
            </div>
            <div class="features-grid">
                <div class="testimonial-card">
                    <p>"A truly wonderful escape. The attention to detail and warm hospitality made our stay unforgettable. We'll definitely be back!"</p>
                    <p>— Satisfied Guest</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section>
        <div class="container">
            <div class="section-header">
                <h2>BOOKING & ENQUIRIES</h2>
                <p>Have a question about availability, pricing, or house options? Reach out and we'll be happy to assist you with your booking.</p>
            </div>
            
            <div class="contact-info">
                <div class="contact-card">
                    <h3>Call or WhatsApp</h3>
                    <a href="tel:+254718756254">+254 718 756 254</a>
                </div>
                <div class="contact-card">
                    <h3>Email us</h3>
                    <a href="mailto:bookings@tausivacations.com">bookings@tausivacations.com</a>
                </div>
                <div class="contact-card">
                    <h3>Location</h3>
                    <p>Nanyuki, Kenya</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 Tausi Holiday & Getaway Homes. All rights reserved.</p>
    </footer>

    <script>
        function goToConfirm(event) {
            event.preventDefault();
            
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            const rooms = document.getElementById('rooms').value;
            const adults = document.getElementById('adults').value;
            const children = document.getElementById('children').value || '0';
            
            if (!checkin || !checkout) {
                alert('Please fill in check-in and check-out dates');
                return;
            }
            
            const params = new URLSearchParams({
                checkin,
                checkout,
                rooms,
                adults,
                children
            });
            
            window.location.href = `/reservation/confirm?${params.toString()}`;
        }
    </script>
</body>
</html>
