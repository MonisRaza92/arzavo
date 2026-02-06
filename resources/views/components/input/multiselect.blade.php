@props([
    'name',
    'label' => '',
    'options' => [],
    'optionLabel' => null,
    'optionValue' => null,
    'value' => [],
])

<x-input.wrapper :label="$label">

    @php
        $uid = 'ms_' . uniqid();
    @endphp

    <div class="relative" id="{{ $uid }}">
        <!-- Pills container -->
        <div class="ms-selected flex flex-wrap gap-2 p-1.5 border-rounded border-primary min-h-9.75 cursor-pointer">
            <span class="text-base text-gray-400">Select options…</span>
        </div>

        <!-- Dropdown -->
        <div class="ms-dropdown hidden absolute z-20 mt-1 w-full border-rounded border-primary max-h-56 overflow-auto">
            @foreach($options as $opt)
                @php
                    $val = $optionValue ? data_get($opt, $optionValue) : $opt->id;
                    $lbl = $optionLabel ? data_get($opt, $optionLabel) : ($opt->name ?? $val);
                @endphp

                <div
                    class="ms-option px-3 py-1 cursor-pointer bg-primary flex justify-between"
                    data-value="{{ $val }}"
                    data-label="{{ $lbl }}"
                >
                    <span>{{ $lbl }}</span>
                    <span class="check hidden">✔</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Hidden inputs -->
    <div class="ms-inputs"></div>

    <script>
    (function () {
        const root = document.getElementById('{{ $uid }}');
        if (!root) return;

        const selectedBox = root.querySelector('.ms-selected');
        const dropdown = root.querySelector('.ms-dropdown');
        const options = root.querySelectorAll('.ms-option');
        const inputsBox = root.nextElementSibling;

        let selected = @json(array_map('strval', (array)$value));

        function render() {
            selectedBox.innerHTML = '';
            inputsBox.innerHTML = '';

            if (selected.length === 0) {
                selectedBox.innerHTML = '<span class="text-base text-gray-400">Select options…</span>';
            }

            options.forEach(opt => {
                const value = opt.dataset.value;
                const label = opt.dataset.label;
                const check = opt.querySelector('.check');

                if (selected.includes(value)) {
                    check.classList.remove('hidden');

                    // pill
                    const pill = document.createElement('span');
                    pill.className = 'flex items-center gap-1 bg-blue-100 text-blue-800 px-2 py-1 border-rounded text-xs';
                    pill.innerHTML = `
                        <span>${label}</span>
                        <button type="button" class="remove text-xs">✕</button>
                    `;

                    pill.querySelector('.remove').addEventListener('click', (e) => {
                        e.stopPropagation();
                        selected = selected.filter(v => v !== value);
                        render();
                    });

                    selectedBox.appendChild(pill);

                    // hidden input
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = '{{ $name }}[]';
                    input.value = value;
                    inputsBox.appendChild(input);

                } else {
                    check.classList.add('hidden');
                }
            });
        }

        selectedBox.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        options.forEach(opt => {
            opt.addEventListener('click', () => {
                const value = opt.dataset.value;

                if (selected.includes(value)) {
                    selected = selected.filter(v => v !== value);
                } else {
                    selected.push(value);
                }
                render();
            });
        });

        document.addEventListener('click', (e) => {
            if (!root.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        render();
    })();
    </script>

</x-input.wrapper>
