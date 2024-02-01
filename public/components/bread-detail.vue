<template>
    <nav class="navcates place">
        当前位置：<i class="fa fa-home"></i>
        <a href="/">首页</a>
        <i class="fa fa-angle-right"></i>
        <span v-for="(item,index) in navMenu.data" :key="item.id">
            <a :href="item.isUrl?item.outUrl:item.temp_list+'?id='+item.id" :target="item.target" :title="item.name">{{item.name}}</a>
            <span v-if="navMenu.data.length > (index+1)">
                <i class="fa fa-angle-right"></i>
            </span>
        </span>
        <i class="fa fa-angle-right"></i>
        <a v-if="navMenu.cate" :href="navMenu.cate.temp_archives+'?id='+navMenu.article.id" :target="navMenu.cate.target" rel="bookmark" :title="navMenu.article.title">{{navMenu.article.title}}</a>
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
                axios.get('/api/bread/breadDetail',{
                    params:{
                        id:this.Id,
                    }
                }).then(res=>{
                    this.navMenu = res.data;
                });
            },
        },
        mounted(){
            this.dataMenu();
        },
    }
</script>