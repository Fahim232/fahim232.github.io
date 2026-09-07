<?php
$page_title = 'Portfolio | Full-Stack Developer & QA Engineer';
$success_message = '';
$error_message = '';

// --- Handle Direct Profile Photo Upload ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
    if ($_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['profile_photo']['tmp_name'];
        if (getimagesize($tmp_name) !== false) {
            if (!is_dir('images')) {
                mkdir('images', 0777, true);
            }
            if (!move_uploaded_file($tmp_name, 'images/photo.jpg')) {
                $error_message = "Permission denied to update images/photo.jpg";
            }
        } else {
            $error_message = "Uploaded file is not a valid image.";
        }
    } else {
        $error_message = "Error uploading profile photo.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $errors = [];
    if (empty($name)) $errors[] = 'Name is required.';
    if (empty($email)) $errors[] = 'Email is required.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
    if (empty($message)) $errors[] = 'Message is required.';

    $attachment_info = '';
    if (empty($errors) && isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", basename($_FILES['image']['name']));
            $target_file = $upload_dir . $filename;
            
            $check = getimagesize($_FILES['image']['tmp_name']);
            if ($check !== false) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                    $domain = $_SERVER['HTTP_HOST'];
                    $path = rtrim(dirname($_SERVER['REQUEST_URI']), '/');
                    $file_url = $protocol . "://" . $domain . $path . "/" . $target_file;
                    $attachment_info = "\n\nAttached Image: " . $file_url;
                } else {
                    $errors[] = 'Failed to save the image. Permission denied on uploads directory.';
                }
            } else {
                $errors[] = 'File is not a valid image format.';
            }
        } else {
            $errors[] = 'Upload error code: ' . $_FILES['image']['error'];
        }
    }

    if (empty($errors)) {
        $to = 'fahim@example.com';
        $headers = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";
        $body = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message" . $attachment_info;
        if (@mail($to, "Portfolio Contact: $subject", $body, $headers)) {
            $success_message = 'Thank you! Your message has been sent successfully.';
        } else {
            $error_message = 'Sorry, the message could not be sent. Please try again or email directly.';
        }
    } else {
        $error_message = implode(' ', $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio of Md. Fahim Montasir Seyam - Full-Stack Developer & QA Engineer specializing in AI-powered systems and software testing">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Simple Layout Start -->

    <header class="header" id="header">
        <nav class="nav container">
            <a href="#hero" class="nav__logo">Fahim.dev</a>
            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li><a href="#hero" class="nav__link">Home</a></li>
                    <li><a href="#about" class="nav__link">About</a></li>
                    <li><a href="#skills" class="nav__link">Skills</a></li>
                    <li><a href="#projects" class="nav__link">Projects</a></li>
                    <li><a href="#contact" class="nav__link">Contact</a></li>
                </ul>
                <button class="nav__close" id="nav-close" aria-label="Close menu">
                    <span></span><span></span>
                </button>
            </div>
            <button class="nav__toggle" id="nav-toggle" aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>
        </nav>
    </header>

    <main>
        <section class="hero" id="hero">
            <div class="hero__bg"></div>
            <div class="container hero__container">
                <div class="hero__content">
                    <p class="hero__greeting reveal">Hello, I'm</p>
                    <h1 class="hero__title reveal">Md. Fahim Montasir Seyam</h1>
                    <p class="hero__subtitle reveal">3rd Year CS Student at UIU | Full-Stack Developer & QA Engineer</p>
                    <p class="hero__desc reveal">Building AI-powered recruitment ecosystems and ensuring software quality through manual testing, API testing, and automation with Playwright. Engineering robust, smart, and reliable web solutions.</p>
                    <div class="hero__cta reveal">
                        <a href="#projects" class="btn btn--primary">View Projects</a>
                        <a href="#" class="btn btn--outline"><i class="fas fa-download"></i> Resume</a>
                    </div>
                    <div class="hero__socials reveal">
                        <a href="#" target="_blank" aria-label="GitHub"><i class="fab fa-github"></i></a>
                        <a href="#" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        <a href="#" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="hero__photo-wrap reveal">
                    <div class="hero__photo-container">
                        <img src="images/photo.jpg?v=<?php echo @filemtime('images/photo.jpg'); ?>" alt="Md.Fahim Montasir Seyam" class="hero__photo" width="400" height="400">
                        <form method="post" enctype="multipart/form-data" class="profile-upload-form">
                            <label for="profile_photo" class="profile-upload-btn" title="Change Profile Photo">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="profile-upload-input" onchange="this.form.submit();">
                        </form>
                    </div>
                </div>
            </div>
            <a href="#about" class="hero__scroll" aria-label="Scroll down">
                <span class="hero__scroll-icon"></span>
            </a>
        </section>

        <section class="section about" id="about">
            <div class="container">
                <h2 class="section__title reveal">About Me</h2>
                <div class="about__grid">
                    <div class="about__image-wrap reveal">
                        <img src="images/photo.jpg?v=<?php echo @filemtime('images/photo.jpg'); ?>" alt="Md.Fahim Montasir Seyam" class="about__image" width="360" height="360">
                    </div>
                    <div class="about__content">
                        <div class="reveal">
                        <p class="about__text">I'm a 3rd-year Computer Science student at UIU, building AI-powered recruitment ecosystems and ensuring software quality through comprehensive testing methodologies.</p>
                        <p class="about__text">My expertise spans full-stack development with PHP, MySQL, and JavaScript — complemented by STQA skills including manual testing, API testing, Playwright automation, and Agile/Scrum practices.</p>
                        <ul class="about__list">
                            <li>Full-stack development: PHP, MySQL, JavaScript, REST APIs</li>
                            <li>Software Testing: Manual Testing, API Testing, Playwright</li>
                            <li>Methodologies: SDLC, Agile, Kanban, Scrum</li>
                            <li>AI integration in web systems (hybrid online/offline engines)</li>
                        </ul>
                        <a href="#contact" class="btn btn--primary">Contact Me</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section skills" id="skills">
            <div class="container">
                <h2 class="section__title reveal">Skills & Expertise</h2>
                <div class="skills__grid">
                    <div class="skill-card reveal">
                        <span class="skill-card__icon"><i class="fas fa-code"></i></span>
                        <h3 class="skill-card__title">Front-End</h3>
                        <p>HTML5, CSS3, JavaScript, Responsive Design, AJAX</p>
                    </div>
                    <div class="skill-card reveal">
                        <span class="skill-card__icon"><i class="fas fa-server"></i></span>
                        <h3 class="skill-card__title">Back-End</h3>
                        <p>PHP, MySQL, REST APIs, WebSocket, Security</p>
                    </div>
                    <div class="skill-card reveal">
                        <span class="skill-card__icon"><i class="fas fa-brain"></i></span>
                        <h3 class="skill-card__title">AI & Automation</h3>
                        <p>AI Matching Engines, Smart Screening, Hybrid AI (Online/Offline)</p>
                    </div>
                    <div class="skill-card skill-card--qa reveal">
                        <span class="skill-card__icon"><i class="fas fa-bug"></i></span>
                        <h3 class="skill-card__title">Software Testing (STQA)</h3>
                        <p>Manual Testing, API Testing, Test Case Design, Bug Reporting, Regression Testing</p>
                    </div>
                    <div class="skill-card skill-card--qa reveal">
                        <span class="skill-card__icon"><i class="fas fa-robot"></i></span>
                        <h3 class="skill-card__title">Test Automation</h3>
                        <p>Playwright, End-to-End Testing, Cross-browser Testing, Test Scripts</p>
                    </div>
                    <div class="skill-card skill-card--qa reveal">
                        <span class="skill-card__icon"><i class="fas fa-project-diagram"></i></span>
                        <h3 class="skill-card__title">Methodologies</h3>
                        <p>SDLC, Agile, Scrum, Kanban, Sprint Planning</p>
                    </div>
                    <div class="skill-card reveal">
                        <span class="skill-card__icon"><i class="fas fa-database"></i></span>
                        <h3 class="skill-card__title">Database</h3>
                        <p>MySQL, Database Design, Query Optimization</p>
                    </div>
                    <div class="skill-card reveal">
                        <span class="skill-card__icon"><i class="fas fa-tools"></i></span>
                        <h3 class="skill-card__title">Tools</h3>
                        <p>Git, VS Code, Figma, XAMPP, Postman</p>
                    </div>
                    <div class="skill-card reveal">
                        <span class="skill-card__icon"><i class="fas fa-layer-group"></i></span>
                        <h3 class="skill-card__title">Architecture</h3>
                        <p>MVC, RESTful Design, Real-time Systems, Scalable Platforms</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section projects" id="projects">
            <div class="container">
                <h2 class="section__title reveal">Flagship Projects</h2>
                <p class="section__subtitle reveal">A full-featured recruitment ecosystem connecting job seekers, companies, and mentors — powered by a hybrid AI engine that works both online and offline.</p>
                <div class="projects__grid">
                    <article class="project-card project-card--featured reveal">
                        <div class="project-card__image project-card__image--jobportal">
                            <div class="project-card__badge">Flagship</div>
                        </div>
                        <div class="project-card__content">
                            <h3 class="project-card__title">AI-Powered Job Portal</h3>
                            <p class="project-card__desc">A comprehensive recruitment platform with AI-driven job matching, smart candidate screening, real-time notifications, and company dashboards. Features automated CV analysis and intelligent job recommendations.</p>
                            <div class="project-card__features">
                                <span><i class="fas fa-robot"></i> AI Job Matching</span>
                                <span><i class="fas fa-file-alt"></i> Smart CV Analysis</span>
                                <span><i class="fas fa-building"></i> Company Portal</span>
                                <span><i class="fas fa-bell"></i> Real-time Alerts</span>
                            </div>
                            <div class="project-card__tags">
                                <span>PHP</span><span>MySQL</span><span>AI/ML</span><span>JavaScript</span><span>REST APIs</span>
                            </div>
                            <a href="#" class="project-card__link">View Case Study →</a>
                        </div>
                    </article>
                    <article class="project-card project-card--featured reveal">
                        <div class="project-card__image project-card__image--grooming">
                            <div class="project-card__badge">Flagship</div>
                        </div>
                        <div class="project-card__content">
                            <h3 class="project-card__title">Career Grooming Platform</h3>
                            <p class="project-card__desc">An integrated career development ecosystem with AI-powered skill assessments, personalized learning paths, mock interviews, and mentorship matching. Bridges the gap between talent and opportunity.</p>
                            <div class="project-card__features">
                                <span><i class="fas fa-brain"></i> AI Skill Assessment</span>
                                <span><i class="fas fa-graduation-cap"></i> Learning Paths</span>
                                <span><i class="fas fa-video"></i> Mock Interviews</span>
                                <span><i class="fas fa-users"></i> Mentorship</span>
                            </div>
                            <div class="project-card__tags">
                                <span>PHP</span><span>AJAX</span><span>AI Engine</span><span>CSS3</span><span>WebSocket</span>
                            </div>
                            <a href="#" class="project-card__link">View Case Study →</a>
                        </div>
                    </article>
                    <article class="project-card project-card--qa reveal">
                        <div class="project-card__image project-card__image--testpanda">
                            <div class="project-card__badge project-card__badge--qa">QA Project</div>
                        </div>
                        <div class="project-card__content">
                            <h3 class="project-card__title">TestPanda Lite — API Testing Suite</h3>
                            <p class="project-card__desc">A comprehensive API testing system for validating REST endpoints. Features automated test execution, response validation, status code verification, and detailed test reporting for quality assurance.</p>
                            <div class="project-card__features">
                                <span><i class="fas fa-plug"></i> API Endpoint Testing</span>
                                <span><i class="fas fa-check-circle"></i> Response Validation</span>
                                <span><i class="fas fa-chart-bar"></i> Test Reports</span>
                                <span><i class="fas fa-sync-alt"></> Regression Suite</span>
                            </div>
                            <div class="project-card__tags">
                                <span>Playwright</span><span>API Testing</span><span>JavaScript</span><span>Postman</span>
                            </div>
                            <a href="#" class="project-card__link">View Details →</a>
                        </div>
                    </article>
                    <article class="project-card reveal">
                        <div class="project-card__image project-card__image--algo"></div>
                        <div class="project-card__content">
                            <h3 class="project-card__title">Algorithm Visualizer</h3>
                            <p class="project-card__desc">Interactive tool to visualize sorting algorithms and data structures in real-time with step-by-step execution.</p>
                            <div class="project-card__tags">
                                <span>JavaScript</span><span>HTML5</span><span>CSS3</span>
                            </div>
                            <a href="#" class="project-card__link">Try It Out →</a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="section contact" id="contact">
            <div class="container">
                <h2 class="section__title reveal">Get In Touch</h2>
                <?php if ($success_message): ?>
                    <div class="alert alert--success"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <div class="alert alert--error"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                <div class="contact__grid">
                    <div class="contact__info">
                        <p class="contact__text">Have a project in mind or want to collaborate? Send me a message.</p>
                        <ul class="contact__details">
                            <li><strong>Email:</strong> fahim@example.com</li>
                            <li><strong>Location:</strong> Dhaka, Bangladesh</li>
                            <li><strong>University:</strong> UIU (Computer Science)</li>
                        </ul>
                    </div>
                    <form class="contact__form" method="post" action="#contact" enctype="multipart/form-data">
                        <input type="hidden" name="contact_submit" value="1">
                        <div class="form__group">
                            <input type="text" id="name" name="name" placeholder=" " required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                            <label for="name">Name *</label>
                        </div>
                        <div class="form__group">
                            <input type="email" id="email" name="email" placeholder=" " required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            <label for="email">Email *</label>
                        </div>
                        <div class="form__group">
                            <input type="text" id="subject" name="subject" placeholder=" " value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                            <label for="subject">Subject</label>
                        </div>
                        <div class="form__group">
                            <textarea id="message" name="message" rows="5" placeholder=" " required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            <label for="message">Message *</label>
                        </div>
                        <div class="form__group form__group--file">
                            <label for="image" class="file-label"><i class="fas fa-image"></i> Attach Image (optional)</label>
                            <input type="file" id="image" name="image" accept="image/*" class="form__file">
                        </div>
                        <button type="submit" class="btn btn--primary btn--full">Send Message</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p class="footer__copy">&copy; <?php echo date('Y'); ?> Md.Fahim Montasir Seyam. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
