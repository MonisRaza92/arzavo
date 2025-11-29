<label class="switch relative w-11 aspect-square cursor-pointer" title="Theme Toggle">
  <input type="checkbox" id="themeToggle">
  <span class="slider absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex items-center justify-center w-full h-full bg-invert border-rounded">
    <i class="fas text-3xl absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2 text-invert fa-cloud-sun sun-icon"></i>
    <i class="fas text-3xl absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2 text-invert fa-moon moon-icon"></i>
  </span>
</label>

<style>
  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider .moon-icon {
    opacity: 0;
  }

  input:checked + .slider .sun-icon {
    opacity: 0;
  }

  input:checked + .slider .moon-icon {
    opacity: 1;
  }
</style>

<script>
  // ✅ Theme toggle + logo sync
  window.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('themeToggle');
    const logo = document.getElementById('logo');

    function updateLogo() {
      if (document.documentElement.classList.contains('dark-mode')) {
        logo.src = "{{ isset($customizations->invert_logo) ? asset($customizations->invert_logo) : asset('images/ARZAQ-dark-logo.png') }}";
      } else {
        logo.src = "{{ isset($customizations) && $customizations->logo ? asset($customizations->logo) : asset('images/ARZAQ-dark-logo.png') }}";
      }
    }

    function setDarkMode(isDark) {
      document.documentElement.classList.toggle('dark-mode', isDark);
      localStorage.setItem('dark-mode', isDark);
      toggle.checked = isDark;
      updateLogo();
    }

    const isDark = localStorage.getItem('dark-mode') === 'true';
    setDarkMode(isDark);

    toggle.addEventListener('change', () => setDarkMode(toggle.checked));
  });
</script>

