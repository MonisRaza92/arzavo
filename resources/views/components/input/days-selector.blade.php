@props([
    'name' => 'days',
    'label' => 'Schedule Days',
    'value' => '',
    'hint' => null,
    'id' => null
])

@php
    $inputId = $id ?? ($attributes->get('id') ?? $name);
@endphp

<x-input.wrapper :label="$label" :hint="$hint">
    <div x-data="{
        daysList: [
            { key: 'Sun', letter: 'S', name: 'Sun' },
            { key: 'Mon', letter: 'M', name: 'Mon' },
            { key: 'Tue', letter: 'T', name: 'Tue' },
            { key: 'Wed', letter: 'W', name: 'Wed' },
            { key: 'Thu', letter: 'T', name: 'Thu' },
            { key: 'Fri', letter: 'F', name: 'Fri' },
            { key: 'Sat', letter: 'S', name: 'Sat' },
        ],
        selected: [],
        get valueString() {
            return this.selected.join(', ');
        },
        toggle(key) {
            if (this.selected.includes(key)) {
                this.selected = this.selected.filter(d => d !== key);
            } else {
                const order = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                const next = [...this.selected, key];
                next.sort((a, b) => order.indexOf(a) - order.indexOf(b));
                this.selected = next;
            }
            this.syncToInput();
        },
        setPreset(preset) {
            if (preset === 'weekdays') this.selected = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
            else if (preset === 'mon_sat') this.selected = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            else if (preset === 'daily') this.selected = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            else if (preset === 'mwf') this.selected = ['Mon', 'Wed', 'Fri'];
            else if (preset === 'tts') this.selected = ['Tue', 'Thu', 'Sat'];
            else if (preset === 'clear') this.selected = [];
            this.syncToInput();
        },
        syncToInput() {
            const el = this.$refs.hiddenDaysInput;
            if (el) {
                el.value = this.valueString;
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
            }
        },
        init() {
            this.syncFromInput();
            this.$watch('$refs.hiddenDaysInput.value', () => this.syncFromInput());
        },
        syncFromInput() {
            const el = this.$refs.hiddenDaysInput;
            const val = el ? el.value : '';
            if (!val) {
                this.selected = [];
                return;
            }
            const map = {
                'mon': 'Mon', 'monday': 'Mon',
                'tue': 'Tue', 'tuesday': 'Tue',
                'wed': 'Wed', 'wednesday': 'Wed',
                'thu': 'Thu', 'thursday': 'Thu',
                'fri': 'Fri', 'friday': 'Fri',
                'sat': 'Sat', 'saturday': 'Sat',
                'sun': 'Sun', 'sunday': 'Sun'
            };
            const parts = val.split(/[,\s]+/).map(p => p.trim().toLowerCase());
            const matched = [];
            parts.forEach(p => {
                if (map[p] && !matched.includes(map[p])) {
                    matched.push(map[p]);
                }
            });
            if (matched.length > 0) {
                const order = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                matched.sort((a, b) => order.indexOf(a) - order.indexOf(b));
                this.selected = matched;
            }
        }
    }" class="space-y-2.5">

        {{-- Hidden Input for Form Submission / x-model binding --}}
        <input type="hidden"
               name="{{ $name }}"
               id="{{ $inputId }}"
               value="{{ $value }}"
               x-ref="hiddenDaysInput"
               {{ $attributes->except(['id', 'name', 'value', 'label', 'hint']) }}>

        {{-- 7 Interactive Day Buttons (S M T W T F S) --}}
        <div class="flex items-center justify-between gap-1.5 p-1.5 rounded-lg bg-secondary">
            <template x-for="d in daysList" :key="d.key">
                <button type="button"
                        @click="toggle(d.key)"
                        class="flex-1 py-1.5 rounded-full flex flex-col items-center justify-center transition select-none cursor-pointer"
                        :class="selected.includes(d.key)
                            ? 'bg-brand text-primary font-semibold shadow-sm ring-2 ring-brand/30'
                            : 'bg-primary/60 text-theme-text-muted hover:bg-subtle hover:text-theme-text'">
                    <span class="text-xs font-semibold leading-none" x-text="d.letter"></span>
                    <span class="text-[9px] opacity-80 leading-none mt-0.5" x-text="d.name"></span>
                </button>
            </template>
        </div>

        {{-- Selected Summary & Quick Presets --}}
        <div class="flex items-center justify-between gap-2 flex-wrap text-[11px]">
            <div class="flex items-center gap-1.5 text-theme-text truncate min-w-0">
                <i class="fa-solid fa-calendar-check text-[10px] text-brand"></i>
                <span class="text-theme-text-muted text-[10px]">Active:</span>
                <span class="font-medium text-brand truncate" x-text="selected.length ? valueString : 'No days selected'"></span>
            </div>

            {{-- Presets Pills --}}
            <div class="flex items-center gap-1 flex-wrap">
                <button type="button" @click="setPreset('weekdays')"
                        class="px-2 py-0.5 rounded-full text-[10px] bg-secondary hover:bg-subtle text-theme-text-muted hover:text-brand transition font-normal">
                    Mon-Fri
                </button>
                <button type="button" @click="setPreset('mon_sat')"
                        class="px-2 py-0.5 rounded-full text-[10px] bg-secondary hover:bg-subtle text-theme-text-muted hover:text-brand transition font-normal">
                    Mon-Sat
                </button>
                <button type="button" @click="setPreset('mwf')"
                        class="px-2 py-0.5 rounded-full text-[10px] bg-secondary hover:bg-subtle text-theme-text-muted hover:text-brand transition font-normal">
                    MWF
                </button>
                <button type="button" @click="setPreset('daily')"
                        class="px-2 py-0.5 rounded-full text-[10px] bg-secondary hover:bg-subtle text-theme-text-muted hover:text-brand transition font-normal">
                    Daily
                </button>
                <button type="button" @click="setPreset('clear')"
                        x-show="selected.length > 0"
                        class="px-2 py-0.5 rounded-full text-[10px] bg-danger-bg text-danger hover:opacity-80 transition font-normal">
                    Clear
                </button>
            </div>
        </div>
    </div>
</x-input.wrapper>
