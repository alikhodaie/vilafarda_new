<template>
    <div>
        <p>مقررات سه گانه لغو رزرو را مطالعه کنید و سپس سیاست لغو مناسب شرایط اقامتگاه خود را از میان 3 گزینه زیر، انتخاب کنید:</p>
        <div class="form-group mt-3">
            <div v-for="policy in reject_policies" class="mt-3">
                <input :id="`policy_${policy.value}`" type="radio" name="reject_policy" class="form-control" :value="policy.value" v-model="reject_policy">
                <label :for="`policy_${policy.value}`" class="form-control-label">
                    {{ policy.title }} <span>({{ getCommission(policy.value) }}% کمیسیون ویلا فردا)</span>
                    <span class="d-block text-muted mt-1">{{ policy.description }}</span>
                </label>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateHomeStep14",
    props: ['old', 'validate_route', 'reject_policies', 'easy_commission', 'balanced_commission', 'strict_commission'],
    data() {
        return {
            can_update: false,
            reject_policy: null
        }
    },
    created() {
        if (this.old){
            this.reject_policy = this.old
            this.activeNextPage()
        }

        setTimeout(function (){
            this.can_update = true
        }.bind(this), 500)
    },
    watch: {
        reject_policy: function (reject_policy){
            this.validate()
        },
    },
    methods: {
        activeNextPage() {
            window.eventBus.$emit('next_page', 15)
        },
        inactiveNextPage() {
            window.eventBus.$emit('next_page', 14)
        },
        validate(){
            if (! this.can_update){
                return false;
            }

            this.inactiveNextPage()

            let params = {
                reject_policy: this.reject_policy
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
        },
        getCommission(policy) {
            switch (policy){
                case 'easy':
                    return this.easy_commission

                case 'balanced':
                    return this.balanced_commission

                case 'strict':
                    return this.strict_commission

                default:
                    return 0
            }
        }
    }
}
</script>

<style scoped>

</style>
