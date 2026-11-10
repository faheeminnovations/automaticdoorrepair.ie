<?php
require_once __DIR__ . '/../includes/functions.php';
$page_title       = 'Privacy Policy | Elite Tech Precision';
$meta_description = 'Privacy Policy for Elite Tech Precision — automaticdoorrepair.ie. How we collect, use and protect your personal data.';
$canonical        = SITE_URL . '/privacy-policy/';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1>Privacy Policy</h1>
        <p class="lead">How we collect, use and protect your information.</p>
        <nav class="breadcrumb-nav" aria-label="Breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
            </ol>
        </nav>
    </div>
</div>

<section>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <p class="text-muted mb-4"><em>Last updated: <?= date('F Y') ?></em></p>

                <h2 class="h4 mb-3">1. Who We Are</h2>
                <p>Elite Tech Precision ("we", "us", "our") operates the website automaticdoorrepair.ie. We are based in Dublin, Ireland. For questions about this policy, contact us at <a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a>.</p>

                <h2 class="h4 mb-3 mt-4">2. Information We Collect</h2>
                <p>We may collect the following personal information when you use our website or contact us:</p>
                <ul>
                    <li>Name, email address, phone number (when you submit a contact form)</li>
                    <li>IP address and browser type (automatically collected via server logs)</li>
                    <li>Cookies and similar tracking technologies (see section 5)</li>
                </ul>

                <h2 class="h4 mb-3 mt-4">3. How We Use Your Information</h2>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Respond to your enquiries and service requests</li>
                    <li>Provide quotes and arrange service visits</li>
                    <li>Send service-related communications</li>
                    <li>Improve our website and services</li>
                    <li>Comply with legal obligations</li>
                </ul>

                <h2 class="h4 mb-3 mt-4">4. Legal Basis for Processing</h2>
                <p>Under the GDPR, our legal basis for processing your personal data is:</p>
                <ul>
                    <li><strong>Legitimate interests:</strong> responding to your enquiries</li>
                    <li><strong>Contract performance:</strong> fulfilling service agreements</li>
                    <li><strong>Legal obligation:</strong> complying with applicable laws</li>
                </ul>

                <h2 class="h4 mb-3 mt-4">5. Cookies</h2>
                <p>Our website uses essential cookies only (session cookies required for form security). We do not use advertising or tracking cookies without your consent.</p>

                <h2 class="h4 mb-3 mt-4">6. Data Sharing</h2>
                <p>We do not sell, rent or trade your personal data. We may share it with trusted third-party service providers (such as email providers) only as necessary to deliver our services, and only under strict data processing agreements.</p>

                <h2 class="h4 mb-3 mt-4">7. Data Retention</h2>
                <p>We retain your contact form data for up to 2 years, after which it is securely deleted unless required by law or ongoing contract.</p>

                <h2 class="h4 mb-3 mt-4">8. Your Rights</h2>
                <p>Under GDPR, you have the right to:</p>
                <ul>
                    <li>Access your personal data</li>
                    <li>Correct inaccurate data</li>
                    <li>Request deletion of your data</li>
                    <li>Object to or restrict processing</li>
                    <li>Lodge a complaint with the Data Protection Commission (dataprotection.ie)</li>
                </ul>

                <h2 class="h4 mb-3 mt-4">9. Contact</h2>
                <p>To exercise your rights or for any privacy-related questions, contact us at:</p>
                <p><strong>Elite Tech Precision</strong><br>
                Email: <a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a><br>
                Phone: <a href="tel:<?= SITE_PHONE ?>"><?= SITE_PHONE_DISPLAY ?></a></p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
