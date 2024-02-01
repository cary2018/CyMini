<template>
    <nav class="navcates place">
        当前位置：<i class="fa fa-home"></i>
        <a href="/">首页</a>
        <i class="fa fa-angle-right"></i>
        <span v-for="(item,index) in navMenu" :key="item.id">
            <a :href="item.isUrl?item.outUrl:item.temp_list+'?id='+item.id" :target="item.target" :title="item.name">{{item.name}}</a>
            <span v-if="navMenu.length > (index+1)">
                <i class="fa fa-angle-right"></i>
            </span>

        </span>
    </nav>
</template>
<script>
    module.exports = {
        data(){
            return {
                navMenu:[],
                Id:'',
            }
        },
        methods:{
            async dataMenu(){
                // 获取URL参数
                const searchParams = new URLSearchParams(window.location.search);
                this.Id = searchParams.get('id');
                axios.get('/api/bread',{
                    params:{
                        id:this.Id,
                    }
                }).then(res=>{
                    this.navMenu = res.data.data;
                });
            },
        },
        mounted(){
            this.dataMenu();
        },
    }
</script>