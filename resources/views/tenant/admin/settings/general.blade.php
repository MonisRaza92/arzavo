@extends('layouts.admin')

@section('title', 'General Settings')

@section('content')
{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary mb-4">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1.5">
            <i class="fa-solid fa-sliders text-primary text-base"></i>
            General Settings
        </h2>
        <p class="text-sm text-secondary hidden sm:block">Control how your platform appears on search engines and configure analytics tracking.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.store') }}" enctype="multipart/form-data">
    @csrf

    {{-- 1. STANDARD SEO SETTINGS --}}
    <div class="bg-primary border-rounded border-primary mb-4">
        <h2 class="text-md font-semibold p-4 border-bottom text-primary flex items-center gap-2">
            <i class="fa-solid fa-earth-americas text-primary text-sm"></i>
            General SEO & Search Settings
        </h2>

        <div class="p-4 flex flex-col gap-4">
            
            {{-- Grid for inputs --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                {{-- Meta Title --}}
                <div class="w-full">
                    <x-input.text name="meta_title" label="Default Meta Title" :value="$settings['meta_title'] ?? ''" placeholder="Enter default meta title..." hint="The primary title of your homepage shown in search results and browser tab. Recommended length: 50-60 characters." />
                </div>

                {{-- Meta Keywords --}}
                <div class="w-full">
                    <x-input.text name="meta_keywords" label="Default Meta Keywords" :value="$settings['meta_keywords'] ?? ''" placeholder="education, courses, school, online class..." hint="Enter key terms relevant to your institute separated by commas." />
                </div>

                {{-- Meta Description --}}
                <div class="w-full md:col-span-2">
                    <x-input.textarea name="meta_description" label="Default Meta Description" :value="$settings['meta_description'] ?? ''" rows="3" placeholder="Write a brief description of your educational institute and its offerings..." hint="A short summary of your page. Search engines display this text under the title. Recommended length: 150-160 characters." />
                </div>

            </div>

            {{-- Separator --}}
            <div class="border-bottom my-2"></div>

            {{-- Toggles grid --}}
            <div class="grid grid-cols-1">

                {{-- Allow Indexing --}}
                <div>
                    <x-input.toggle name="allow_indexing" label="Search Engine Visibility" :value="($settings['allow_indexing'] ?? '1') == '1'" hint="Turn ON to allow search engines like Google to find and list your site. Turn OFF to block search results (useful during setup)." />
                </div>

            </div>

        </div>
    </div>

    {{-- 2. INTERACTIVE ADVANCED SETTINGS TOGGLE --}}
    <div class="bg-primary border-rounded border-primary mb-4 p-4 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-primary text-sm flex items-center gap-2">
                <i class="fa-solid fa-gears text-primary text-base"></i>
                Advanced Settings & Integration
            </h3>
            <p class="text-[11px] text-secondary mt-0.5">Toggle this to show Google Analytics, tracking pixels, sitemap settings, and custom head code insertion.</p>
        </div>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" id="toggle-advanced-seo" class="sr-only peer">
            <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-black transition-colors duration-200"></div>
            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 peer-checked:translate-x-5"></div>
        </label>
    </div>

    {{-- 3. ADVANCED OPTIONS CONTAINER (HIDDEN BY DEFAULT) --}}
    <div id="advanced-seo-container" class="hidden space-y-4">

        {{-- Tracking Pixels Card --}}
        <div class="bg-primary border-rounded border-primary">
            <h2 class="text-md font-semibold p-4 border-bottom text-primary flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-primary text-sm"></i>
                Analytics & Tracking Pixels
            </h2>
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                
                {{-- Google Analytics GA4 ID --}}
                <div class="w-full">
                    <x-input.text name="google_analytics" label="Google Analytics GA4 ID" :value="$settings['google_analytics'] ?? ''" placeholder="G-XXXXXXXXXX" hint="Tracks site traffic and user metrics. Enter your Measurement ID (starts with G-)." />
                </div>

                {{-- Google Tag Manager ID --}}
                <div class="w-full">
                    <x-input.text name="google_tag_manager" label="Google Tag Manager ID" :value="$settings['google_tag_manager'] ?? ''" placeholder="GTM-XXXXXXX" hint="Manages third-party script triggers. Enter GTM Container ID." />
                </div>

                {{-- Facebook Pixel ID --}}
                <div class="w-full">
                    <x-input.text name="facebook_pixel_id" label="Facebook / Meta Pixel ID" :value="$settings['facebook_pixel_id'] ?? ''" placeholder="Enter pixel ID..." hint="Integrate Meta ads tracking to measure conversion and build remarketing lists." />
                </div>

                {{-- Microsoft Clarity ID --}}
                <div class="w-full">
                    <x-input.text name="microsoft_clarity_id" label="Microsoft Clarity Project ID" :value="$settings['microsoft_clarity_id'] ?? ''" placeholder="Enter project ID..." hint="Tracks visual heatmaps and user session replays." />
                </div>

            </div>
        </div>

        {{-- Verification & Sitemap --}}
        <div class="bg-primary border-rounded border-primary">
            <h2 class="text-md font-semibold p-4 border-bottom text-primary flex items-center gap-2">
                <i class="fa-solid fa-check-double text-primary text-sm"></i>
                Domain Verification & Sitemap
            </h2>
            <div class="p-4 flex flex-col gap-4">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Google Site Verification --}}
                    <div class="w-full">
                        <x-input.text name="google_site_verification" label="Google Site Verification Token" :value="$settings['google_site_verification'] ?? ''" placeholder="Verification token from Google Search Console..." hint="Verify ownership on Google Search Console to index your site quickly." />
                    </div>

                    {{-- Bing Site Verification --}}
                    <div class="w-full">
                        <x-input.text name="bing_site_verification" label="Bing Site Verification Token" :value="$settings['bing_site_verification'] ?? ''" placeholder="Verification token from Bing Webmaster..." hint="Verify ownership for Microsoft Bing Webmaster tools." />
                    </div>

                    {{-- Robots Meta Directives --}}
                    <div class="w-full">
                        <x-input.text name="robots_meta" label="Robots Meta Directives" :value="$settings['robots_meta'] ?? 'index, follow'" placeholder="index, follow" hint="Custom rules for search crawlers. Default: index, follow." />
                    </div>
                </div>

                {{-- Separator --}}
                <div class="border-bottom my-2"></div>

                {{-- Sitemap.xml Toggle --}}
                <div>
                    <x-input.toggle name="sitemap_enabled" label="Enable Sitemap.xml" :value="($settings['sitemap_enabled'] ?? '1') == '1'" hint="Dynamically generate a XML sitemap at /sitemap.xml listing all pages, books, and courses for search engines to crawl." />
                </div>

            </div>
        </div>

        {{-- Custom HTML Head & Schema --}}
        <div class="bg-primary border-rounded border-primary">
            <h2 class="text-md font-semibold p-4 border-bottom text-primary flex items-center gap-2">
                <i class="fa-solid fa-code text-primary text-sm"></i>
                Custom Integrations & Scripts
            </h2>
            <div class="p-4 flex flex-col gap-4">
                
                {{-- Custom JSON Schema --}}
                <div class="w-full">
                    <x-input.textarea name="schema_org_json" label="Structured Schema Markup (JSON-LD)" :value="$settings['schema_org_json'] ?? ''" rows="4" placeholder='{
  "@context": "https://schema.org",
  "@type": "EducationalOrganization",
  "name": "My Academy"
}' hint="Add raw JSON-LD code for structured listings (Local Business, Organization, etc.) on search results." class="font-mono text-xs!" />
                </div>

                {{-- Custom head HTML --}}
                <div class="w-full">
                    <x-input.textarea name="custom_head_tags" label="Custom Head HTML Insertion" :value="$settings['custom_head_tags'] ?? ''" rows="4" placeholder="<!-- Paste custom head scripts here -->" hint="Insert custom scripts, stylesheet links, or meta tags directly inside the HTML <head> tag of all user pages." class="font-mono text-xs!" />
                </div>

            </div>
        </div>

    </div>

    {{-- Save Button --}}
    <div class="flex justify-end mt-6 mb-12">
        <button class="primary-button font-bold flex items-center gap-2 text-sm">
            <i class="fa-solid fa-floppy-disk text-base"></i>
            Save General Settings
        </button>
    </div>

</form>

<script>
    document.addEventListener("turbo:load", () => {
        const advancedToggle = document.getElementById('toggle-advanced-seo');
        const advancedContainer = document.getElementById('advanced-seo-container');
        
        if (advancedToggle && advancedContainer) {
            // Determine if advanced settings should be open based on filled fields or saved state
            const hasAnalytics = @json(!empty($settings['google_analytics']));
            const hasTagManager = @json(!empty($settings['google_tag_manager']));
            const hasPixel = @json(!empty($settings['facebook_pixel_id']));
            const hasClarity = @json(!empty($settings['microsoft_clarity_id']));
            const hasGoogleVerification = @json(!empty($settings['google_site_verification']));
            const hasBingVerification = @json(!empty($settings['bing_site_verification']));
            const hasSchema = @json(!empty($settings['schema_org_json']));
            const hasCustomHead = @json(!empty($settings['custom_head_tags']));

            const shouldOpen = hasAnalytics || hasTagManager || hasPixel || hasClarity || 
                               hasGoogleVerification || hasBingVerification || hasSchema || 
                               hasCustomHead || localStorage.getItem('advanced_seo_open') === 'true';
            
            if (shouldOpen) {
                advancedToggle.checked = true;
                advancedContainer.classList.remove('hidden');
            } else {
                advancedToggle.checked = false;
                advancedContainer.classList.add('hidden');
            }
            
            advancedToggle.addEventListener('change', function() {
                if (this.checked) {
                    advancedContainer.classList.remove('hidden');
                    localStorage.setItem('advanced_seo_open', 'true');
                } else {
                    advancedContainer.classList.add('hidden');
                    localStorage.setItem('advanced_seo_open', 'false');
                }
            });
        }
    });
</script>
@endsection
