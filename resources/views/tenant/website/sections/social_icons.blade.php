@php
$s = $section->settings ?? [];

$bgType = $s['background_type'] ?? 'none';
$bgImage = $s['background_image'] ?? '';
$alignment = $s['alignment'] ?? 'center';
$iconSize = $s['icon_size'] ?? 'medium';
$iconStyle = $s['icon_style'] ?? 'default';
$gap = $s['gap'] ?? '16';
$pt = $s['padding_top'] ?? '40';
$pb = $s['padding_bottom'] ?? '40';
$mt = $s['margin_top'] ?? '0';
$mb = $s['margin_bottom'] ?? '0';

// Social Links
$facebook = $s['facebook_url'] ?? '';
$twitter = $s['twitter_url'] ?? '';
$instagram = $s['instagram_url'] ?? '';
$linkedin = $s['linkedin_url'] ?? '';
$youtube = $s['youtube_url'] ?? '';
$pinterest = $s['pinterest_url'] ?? '';
$tiktok = $s['tiktok_url'] ?? '';
$whatsapp = $s['whatsapp_url'] ?? '';
$telegram = $s['telegram_url'] ?? '';
$snapchat = $s['snapchat_url'] ?? '';
$discord = $s['discord_url'] ?? '';
$github = $s['github_url'] ?? '';

// Icon Sizes
$sizeClasses = [
'small' => 'w-8 h-8 text-sm',
'medium' => 'w-10 h-10 text-base',
'large' => 'w-12 h-12 text-lg',
'extra-large' => 'w-16 h-16 text-2xl',
'huge' => 'w-20 h-20 text-3xl',
'jumbo' => 'w-24 h-24 text-4xl',
'gigantic' => 'w-28 h-28 text-5xl' 
];

// Icon Styles
$styleClasses = [
'default' => 'bg-transparent hover:scale-110',
'filled' => 'rounded-full hover:scale-110',
'outlined' => 'border-2 rounded-full hover:scale-110',
'square' => 'rounded-lg hover:scale-110',
'square-outlined' => 'border-2 rounded-lg hover:scale-110'
];

$colors = $section->colorScheme->scheme_colors;
@endphp

<div
    style="
    --arzavo-background: {{ $colors->background ?? '' }};
    --arzavo-link-color: {{ $colors->link ?? '' }};
    --arzavo-link-hover-color: {{ $colors->link_hover ?? '' }};
    @if ($bgType === 'image' && $bgImage)
    background-image: url('{{ asset($bgImage) }}');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    @elseif ($bgType === 'color')
    background: var(--arzavo-background);
    @else
    background: transparent;
    @endif
    padding-top: {{ $pt }}px;
    padding-bottom: {{ $pb }}px;
    margin-top: {{ $mt }}px;
    margin-bottom: {{ $mb }}px;
    "
    class="social-icons-section w-full relative overflow-hidden">
    <div class="container mx-auto">
        <div class="flex flex-wrap 
            {{ $alignment === 'start' ? 'justify-start' : '' }}
            {{ $alignment === 'center' ? 'justify-center' : '' }}
            {{ $alignment === 'end' ? 'justify-end' : '' }}"
            style="gap: {{ $gap }}px;">

            @if($facebook)
            <a href="{{ $facebook }}" target="_blank" rel="noopener noreferrer"
                class="social-icon {{ $sizeClasses[$iconSize] }} {{ $styleClasses[$iconStyle] }} flex items-center justify-center transition-all duration-300"
                style="color: var(--arzavo-link-color); 
                      @if($iconStyle === 'filled') background: var(--arzavo-link-color); color: var(--arzavo-background); @endif
                      @if(in_array($iconStyle, ['outlined', 'square-outlined'])) border-color: var(--arzavo-link-color); @endif"
                onmouseover="this.style.color='var(--arzavo-link-hover-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-hover-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-hover-color)'; @endif"
                onmouseout="this.style.color='var(--arzavo-link-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-color)'; @endif">
                <i class="fab fa-facebook-f"></i>
            </a>
            @endif

            @if($twitter)
            <a href="{{ $twitter }}" target="_blank" rel="noopener noreferrer"
                class="social-icon {{ $sizeClasses[$iconSize] }} {{ $styleClasses[$iconStyle] }} flex items-center justify-center transition-all duration-300"
                style="color: var(--arzavo-link-color); 
                      @if($iconStyle === 'filled') background: var(--arzavo-link-color); color: var(--arzavo-background); @endif
                      @if(in_array($iconStyle, ['outlined', 'square-outlined'])) border-color: var(--arzavo-link-color); @endif"
                onmouseover="this.style.color='var(--arzavo-link-hover-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-hover-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-hover-color)'; @endif"
                onmouseout="this.style.color='var(--arzavo-link-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-color)'; @endif">
                <i class="fab fa-twitter"></i>
            </a>
            @endif

            @if($instagram)
            <a href="{{ $instagram }}" target="_blank" rel="noopener noreferrer"
                class="social-icon {{ $sizeClasses[$iconSize] }} {{ $styleClasses[$iconStyle] }} flex items-center justify-center transition-all duration-300"
                style="color: var(--arzavo-link-color); 
                      @if($iconStyle === 'filled') background: var(--arzavo-link-color); color: var(--arzavo-background); @endif
                      @if(in_array($iconStyle, ['outlined', 'square-outlined'])) border-color: var(--arzavo-link-color); @endif"
                onmouseover="this.style.color='var(--arzavo-link-hover-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-hover-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-hover-color)'; @endif"
                onmouseout="this.style.color='var(--arzavo-link-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-color)'; @endif">
                <i class="fab fa-instagram"></i>
            </a>
            @endif

            @if($linkedin)
            <a href="{{ $linkedin }}" target="_blank" rel="noopener noreferrer"
                class="social-icon {{ $sizeClasses[$iconSize] }} {{ $styleClasses[$iconStyle] }} flex items-center justify-center transition-all duration-300"
                style="color: var(--arzavo-link-color); 
                      @if($iconStyle === 'filled') background: var(--arzavo-link-color); color: var(--arzavo-background); @endif
                      @if(in_array($iconStyle, ['outlined', 'square-outlined'])) border-color: var(--arzavo-link-color); @endif"
                onmouseover="this.style.color='var(--arzavo-link-hover-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-hover-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-hover-color)'; @endif"
                onmouseout="this.style.color='var(--arzavo-link-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-color)'; @endif">
                <i class="fab fa-linkedin-in"></i>
            </a>
            @endif

            @if($youtube)
            <a href="{{ $youtube }}" target="_blank" rel="noopener noreferrer"
                class="social-icon {{ $sizeClasses[$iconSize] }} {{ $styleClasses[$iconStyle] }} flex items-center justify-center transition-all duration-300"
                style="color: var(--arzavo-link-color); 
                      @if($iconStyle === 'filled') background: var(--arzavo-link-color); color: var(--arzavo-background); @endif
                      @if(in_array($iconStyle, ['outlined', 'square-outlined'])) border-color: var(--arzavo-link-color); @endif"
                onmouseover="this.style.color='var(--arzavo-link-hover-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-hover-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-hover-color)'; @endif"
                onmouseout="this.style.color='var(--arzavo-link-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-color)'; @endif">
                <i class="fab fa-youtube"></i>
            </a>
            @endif

            @if($pinterest)
            <a href="{{ $pinterest }}" target="_blank" rel="noopener noreferrer"
                class="social-icon {{ $sizeClasses[$iconSize] }} {{ $styleClasses[$iconStyle] }} flex items-center justify-center transition-all duration-300"
                style="color: var(--arzavo-link-color); 
                      @if($iconStyle === 'filled') background: var(--arzavo-link-color); color: var(--arzavo-background); @endif
                      @if(in_array($iconStyle, ['outlined', 'square-outlined'])) border-color: var(--arzavo-link-color); @endif"
                onmouseover="this.style.color='var(--arzavo-link-hover-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-hover-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-hover-color)'; @endif"
                onmouseout="this.style.color='var(--arzavo-link-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-color)'; @endif">
                <i class="fab fa-pinterest"></i>
            </a>
            @endif

            @if($tiktok)
            <a href="{{ $tiktok }}" target="_blank" rel="noopener noreferrer"
                class="social-icon {{ $sizeClasses[$iconSize] }} {{ $styleClasses[$iconStyle] }} flex items-center justify-center transition-all duration-300"
                style="color: var(--arzavo-link-color); 
                      @if($iconStyle === 'filled') background: var(--arzavo-link-color); color: var(--arzavo-background); @endif
                      @if(in_array($iconStyle, ['outlined', 'square-outlined'])) border-color: var(--arzavo-link-color); @endif"
                onmouseover="this.style.color='var(--arzavo-link-hover-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-hover-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-hover-color)'; @endif"
                onmouseout="this.style.color='var(--arzavo-link-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-color)'; @endif">
                <i class="fab fa-tiktok"></i>
            </a>
            @endif

            @if($whatsapp)
            <a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer"
                class="social-icon {{ $sizeClasses[$iconSize] }} {{ $styleClasses[$iconStyle] }} flex items-center justify-center transition-all duration-300"
                style="color: var(--arzavo-link-color); 
                      @if($iconStyle === 'filled') background: var(--arzavo-link-color); color: var(--arzavo-background); @endif
                      @if(in_array($iconStyle, ['outlined', 'square-outlined'])) border-color: var(--arzavo-link-color); @endif"
                onmouseover="this.style.color='var(--arzavo-link-hover-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-hover-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-hover-color)'; @endif"
                onmouseout="this.style.color='var(--arzavo-link-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-color)'; @endif">
                <i class="fab fa-whatsapp"></i>
            </a>
            @endif

            @if($telegram)
            <a href="{{ $telegram }}" target="_blank" rel="noopener noreferrer"
                class="social-icon {{ $sizeClasses[$iconSize] }} {{ $styleClasses[$iconStyle] }} flex items-center justify-center transition-all duration-300"
                style="color: var(--arzavo-link-color); 
                      @if($iconStyle === 'filled') background: var(--arzavo-link-color); color: var(--arzavo-background); @endif
                      @if(in_array($iconStyle, ['outlined', 'square-outlined'])) border-color: var(--arzavo-link-color); @endif"
                onmouseover="this.style.color='var(--arzavo-link-hover-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-hover-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-hover-color)'; @endif"
                onmouseout="this.style.color='var(--arzavo-link-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-color)'; @endif">
                <i class="fab fa-telegram"></i>
            </a>
            @endif

            @if($snapchat)
            <a href="{{ $snapchat }}" target="_blank" rel="noopener noreferrer"
                class="social-icon {{ $sizeClasses[$iconSize] }} {{ $styleClasses[$iconStyle] }} flex items-center justify-center transition-all duration-300"
                style="color: var(--arzavo-link-color); 
                      @if($iconStyle === 'filled') background: var(--arzavo-link-color); color: var(--arzavo-background); @endif
                      @if(in_array($iconStyle, ['outlined', 'square-outlined'])) border-color: var(--arzavo-link-color); @endif"
                onmouseover="this.style.color='var(--arzavo-link-hover-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-hover-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-hover-color)'; @endif"
                onmouseout="this.style.color='var(--arzavo-link-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-color)'; @endif">
                <i class="fab fa-snapchat"></i>
            </a>
            @endif

            @if($discord)
            <a href="{{ $discord }}" target="_blank" rel="noopener noreferrer"
                class="social-icon {{ $sizeClasses[$iconSize] }} {{ $styleClasses[$iconStyle] }} flex items-center justify-center transition-all duration-300"
                style="color: var(--arzavo-link-color); 
                      @if($iconStyle === 'filled') background: var(--arzavo-link-color); color: var(--arzavo-background); @endif
                      @if(in_array($iconStyle, ['outlined', 'square-outlined'])) border-color: var(--arzavo-link-color); @endif"
                onmouseover="this.style.color='var(--arzavo-link-hover-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-hover-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-hover-color)'; @endif"
                onmouseout="this.style.color='var(--arzavo-link-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-color)'; @endif">
                <i class="fab fa-discord"></i>
            </a>
            @endif

            @if($github)
            <a href="{{ $github }}" target="_blank" rel="noopener noreferrer"
                class="social-icon {{ $sizeClasses[$iconSize] }} {{ $styleClasses[$iconStyle] }} flex items-center justify-center transition-all duration-300"
                style="color: var(--arzavo-link-color); 
                      @if($iconStyle === 'filled') background: var(--arzavo-link-color); color: var(--arzavo-background); @endif
                      @if(in_array($iconStyle, ['outlined', 'square-outlined'])) border-color: var(--arzavo-link-color); @endif"
                onmouseover="this.style.color='var(--arzavo-link-hover-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-hover-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-hover-color)'; @endif"
                onmouseout="this.style.color='var(--arzavo-link-color)'; @if($iconStyle === 'filled') this.style.background='var(--arzavo-link-color)'; @endif @if(in_array($iconStyle, ['outlined', 'square-outlined'])) this.style.borderColor='var(--arzavo-link-color)'; @endif">
                <i class="fab fa-github"></i>
            </a>
            @endif

        </div>
    </div>
</div>