@php
    $s = $block['settings'] ?? [];
    $tabsItems = $s['tabs_items'] ?? [];
    $tabsStyle = $s['tabs_style'] ?? 'horizontal';
    $tabsAlignment = $s['tabs_alignment'] ?? 'start';
    $activeTab = $s['active_tab'] ?? 0;
    $pt = $s['padding_top'] ?? '16';
    $pb = $s['padding_bottom'] ?? '16';
    $hideMobile = $s['hide_mobile'] ?? '0';
    $hideDesktop = $s['hide_desktop'] ?? '0';
@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" x-data="{ activeTab: {{ $activeTab }} }"
    class="tabs-block {{ $hideMobile === '1' ? 'hidden md:block' : '' }} {{ $hideDesktop === '1' ? 'md:hidden' : '' }}"
    style="padding-top: {{ $pt }}px; padding-bottom: {{ $pb }}px;">

    <div class="tabs-wrapper {{ $tabsStyle === 'vertical' ? 'flex gap-6' : '' }}">
        <!-- Tabs Header -->
        <div
            class="tabs-header flex {{ $tabsStyle === 'horizontal' ? 'border-b arzavo-border' : 'flex-col border-r arzavo-border' }} justify-{{ $tabsAlignment }} gap-4 mb-6">
            @foreach($tabsItems as $index => $tab)
                <button @click="activeTab = {{ $index }}"
                    :class="{ 'arzavo-primary arzavo-border-b-2': activeTab === {{ $index }}, 'arzavo-text-muted': activeTab !== {{ $index }} }"
                    class="tab-button px-4 py-2 font-medium transition-all hover:arzavo-primary">
                    @if(!empty($tab['icon']))
                        <i class="{{ $tab['icon'] }} mr-2"></i>
                    @endif
                    {{ $tab['title'] }}
                </button>
            @endforeach
        </div>

        <!-- Tabs Content -->
        <div class="tabs-content flex-1">
            @foreach($tabsItems as $index => $tab)
                <div x-show="activeTab === {{ $index }}" x-transition class="tab-pane">
                    {!! $tab['content'] !!}
                </div>
            @endforeach
        </div>
    </div>
</div>