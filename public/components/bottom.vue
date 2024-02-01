<template>
    <div>
        <div class="pic-list clearfix">
            <!--图文模块-->
            <div class="pic-box box-shadow wow fadeInDown" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInDown;">
                <section class="two-box-tit">
                    <h3>{{arList.name}}</h3>
                    <span class="suburl">
                        <a :href="arList.isUrl?arList.outUrl:arList.temp_list+'?id='+arList.id" :target="arList.target">
                            更多<i class="fa fa-plus-circle"></i>
                        </a>
                    </span>
                </section>
                <ul class="pic-box-list">
                    <li v-for="item in arList.list">
                        <a :href="arList.temp_archives+'?id='+item.id">
                            <i class="pic-thumb">
                                <img class="lazy" :src="'/'+item.articleThumbImg" :key="item.id" :alt="item.keywords" title="item.title" :original="'/'+item.articleThumbImg" style="display: block;">
                            </i>
                            <h3>{{item.title}}</h3>
                            <p>
                                <b class="datetime">{{item.updateTime}}</b>
                                <span class="viewd">{{item.views}} 人在看</span>
                            </p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="two-list clearfix">
            <!--图文专栏-->
            <div class="two-box box-shadow wow fadeInDown" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInDown;">
                <section class="two-box-tit">
                    <h3>{{arListL.name}}</h3>
                    <span class="suburl">
                        <a :href="arListL.isUrl?arListL.outUrl:arListL.temp_list+'?id='+arListL.id" :target="arListL.target">
                            更多<i class="fa fa-plus-circle"></i>
                        </a>
                    </span>
                </section>
                <div class="two-list-img">
                    <a class="figure-thumb" :href="arListL.temp_archives+'?id='+arListL.list[0].id" v-if="arListL.list">
                        <i class="two-thumb" >
                            <img class="lazy" :src="'/'+arListL.list[0].articleThumbImg" :alt="'/'+arListL.list[0].title" :title="'/'+arListL.list[0].title" :original="'/'+arListL.list[0].articleThumbImg" style="display: block;">
                        </i>
                    </a>
                    <ul class="two-box-list">
                        <li v-for="fl in arListL.list" :key="fl.id" class="two-list-title">
                            <span class="two-list-date">{{fl.dateTime}}</span>
                            <a :href="arListL.temp_archives+'?id='+fl.id" rel="bookmark" :title="fl.title">
                                {{fl.title}}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="two-box box-shadow wow fadeInDown" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInDown;">
                <section class="two-box-tit">
                    <h3>{{arListR.name}}</h3>
                    <span class="suburl">
                        <a :href="arListR.isUrl?arListR.outUrl:arListR.temp_list+'?id='+arListR.id" :target="arListR.target">
                            更多<i class="fa fa-plus-circle"></i>
                        </a>
                    </span>
                </section>
                <div class="two-list-img">
                    <a class="figure-thumb" :href="arListR.temp_archives+'?id='+arListR.list[0].id" v-if="arListR.list">
                        <i class="two-thumb" >
                            <img class="lazy" :src="'/'+arListR.list[0].articleThumbImg" :alt="arListR.list[0].title" :title="arListR.list[0].title" :original="'/'+arListR.list[0].articleThumbImg" style="display: block;">
                        </i>
                    </a>
                    <ul class="two-box-list">
                        <li v-for="fr in arListR.list" :key="fr.id" class="two-list-title">
                            <span class="two-list-date">{{fr.dateTime}}</span>
                            <a :href="arListR.temp_archives+'?id='+fr.id" rel="bookmark" :title="fr.title">
                                {{fr.title}}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    module.exports = {
        data(){
            return {
                arList:[],
                arListL:[],
                arListR:[],
            }
        },
        methods:{
            async articleList(){
                // 从api获取数据
                axios.get('/api/article/datalist',{
                    params:{
                        limit:4,
                        page:1,
                        cate:'2',
                    }
                }).then(res=>{
                    this.arList = res.data.data;
                });
            },
            async list2(){
                // 从api获取数据
                axios.get('/api/article/datalist',{
                    params:{
                        limit:5,
                        page:1,
                        cate:'3',
                    }
                }).then(res=>{
                    this.arListL = res.data.data;
                });
            },
            async list3(){
                // 从api获取数据
                axios.get('/api/article/datalist',{
                    params:{
                        limit:5,
                        page:1,
                        cate:'4',
                    }
                }).then(res=>{
                    this.arListR = res.data.data;
                });
            },
        },
        mounted(){
            this.articleList();
            this.list2();
            this.list3();
        },
    }
</script>
<style scoped>

</style>