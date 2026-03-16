@extends('layouts.home')

@section('title', 'Welcome to Our Website')

@section('content')
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1>Welcome to Our Website</h1>
                <p>We provide amazing solutions for your business needs. Let us help you grow and succeed in the digital world.</p>
                <a href="#contact" class="hero-btn">Get Started</a>
            </div>
        </div>
    </section>

    <section id="about" class="about">
        <div class="about-container">
            <h2 class="section-title">About Us</h2>
            <div class="about-content">
                <div class="about-text">
                    <h3>Who We Are</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.</p>
                </div>
                <div class="about-stats">
                    <div class="stat">
                        <h4>10+</h4>
                        <p>Years Experience</p>
                    </div>
                    <div class="stat">
                        <h4>500+</h4>
                        <p>Projects Completed</p>
                    </div>
                    <div class="stat">
                        <h4>100+</h4>
                        <p>Happy Clients</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="contact-container">
            <h2 class="section-title">Contact Us</h2>
            <div class="contact-content">
                <div class="contact-info">
                    <h3>Get in Touch</h3>
                    <p>Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                    <div class="contact-details">
                        <div class="contact-item">
                            <h4>Address:</h4>
                            <p>123 Business Street, City, State 12345</p>
                        </div>
                        <div class="contact-item">
                            <h4>Email:</h4>
                            <p>info@yourcompany.com</p>
                        </div>
                        <div class="contact-item">
                            <h4>Phone:</h4>
                            <p>(123) 456-7890</p>
                        </div>
                    </div>
                </div>
                <div class="contact-form">
                    <form action="#" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection