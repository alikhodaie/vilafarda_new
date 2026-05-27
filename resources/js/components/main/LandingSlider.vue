<script>
import Pagination from 'swiper/components/pagination';
import Autoplay from 'swiper/components/autoplay';
import { SwiperCore, Swiper, SwiperSlide } from 'swiper-vue2'

import 'swiper/swiper-bundle.css'

SwiperCore.use([Pagination, Autoplay])

export default {
    name: 'LandingSlider',
    components: {
        Swiper,
        SwiperSlide
    },
    props: ['items', 'per_view'],
};
</script>

<template>
    <div>
        <swiper
            :slides-per-view="per_view"
            :loop="true"
            :pagination="true"
            :autoplay="true"
            :grabCursor="true"
            :centeredSlides="true"
        >
            <swiper-slide
                v-for="(item, index) in items"
                :key="index"
            >
                <a :href="item.link">
                    <img :src="item.image" alt="" class="img-fluid" style="max-width: 100%; border-radius: 10px">
                </a>
            </swiper-slide>
        </swiper>
    </div>
</template>

<style scoped>

</style>
