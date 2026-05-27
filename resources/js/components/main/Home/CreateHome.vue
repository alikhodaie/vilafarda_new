<template>
    <div>
        <div class="row">
            <div class="col-12 col-md-3">
                <div id="page-list" class="card scroll-none">
                    <div class="card-body">
                        <div class="wizard">
                            <div class="wizard-inner">
                                <div class="connecting-line"></div>
                                <ul class="list-actions" role="tablist">
                                    <li id="page_button_1" role="presentation" :class="{'active': page === 1, 'disabled': page !== 1}">
                                        <a @click="setPage(1)" href="javascript:" role="tab"><span class="round-tab">1</span> <i>{{ text_address }}</i></a>
                                    </li>
                                    <li id="page_button_2" role="presentation" :class="{'active': page === 2, 'disabled': page !== 2}">
                                        <a @click="setPage(2)" href="javascript:" role="tab"><span class="round-tab">2</span> <i>{{ text_map }}</i></a>
                                    </li>
                                    <li id="page_button_3" role="presentation" :class="{'active': page === 3, 'disabled': page !== 3}">
                                        <a @click="setPage(3)" href="javascript:" role="tab"><span class="round-tab">3</span><i>{{ text_images }}</i></a>
                                    </li>
                                    <li id="page_button_4" role="presentation" :class="{'active': page === 4, 'disabled': page !== 4}">
                                        <a @click="setPage(4)" href="javascript:" role="tab"><span class="round-tab">4</span><i>{{ text_about }}</i></a>
                                    </li>
                                    <li id="page_button_5" role="presentation" :class="{'active': page === 5, 'disabled': page !== 5}">
                                        <a @click="setPage(5)" href="javascript:" role="tab"><span class="round-tab">5</span><i>{{ text_residence }}</i></a>
                                    </li>
                                    <li id="page_button_6" role="presentation" :class="{'active': page === 6, 'disabled': page !== 6}">
                                        <a @click="setPage(6)" href="javascript:" role="tab"><span class="round-tab">6</span><i>{{ text_capacity }}</i></a>
                                    </li>
                                    <li id="page_button_7" role="presentation" :class="{'active': page === 7, 'disabled': page !== 7}">
                                        <a @click="setPage(7)" href="javascript:" role="tab"><span class="round-tab">7</span><i>{{ text_sleep_residence }}</i></a>
                                    </li>
                                    <li id="page_button_8" role="presentation" :class="{'active': page === 8, 'disabled': page !== 8}">
                                        <a @click="setPage(8)" href="javascript:" role="tab"><span class="round-tab">8</span><i>{{ text_options }}</i></a>
                                    </li>
                                    <li id="page_button_9" role="presentation" :class="{'active': page === 9, 'disabled': page !== 9}">
                                        <a @click="setPage(9)" href="javascript:" role="tab"><span class="round-tab">9</span><i>{{ text_health_items }}</i></a>
                                    </li>
                                    <li id="page_button_10" role="presentation" :class="{'active': page === 10, 'disabled': page !== 10}">
                                        <a @click="setPage(10)" href="javascript:" role="tab"><span class="round-tab">10</span><i>{{ text_price }}</i></a>
                                    </li>
                                    <li id="page_button_11" role="presentation" :class="{'active': page === 11, 'disabled': page !== 11}">
                                        <a @click="setPage(11)" href="javascript:" role="tab"><span class="round-tab">11</span><i>{{ text_off }}</i></a>
                                    </li>
                                    <li id="page_button_12" role="presentation" :class="{'active': page === 12, 'disabled': page !== 12}">
                                        <a @click="setPage(12)" href="javascript:" role="tab"><span class="round-tab">12</span><i>{{ text_safety }}</i></a>
                                    </li>
                                    <li id="page_button_13" role="presentation" :class="{'active': page === 13, 'disabled': page !== 13}">
                                        <a @click="setPage(13)" href="javascript:" role="tab"><span class="round-tab">13</span><i>{{ text_rule }}</i></a>
                                    </li>
                                    <li id="page_button_14" role="presentation" :class="{'active': page === 14, 'disabled': page !== 14}">
                                        <a @click="setPage(14)" href="javascript:" role="tab"><span class="round-tab">14</span><i>{{ text_cancel_rule_reserve }}</i></a>
                                    </li>
                                    <li id="page_button_15" role="presentation" :class="{'active': page === 15, 'disabled': page !== 15}">
                                        <a @click="setPage(15)" href="javascript:" role="tab"><span class="round-tab">15</span><i>{{ text_site_privacy }}</i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <create-home-step1 :hidden="page !== 1"
                                   :old="step_1_old"
                                   :validate_route="validate_route_step1"
                                   :province_route="province_route"
                                   :province_title="province_title"
                                   :city_title="city_title"
                                   :address_title="address_title"
                                   :city_place_holder="city_place_holder"
                                   :province_place_holder="province_place_holder"
                                   :select_label="select_label"
                                   :selected_label="selected_label"
                                   :deselect_label="deselect_label"
                                   :no_result_text="no_result_text"
                                   :no_options_text="no_options_text"
                ></create-home-step1>

                <create-home-step2 :hidden="page !== 2"
                                   :old="step_2_old"
                                   :validate_route="validate_route_step2"
                                   :select_label="select_label"
                                   :selected_label="selected_label"
                                   :deselect_label="deselect_label"
                                   :no_result_text="no_result_text"
                                   :no_options_text="no_options_text"
                ></create-home-step2>

                <create-home-step3 :hidden="page !== 3"
                                   :route_change_cover="change_cover_route"
                                   :route_change_image="change_images_route"
                                   :csrf="csrf"
                                   :validate_route="validate_route_step3"
                                   :cover="this.home.cover"
                                   :images="this.home.images"
                                   :max_size="max_size"
                ></create-home-step3>

                <create-home-step4 :hidden="page !== 4"
                                   :old="step_4_old"
                                   :validate_route="validate_route_step4"
                                   :name_title="name_title"
                                   :description_title="description_title"
                ></create-home-step4>

                <create-home-step5 :hidden="page !== 5"
                                   :old="step_5_old"
                                   :validate_route="validate_route_step5"
                                   :atmosphere_title="atmosphere_title"
                                   :type_title="type_title"
                                   :area_title="area_title"
                                   :atmospheres="atmospheres"
                                   :types="types"
                                   :areas="areas"

                                   :place_holder="option_place_holder"
                                   :options_title="options_title"
                                   :select_label="select_label"
                                   :selected_label="selected_label"
                                   :deselect_label="deselect_label"
                                   :no_result_text="no_result_text"
                                   :no_options_text="no_options_text"
                ></create-home-step5>

                <create-home-step6 :hidden="page !== 6"
                                   :old="step_6_old"
                                   :validate_route="validate_route_step6"
                                   :main_guest_title="main_guest_title"
                                   :extra_guest_title="extra_guest_title"
                                   :yard_title="yard_title"
                                   :infrastructure_title="infrastructure_title"
                ></create-home-step6>

                <create-home-step7 :hidden="page !== 7"
                                   :old="step_7_old"
                                   :validate_route="validate_route_step7"
                                   :count_sleep_rooms="count_sleep_rooms"
                ></create-home-step7>

                <create-home-step8 :hidden="page !== 8"
                                   :old="step_8_old"
                                   :validate_route="validate_route_step8"
                                   :values="options"
                ></create-home-step8>

                <create-home-step9 :hidden="page !== 9"
                                   :old="step_9_old"
                                   :validate_route="validate_route_step9"
                                   :values="healths"
                ></create-home-step9>

                <create-home-step10 :hidden="page !== 10"
                                   :old="step_10_old"
                                   :validate_route="validate_route_step10"
                                   :week_price_title="week_price_title"
                                   :thu_price_title="thu_price_title"
                                   :wed_price_title="wed_price_title"
                                   :fri_price_title="fri_price_title"
                                   :price_per_surplus_title="price_per_surplus_title"
                ></create-home-step10>

                <create-home-step11 :hidden="page !== 11"
                                   :old="step_11_old"
                                   :daily_off="daily_off"
                                   :validate_route="validate_route_step11"
                ></create-home-step11>

                <create-home-step12 :hidden="page !== 12"
                                   :old="step_12_old"
                                   :validate_route="validate_route_step12"
                                   :values="safeties"
                ></create-home-step12>

                <create-home-step13 :hidden="page !== 13"
                                   :old="step_13_old"
                                   :validate_route="validate_route_step13"
                                   :rules_title="rules_title"
                                   :inputs="variables"
                ></create-home-step13>

                <create-home-step14 :hidden="page !== 14"
                                   :old="step_14_old"
                                   :validate_route="validate_route_step14"
                                   :reject_policies="reject_policies"
                                   :easy_commission="reject_policy_easy_commission"
                                   :balanced_commission="reject_policy_balanced_commission"
                                   :strict_commission="reject_policy_strict_commission"
                ></create-home-step14>

                <div :hidden="page !== 15">
                    <div class="form-group">
                        <label for="document" class="form-label">مدرک مالکیت</label>
                        <input type="file" name="document" id="document" @change="documentChanges">
                        <p>تصویر مدرک مالکیت خود را بارگذاری کنید ( قولنامه ، اجاره نامه ، قبض آب یا برق یا گاز )</p>
                    </div>
                    <div class="form-group mt-3">
                        <label for="shab" class="form-label">حساب بانکی</label>
                        <input class="form-control" type="text" name="shaba" id="shab" v-model="shaba" placeholder="شماره شبای خود را بدون IR وارد کنید">
                    </div>
                    <div class="mb-4 mt-3" style="max-height: 200px; overflow-x: auto" v-html="site_policy"></div>
                    <input id="submit_accept_policy" type="checkbox" class="form-control-check" v-model="can_submit">
                    <label for="submit_accept_policy" class="form-control-label">قوانین وب سایت را خواندم و محتوای آن را می پذیرم</label>
                </div>
            </div>

            <div v-if="help" class="col-12 col-md-3 d-none d-md-block">
                <div class="card">
                    <div class="card-body">
                        {{ help }}
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center" style="position: fixed; bottom: 54px; right: 0; left: 50%; z-index: 11; width: 100%;">
            <div class="text-center" style="width: 100%; max-width: 360px">
                <div class="p-2 d-flex justify-content-between" style="background-color: rgba(0, 0, 0, 0.6)">
                    <template v-if="page !== 15">
                        <template v-if="page !== 1">
                            <a @click="prevPage" class="btn btn-danger text-light">{{ prev_text }}</a>
                        </template>
                        <span v-else></span>
                        <a @click="nextPage" :class="{'btn btn-success text-light': true, 'disabled': !is_active_next_page}">{{ next_text }}</a>
                    </template>
                    <template v-else>
                        <a @click="prevPage" class="btn btn-danger text-light">{{ prev_text }}</a>
                        <button type="submit" :class="{'btn btn-success text-light': true, 'disabled': !can_submit || (!shaba || (shaba && shaba.length !== 24)) || !has_document}">{{ submit_text }}</button>
                    </template>
                </div>
            </div>
        </div>

        <div v-if="help" class="d-block d-md-none" style="position: fixed; bottom: 62px; left: 44%; z-index: 11;">
            <button type="button" data-toggle="modal" data-target="#info_modal" class="btn-info" style="border-radius: 50%; width: 45px; height: 45px" title="راهنما"><i class="fa fa-2x fa-question-circle"></i></button>
        </div>
        <div v-if="help" class="modal fade" id="info_modal" tabindex="-1" role="dialog" aria-labelledby="info_modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        {{ help }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateHome",
    props: ['next_text', 'prev_text', 'submit_text', 'message_active_page', 'variables', 'text_map',
        'validate_route_step1', 'validate_route_step2', 'validate_route_step3', 'validate_route_step4',
        'validate_route_step5', 'validate_route_step6', 'validate_route_step7', 'validate_route_step8',
        'validate_route_step9', 'validate_route_step10', 'validate_route_step11', 'validate_route_step12',
        'validate_route_step13', 'validate_route_step14', 'site_policy', 'help_collection', 'daily_off',
        'atmosphere_title', 'type_title', 'area_title', 'atmospheres', 'types', 'areas', 'max_size',
        'province_route', 'province_title', 'city_title', 'address_title', 'city_place_holder', 'province_place_holder',
        'select_label', 'selected_label', 'deselect_label', 'no_result_text', 'no_options_text', 'wed_price_title',
        'description_title', 'yard_title', 'infrastructure_title', 'main_guest_title', 'extra_guest_title',
        'week_price_title', 'thu_price_title', 'fri_price_title', 'price_per_surplus_title', 'reject_policy_title',
        'options', 'options_title', 'option_place_holder', 'name_title', 'rules_title', 'reject_policies',
        'reject_policy_easy_commission', 'reject_policy_balanced_commission', 'reject_policy_strict_commission',
        'text_address', 'text_main_information', 'text_price', 'text_details', 'text_options', 'home',
        'text_images', 'text_residence', 'text_capacity', 'text_sleep_residence', 'text_health_items',
        'text_off', 'text_safety', 'text_rule', 'text_cancel_rule_reserve', 'text_extra_detail', 'text_site_privacy',
        'change_cover_route', 'csrf', 'text_about', 'count_sleep_rooms', 'healths', 'safeties', 'change_images_route'],
    data(){
        return {
            page: 1,
            active_page: 1,
            step_1_old: [],
            step_2_old: [],
            step_4_old: [],
            step_5_old: [],
            step_6_old: [],
            step_7_old: [],
            step_8_old: [],
            step_9_old: [],
            step_10_old: [],
            step_11_old: [],
            step_12_old: [],
            step_13_old: [],
            step_14_old: [],
            is_active_next_page: false,
            can_submit: false,
            has_document: null,
            shaba: null,
            help: ''
        }
    },
    created() {
        this.help = this.help_collection[1]

        this.step_1_old.province = this.home.province_id
        this.step_1_old.city = this.home.city_id
        this.step_1_old.address = this.home.address

        this.step_2_old.latitude = this.home.latitude
        this.step_2_old.longitude = this.home.longitude

        this.step_4_old.name = this.home.name
        this.step_4_old.description = this.home.description

        this.step_5_old.atmosphere = this.home.atmosphere
        this.step_5_old.type = this.home.type
        this.step_5_old.area = this.home.area

        this.step_6_old.main_guest = this.home.main_guest
        this.step_6_old.extra_guest = this.home.extra_guest
        this.step_6_old.yard = this.home.yard_meter
        this.step_6_old.infrastructure = this.home.infrastructure_meter

        this.step_7_old.rooms = this.home.sleep_places
        this.step_7_old.sleep_area_description = this.home.sleep_area_description

        this.step_8_old = this.home.options

        this.step_9_old.healths = this.home.healths
        this.step_9_old.more = this.home.more_health

        this.step_10_old.week_price = this.home.week_price
        this.step_10_old.wed_price = this.home.wed_price
        this.step_10_old.thu_price = this.home.thu_price
        this.step_10_old.fri_price = this.home.fri_price
        this.step_10_old.price_per_surplus = this.home.price_per_surplus

        this.step_11_old.off = this.home.off
        this.step_11_old.daily = this.home.daily_off
        this.step_11_old.daily_amount = this.home.daily_off_amount

        this.step_12_old.safeties = this.home.safeties
        this.step_12_old.more = this.home.more_safety

        this.step_13_old.rules = this.home.rules
        this.step_13_old.variables = this.home.variables

        this.step_14_old = this.home.reject_policy
    },
    mounted() {
        window.eventBus.$on('next_page', (page) => {
            this.active_page = page
        })
    },
    watch: {
        active_page: function (active_page) {
            this.checkPage()
        },
        page: function (page) {
            setTimeout(function (){
                document.getElementById('page-list').scrollTop = document.getElementById('page_button_' + page).offsetTop;
            }, 500)

            this.help = this.help_collection[page]

            this.checkPage()

            window.eventBus.$emit('page_updated', page)
        }
    },
    methods: {
        nextPage(){
            if (this.page !== 15 && this.is_active_next_page){
                this.page += 1
            }
        },
        prevPage(){
            if (this.page !== 1){
                this.page -= 1
            }
        },
        setPage(page){
            if (this.active_page >= page){
                this.page = page
            }
        },
        checkPage(){
            if (this.active_page > this.page){
                this.is_active_next_page = true
            }
            else {
                this.is_active_next_page = false
            }
        },
        documentChanges(e){
            this.has_document = e.target.files.length >= 1
        }
    }
}
</script>

<style scoped>

</style>
