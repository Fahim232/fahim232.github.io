# Portfolio Website

A professional, responsive, and interactive portfolio built with HTML, CSS, PHP, and JavaScript.

## Features

- **Responsive design** – Works on mobile, tablet, and desktop with a polished layout
- **Your photo** – Hero and About sections use your picture (`images/photo.jpg`)
- **Scroll animations** – Sections and cards reveal as you scroll
- **Active nav** – Navigation highlights the current section
- **Interactive elements** – Hover effects on cards, buttons, and your photo
- **Sections**: Hero (with photo), About, Skills, Projects, Contact
- **Contact form** – Handled with PHP (mail); customize recipient in `index.php`
- **Dark theme** – Modern look with accent color
- **Smooth scroll** – In-page navigation
- **Mobile menu** – Hamburger navigation on small screens
- **Focus states** – Keyboard-accessible focus outlines

## Setup

1. **Local server (PHP required)**  
   From the `portfolio` folder run:
   ```bash
   php -S localhost:8000
   ```
   Then open http://localhost:8000

2. **Or** place the `portfolio` folder in your web server’s document root (e.g. XAMPP `htdocs`, MAMP `htdocs`) and open the site in your browser.

## Your photo

Add your photo so it appears in the Hero and About sections:

1. Place your image in the `images` folder.
2. Name it **`photo.jpg`** (or update both `src="images/photo.jpg"` in `index.php` to your filename).
3. Use a square or portrait image (e.g. 400×400 or 600×600) for best results.

## Customize

- **Name & role**: Edit the hero section in `index.php` (name, subtitle, description).
- **About**: Update the about text and list in `index.php`.
- **Skills**: Change the skill cards and text in the Skills section.
- **Projects**: Replace placeholder projects with your own titles, descriptions, links, and images.
- **Contact**: In `index.php`, set `$to = 'your@email.com';` to the email that should receive form submissions.
- **Footer**: Change "Your Name" in the footer to your name.

## Contact form (mail)

The form uses PHP’s `mail()`. For it to work you need:

- A server that can send email (e.g. proper PHP `mail()` config or SMTP), or
- A script that forwards submissions to your email or saves them to a file/database.

On many local setups, `mail()` won’t send real email; the form will still submit and show the success/error message.

## File structure

```
portfolio/
├── index.php       # Main page + contact form handling
├── images/
│   └── photo.jpg   # Your photo (add this file)
├── css/
│   └── style.css   # All styles (responsive, animations, interactions)
├── js/
│   └── main.js     # Mobile menu, scroll reveal, active nav
└── README.md       # This file
```
