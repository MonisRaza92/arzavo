{{-- Dynamic SVG Connecting Lines (Unified Brand Color) --}}
<svg class="absolute inset-0 w-full h-full pointer-events-none z-0 overflow-visible" id="curriculum-svg">
    {{-- Tier 1 -> Tier 2 (Category -> Classes) --}}
    <path :d="connectors.catToClass.path"
          fill="none"
          stroke="var(--color-brand)"
          stroke-width="2"
          stroke-dasharray="5 4"
          stroke-linecap="round"
          stroke-linejoin="round" />
    <template x-for="(c, i) in connectors.catToClass.circles" :key="'c1-' + i">
        <circle :cx="c.cx" :cy="c.cy" :r="c.r" fill="var(--color-brand)" />
    </template>

    {{-- Tier 2 -> Tier 3 (Class -> Subjects) --}}
    <path :d="connectors.classToSubject.path"
          fill="none"
          stroke="var(--color-brand)"
          stroke-width="2"
          stroke-dasharray="5 4"
          stroke-linecap="round"
          stroke-linejoin="round" />
    <template x-for="(c, i) in connectors.classToSubject.circles" :key="'c2-' + i">
        <circle :cx="c.cx" :cy="c.cy" :r="c.r" fill="var(--color-brand)" />
    </template>
</svg>
