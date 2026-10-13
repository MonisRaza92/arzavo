@extends('layouts.arzavo.website')
@section('title', 'Cookie Policy - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="pt-32 pb-16 md:pt-40 md:pb-20 bg-secondary"
         >
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-theme-text mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-theme-text tracking-tight mb-5 leading-tight animate-fade-in-up">
                Cookie
                <span class="text-theme-text">Policy.</span>
            </h1>
            <p class="text-lg text-theme-text-muted leading-relaxed animate-fade-in-up" style="animation-delay:.1s;">
                Effective Date: 05/08/2026 · Last Updated: 05/08/2026
            </p>
        </div>
    </div>
</section>

{{-- Policy Content --}}
<section class="py-12 bg-secondary"
         >
    <div class="container max-w-4xl">
        <div class="bg-primary rounded-lg p-8 md:p-12 text-theme-text-muted leading-relaxed text-sm space-y-6">
            
            {{-- 1. Introduction --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">1. Introduction</h3>
                <p>
                    This Cookie Policy explains how Arzavo by <strong>ARZAQ INSIGHTS</strong> uses cookies and similar technologies when you visit the Arzavo website or use the platform.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 2. What Are Cookies? --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">2. What Are Cookies?</h3>
                <p>
                    Cookies are small text files stored on your device that help websites remember preferences, maintain sessions, improve performance, and enhance security.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 3. Cookies We Use --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">3. Cookies We Use</h3>
                <p class="mb-4">We categorize our cookies as follows:</p>
                <div class="space-y-4">
                    <div class="bg-primary rounded-lg p-5">
                        <h4 class="text-sm font-semibold text-theme-text mb-2">Essential Cookies</h4>
                        <p class="text-sm text-theme-text-muted leading-relaxed">
                            Required for login authentication, tenant isolation, CSRF protection, and core platform security. These cannot be disabled as the system cannot function without them.
                        </p>
                    </div>
                    <div class="bg-primary rounded-lg p-5">
                        <h4 class="text-sm font-semibold text-theme-text mb-2">Preference Cookies</h4>
                        <p class="text-sm text-theme-text-muted leading-relaxed">
                            Used to remember your selected language settings, UI preferences, dark/light themes, and custom configuration options.
                        </p>
                    </div>
                    <div class="bg-primary rounded-lg p-5">
                        <h4 class="text-sm font-semibold text-theme-text mb-2">Analytics Cookies</h4>
                        <p class="text-sm text-theme-text-muted leading-relaxed">
                            Help us evaluate and improve platform performance, track navigation patterns, and identify system usability bottlenecks. Analytics data is aggregated and anonymized.
                        </p>
                    </div>
                    <div class="bg-primary rounded-lg p-5">
                        <h4 class="text-sm font-semibold text-theme-text mb-2">Security Cookies</h4>
                        <p class="text-sm text-theme-text-muted leading-relaxed">
                            Used to detect suspicious activities, mitigate login abuse, prevent potential fraud, and verify authorization tokens.
                        </p>
                    </div>
                    <div class="bg-primary rounded-lg p-5">
                        <h4 class="text-sm font-semibold text-theme-text mb-2">Performance Cookies</h4>
                        <p class="text-sm text-theme-text-muted leading-relaxed">
                            Help optimize layout rendering speed, request routing, and deliver a smooth responsive experience to all end-users.
                        </p>
                    </div>
                </div>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 4. Third-Party Technologies --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">4. Third-Party Technologies</h3>
                <p>
                    Depending on enabled features, Arzavo may integrate with AWS, Cloudflare, payment providers (Razorpay), communication providers, single sign-on (Google/Microsoft), analytics tools, and other trusted service providers. These providers may use cookies or similar technologies as described in their own respective privacy policies.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 5. Managing Cookies --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">5. Managing Cookies</h3>
                <p>
                    Most modern browsers allow you to view, block, or delete cookies through their built-in preferences panels. Please note that disabling essential cookies may prevent parts of Arzavo from functioning correctly.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 6. Data Protection --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">6. Data Protection</h3>
                <p>
                    Cookies are used in strict conjunction with our Privacy Policy and security practices. Tenant data remains completely and logically isolated through Arzavo's multi-tenant backend architecture.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 7. Changes to this Policy --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">7. Changes to this Policy</h3>
                <p>
                    We may update this Cookie Policy from time to time to align with legal guidelines or structural updates. Material changes will be reflected by updating the 'Last Updated' date at the top of this document.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 8. Contact --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-theme-text mb-4">8. Contact</h3>
                <p class="mb-4">For any questions or support regarding cookies, contact us:</p>
                <div class="space-y-2 text-sm">
                    <p><strong>ARZAQ INSIGHTS</strong></p>
                    <p><strong>Address:</strong> 208/10 Musapur, Sandila, Hardoi, Uttar Pradesh 241204</p>
                    <p><strong>Email:</strong> <a href="mailto:support@arzavo.com" class="text-theme-text hover:underline">support@arzavo.com</a></p>
                    <p><strong>Phone:</strong> +91 8090492602</p>
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
