import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import { createApp } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

// Components
import UserSearch from './components/UserSearch.vue';
import ProjectStatistics from './components/ProjectStatistics.vue';
import WeeklyWorkedTime from './components/WeeklyWorkedTime.vue';
import Notification from './components/Notification.vue';
import ConfirmationModal from './components/ConfirmationModal.vue';

const app = createApp({
    data() {
        return {
            tab: 'info',
            confirm: {
                visible: false,
                title: '',
                message: '',
                action: null,
            }
        };
    },
    methods: {
        showConfirm(title,message, action){
            this.confirm.title = title;
            this.confirm.message = message;
            this.confirm.action = action;
            this.confirm.visible = true;
        }
    }
});

// Plugins
app.use(VueApexCharts);

// Global components
app.component('project-statistics', ProjectStatistics);
app.component('weekly-worked-time', WeeklyWorkedTime);
app.component('notification', Notification);
app.component('user-search', UserSearch);
app.component('confirmation-modal', ConfirmationModal);

// Mount
app.mount('#app');

