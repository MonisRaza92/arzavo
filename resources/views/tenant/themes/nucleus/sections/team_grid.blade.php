@php
$s = $section->settings ?? [];
$colors = $section->colorScheme->scheme_colors;

$sectionTitle = $s['section_title'] ?? 'Meet Our Team';
$sectionSubtitle = $s['section_subtitle'] ?? 'The passionate people behind our success';
$gridColumns = (int)($s['grid_columns'] ?? 3);
$teamMembersJson = $s['team_members'] ?? '[]';
$showBio = ($s['show_bio'] ?? 'yes') === 'yes';
$cardStyle = $s['card_style'] ?? 'shadow';
$pt = $s['padding_top'] ?? 60;
$pb = $s['padding_bottom'] ?? 60;

try {
    $teamMembers = json_decode($teamMembersJson, true) ?? [];
} catch (Exception $e) {
    $teamMembers = [];
}

$cardClasses = match($cardStyle) {
    'simple' => '',
    'bordered' => 'border',
    'shadow' => 'shadow-md',
    'elevated' => 'shadow-lg border',
    default => 'shadow-md'
};
@endphp

<section  data-section-id="{{ $section->id }}" data-name="{{ $section->name }}" 
    style="
        --arzavo-background: {{ $colors->background ?? '' }};
        --arzavo-border-color: {{ $colors->border ?? '' }};
        --arzavo-heading-color: {{ $colors->heading ?? '' }};
        --arzavo-paragraph-color: {{ $colors->paragraph ?? '' }};
        background: var(--arzavo-background);
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
    "
    class="team-grid-section"
>
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="arzavo-heading-2 mb-4" style="color: var(--arzavo-heading-color);">{{ $sectionTitle }}</h2>
            <p class="arzavo-paragraph text-lg max-w-2xl mx-auto" style="color: var(--arzavo-paragraph-color);">{{ $sectionSubtitle }}</p>
        </div>
        
        <div class="grid md:grid-cols-{{ $gridColumns }} gap-8 max-w-6xl mx-auto">
            @foreach($teamMembers as $member)
            <div class="team-member-card {{ $cardClasses }} rounded-lg p-6 text-center arzavo-border" 
                 style="background: var(--arzavo-background);">
                
                @if(!empty($member['image']))
                <div class="mb-4">
                    <img src="{{ asset($member['image']) }}" 
                         alt="{{ $member['name'] ?? 'Team Member' }}" 
                         class="w-24 h-24 rounded-full mx-auto object-cover">
                </div>
                @else
                <div class="w-24 h-24 rounded-full mx-auto mb-4 flex items-center justify-center bg-gray-200">
                    <i class="fa-solid fa-user text-2xl text-gray-500"></i>
                </div>
                @endif
                
                <h3 class="arzavo-heading-4 mb-2">
                    {{ $member['name'] ?? 'Team Member' }}
                </h3>
                
                <p class="arzavo-paragraph mb-3">
                    {{ $member['position'] ?? 'Position' }}
                </p>
                
                @if($showBio && !empty($member['bio']))
                <p class="arzavo-paragraph text-sm">
                    {{ $member['bio'] }}
                </p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>