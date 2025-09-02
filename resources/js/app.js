import './bootstrap';
import './swal';

Livewire.on('livewire:initialized', () => {
  initFlowbite();
});

window.addEventListener('livewire:navigated', () => {
  initFlowbite();
});

import { Datepicker } from 'flowbite-datepicker';
window.Datepicker = Datepicker;