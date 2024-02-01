<template>
    <div class="swiper mySwiper" >
        <div class="swiper-wrapper" >
            <slot></slot>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
</template>
<script>
    module.exports = {
        data() {
            return {
                banner:[], //广告图
            };
        },
        methods:{

        },
        async created(){

        },
        mounted(){
            //广告图
        },
        updated(){

        },
    };
</script>
<style scoped>

</style>