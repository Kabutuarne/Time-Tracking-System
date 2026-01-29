import './bootstrap';

import '@fortawesome/fontawesome-free/css/all.css';

// import Alpine from 'alpinejs';

// window.Alpine = Alpine;

// Alpine.start();
import { createApp } from 'vue'
import UserSearch from './components/UserSearch.vue'

const el = document.getElementById('user-search')
if (el) {
  createApp(UserSearch).mount(el)
}
