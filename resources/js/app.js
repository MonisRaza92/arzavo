import './bootstrap';
import Alpine from 'alpinejs';
import barba from '@barba/core';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
Alpine.start();


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

