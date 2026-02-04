<template>
    <div class="rounded-xl bg-slate-950/40 p-6 ring-1 ring-white/5">
        <h3 class="text-lg font-semibold text-textcol mb-4">
            Worked Time – Past 7 Days
        </h3>

        <apexchart
            type="bar"
            height="320"
            :options="chartOptions"
            :series="chartSeries"
        />
    </div>
</template>

<script>
import ApexChart from "vue3-apexcharts";

export default {
    components: { apexchart: ApexChart },

    props: {
        weeklyWork: {
            type: Array,
            required: true,
        },
    },

    computed: {
        days() {
            const days = [];
            for (let i = 6; i >= 0; i--) {
                days.push(
                    new Date(Date.now() - i * 86400000)
                        .toISOString()
                        .slice(0, 10)
                );
            }
            return days;
        },

        chartSeries() {
            const projectMap = {};

            this.weeklyWork.forEach(row => {
                if (!projectMap[row.project_title]) {
                    projectMap[row.project_title] = {};
                }

                projectMap[row.project_title][row.work_date] =
                    (projectMap[row.project_title][row.work_date] || 0) +
                    row.total_minutes;
            });

            return Object.entries(projectMap).map(([project, days]) => ({
                name: project,
                data: this.days.map(day => days[day] || 0),
            }));
        },


        chartOptions() {
            return {
                chart: {
                    stacked: true,
                    toolbar: { show: false },
                    background: 'transparent',
                    foreColor: '#c5a8b7',
                    
                },
                theme: {
                    mode: 'dark',
                },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: '60%',
                    },
                    formatter: val => {
                            const h = Math.floor(val / 60);
                            const m = val % 60;
                            return h ? `${h}h ${m}m` : `${m}m`;
                        },
                },
                dataLabels: {
                    enabled: true,
                    formatter: val => {
                        const h = Math.floor(val / 60);
                        const m = val % 60;
                        return h ? `${h}h ${m}m` : `${m}m`;
                    },
                    style: {
                        colors: ['#150811'],
                    },
                },
                xaxis: {
                    categories: this.days,
                    labels: {
                        style: {
                            colors: '#F1E4E8',
                            fontSize: '12px',
                        },
                        formatter: val =>
                            new Date(val).toLocaleDateString(undefined, {
                                weekday: 'long',
                            }),
                    },
                },
                yaxis: {
                    labels: {
                        formatter: val => {
                            const h = Math.floor(val / 60);
                            const m = val % 60;
                            return h ? `${h}h ${m}` : `${m}m`;
                        },
                        style: {
                            colors: '#F1E4E8',
                        },
                    },
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: '#F1E4E8',
                    },
                    fontSize: '16px',
                    
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: val => {
                            const h = Math.floor(val / 60);
                            const m = val % 60;
                            return h ? `${h}h ${m}m` : `${m}m`;
                        },
                    },
                },
                grid: {
                    borderColor: 'rgba(241, 228, 232, 0.05)',
                    strokeDashArray: 4,
                    
                },
            };
        },
    },
};
</script>
