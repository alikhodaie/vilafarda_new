<template>
    <div>
        <div class="form-group row">
            <div class="col-12 col-md-4">
                <label for="week_price" class="form-label">{{ week_price_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <input type="number" name="week_price" v-model="week_price" hidden>
                <money v-model="week_price"
                       inputmode="numeric"
                       id="week_price"
                       class="form-control"
                       min="0"
                ></money>
                <p v-if="week_price">{{ week_price.num2persian() }} تومان</p>
            </div>
        </div>
        <div class="form-group row mt-5">
            <div class="col-12 col-md-4">
                <label for="wed_price" class="form-label">{{ wed_price_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <input type="number" name="wed_price" v-model="wed_price" hidden>
                <money v-model="wed_price"
                       inputmode="numeric"
                       id="wed_price"
                       class="form-control"
                       min="0"
                ></money>
                <p v-if="wed_price">{{ wed_price.num2persian() }} تومان</p>
            </div>
        </div>
        <div class="form-group row mt-5">
            <div class="col-12 col-md-4">
                <label for="thu_price" class="form-label">{{ thu_price_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <input type="number" name="thu_price" v-model="thu_price" hidden>
                <money v-model="thu_price"
                       inputmode="numeric"
                       id="thu_price"
                       class="form-control"
                       min="0"
                ></money>
                <p v-if="thu_price">{{ thu_price.num2persian() }} تومان</p>
            </div>
        </div>
        <div class="form-group row mt-5">
            <div class="col-12 col-md-4">
                <label for="fri_price" class="form-label">{{ fri_price_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <input type="number" name="fri_price" v-model="fri_price" hidden>
                <money v-model="fri_price"
                       inputmode="numeric"
                       id="fri_price"
                       class="form-control"
                       min="0"
                ></money>
                <p v-if="fri_price">{{ fri_price.num2persian() }} تومان</p>
            </div>
        </div>
        <div class="form-group row mt-5">
            <div class="col-12 col-md-4">
                <label for="price_per_surplus" class="form-label">{{ price_per_surplus_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <input type="number" name="price_per_surplus" v-model="price_per_surplus" hidden>
                <money v-model="price_per_surplus"
                       inputmode="numeric"
                       id="price_per_surplus"
                       class="form-control"
                       min="0"
                ></money>
                <p v-if="price_per_surplus">{{ price_per_surplus.num2persian() }} تومان</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateHomeStep10",
    props: ['old', 'validate_route', 'week_price_title', 'wed_price_title', 'thu_price_title', 'fri_price_title', 'price_per_surplus_title'],
    data() {
        return {
            can_update: false,
            week_price: 0,
            wed_price: 0,
            thu_price: 0,
            fri_price: 0,
            price_per_surplus: 0
        }
    },
    created() {
        if (this.old.week_price){
            this.week_price = this.old.week_price
        }
        if (this.old.wed_price){
            this.wed_price = this.old.wed_price
        }
        if (this.old.thu_price){
            this.thu_price = this.old.thu_price
        }
        if (this.old.fri_price){
            this.fri_price = this.old.fri_price
        }
        if (this.old.price_per_surplus){
            this.price_per_surplus = this.old.price_per_surplus
        }

        setTimeout(function (){
            this.can_update = true
        }.bind(this), 500)
    },
    watch: {
        week_price: function (week_price){
            this.check()
        },
        wed_price: function (wed_price){
            this.check()
        },
        thu_price: function (thu_price){
            this.check()
        },
        fri_price: function (fri_price){
            this.check()
        },
        price_per_surplus: function (price_per_surplus){
            this.check()
        }
    },
    methods: {
        activeNextPage() {
            window.eventBus.$emit('next_page', 14)
        },
        inactiveNextPage() {
            window.eventBus.$emit('next_page', 10)
        },
        check() {
            if (this.week_price && this.wed_price && this.thu_price && this.fri_price && this.price_per_surplus >= 0) {
                this.validate()
            }
            else {
                this.inactiveNextPage()
            }
        },
        validate(){
            if (! this.can_update){
                return false;
            }

            let params = {
                week_price: this.week_price,
                wed_price: this.wed_price,
                thu_price: this.thu_price,
                fri_price: this.fri_price,
                price_per_surplus: this.price_per_surplus
            };

            axios.post(this.validate_route, params)
                .then((response) => {
                    this.activeNextPage()
                })
                .catch((error) => {
                    // let message = '';
                    // this.$root.formatErrors(error).map(item => {
                    //     message += item + '\n'
                    // })
                    // this.$root.showAlert(message, 'error', true)
                });
        }
    }
}
</script>

<style scoped>

</style>
