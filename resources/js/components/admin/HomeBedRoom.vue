<template>
    <div>
        <div class="form-group mb-5 row">
            <div class="col-12 col-md-4">
                <label class="form-label">{{ count_sleep_rooms }}</label>
            </div>
            <div class="col-12 col-md-8">
                <div class="guests">
                    <div class="guests-box">
                        <button class="btn btn-falcon-danger me-6" type="button" @click="removeRoom()"><i class="fa fa-minus"></i></button>
                        <span class="w-50 text-center">{{ rooms.length | formatNumber }}</span>
                        <button class="btn btn-falcon-info ms-6" type="button" @click="addRoom()"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div v-for="(room, index) in rooms" class="form-group card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <input v-if="room.id" type="hidden" :name="`sleep_room[${index}][single_bed]`" v-model="room.id">
                    <input type="hidden" :name="`sleep_room[${index}][single_bed]`" v-model="room.single_bed">
                    <input type="hidden" :name="`sleep_room[${index}][double_bed]`" v-model="room.double_bed">
                    <input type="hidden" :name="`sleep_room[${index}][traditional_bed]`" v-model="room.traditional_bed">

                    <div>
                        <span>اتاق {{ index + 1 }}</span>
                        <p class="text-muted">
                            {{ (room.single_bed > 0) ? room.single_bed + ' تخت یک نفره .' : '' }}
                            {{ (room.double_bed > 0) ? room.double_bed + ' تخت دو نفره .' : '' }}
                            {{ (room.traditional_bed > 0) ? room.traditional_bed + ' رخت خواب سنتی .' : '' }}
                        </p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-falcon-info accordion-button text-info collapsed" data-bs-toggle="collapse" :data-bs-target="`#room_${index}_sleep_place`" aria-expanded="false" :aria-controls="`room_${index}_sleep_place`">ویرایش</button>
                    </div>
                </div>
                <div class="accordion-collapse collapse  mt-3" :id="`room_${index}_sleep_place`">
                    <div class="form-group row mt-3">
                        <div class="col-12 col-md-5">
                            <label class="form-label">تخت یک نفره</label>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="guests">
                                <div class="guests-box">
                                    <button class="btn btn-falcon-danger me-6" type="button" @click="(room.single_bed > 0) ? room.single_bed--: ''"><i class="fa fa-minus"></i></button>
                                    <span class="w-50 text-center">{{ room.single_bed | formatNumber }}</span>
                                    <button class="btn btn-falcon-info ms-6" type="button" @click="room.single_bed++"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-12 col-md-5">
                            <label class="form-label">تخت دو نفره</label>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="guests">
                                <div class="guests-box">
                                    <button class="btn btn-falcon-danger me-6" type="button" @click="(room.double_bed > 0) ? room.double_bed--: ''"><i class="fa fa-minus"></i></button>
                                    <span class="w-50 text-center">{{ room.double_bed | formatNumber }}</span>
                                    <button class="btn btn-falcon-info ms-6" type="button" @click="room.double_bed++"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-12 col-md-5">
                            <label class="form-label">رخت خواب سنتی</label>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="guests">
                                <div class="guests-box">
                                    <button class="btn btn-falcon-danger me-6" type="button" @click="(room.traditional_bed > 0) ? room.traditional_bed--: ''"><i class="fa fa-minus"></i></button>
                                    <span class="w-50 text-center">{{ room.traditional_bed | formatNumber }}</span>
                                    <button class="btn btn-falcon-info ms-6" type="button" @click="room.traditional_bed++"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-12 col-md-4">
                            <label :for="`room_${index}_more`" class="form-label">سایر موارد:</label>
                        </div>
                        <div class="col-12 col-md-8">
                            <input :name="`sleep_room[${index}][more]`" placeholder="مثل: مبل تخت خواب شو" :id="`room_${index}_more`" type="text" class="form-control" v-model="room.more">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <input v-if="share.id" type="hidden" name="sleep_share[id]" v-model="share.id">
                    <input type="hidden" name="sleep_share[single_bed]" v-model="share.single_bed">
                    <input type="hidden" name="sleep_share[double_bed]" v-model="share.double_bed">
                    <input type="hidden" name="sleep_share[traditional_bed]" v-model="share.traditional_bed">

                    <div>
                        <span>فضای مشترک</span>

                        <p class="text-muted">
                            {{ (share.single_bed > 0) ? share.single_bed + ' تخت یک نفره .' : '' }}
                            {{ (share.double_bed > 0) ? share.double_bed + ' تخت دو نفره .' : '' }}
                            {{ (share.traditional_bed > 0) ? share.traditional_bed + ' رخت خواب سنتی .' : '' }}
                        </p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-falcon-info accordion-button text-info collapsed" data-bs-toggle="collapse" data-bs-target="#share_sleep_place" aria-expanded="false" aria-controls="share_sleep_place">ویرایش</button>
                    </div>
                </div>
                <div class="accordion-collapse collapse mt-3" id="share_sleep_place">
                    <div class="form-group row mt-3">
                        <div class="col-12 col-md-5">
                            <label class="form-label">تخت یک نفره</label>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="guests">
                                <div class="guests-box">
                                    <button class="btn btn-falcon-danger me-6" type="button" @click="(share.single_bed > 0) ? share.single_bed--: ''"><i class="fa fa-minus"></i></button>
                                    <span class="w-50 text-center">{{ share.single_bed | formatNumber }}</span>
                                    <button class="btn btn-falcon-info ms-6" type="button" @click="share.single_bed++"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-12 col-md-5">
                            <label class="form-label">تخت دو نفره</label>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="guests">
                                <div class="guests-box">
                                    <button class="btn btn-falcon-danger me-6" type="button" @click="(share.double_bed > 0) ? share.double_bed--: ''"><i class="fa fa-minus"></i></button>
                                    <span class="w-50 text-center">{{ share.double_bed | formatNumber }}</span>
                                    <button class="btn btn-falcon-info ms-6" type="button" @click="share.double_bed++"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-12 col-md-5">
                            <label class="form-label">رخت خواب سنتی</label>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="guests">
                                <div class="guests-box">
                                    <button class="btn btn-falcon-danger me-6" type="button" @click="(share.traditional_bed > 0) ? share.traditional_bed--: ''"><i class="fa fa-minus"></i></button>
                                    <span class="w-50 text-center">{{ share.traditional_bed | formatNumber }}</span>
                                    <button class="btn btn-falcon-info ms-6" type="button" @click="share.traditional_bed++"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-12 col-md-4">
                            <label for="sleep_share_more" class="form-label">سایر موارد:</label>
                        </div>
                        <div class="col-12 col-md-8">
                            <input name="sleep_share[more]" placeholder="مثل: مبل تخت خواب شو" id="sleep_share_more" type="text" class="form-control" v-model="share.more">
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
                <textarea name="sleep_area_description" class="form-control" id="sleep_area_description" cols="30" rows="10" v-model="sleep_area_description"></textarea>
                <p class="text-muted">در این قسمت می توانید توضیحات تکمیلی درباره امکانات و شرایط مهیا شده برای خواب میهمانان را ارائه کنید</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "HomeBedRoom",
    props: ['old', 'old_more', 'count_sleep_rooms'],
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
        if (this.old) {
            if (this.old.rooms) {
                this.old.rooms.map((room) => {
                    if (parseInt(room.is_share) === 1) {
                        this.share.id = room.id
                        this.share.single_bed = room.single_bed
                        this.share.double_bed = room.double_bed
                        this.share.traditional_bed = room.traditional_bed
                        this.share.more = room.more
                    } else {
                        this.addRoom(room.id, room.single_bed, room.double_bed, room.traditional_bed, room.more)
                    }
                })
            }
            if (this.old.sleep_area_description) {
                this.sleep_area_description = this.old.sleep_area_description
            }
        }
    },
    methods: {
        addRoom(id = '', single_bed = 0, double_bed = 0, traditional_bed = 0, more = ''){
            this.rooms.push({id: id, single_bed: single_bed, double_bed: double_bed, traditional_bed: traditional_bed, more: more})
        },
        removeRoom(){
            if (this.rooms.length > 0){
                this.rooms.pop()
            }
        }
    }
}
</script>

<style scoped>

</style>
