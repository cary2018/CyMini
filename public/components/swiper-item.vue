<template>
    <div style="max-height:360px;" class="swiper-slide">
        <slot></slot>
    </div>
</template>
<script>
    //import("/v1.0.0/js/banner/swiper-bundle.min.js");
    module.exports = {
        data() {
            return {

            }
        },
        methods:{
            swipeBanner(){
                new Swiper(".mySwiper", {
                    spaceBetween: 30,
                    height:360,
                    loop: true,//启动无循环
                    //启动自动切换
                    autoplay: {
                        delay: 2000,
                        disableOnInteraction: false,
                    },
                    //切换按钮
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    //点点
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                });
            }
        },
        async created(){

        },
        mounted(){
            this.swipeBanner();
        },
        updated(){

        },
    };
</script>
<style scoped>
    @import "/hyy1.0.0/frontend/css/swiper-bundle.min.css";
</style>