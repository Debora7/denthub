<template>
    <div>
        <div>
            <label for="date">Selectează Data:</label>
            <input
                type="date"
                v-model="selectedDate"
                :min="startDate"
                :max="endDate"
                @change="validateDate"
                class="form-select form-select-sm"
                :class="{ 'is-invalid': !validDate && selectedDate }"
            />
            <p v-if="!validDate && selectedDate" class="text-danger">
                Medicul nu are program în această zi.
            </p>
        </div>
        <div v-if="validDate" class="mt-2">
            <label for="time">Selectează Ora:</label>
            <div>
                <span
                    v-for="time in availableTimes"
                    :key="time"
                    :class="[
                        'badge',
                        { 'selected-badge': time === selectedTime },
                    ]"
                    @click="selectTime(time)"
                >
                    {{ time }}
                </span>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "DateTime",
    props: {
        days: {
            type: Array,
            required: true,
        },
        timeSlots: {
            type: Object,
            required: true,
        },
        startDate: {
            type: String,
            required: true,
        },
        endDate: {
            type: String,
            required: true,
        },
        consult_time: {
            type: Number,
            required: true,
        },
        disabledTimeSlots: {
            type: Array,
            required: true,
        },
    },

    data() {
        return {
            selectedDate: "",
            validDate: false,
            availableTimes: [],
            selectedTime: "",
        };
    },
    computed: {
        disabledDates() {
            let dates = [];
            let start = new Date(this.startDate);
            let end = new Date(this.endDate);

            while (start <= end) {
                const day = start.getDay();
                if (!this.days.includes(day)) {
                    dates.push(new Date(start));
                }
                start.setDate(start.getDate() + 1);
            }

            return dates;
        },
    },
    methods: {
        validateDate() {
            const selectedDay = new Date(this.selectedDate).getDay();
            if (this.days.includes(selectedDay)) {
                this.validDate = true;
                this.generateTimes(selectedDay);
            } else {
                this.validDate = false;
            }
        },

        generateTimes(day) {
            const slot = this.timeSlots[day];
            const start = new Date(`${this.selectedDate}T${slot.start}:00`);
            const end = new Date(`${this.selectedDate}T${slot.end}:00`);
            const interval = this.consult_time * 60000;
            let currentTime = start;

            this.availableTimes = [];
            while (currentTime <= end) {
                const hours = currentTime
                    .getHours()
                    .toString()
                    .padStart(2, "0");
                const minutes = currentTime
                    .getMinutes()
                    .toString()
                    .padStart(2, "0");
                const timeString = `${hours}:${minutes}`;

                // Check if the time slot is disabled
                const fullDateTime = new Date(
                    `${this.selectedDate}T${timeString}:00`
                ).toISOString();
                if (!this.disabledTimeSlots.includes(fullDateTime)) {
                    this.availableTimes.push(timeString);
                }

                currentTime = new Date(currentTime.getTime() + interval);
            }
        },

        selectTime(time) {
            this.selectedTime = time;
            this.$emit("update:time", {
                date: this.formatDate(this.selectedDate),
                time: this.selectedTime,
            });
        },
        formatDate(date) {
            const d = new Date(date);
            const day = d.getDate().toString().padStart(2, "0");
            const month = (d.getMonth() + 1).toString().padStart(2, "0");
            const year = d.getFullYear();
            return `${day}.${month}.${year}`;
        },
        isDateDisabled(date) {
            const d = new Date(date);
            return this.disabledDates.some(
                (disabledDate) =>
                    disabledDate.getDate() === d.getDate() &&
                    disabledDate.getMonth() === d.getMonth() &&
                    disabledDate.getFullYear() === d.getFullYear()
            );
        },
    },
    watch: {
        selectedDate(val) {
            if (this.isDateDisabled(val)) {
                this.validDate = false;
            }
        },
    },
};
</script>
<style scoped>
.badge {
    display: inline-block;
    padding: 0.5em;
    margin: 0.2em;
    background-color: #28a745;
    border-radius: 0.25em;
    cursor: pointer;
    color: #fff;
    font-weight: normal;
}
.selected-badge {
    background-color: #007bff;
    color: #fff;
    font-weight: bold;
}
.is-invalid {
    border-color: red;
}
</style>
