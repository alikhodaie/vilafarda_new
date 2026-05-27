<template>
    <div>
        <form v-if="time_is_over" :action="route_resend" method="POST">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="mobile" :value="mobile">

            <button class="btn btn-primary w-100 rounded mb-4">
                {{ resend_text }}
            </button>
        </form>
        <p v-else>
            اگر ارسال نشد. بعد از <span class="text-danger">{{ timer }}</span> ثانیه دوباره امتحان کنید
        </p>
    </div>
</template>

<script>
export default {
    name: "ResendTimer",
    props: {
        csrf: '',
        mobile: '',
        now_prop: '',
        expired_at_prop: '',
        route_resend: '',
        resend_text: ''
    },
    data() {
        return {
            time_is_over: false,
            now: Math.trunc(Date.parse(this.now_prop) / 1000),
            expired_at: Math.trunc(Date.parse(this.expired_at_prop) / 1000),
            timer: 0
        }
    },
    mounted() {
        this.timer = this.expired_at - this.now

        window.setInterval(() => {
            this.timer -= 1

            if (this.timer <= 0){
                this.time_is_over = true
            }
        }, 1000)
    }
}
</script>

<style scoped>

</style>
