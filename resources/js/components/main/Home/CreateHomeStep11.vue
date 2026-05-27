<template>
    <div>
        <div class="form-group row">
            <div class="col-12 col-md-4">
                <label for="off" class="form-label">تخفیف</label>
            </div>
            <div class="col-12 col-md-8">
                <input class="form-control" type="number" id="off" min="0" max="50" v-model="off">
                <p class="text-muted">انتخاب درصد تخفیف بین بازه 0 تا 50<br>تخفیف برای تمامی روز های هفته اعمال میشود.<br> اگر نمیخواهید تخفیف بگذارید آن را برابر 0 قرار دهید!</p>
            </div>
        </div>
        <div class="form-group mt-5 row">
            <div class="col-12">
                <label class="form-label">تخفیف روزانه</label>
            </div>
            <div class="col-12 col-md-6">
                <label for="daily_off" class="form-label">تعداد روز</label>
                <input class="form-control" type="number" id="daily_off" min="0" max="90" v-model="daily_off_day">
                <p class="text-muted">اگر برابر صفر بگذارید تخفیف روزانه ای اعمال نمیشود</p>
            </div>
            <div class="col-12 col-md-6">
                <label for="daily_off_amount" class="form-label">درصد تخفیف</label>
                <select class="form-control" id="daily_off_amount" v-model="daily_off_amount">
                    <option v-for="item in daily_off" :value="item.value">{{ item.text }}</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateHomeStep11",
    props: ['old', 'validate_route', 'daily_off'],
    data() {
        return {
            can_update: false,
            off: 0,
            daily_off_day: 0,
            daily_off_amount: 0
        }
    },
    created() {
        if (this.old.off){
            this.off = this.old.off
        }
        if (this.old.daily){
            this.daily_off_day = this.old.daily
        }
        if (this.old.daily_amount){
            this.daily_off_amount = this.old.daily_amount
        }

        setTimeout(function (){
            this.can_update = true
        }.bind(this), 500)
    },
    watch: {
        off: function (off){
            this.validate()
        },
        daily_off_day: function (daily_off_day){
            this.validate()
        },
        daily_off_amount: function (daily_off_amount){
            this.validate()
        }
    },
    methods: {
        activeNextPage() {
            window.eventBus.$emit('next_page', 14)
        },
        inactiveNextPage() {
            window.eventBus.$emit('next_page', 11)
        },
        validate(){
            if (! this.can_update){
                return false;
            }

            let params = {
                off: this.off,
                daily_off: this.daily_off_day,
                daily_off_amount: this.daily_off_amount
            };

            axios.post(this.validate_route, params)
                .then((response) => {
                    this.activeNextPage()
                })
                .catch((error) => {
                    this.inactiveNextPage()

                    let message = '';
                    this.$root.formatErrors(error).map(item => {
                        message += item + '\n'
                    })
                    this.$root.showAlert(message, 'error', true)
                });
        }
    }
}
</script>

<style scoped>

</style>
