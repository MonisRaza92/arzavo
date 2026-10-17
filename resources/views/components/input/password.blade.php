@props([
    'name',
    'label' => '',
    'value' => '',
    'placeholder' => '',
    'class' => '',
    'disabled' => false,
    'required' => false,
    'hint' => null
])

<x-input.wrapper :label="$label" :hint="$hint" :required="$required">

    <div class="relative">
        <input
            type="password"
            name="{{ $name }}"
            value="{{ $value }}"
            id="{{ $name }}"
            placeholder="{{ $placeholder }}"
            @disabled($disabled)
            @required($required)
            class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 pr-10 rounded-full focus:outline-none focus:ring-1 focus:ring-brand font-normal transition {{ $class }}">

        <button type="button"
            class="w-7 h-7 rounded-full hover:bg-subtle text-theme-text-muted hover:text-theme-text flex items-center justify-center absolute right-2 top-1/2 -translate-y-1/2 text-xs transition"
            onclick="togglePassword(this)">
            <i class="fa-solid fa-eye"></i>
        </button>
    </div>

</x-input.wrapper>

<script>
function togglePassword(btn) {
    const input = btn.parentElement.querySelector('input');
    const icon = btn.querySelector('i');

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>
