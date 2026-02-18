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
            <div v-if="activeTab === 'Weekly Summary'" class="space-y-6">
                <!-- Week navigation -->
                <div class="flex items-center justify-between rounded-xl bg-slate-950/40 p-6 ring-1 ring-white/5">
                    <button
                        @click="previousWeek"
                        class="p-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-textcol transition-colors"
                    >
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <div class="text-center">
                        <p class="text-sm font-semibold text-textcol2">Week of</p>
                        <p class="text-lg font-bold text-textcol">
                            {{ formatDate(weekStart) }} to {{ formatDate(weekEnd) }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button
                            @click="exportWeek"
                            class="px-4 py-2 rounded-lg bg-primary hover:bg-primary/80 text-white text-sm font-semibold transition-colors"
                        >
                            <i class="fas fa-file-csv mr-2"></i>
                            Export CSV
                        </button>

                        <button
                            @click="nextWeek"
                            class="p-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-textcol transition-colors"
                        >
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>


                <!-- Key metrics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="rounded-xl bg-slate-950/40 p-6 ring-1 ring-white/5">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-primary/20 rounded-lg">
                                <i class="fas fa-pencil-alt text-primary text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-textcol2">Total Entries</p>
                                <p class="text-2xl font-bold text-textcol">{{ weeklyStats.total_entries ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl bg-slate-950/40 p-6 ring-1 ring-white/5">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-secondary/20 rounded-lg">
                                <i class="fas fa-clock text-secondary text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-textcol2">Total Hours</p>
                                <p class="text-2xl font-bold text-textcol">{{ formatHours(weeklyStats.total_minutes ?? 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl bg-slate-950/40 p-6 ring-1 ring-white/5">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-purple-500/20 rounded-lg">
                                <i class="fas fa-users text-purple-400 text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-textcol2">Active Users</p>
                                <p class="text-2xl font-bold text-textcol">{{ weeklyStats.total_users ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl bg-slate-950/40 p-6 ring-1 ring-white/5">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-green-500/20 rounded-lg">
                                <i class="fas fa-check-circle text-green-400 text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-textcol2">Completed Tasks</p>
                                <p class="text-2xl font-bold text-textcol">{{ weeklyStats.tasks_completed ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User activity table -->
                <div class="rounded-xl bg-slate-950/40 p-6 ring-1 ring-white/5">
                    <h3 class="text-lg font-semibold text-textcol mb-4">
                        User Activity
                    </h3>
                    <div v-if="weeklyUserActivity.length" class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-white/5">
                                    <th class="px-4 py-3 text-left font-semibold text-textcol2">User</th>
                                    <th class="px-4 py-3 text-center font-semibold text-textcol2">Entries</th>
                                    <th class="px-4 py-3 text-center font-semibold text-textcol2">Hours</th>
                                    <th class="px-4 py-3 text-center font-semibold text-textcol2">Avg per Entry</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="user in weeklyUserActivity"
                                    :key="user.id"
                                    class="border-b border-white/5 hover:bg-slate-900/50 transition-colors"
                                >
                                    <td class="px-4 py-3 text-textcol">{{ user.name }}</td>
                                    <td class="px-4 py-3 text-center text-textcol">{{ user.entry_count }}</td>
                                    <td class="px-4 py-3 text-center text-textcol">{{ formatHours(user.total_minutes) }}</td>
                                    <td class="px-4 py-3 text-center text-textcol2">
                                        {{ formatMinutes(Math.round(user.total_minutes / user.entry_count)) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="text-center py-8 text-textcol2 italic">
                        No user activity this week.
                    </div>
                </div>

                <!-- Daily breakdown -->
                <div class="rounded-xl bg-slate-950/40 p-6 ring-1 ring-white/5">
                    <h3 class="text-lg font-semibold text-textcol mb-4">
                        Daily Breakdown
                    </h3>
                    <div v-if="dailyActivityBreakdown.length" class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-white/5">
                                    <th class="px-4 py-3 text-left font-semibold text-textcol2">Date</th>
                                    <th class="px-4 py-3 text-center font-semibold text-textcol2">Day</th>
                                    <th class="px-4 py-3 text-center font-semibold text-textcol2">Entries</th>
                                    <th class="px-4 py-3 text-center font-semibold text-textcol2">Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="day in dailyActivityBreakdown"
                                    :key="day.date"
                                    class="border-b border-white/5 hover:bg-slate-900/50 transition-colors"
                                >
                                    <td class="px-4 py-3 text-textcol">{{ formatDate(day.date) }}</td>
                                    <td class="px-4 py-3 text-center text-textcol2">{{ formatDay(day.date) }}</td>
                                    <td class="px-4 py-3 text-center text-textcol">{{ day.entry_count }}</td>
                                    <td class="px-4 py-3 text-center text-textcol">{{ formatHours(day.total_minutes) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="text-center py-8 text-textcol2 italic">
                        No entries this week.
                    </div>
                </div>
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
            weeklyUserActivity: [],
            dailyActivityBreakdown: [],
            weeklyStats: {
                total_entries: 0,
                total_minutes: 0,
                total_users: 0,
                tasks_completed: 0,
            },
            weekStart: null,
            weekEnd: null,
            previousWeekStart: null,
            nextWeekStart: null,
            loading: true,
        };
    },

    mounted() {
        this.fetchStatistics();
    },

    methods: {
        async fetchStatistics() {
            try {
                const params = {};
                if (this.weekStart) {
                    params.week_start = this.weekStart;
                }

                const response = await axios.get(
                    `/projects/${this.projectId}/statistics`,
                    { params }
                );

                this.taskStatusStats = response.data.taskStatusStats || [];
                this.taskTimeStats = response.data.taskTimeStats || [];
                this.weeklyUserActivity = response.data.weeklyUserActivity || [];
                this.dailyActivityBreakdown = response.data.dailyActivityBreakdown || [];
                this.weeklyStats = response.data.weeklyStats || {
                    total_entries: 0,
                    total_minutes: 0,
                    total_users: 0,
                    tasks_completed: 0,
                };
                this.weekStart = response.data.weekStart;
                this.weekEnd = response.data.weekEnd;
                this.previousWeekStart = response.data.previousWeekStart;
                this.nextWeekStart = response.data.nextWeekStart;
            } catch (error) {
                console.error("Failed to load statistics:", error);
            } finally {
                this.loading = false;
            }
        },
        exportWeek() {
            const url = `/projects/${this.projectId}/statistics/export?week_start=${this.weekStart}`;
            window.open(url, "_blank");
        },

        previousWeek() {
            this.weekStart = this.previousWeekStart;
            this.loading = true;
            this.fetchStatistics();
        },

        nextWeek() {
            this.weekStart = this.nextWeekStart;
            this.loading = true;
            this.fetchStatistics();
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString("en-US", {
                month: "short",
                day: "numeric",
                year: "numeric",
            });
        },

        formatDay(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString("en-US", { weekday: "short" });
        },

        formatHours(minutes) {
            if (!minutes) return "0h";
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return hours > 0 ? `${hours}h ${mins}m` : `${mins}m`;
        },

        formatMinutes(minutes) {
            if (!minutes) return "0m";
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return hours > 0 ? `${hours}h ${mins}m` : `${mins}m`;
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
