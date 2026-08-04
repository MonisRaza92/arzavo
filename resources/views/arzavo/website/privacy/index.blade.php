@extends('layouts.app')
@section('title', 'Privacy Policy - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Privacy
                <span class="text-accent">Policy.</span>
            </h1>
            <p class="text-lg text-dark/60 leading-relaxed animate-fade-in-up" style="animation-delay:.1s;">
                Last updated: 05/08/2026 · Effective immediately
            </p>
        </div>
    </div>
</section>

{{-- Policy Content --}}
<section class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #f9f9f9 0%, #fff 100%);">
    <div class="container max-w-4xl">
        <div class="rounded-lg border border-gray-200 bg-white p-8 md:p-12 text-dark/70 leading-relaxed text-sm space-y-6">
            
            <p>
                Arzavo (operated by <strong>ARZAQ INSIGHTS</strong>, a sole proprietorship with registered address at 208/10 Musapur, Sandila, Hardoi, Uttar Pradesh – 241204) provides a cloud-based, multi-tenant ERP/LMS platform designed for educational institutions (schools, colleges, coaching institutes, academies, etc.). Arzavo is hosted on AWS’s Mumbai data center.
            </p>
            <p>
                This Privacy Policy explains how we (ARZAQ INSIGHTS, “we,” “us,” or “Arzavo”) collect, use, disclose, and protect personal data in connection with our services and website (collectively, the “Platform”), and describes your choices regarding that data. It applies to all individuals whose data is processed via the Platform, including institutional administrators, teachers, staff, students, parents/guardians, and others (“users” or “you”).
            </p>
            <p>
                This Policy is issued in compliance with Indian law, including the Information Technology Act 2000 and its SPDI Rules 2011, and the Digital Personal Data Protection Act 2023 (“DPDP Act”). The DPDP Act imposes obligations on data fiduciaries to process personal data only for lawful purposes with consent, to maintain accuracy and security of data, and to delete data once its purpose has been met.
            </p>
            
            <div class="p-4 rounded bg-accent/5 border border-accent/15 text-dark/80 my-6">
                <strong>Data Processing Role:</strong> Each subscribing institution (“Institution”) is the <strong>Data Fiduciary</strong> for the personal data of its students, staff, and associated individuals, and Arzavo (ARZAQ INSIGHTS) acts as a <strong>Data Processor</strong> on the Institution’s behalf. The Institution controls what data is collected and how it is used, while Arzavo processes that data solely to provide the Platform’s services. Each Institution’s data is stored in a logically isolated database, ensuring no data is shared across different tenants.
            </div>

            <p>
                Please read this Policy carefully. By using Arzavo or providing us with any personal data, you acknowledge that you have read and understood this Policy and consent to the practices described herein. If you do not agree with this Policy, you should not use our services or provide any data to us.
            </p>

            <hr class="border-gray-100 my-8">

            {{-- 1. Information We Collect --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">1. Information We Collect</h3>
                <p class="mb-4">We collect different categories of information, depending on how you use the Platform. Broadly, the data can be divided as follows:</p>
                <ul class="list-disc pl-5 space-y-3 mb-4">
                    <li>
                        <strong>Institutional Data (Data Provided by Institutions):</strong> Institutions may upload or enter information about their students, staff, academic records, attendance, fee payments, and other operational data into the Platform. This includes personal details such as names, dates of birth, contact information (email, phone, address), identification numbers, school records, attendance logs, and financial records.
                    </li>
                    <li>
                        <strong>Account and Profile Information:</strong> When an individual user creates an account, we collect personal identifiers such as name, email address, and login credentials (passwords are stored in encrypted form). We may also collect profile details like job title, department, role within the Institution, and profile photo if provided.
                    </li>
                    <li>
                        <strong>Communications and Support:</strong> We store information you provide when contacting support (support@arzavo.com) or providing feedback, including the content of emails or tickets and any attachments.
                    </li>
                    <li>
                        <strong>Usage and Technical Data (Automatically Collected):</strong> When you access or use the Platform, we automatically log technical data about your device and connection, including IP address, device type, operating system, browser type, access times, pages/features used, and error logs.
                    </li>
                    <li>
                        <strong>Third-Party Authentication Data:</strong> If you choose to log in using third-party services (e.g. Google or Microsoft single sign-on), we collect the information that those services provide with your consent (typically name, email, and unique ID).
                    </li>
                    <li>
                        <strong>Payment Information:</strong> We do not directly collect full credit card information. Payment transactions (for paid plans) are processed through Razorpay (India) or other PCI-compliant gateways. We may record transactional data such as the amount charged, date of payment, and invoice details.
                    </li>
                    <li>
                        <strong>Communications Data:</strong> If enabled by the Institution, we may send notifications (email, SMS, WhatsApp messages, push notifications) to you on behalf of the Institution.
                    </li>
                </ul>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 2. How We Use Information --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">2. How We Use Information</h3>
                <p class="mb-4">We use the collected data for the following purposes:</p>
                <ul class="list-disc pl-5 space-y-2 mb-4">
                    <li><strong>Providing the Service:</strong> To perform the core functions of Arzavo, rendering the software features subscribed to by your Institution.</li>
                    <li><strong>Account Management:</strong> To send you account-related notifications (e.g. password resets, important updates, or invoices).</li>
                    <li><strong>Customer Support:</strong> To assist you and improve our support process when you reach out for help.</li>
                    <li><strong>Payment Processing:</strong> To bill for subscriptions, process refunds, and maintain accounting records.</li>
                    <li><strong>Security and Fraud Prevention:</strong> To monitor use patterns and log data to detect and prevent security incidents or unauthorized access.</li>
                    <li><strong>Product Improvement:</strong> To analyze usage data (in aggregate form) to understand feature adoption and improve Platform usability.</li>
                    <li><strong>Legal and Compliance:</strong> To comply with legal obligations (such as tax and accounting laws) or to enforce our Terms of Service.</li>
                </ul>
                <p>We never sell your personal data or use it for unrelated marketing purposes.</p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 3. Data Sharing and Disclosure --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">3. Data Sharing and Disclosure</h3>
                <p class="mb-4">We engage trusted third parties to perform services on our behalf:</p>
                <ul class="list-disc pl-5 space-y-2 mb-4">
                    <li><strong>Cloud Infrastructure:</strong> Amazon Web Services (AWS) hosts our servers in the Mumbai region under strict data protection compliance.</li>
                    <li><strong>Content Delivery and Security:</strong> We use Cloudflare to accelerate delivery and protect against security attacks.</li>
                    <li><strong>Payment Gateway:</strong> Razorpay (India) processes subscription payments securely.</li>
                    <li><strong>Email, SMS and Push Notification:</strong> We use certified email and messaging providers (e.g. Twilio) to deliver communications.</li>
                    <li><strong>Analytics and Monitoring:</strong> Google Analytics and error tracking tools collect limited metadata to help us optimize the platform.</li>
                </ul>
                <p>Each such provider is contractually obligated to protect data and use it only to perform its function. We do not share data with third parties for their own marketing purposes.</p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 4. Data Retention and Deletion --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">4. Data Retention and Deletion</h3>
                <p class="mb-4">We retain personal data as long as the Institution’s account is active and as needed to provide the service:</p>
                <ul class="list-disc pl-5 space-y-2 mb-4">
                    <li><strong>Account Data Deletion:</strong> Upon deletion requests, we purge personal records. Certain administrative records (invoices, basic customer info, payment confirmations) are kept for up to 8 years for statutory compliance with tax and company law.</li>
                    <li><strong>User-Initiated Deletion:</strong> Individual users may request deletion of their personal accounts. We remove personal profile info within 30 days.</li>
                    <li><strong>Children’s Data:</strong> If we are notified of accidentally collecting children\'s data without parental consent, we will promptly delete it.</li>
                </ul>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 5. Data Security --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">5. Data Security</h3>
                <p>
                    We implement industry-standard technical measures (TLS/HTTPS in transit, encrypted storage, and access controls) to protect data confidentiality. While we strive to protect data, no system is 100% secure, and we continually assess risks and improve safeguards. If a breach occurs, we follow legal requirements to notify affected parties.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 6. Your Rights --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">6. Your Rights</h3>
                <p class="mb-4">Under the DPDP Act and related laws, you have certain rights regarding your personal data:</p>
                <ul class="list-disc pl-5 space-y-2 mb-4">
                    <li><strong>Access and Correction:</strong> Request confirmation of what data we hold and request corrections.</li>
                    <li><strong>Erasure:</strong> Request deletion of your personal data, subject to tax and company law retention requirements.</li>
                    <li><strong>Grievance Redressal:</strong> Raise concerns directly to our Grievance Officer (contact details below).</li>
                    <li><strong>Consent Withdrawal:</strong> Withdraw consent for marketing or communication activities at any time.</li>
                </ul>
                <p>Since the Institution is the Data Fiduciary, requests regarding student information should be directed to the Institution's administrator.</p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 7. Children’s Privacy --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">7. Children’s Privacy</h3>
                <p>
                    If any personal data collected pertains to children (individuals under 18), the Institution must ensure parental consent has been obtained. Arzavo does not knowingly collect personal data directly from children without parental consent. Parents/guardians can request information or deletion of a child’s data through the Institution.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 8. Cookies and Tracking --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">8. Cookies and Tracking</h3>
                <p>
                    Our Platform uses cookies and similar technologies to store preferences, sessions, and track usage statistics (Google Analytics). You can control cookies through browser settings, though disabling them may limit some site functionalities.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 9. Governing Law and Disputes --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">9. Governing Law and Disputes</h3>
                <p>
                    This Privacy Policy is governed by the laws of India. Any disputes related to this Policy or Arzavo’s data practices will be subject to the exclusive jurisdiction of courts in <strong>Ghaziabad, Uttar Pradesh</strong>. We reserve the right to modify this Policy at any time.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 10. Contact Us --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-dark mb-4">10. Contact Us</h3>
                <p class="mb-4">For questions or concerns about this Privacy Policy, or to exercise your privacy rights, please contact us:</p>
                <div class="space-y-2 text-sm">
                    <p><strong>Grievance Officer:</strong> Monis Raza Khan</p>
                    <p><strong>Address:</strong> 208/10 Musapur, Sandila, Hardoi, Uttar Pradesh – 241204</p>
                    <p><strong>Email:</strong> <a href="mailto:support@arzavo.com" class="text-accent hover:underline">support@arzavo.com</a></p>
                    <p><strong>Phone:</strong> +91 80904 92602</p>
                </div>
            </div>
            
        </div>
    </div>
</section>

@include('arzavo.website.partials.footer')
@endsection

<style>
@keyframes fade-in-down { from{opacity:0;transform:translateY(-12px);}to{opacity:1;transform:translateY(0);} }
@keyframes fade-in-up { from{opacity:0;transform:translateY(18px);}to{opacity:1;transform:translateY(0);} }
.animate-fade-in-down { animation: fade-in-down .6s ease-out both; }
.animate-fade-in-up { animation: fade-in-up .6s ease-out both; }
.{ opacity:0; transform:translateY(15px); transition:opacity .5s ease,transform .5s ease; transition-delay:var(--reveal-delay,0s); }
..visible { opacity:1; transform:translateY(0); }
</style>
<script>
document.addEventListener('DOMContentLoaded',()=>{
    const obs=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible');}),{threshold:.05});
    document.querySelectorAll('.').forEach(el=>obs.observe(el));
});
</script>
