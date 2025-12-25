@php
$s = $section->settings ?? [];

$bgType = $s['background_type'] ?? 'none';
$bgImage = $s['background_image'] ?? '';
$youtubeUrl = $s['youtube_url'] ?? '';
$videoTitle = $s['video_title'] ?? '';
$videoDescription = $s['video_description'] ?? '';
$aspectRatio = $s['aspect_ratio'] ?? '16:9';
$alignment = $s['alignment'] ?? 'center';
$showTitle = $s['show_title'] ?? 'enable';
$showDescription = $s['show_description'] ?? 'enable';
$autoplay = $s['autoplay'] ?? 'disable';
$muted = $s['muted'] ?? 'disable';
$controls = $s['controls'] ?? 'enable';
$loop = $s['loop'] ?? 'disable';
$maxWidth = $s['max_width'] ?? '1200';
$pt = $s['padding_top'] ?? '40';
$pb = $s['padding_bottom'] ?? '40';
$mt = $s['margin_top'] ?? '0';
$mb = $s['margin_bottom'] ?? '0';

// Extract YouTube video ID from URL
function getYouTubeId($url) {
preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches);
return $matches[1] ?? '';
}

$videoId = getYouTubeId($youtubeUrl);

// Aspect ratio classes
$aspectRatioClasses = [
'16:9' => 'aspect-video',
'4:3' => 'aspect-4/3',
'21:9' => 'aspect-21/9',
'1:1' => 'aspect-square'
];

$aspectClass = $aspectRatioClasses[$aspectRatio] ?? 'aspect-video';

// Build embed parameters
$embedParams = [];
if ($autoplay === 'enable') $embedParams[] = 'autoplay=1';
if ($muted === 'enable') $embedParams[] = 'mute=1';
if ($controls === 'disable') $embedParams[] = 'controls=0';
if ($loop === 'enable') $embedParams[] = 'loop=1&playlist=' . $videoId;
$embedParams[] = 'rel=0'; // Don't show related videos from other channels

$embedUrl = "https://www.youtube.com/embed/{$videoId}?" . implode('&', $embedParams);

$colors = $section->colorScheme->scheme_colors;
@endphp

<div
    style="
    --arzavo-background: {{ $colors->background ?? '' }};
    --arzavo-heading-color: {{ $colors->heading ?? '' }};
    --arzavo-paragraph-color: {{ $colors->paragraph ?? '' }};
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
    class="video-section w-full relative overflow-hidden">
    <div class="container mx-auto">
        <div class="video-wrapper mx-auto
            {{ $alignment === 'start' ? 'mr-auto ml-0' : '' }}
            {{ $alignment === 'center' ? 'mx-auto' : '' }}
            {{ $alignment === 'end' ? 'ml-auto mr-0' : '' }}"
            style="max-width: {{ $maxWidth }}px;">

            @if($showTitle === 'enable' && $videoTitle)
            <h2 class="video-title text-2xl md:text-3xl font-bold mb-3 text-center"
                style="color: var(--arzavo-heading-color);">
                {{ $videoTitle }}
            </h2>
            @endif

            @if($showDescription === 'enable' && $videoDescription)
            <p class="video-description text-base md:text-lg mb-6 text-center"
                style="color: var(--arzavo-paragraph-color); line-height: 1.6;">
                {{ $videoDescription }}
            </p>
            @endif

            @if($videoId)
            <div class="video-container {{ $aspectClass }} w-full relative rounded-lg overflow-hidden shadow-lg">
                <iframe
                    class="absolute top-0 left-0 w-full h-full"
                    src="{{ $embedUrl }}"
                    title="{{ $videoTitle ?: 'YouTube video player' }}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen>
                </iframe>
            </div>
            @else
            <div class="video-placeholder {{ $aspectClass }} w-full relative rounded-lg overflow-hidden bg-gray-200 flex items-center justify-center"
                style="background: rgba(0,0,0,0.05);">
                <div class="text-center p-8">
                    <svg class="w-16 h-16 mx-auto mb-4" style="color: var(--arzavo-paragraph-color); opacity: 0.4;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p style="color: var(--arzavo-paragraph-color); opacity: 0.6;">
                        Enter a valid YouTube URL to display video
                    </p>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<style>
    .video-container iframe {
        border: none;
    }

    .aspect-4\/3 {
        aspect-ratio: 4 / 3;
    }

    .aspect-21\/9 {
        aspect-ratio: 21 / 9;
    }

    .video-section .video-container {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .video-section .video-container:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
</style>