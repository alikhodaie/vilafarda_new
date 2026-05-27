<template>
    <div class="rent-discount mb-3">
        <label class="form-label small text-muted mb-1">کد تخفیف</label>
        <div class="input-group">
            <input
                v-model="discount_code"
                type="text"
                class="form-control"
                placeholder="کد تخفیف را وارد کنید"
                :disabled="discount_is_success"
            />
            <button
                type="button"
                class="btn btn-warning"
                :disabled="discount_is_success || !discount_code"
                @click="handleDiscount"
            >
                اعمال
            </button>
        </div>
        <p v-if="discount_is_success" class="text-success small mb-0 mt-2">کد تخفیف با موفقیت اعمال شد.</p>
        <p v-if="payable_price !== null" class="mb-0 mt-2">
            <span class="text-muted small">مبلغ قابل پرداخت:</span>
            <strong>{{ formatPrice(payable_price) }} تومان</strong>
        </p>
    </div>
</template>

<script>
export default {
    name: 'RentDiscount',
    props: {
        discount_route: { type: String, required: true },
        has_discount: { type: [Boolean, Number, String], default: false },
        original_price: { type: [Number, String], required: true },
        discount_amount: { type: [Number, String], default: 0 },
    },
    data() {
        return {
            discount_code: '',
            discount_is_success: false,
            payable_price: null,
        };
    },
    created() {
        this.discount_is_success = !!this.has_discount;
        this.payable_price = Math.max(0, Number(this.original_price) - Number(this.discount_amount));
    },
    methods: {
        formatPrice(value) {
            return Number(value || 0).toLocaleString('fa-IR');
        },
        handleDiscount() {
            window.eventBus.$emit('show_loader', true);

            axios.post(this.discount_route, { code: this.discount_code })
                .then((response) => {
                    this.discount_is_success = true;
                    if (response.data?.payable_price !== undefined) {
                        this.payable_price = response.data.payable_price;
                    }
                    window.location.reload();
                })
                .catch((error) => {
                    let message = '';
                    this.$root.formatErrors(error).forEach((item) => {
                        message += item + '\n';
                    });
                    this.$root.showAlert(message, 'error', true);
                })
                .finally(() => {
                    window.eventBus.$emit('hide_loader', true);
                });
        },
    },
};
</script>
