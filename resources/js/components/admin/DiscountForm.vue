<template>
    <div class="col-12 row">
        <div class="col-12 mb-3">
            <label class="form-label" for="expired_at">{{ title_expired_at }} <span>*</span></label>
            <date-picker
                :min="min_expired_at"
                type="date"
                id="expired_at"
                format="YYYY-MM-DD"
                display-format="jYYYY-jMM-jDD"
                v-model="expired_at"
            ></date-picker>
            <input type="hidden" name="expired_at" v-model="expired_at"/>
        </div>

        <div class="col-12 col-md-6 mb-3">
            <label class="form-label" for="type">{{ title_type }} <span>*</span></label>
            <select class="form-control" name="type" id="type" v-model="type">
                <option v-for="item in types" :value="item.value">{{ item.fa_text }}</option>
            </select>
        </div>

        <div class="col-12 col-md-6 mb-3">
            <label class="form-label" for="amount">{{ title_amount }} <span>*</span></label>
            <input class="form-control" name="amount" id="amount" type="number" v-model="amount" :min="min_amount" :max="max_amount"/>
        </div>

        <div v-if="!is_edit" class="col-12 mb-3">
            <label class="form-label" for="user_type">{{ title_user_type }} <span>*</span></label>
            <select class="form-control" name="user_type" id="user_type" v-model="user_type">
                <option v-for="user_type in user_types" :value="user_type.value">{{ user_type.fa_text }}</option>
            </select>
        </div>

        <template v-if="!is_edit && user_type === 'old_users'">
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="users">{{ title_users }} <span>*</span></label>
                <select class="form-control" name="users" id="users" v-model="users">
                    <option value="all">تمام کاربران</option>
                    <option value="has_orders">کاربرانی که سفارش ثبت کردند</option>
                    <option value="has_not_orders">کاربرانی که سفارش ثبت نکردند</option>
                    <option value="owners">میزبان‌ها</option>
                    <option value="selected">کاربران انتخابی</option>
                </select>
            </div>
            <div v-if="users === 'selected'" class="col-12 col-md-6 mb-3">
                <label class="form-label" for="users_list">{{ title_users_list }} <span>*</span></label>
                <user-input
                    :old="users_list_old"
                    name="users_list"
                    :route="users_route"
                    v-model="users_list"
                    :placeholder="placeholder"
                    :select_label="select_label"
                    :selected_label="selected_label"
                    :deselect_label="deselect_label"
                    :no_result_text="no_result_text"
                    :no_options_text="no_options_text"
                    :multiple="true"
                    >
                </user-input>
            </div>
            <div v-if="users === 'has_orders'" class="col-12 col-md-6 mb-3 row">
                <div class="col-12 col-md-6">
                    <label class="form-label" for="start_date">{{ title_start_date }} <span>*</span></label>
                    <div class="d-flex justify-content-center">
                        <date-picker
                            class="w-100"
                            :max="max_start_date"
                            type="date"
                            id="start_date"
                            v-model="start_date"
                            display-format="jYYYY-jMM-jDD"
                            format="YYYY-MM-DD"
                        ></date-picker>
                        <input type="hidden" name="start_date" v-model="start_date"/>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="end_date">{{ title_end_date }} <span>*</span></label>
                    <div class="d-flex justify-content-center pl-5 pl-md-0">
                        <date-picker
                            class="w-100"
                            :min="min_end_date"
                            :max="max_end_date"
                            type="date"
                            id="end_date"
                            v-model="end_date"
                            display-format="jYYYY-jMM-jDD"
                            format="YYYY-MM-DD"
                        ></date-picker>
                        <input type="hidden" name="end_date" v-model="end_date"/>
                    </div>
                </div>
            </div>
        </template>

        <template v-if="!is_edit">
            <div class="col-12 mb-3">
                <label class="form-label" for="sms_type">{{ title_sms_type }}</label>
                <select class="form-control" name="sms_type" id="sms_type" v-model="sms_type">
                    <option value="">هیچکدام</option>
                    <option value="pattern">الگو</option>
                    <option value="custom">کاستوم</option>
                </select>
            </div>
            <div v-if="sms_type" class="col-12 mb-3">
                <label class="form-label" for="sms">{{ title_sms }} <span>*</span></label>
                <textarea v-if="sms_type === 'custom'" placeholder="متن پیامک" class="form-control" name="sms" id="sms_type" v-model="sms"></textarea>
                <input v-if="sms_type === 'pattern'" placeholder="کد الگو" class="form-control" name="sms" id="sms_type" v-model="sms"/>
            </div>
        </template>
    </div>
</template>

<script>
import moment from "moment";

export default {
    name: "DiscountForm",
    props: ['title_sms', 'title_sms_type', 'title_expired_at', 'title_type', 'title_user_type', 'title_amount', 'title_users', 'title_users_list', 'title_start_date', 'title_end_date',
        'expired_at_old', 'type_old', 'user_type_old', 'amount_old', 'users_old', 'users_list_old', 'start_date_old', 'end_date_old', 'sms_type_old', 'sms_old',
        'users_route',
        'placeholder', 'select_label', 'selected_label', 'deselect_label', 'no_result_text', 'no_options_text',
        'user_types', 'types',
        'is_edit',
    ],
    data() {
        return {
            expired_at: '',
            type: '',
            user_type: '',
            amount: '',
            users: '',
            users_list: [],
            start_date: '',
            end_date: '',
            sms: '',
            sms_type: '',
        }
    },
    watch: {
        start_date: function (date) {
            this.end_date = '';
        },
    },
    computed: {
        max_amount() {
            if (this.type === 'percent') {
                return 99;
            }

            return 100000000;
        },
        min_expired_at() {
            return moment().format('YYYY-MM-DD');
        },
        min_amount() {
            return 1;
        },
        min_end_date() {
            if (this.start_date) {
                return this.start_date;
            }

            return moment().format('YYYY-MM-DD');
        },
        max_end_date() {
            return moment().format('YYYY-MM-DD');
        },
        max_start_date() {
            return moment().format('YYYY-MM-DD');
        },
    },
    mounted() {
        this.expired_at = this.expired_at_old ? moment(this.expired_at_old).utc().format('YYYY-MM-DD'): moment().utc().format('YYYY-MM-DD');
        this.type = this.type_old ?? '';
        this.user_type = this.user_type_old ?? '';
        this.amount = this.amount_old ?? '';
        this.users = this.users_old ?? '';
        this.users_list = this.users_list_old ?? [];
        this.start_date = this.start_date_old ?? '';
        this.end_date = this.end_date_old ?? '';
        this.sms_type = this.sms_type_old ?? '';
        this.sms = this.sms_old ?? '';
    },
}
</script>

<style>
.vpd-input-group input {
    width: 100%;
}

.vpd-icon-btn {
    margin-bottom: 0;
}
</style>
