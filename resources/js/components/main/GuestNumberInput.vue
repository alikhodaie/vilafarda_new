<script>
export default {
    name: 'GuestNumberInput',
    props: ['count_guest_text', 'name'],
    data() {
        return {
            guest: 0
        }
    }
}
</script>

<template>
    <div class="form-group">
        <div class="guests">
            <label>{{ count_guest_text }}</label>
            <div class="guests-box">
                <button class="counter-btn" type="button" @click="guest > 0 ? guest -= 1: ''"><i class="ti-minus"></i></button>
                <input type="text" v-model="guest" id="guestNo" :name="name"/>
                <button class="counter-btn" type="button" @click="guest += 1"><i class="ti-plus"></i></button>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
