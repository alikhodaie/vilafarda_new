<template>
    <div>
        <div class="form-group" v-for="(safety, index) in values">
            <div>
                <input type="checkbox" :id="`safety-${safety.id}`" class="form-check-input" :value="safety.id" v-model="safeties[index].checked">
                <label :for="`safety-${safety.id}`" class="form-label" style="margin-right: 30px;">
                    {{ safety.title }}
                </label>
            </div>
            <div v-if="checkHas(safety.id)" class="mt-1 mb-3">
                <input type="text" :placeholder="safety.placeholder" class="form-control" v-model="safeties[index].description">
            </div>
        </div>
        <div class="form-group mt-3 row">
            <div class="col-12 col-md-4">
                <label for="more" class="form-label">موارد بیشتر</label>
            </div>
            <div class="col-12 col-md-8">
                <input id="more" class="form-control" v-model="more">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateHomeStep12",
    props: ['old', 'validate_route', 'values'],
    data() {
        return {
            can_update: false,
            safeties: [],
            more: ''
        }
    },
    created() {
        this.values.map((safety) => {
            this.safeties.push({'id': safety.id, 'description': '', 'checked': false})
        })

        if (this.old.more){
            this.more = this.old.more
        }
        if (this.old.safeties){
            this.old.safeties.map((old) => {
                this.safeties.map((safety) => {
                    if (safety.id === old.id){
                        safety.checked = true
                        safety.description = old.pivot.description
                    }
                })
            })
        }

        setTimeout(function (){
            this.can_update = true
        }.bind(this), 500)

        this.activeNextPage()
    },
    watch: {
        safeties: {
            handler (safeties){
                this.validate()
                this.activeNextPage()
            },
            deep: true
        },
        more: function (more){
            this.validate()
            this.activeNextPage()
        },
    },
    methods: {
        activeNextPage() {
            window.eventBus.$emit('next_page', 14)
        },
        inactiveNextPage() {
            window.eventBus.$emit('next_page', 12)
        },
        checkHas(id) {
            let result = false

            this.safeties.map((safety) => {
                if (safety.id === id && safety.checked) {
                    result = true
                }
            })

            return result;
        },
        validate(){
            if (! this.can_update){
                return false;
            }
            let safeties = [];
            this.safeties.map((safety) => {
                if (safety.checked){
                    safeties.push({'id': safety.id, 'description': safety.description})
                }
            })

            let params = {
                safeties: safeties,
                more: this.more
            };

            axios.post(this.validate_route, params)
                .then((response) => {
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
