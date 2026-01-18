@props([
    'name',
    'label' => '',
    'value' => '',
    'class' => ''
])

<x-input.wrapper :label="$label">

    <div class="relative">
        <input
            type="password"
            name="{{ $name }}"
            value="{{ $value }}"
            class="w-full p-2 pr-10 rounded-md border border-primary bg-transparent {{ $class }}">

        <button type="button"
            class="absolute right-2 top-1/2 -translate-y-1/2 text-tertiary hover:text-primary"
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
