import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';

// import Alpine from 'alpinejs';
// window.Alpine = Alpine;
// // Alpine.start();
const Alpine = window.Alpine;

// alpine import
Alpine.data('myComponent', () => ({
    count: 0,
    increment() {
        this.count++
    }
}));

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
            confirmModalVisible: false,
            confirmModalTitle: '',
            confirmModalMessage: '',
            confirmModalAction: null,
        };
    },
    methods: {
        showConfirm(title, message, action){
            this.confirmModalTitle = title;
            this.confirmModalMessage = message;
            this.confirmModalAction = action;
            this.confirmModalVisible = true;
        },
        handleConfirm() {
            if (this.confirmModalAction && typeof this.confirmModalAction === 'function') {
                this.confirmModalAction();
            }
            this.confirmModalVisible = false;
        }
    }
});
const statisticsApp = createApp({});
// Plugins
app.use(VueApexCharts);

// Global components
statisticsApp.component('project-statistics', ProjectStatistics);
statisticsApp.component('weekly-worked-time', WeeklyWorkedTime);
app.component('notification', Notification);
app.component('user-search', UserSearch);
app.component('confirmation-modal', ConfirmationModal);

// mounts and stores the root component instance
const vueAppInstance = app.mount('#app');
const vueAppInstance2 = statisticsApp.mount('#statistics-app');
// stores both the app and the instance globally
window.vueApp = app;
window.vueAppInstance = vueAppInstance;
window.vueAppInstance2 = vueAppInstance2;

// listens for custom events to show the confirmation modal
window.addEventListener('show-vue-confirm', e => {
    const { title, message, action } = e.detail;
    window.vueAppInstance.showConfirm(title, message, action);
});