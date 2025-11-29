import './bootstrap';
import Alpine from 'alpinejs';
import barba from '@barba/core';
import Pickr from '@simonwep/pickr';
import '@simonwep/pickr/dist/themes/monolith.min.css';


window.Alpine = Alpine;
Alpine.start();

// Color Picker
window.Pickr = Pickr;


barba.init({
  transitions: [
    {
      name: 'default',
      leave(data) {
        return new Promise(resolve => {
          // yaha aap animation daal sakte ho (GSAP ya CSS class)
          data.current.container.classList.add('fade-out');
          setTimeout(resolve, 500);
        });
      },
      enter(data) {
        data.next.container.classList.add('fade-in');
      }
    }
  ]
});



