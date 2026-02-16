<template>
    <div>
        <!-- Loading -->
        <div
            v-if="loading"
            class="h-[387px] flex items-center justify-center text-textcol2 italic"
        >
            Loading statistics...
        </div>

        <!-- Empty state -->
        <div
            v-else-if="!taskStatusStats.length && !taskTimeStats.length"
            class="h-[387px] flex items-center justify-center text-textcol2 italic"
        >
            No statistics available.
        </div>

        <!-- Content -->
        <div v-else>
            <!-- Tabs -->
            <div class="flex gap-2 border-b border-white/5 mb-6">
                <button
                    v-for="tab in tabs"
                    :key="tab"
                    @click="activeTab = tab"
                    class="px-4 py-2 text-sm font-semibold rounded-t-md transition-colors"
                    :class="activeTab === tab
                        ? 'bg-primary/10 text-primary border-b-2 border-primary'
                        : 'text-textcol2 hover:text-textcol hover:bg-white/5'"
                >
                    {{ tab }}
                </button>
            </div>

            <!-- Tasks tab -->
            <div
                v-if="activeTab === 'Tasks'"
                class="grid grid-cols-1 md:grid-cols-2 gap-6"
            >
                <!-- Task status donut -->
                <div class="rounded-xl bg-slate-950/40 p-6 ring-1 ring-white/5">
                    <h3 class="text-lg font-semibold text-textcol mb-4">
                        Task Status Distribution
                    </h3>
                    <apexchart
                        type="donut"
                        height="280"
                        :options="taskDonutOptions"
                        :series="taskDonutSeries"
                    />
                </div>

                <!-- Time spent per task -->
                <div class="rounded-xl bg-slate-950/40 p-6 ring-1 ring-white/5">
                    <h3 class="text-lg font-semibold text-textcol mb-4">
                        Time Spent per Task
                    </h3>
                    <apexchart
                        type="bar"
                        height="280"
                        :options="taskTimeOptions"
                        :series="taskTimeSeries"
                    />
                </div>
            </div>

            <!-- Weekly summary tab -->
            <div
                v-if="activeTab === 'Weekly Summary'"
                class="h-[387px] flex items-center justify-center text-textcol2 italic"
            >
                Weekly summary data coming soon.
            </div>
        </div>
    </div>
</template>

<script>
import ApexChart from "vue3-apexcharts";
import axios from "axios";

export default {
    components: { apexchart: ApexChart },

    props: {
        projectId: {
            type: Number,
            required: true,
        },
    },

    data() {
        return {
            tabs: ["Tasks", "Weekly Summary"],
            activeTab: "Tasks",
            taskStatusStats: [],
            taskTimeStats: [],
            loading: true,
        };
    },

    mounted() {
        this.fetchStatistics();
    },

    methods: {
        async fetchStatistics() {
            try {
                const response = await axios.get(
                    `/projects/${this.projectId}/statistics`
                );

                this.taskStatusStats = response.data.taskStatusStats || [];
                this.taskTimeStats = response.data.taskTimeStats || [];
            } catch (error) {
                console.error("Failed to load statistics:", error);
            } finally {
                this.loading = false;
            }
        },
    },

    computed: {
        humanizedTaskStatusStats() {
            const statusMap = {
                in_progress: "In Progress",
                completed: "Completed",
            };

            return this.taskStatusStats.map((item) => ({
                ...item,
                status: statusMap[item.status] || item.status,
            }));
        },

        /* Donut */
        taskDonutSeries() {
            return this.humanizedTaskStatusStats.map((s) => s.count);
        },

        taskDonutOptions() {
            return {
                labels: this.humanizedTaskStatusStats.map((s) => s.status),
                colors: ["#01BAEF", "#0CBABA", "#380036", "#F1E4E8", "#8B5CF6"],
                chart: {
                    background: "transparent",
                    foreColor: "#c5a8b7",
                },
                theme: { mode: "dark" },
                legend: {
                    position: "bottom",
                    labels: { colors: "#c5a8b7" },
                    fontSize: "13px",
                    fontFamily: "inherit",
                },
                dataLabels: {
                    style: {
                        fontSize: "14px",
                        fontFamily: "inherit",
                        fontWeight: 600,
                    },
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: "65%",
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: "Total Tasks",
                                    formatter: (w) =>
                                        w.globals.seriesTotals.reduce(
                                            (a, b) => a + b,
                                            0
                                        ),
                                },
                            },
                        },
                    },
                },
                stroke: {
                    colors: ["#150811"],
                    width: 2,
                },
                tooltip: {
                    theme: "dark",
                },
            };
        },

        /* Bar */
        taskTimeSeries() {
            return [
                {
                    name: "Time",
                    data: this.taskTimeStats.map((t) => t.minutes),
                },
            ];
        },

        taskTimeOptions() {
            return {
                chart: {
                    toolbar: { show: false },
                    background: "transparent",
                    foreColor: "#c5a8b7",
                },
                theme: { mode: "dark" },
                colors: ["#01BAEF"],
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        columnWidth: "60%",
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: (val) => (val / 60).toFixed(1) + "h",
                },
                xaxis: {
                    categories: this.taskTimeStats.map((t) => t.title),
                },
                tooltip: {
                    theme: "dark",
                    y: {
                        formatter: (val) => {
                            const hours = Math.floor(val / 60);
                            const mins = val % 60;
                            return hours > 0
                                ? `${hours}h ${mins}m`
                                : `${mins}m`;
                        },
                    },
                },
            };
        },
    },
};
</script>
