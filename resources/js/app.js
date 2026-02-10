import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';
import Notification from './components/Notification.vue';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import { createApp } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

// Components
import UserSearch from './components/UserSearch.vue';
import ProjectStatistics from './components/ProjectStatistics.vue';
import WeeklyWorkedTime from './components/WeeklyWorkedTime.vue';

const app = createApp({});

// Plugins
app.use(VueApexCharts);

// Global components
app.component('project-statistics', ProjectStatistics);
app.component('weekly-worked-time', WeeklyWorkedTime);
app.component('notification', Notification);
// Mount
app.mount('#app');

const userSearchEl = document.getElementById('user-search');

if (userSearchEl) {
    const userSearchApp = createApp(UserSearch);
    userSearchApp.mount(userSearchEl);
}
