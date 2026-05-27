<template>
    <div class="countdown-timer text-center">
        <div v-if="!is_end" class="countdown-clock">
            <span class="time-part">{{ seconds|two_digits }}</span>
            <span class="separator">:</span>
            <span class="time-part">{{ minutes|two_digits }}</span>
        </div>
        <div v-else class="text-muted fw-bold fs-3 mt-2">{{ text_expired }}</div>

    </div>
</template>

<script>
export default {
    name: "CountDownTimer",
    props: ['time', 'prop_now', 'color', 'text', 'text_expired'],
    data() {
        return {
            date: Math.trunc(Date.parse(this.time) / 1000),
            now: Math.trunc(Date.parse(this.prop_now) / 1000),
            is_end: false,
        };
    },
    mounted() {
        this.interval = window.setInterval(() => {
            this.now += 1;
            this.checkExpired();
        }, 1000);
        this.color = this.color ?? '#dc3545'; // Default to Bootstrap red
    },
    beforeUnmount() {
        clearInterval(this.interval);
    },
    computed: {
        seconds() {
            return Math.max((this.date - this.now) % 60, 0);
        },
        minutes() {
            return Math.max(Math.trunc((this.date - this.now) / 60) % 60, 0);
        }
    },
    methods: {
        checkExpired() {
            this.is_end = (this.minutes <= 0 && this.seconds <= 0);
        }
    },
    filters: {
        two_digits(value) {
            return value.toString().padStart(2, '0');
        }
    }
}
</script>

<style scoped>
.countdown-timer {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.countdown-clock {
    font-family: 'Courier New', monospace;
    font-size: 2.8rem;
    font-weight: bold;
    color: v-bind(color); /* Vue 3 inline bind won't work here. Use style binding in template. */
    color: #dc3545;
}

.time-part {
    font-size: 20px;
    min-width: 45px;
    display: inline-block;
}

.separator {
    padding: 0 2px;
    font-weight: bold;
}

.countdown-label {
    font-size: 0.9rem;
    color: #6c757d;
}
</style>
