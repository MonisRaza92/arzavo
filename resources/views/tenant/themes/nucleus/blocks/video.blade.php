@php
$s = $block->settings ?? [];

$videoType = $s['video_type'] ?? 'youtube';
$youtubeUrl = $s['youtube_url'] ?? '';
$vimeoUrl = $s['vimeo_url'] ?? '';
$videoFile = $s['video_file'] ?? '';
$videoPoster = $s['video_poster'] ?? '';
$aspectRatio = $s['aspect_ratio'] ?? '16:9';
$videoWidth = $s['video_width'] ?? 'full';
$customWidth = $s['custom_width'] ?? 80;
$alignment = $s['alignment'] ?? 'center';
$mobileAlignment = $s['mobile_alignment'] ?? 'center';
$autoplay = $s['autoplay'] ?? 'no';
$muted = $s['muted'] ?? 'yes';
$controls = $s['controls'] ?? 'yes';
$borderRadius = $s['border_radius'] ?? 8;
$marginTop = $s['margin_top'] ?? 0;
$marginBottom = $s['margin_bottom'] ?? 0;

// Extract video ID for YouTube and Vimeo
$videoId = '';
if ($videoType === 'youtube' && $youtubeUrl) {
    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $youtubeUrl, $matches);
    $videoId = $matches[1] ?? '';
} elseif ($videoType === 'vimeo' && $vimeoUrl) {
    preg_match('/vimeo\.com\/(\d+)/', $vimeoUrl, $matches);
    $videoId = $matches[1] ?? '';
}

// Aspect ratio classes
$aspectClasses = [
    '16:9' => 'aspect-video',
    '4:3' => 'aspect-[4/3]',
    '1:1' => 'aspect-square',
    '21:9' => 'aspect-[21/9]'
];

// Width classes
$widthClass = match($videoWidth) {
    'full' => 'w-full',
    'container' => 'max-w-4xl mx-auto',
    'custom' => "w-full max-w-[{$customWidth}%] mx-auto",
    default => 'w-full'
};

// Alignment classes
$alignmentClass = match($alignment) {
    'left' => 'justify-start',
    'center' => 'justify-center',
    'right' => 'justify-end',
    default => 'justify-center'
};

$mobileAlignmentClass = match($mobileAlignment) {
    'left' => 'max-md:justify-start',
    'center' => 'max-md:justify-center',
    'right' => 'max-md:justify-end',
    default => 'max-md:justify-center'
};
@endphp

<div data-block-id="{{ $block->id }}" data-name="{{ $block->name }}" class="arzavo-video flex {{ $alignmentClass }} {{ $mobileAlignmentClass }}" 
     style="margin-top: {{ $marginTop }}px; margin-bottom: {{ $marginBottom }}px;">
    <div class="{{ $widthClass }}">
        @if($videoType === 'youtube' && $videoId)
            <div class="{{ $aspectClasses[$aspectRatio] ?? 'aspect-video' }} rounded-lg overflow-hidden" 
                 style="border-radius: {{ $borderRadius }}px;">
                <iframe 
                    src="https://www.youtube.com/embed/{{ $videoId }}?autoplay={{ $autoplay === 'yes' ? '1' : '0' }}&mute={{ $muted === 'yes' ? '1' : '0' }}&controls={{ $controls === 'yes' ? '1' : '0' }}"
                    class="w-full h-full"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        @elseif($videoType === 'vimeo' && $videoId)
            <div class="{{ $aspectClasses[$aspectRatio] ?? 'aspect-video' }} rounded-lg overflow-hidden" 
                 style="border-radius: {{ $borderRadius }}px;">
                <iframe 
                    src="https://player.vimeo.com/video/{{ $videoId }}?autoplay={{ $autoplay === 'yes' ? '1' : '0' }}&muted={{ $muted === 'yes' ? '1' : '0' }}&controls={{ $controls === 'yes' ? '1' : '0' }}"
                    class="w-full h-full"
                    frameborder="0"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        @elseif($videoType === 'upload' && $videoFile)
            <div class="{{ $aspectClasses[$aspectRatio] ?? 'aspect-video' }} rounded-lg overflow-hidden" 
                 style="border-radius: {{ $borderRadius }}px;">
                <video 
                    class="w-full h-full object-cover"
                    @if($videoPoster) poster="{{ $videoPoster }}" @endif
                    @if($autoplay === 'yes') autoplay @endif
                    @if($muted === 'yes') muted @endif
                    @if($controls === 'yes') controls @endif
                    playsinline>
                    <source src="{{ $videoFile }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        @else
            <div class="{{ $aspectClasses[$aspectRatio] ?? 'aspect-video' }} bg-gray-100 rounded-lg flex items-center justify-center" 
                 style="border-radius: {{ $borderRadius }}px;">
                <div class="text-center text-gray-500">
                    <i class="fa-solid fa-play-circle text-4xl mb-2"></i>
                    <p>Please configure video settings</p>
                </div>
            </div>
        @endif
    </div>
</div>
