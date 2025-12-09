import './bootstrap';
import Alpine from 'alpinejs';
import Pickr from '@simonwep/pickr';
import 'htmx.org';
import "@hotwired/turbo";
import '@simonwep/pickr/dist/themes/monolith.min.css';


window.Alpine = Alpine;
Alpine.start();

// Color Picker
window.Pickr = Pickr;


function openModal(id) {
  const modal = document.getElementById(id);
  if (modal) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Remove closing animation if present
    modal.classList.remove('modal-hide');
    // Add opening animation
    modal.classList.add('modal-show');
  }
}

function closeModal(id) {
  const modal = document.getElementById(id);
  if (modal) {
    // Remove opening animation
    modal.classList.remove('modal-show');
    // Add closing animation
    modal.classList.add('modal-hide');

    // Delay hiding until animation ends
    setTimeout(() => {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }, 150); // matches animation duration
  }
}

function toggleModal(id) {
  const modal = document.getElementById(id);
  if (!modal) return;

  const isHidden = modal.classList.contains('hidden');

  if (isHidden) {
    openModal(id);
  } else {
    closeModal(id);
  }
}

window.openModal = openModal;
window.closeModal = closeModal;
window.toggleModal = toggleModal;