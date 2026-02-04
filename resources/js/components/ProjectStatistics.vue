<template>
    <div>
        <!-- Tabs -->
        <div class="flex gap-2 border-b border-white/5 mb-6">
            <button
                v-for="tab in tabs"
                :key="tab"
                @click="activeTab = tab"
                class="px-4 py-2 text-sm font-semibold rounded-t-md transition-colors"
                :class="activeTab === tab
                    ? 'bg-primary/10 text-primary border-b-2 border-primary'
                    : 'text-textcol2 hover:text-textcol hover:bg-white/5'">
                {{ tab }}
            </button>
        </div>

        <!-- Tasks tab -->
        <div v-if="activeTab === 'Tasks'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Task status donut -->
            <div class="rounded-xl bg-slate-950/40 p-6 ring-1 ring-white/5">
                <h3 class="text-lg font-semibold text-textcol mb-4">Task Status Distribution</h3>
                <apexchart
                    type="donut"
                    height="280"
                    :options="taskDonutOptions"
                    :series="taskDonutSeries"
                />
            </div>

            <!-- Time spent per task -->
            <div class="rounded-xl bg-slate-950/40 p-6 ring-1 ring-white/5">
                <h3 class="text-lg font-semibold text-textcol mb-4">Time Spent per Task</h3>
                <apexchart
                    type="bar"
                    height="280"
                    :options="taskTimeOptions"
                    :series="taskTimeSeries"
                />
            </div>
        </div>

        <div
            v-if="activeTab === 'Weekly Summary'"
            class="h-[387px] flex items-center justify-center text-textcol2 italic"
        >
            Weekly summary data | gonna compare to last weeks data
        </div>
    </div>
</template>

<script>
import ApexChart from "vue3-apexcharts";

export default {
    components: { apexchart: ApexChart },

    props: {
        taskStatusStats: Array,
        taskTimeStats: Array,
    },

    data() {
        return {
            tabs: ['Tasks', 'Weekly Summary'],
            activeTab: 'Tasks',
        };
    },

    computed: {
        humanizedTaskStatusStats() {
            const statusMap = {
                in_progress: "In Progress",
                completed: "Completed",
            };
            // makes data more presentable
            return this.taskStatusStats.map(item => ({
                ...item,
                status:
                    statusMap[item.status],
            }));
        },

        /* Donut */
        taskDonutSeries() {
            return this.humanizedTaskStatusStats.map(s => s.count);
        },

        taskDonutOptions() {
            return {
                labels: this.humanizedTaskStatusStats.map(s => s.status),
                colors: ['#01BAEF', '#0CBABA', '#380036', '#F1E4E8', '#8B5CF6'],
                chart: {
                    background: 'transparent',
                    foreColor: '#c5a8b7',
                },
                theme: {
                    mode: 'dark',
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: '#c5a8b7',
                    },
                    fontSize: '13px',
                    fontFamily: 'inherit',
                },
                dataLabels: {
                    style: {
                        fontSize: '14px',
                        fontFamily: 'inherit',
                        fontWeight: 600,
                    },
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Tasks',
                                    fontSize: '14px',
                                    color: '#c5a8b7',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                },
                                value: {
                                    fontSize: '24px',
                                    fontWeight: 700,
                                    color: '#F1E4E8',
                                }
                            }
                        }
                    }
                },
                stroke: {
                    colors: ['#150811'],
                    width: 2,
                },
                states: {
                    hover: {
                        filter: {
                            type: 'lighten',
                            value: 0.1,
                        }
                    }
                },
                tooltip: {
                    theme: 'dark',
                    style: {
                        fontSize: '13px',
                        fontFamily: 'inherit',
                    },
                    fillSeriesColor: false,
                },
            };
        },

        /* Bar: time per task */
        taskTimeSeries() {
            return [{
                name: 'Time',
                data: this.taskTimeStats.map(t => t.minutes),
            }];
        },

        taskTimeOptions() {
            return {
                chart: {
                    toolbar: { show: false },
                    background: 'transparent',
                    foreColor: '#c5a8b7',
                },
                theme: {
                    mode: 'dark',
                },
                colors: ['#01BAEF'],
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        columnWidth: '60%',
                        dataLabels: {
                            position: 'top',
                        },
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                            const hours = (val / 60).toFixed(1);
                            return hours + 'h';
                        },
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ['#F1E4E8'],
                        fontWeight: 600,
                    }
                },
                xaxis: {
                    categories: this.taskTimeStats.map(t => t.title),
                    labels: {
                        rotate: -45,
                        rotateAlways: true,
                        style: {
                            colors: '#c5a8b7',
                            fontSize: '12px',
                        },
                        trim: true,
                        maxHeight: 80,
                    },
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            const hours = Math.floor(val / 60);
                            const mins = val % 60;
                            if (hours > 0) {
                                return hours + 'h ' + mins + 'm';
                            }
                            return mins + 'm';
                        },
                        style: {
                            colors: '#c5a8b7',
                            fontSize: '12px',
                        },
                    },
                },
                grid: {
                    borderColor: 'rgba(241, 228, 232, 0.05)',
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: false,
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true,
                        }
                    },
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                            const hours = Math.floor(val / 60);
                            const mins = val % 60;
                            if (hours > 0) {
                                return hours + 'h ' + mins + 'm';
                            }
                            return mins + 'm';
                        }
                    },
                    style: {
                        fontSize: '13px',
                        fontFamily: 'inherit',
                    },
                },
            };
        },
    },
};
</script>