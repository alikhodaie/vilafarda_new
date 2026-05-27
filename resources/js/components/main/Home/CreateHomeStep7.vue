<template>
    <div>
        <div class="form-group mb-5 row">
            <div class="col-12 col-md-4">
                <label class="form-label">{{ count_sleep_rooms }}</label>
            </div>
            <div class="col-12 col-md-8">
                <div class="guests">
                    <div class="guests-box">
                        <button class="counter-btn" type="button" @click="removeRoom()"><i class="ti-minus"></i></button>
                        <span class="w-50 text-center">{{ rooms.length | formatNumber }}</span>
                        <button class="counter-btn" type="button" @click="addRoom()"><i class="ti-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div v-for="(room, index) in rooms" class="form-group card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <span>اتاق {{ ++index }}</span>
                        <p class="text-muted">
                            {{ (room.single_bed > 0) ? room.single_bed + ' تخت یک نفره .' : '' }}
                            {{ (room.double_bed > 0) ? room.double_bed + ' تخت دو نفره .' : '' }}
                            {{ (room.traditional_bed > 0) ? room.traditional_bed + ' رخت خواب سنتی .' : '' }}
                        </p>
                    </div>
                    <button type="button" class="btn btn-light" data-toggle="collapse" :data-target="`#${index}_room_sleep_place`" aria-expanded="false" :aria-controls="`${index}_room_sleep_place`">ویرایش</button>
                </div>
                <div class="collapse mt-3" :id="`${index}_room_sleep_place`">
                    <div class="form-group row">
                        <div class="col-12 col-md-5">
                            <label class="form-label">تخت یک نفره</label>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="guests">
                                <div class="guests-box">
                                    <button class="counter-btn" type="button" @click="(room.single_bed > 0) ? room.single_bed--: ''"><i class="ti-minus"></i></button>
                                    <span class="w-50 text-center">{{ room.single_bed | formatNumber }}</span>
                                    <button class="counter-btn" type="button" @click="room.single_bed++"><i class="ti-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-5">
                            <label class="form-label">تخت دو نفره</label>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="guests">
                                <div class="guests-box">
                                    <button class="counter-btn" type="button" @click="(room.double_bed > 0) ? room.double_bed--: ''"><i class="ti-minus"></i></button>
                                    <span class="w-50 text-center">{{ room.double_bed | formatNumber }}</span>
                                    <button class="counter-btn" type="button" @click="room.double_bed++"><i class="ti-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-5">
                            <label class="form-label">رخت خواب سنتی</label>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="guests">
                                <div class="guests-box">
                                    <button class="counter-btn" type="button" @click="(room.traditional_bed > 0) ? room.traditional_bed--: ''"><i class="ti-minus"></i></button>
                                    <span class="w-50 text-center">{{ room.traditional_bed | formatNumber }}</span>
                                    <button class="counter-btn" type="button" @click="room.traditional_bed++"><i class="ti-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-4">
                            <label :for="`room_${index}_more`" class="form-label">سایر موارد:</label>
                        </div>
                        <div class="col-12 col-md-8">
                            <input placeholder="مثل: مبل تخت خواب شو" :id="`room_${index}_more`" type="text" class="form-control" v-model="room.more">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <span>فضای مشترک</span>
                        <p class="text-muted">
                            {{ (share.single_bed > 0) ? share.single_bed + ' تخت یک نفره .' : '' }}
                            {{ (share.double_bed > 0) ? share.double_bed + ' تخت دو نفره .' : '' }}
                            {{ (share.traditional_bed > 0) ? share.traditional_bed + ' رخت خواب سنتی .' : '' }}
                        </p>
                    </div>
                    <button type="button" class="btn btn-light" data-toggle="collapse" data-target="#share_sleep_place" aria-expanded="false" aria-controls="share_sleep_place">ویرایش</button>
                </div>
                <div class="collapse mt-3" id="share_sleep_place">
                    <div class="form-group row">
                        <div class="col-12 col-md-5">
                            <label class="form-label">تخت یک نفره</label>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="guests">
                                <div class="guests-box">
                                    <button class="counter-btn" type="button" @click="(share.single_bed > 0) ? share.single_bed--: ''"><i class="ti-minus"></i></button>
                                    <span class="w-50 text-center">{{ share.single_bed | formatNumber }}</span>
                                    <button class="counter-btn" type="button" @click="share.single_bed++"><i class="ti-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-5">
                            <label class="form-label">تخت دو نفره</label>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="guests">
                                <div class="guests-box">
                                    <button class="counter-btn" type="button" @click="(share.double_bed > 0) ? share.double_bed--: ''"><i class="ti-minus"></i></button>
                                    <span class="w-50 text-center">{{ share.double_bed | formatNumber }}</span>
                                    <button class="counter-btn" type="button" @click="share.double_bed++"><i class="ti-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-5">
                            <label class="form-label">رخت خواب سنتی</label>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="guests">
                                <div class="guests-box">
                                    <button class="counter-btn" type="button" @click="(share.traditional_bed > 0) ? share.traditional_bed--: ''"><i class="ti-minus"></i></button>
                                    <span class="w-50 text-center">{{ share.traditional_bed | formatNumber }}</span>
                                    <button class="counter-btn" type="button" @click="share.traditional_bed++"><i class="ti-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-4">
                            <label for="share_more" class="form-label">سایر موارد:</label>
                        </div>
                        <div class="col-12 col-md-8">
                            <input placeholder="مثل: مبل تخت خواب شو" id="share_more" type="text" class="form-control" v-model="share.more">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group mt-5 row">
            <div class="col-12 col-md-4">
                <label for="sleep_area_description" class="form-label">توضیحات فضای خواب</label>
            </div>
            <div class="col-12 col-md-8">
                <textarea class="form-control" id="sleep_area_description" cols="30" rows="10" v-model="sleep_area_description"></textarea>
                <p class="text-muted">در این قسمت می توانید توضیحات تکمیلی درباره امکانات و شرایط مهیا شده برای خواب میهمانان را ارائه کنید</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateHomeStep7",
    props: ['old', 'validate_route', 'count_sleep_rooms'],
    data() {
        return {
            can_update: false,
            rooms: [],
            share: {
                single_bed: 0,
                double_bed: 0,
                traditional_bed: 0,
                more: '',
            },
            sleep_area_description: ''
        }
    },
    created() {
        if (this.old.rooms){
            this.old.rooms.map((room) => {
                if (parseInt(room.is_share) === 1) {
                   this.share.single_bed = room.single_bed
                   this.share.double_bed = room.double_bed
                   this.share.traditional_bed = room.traditional_bed
                   this.share.more = room.more
                }
                else {
                    this.addRoom('', room.single_bed, room.double_bed, room.traditional_bed, room.more)
                }
            })
        }
        if (this.old.sleep_area_description){
            this.sleep_area_description = this.old.sleep_area_description
        }

        setTimeout(function (){
            this.can_update = true

            window.eventBus.$emit('next_page', 10)
        }.bind(this), 1000)
    },
    watch: {
        rooms: {
            handler(rooms) {
                window.eventBus.$emit('next_page', 10)

                this.validate()
            },
            deep: true
        },
        share: {
            handler(rooms) {
                window.eventBus.$emit('next_page', 10)

                this.validate()
            },
            deep: true
        },
        sleep_area_description: function (rooms){
            window.eventBus.$emit('next_page', 10)

            this.validate()
        },
    },
    methods: {
        addRoom(id = '', single_bed = 0, double_bed = 0, traditional_bed = 0, more = ''){
            this.rooms.push({id: id, single_bed: single_bed, double_bed: double_bed, traditional_bed: traditional_bed, more: more})
        },
        removeRoom(){
            if (this.rooms.length > 0){
                this.rooms.pop()
            }
        },
        validate(){
            if (! this.can_update){
                return false;
            }

            let params = {
                rooms: this.rooms,
                share_room: this.share,
                sleep_area_description: this.sleep_area_description
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
