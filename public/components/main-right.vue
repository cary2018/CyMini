<template>
    <div class="side fr" style="position: relative; overflow: visible; box-sizing: border-box; min-height: 0px;">
        <div class="theiaStickySidebar" style="padding-top: 0px; padding-bottom: 0px; position: static; top: 15px; left: 1027.21px;">
            <section v-if="urlPar[1] ==''" class="widget abautor">
                <div class="widget-list">
                    <div class="widget_avatar" style="background-image: url(https://img.t.sinajs.cn/t5/skin/public/profile_cover/015_s.jpg);">
                        <a href="/">
                            <img class="widget-about-image" src="/images/1.png" alt="admin" height="70" width="70">
                            <div class="widget-cover vip1"></div>
                            <i title="管理员" class="author-ident author1"></i>
                        </a>
                    </div>
                    <div class="widget-about-intro">
                        <div class="name">
                            <h3>admin</h3><span class="autlv aut-1 vs">V</span><span class="autlv aut-1">管理员</span>
                        </div>
                        <div class="widget-about-desc">文章 252 篇{{month}} <i>|</i> 评论 237 次</div>
                        <div class="widget-article-newest"><span>最新文章</span></div>
                        <div class="widget-article-newest"><span>随机推荐</span></div>
                        <ul class="widget-about-posts">
                            <li v-if="arRand.length" v-for="item in arRand">
                                <span class="widget-posts-meta">{{item.month}}/{{item.day}}</span>
                                <a class="widget-posts-title" :href="item.temp_archives+'?id='+item.id" :title="item.title">
                                    {{item.title}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
            <section v-if="urlPar[1] ==''" class="widget" id="divContorPanel">
                <h3 class="widget-title"><i class="fa fa-divContorPanel"></i><span>控制面板</span></h3>
                <div class="widget-box divContorPanel">
                    <span class="cp-hello">您好,欢迎到访本站!</span><br>
                    <!--<span class="cp-login"><a href="/">登录后台</a></span>
                    <span class="cp-vrs"><a href="/">查看权限</a></span>-->
                </div>
            </section>
            <section class="widget wow fadeInDown" id="divPrevious" style="visibility: visible; animation-name: fadeInDown;">
                <h3 class="widget-title"><i class="fa fa-divPrevious"></i><span>最近发表</span></h3>
                <ul class="widget-about-posts">
                    <li v-for="item in ArData">
                        <span class="widget-posts-meta">{{item.month}}/{{item.day}}</span>
                        <a class="widget-posts-title" :href="item.temp_archives+'?id='+item.id" :title="item.title">
                            {{item.title}}
                        </a>
                    </li>
                </ul>
            </section>
            <section v-if="feed.length" v-if="urlPar[1] !==''" class="widget" id="divComments">
                <h3 class="widget-title"><i class="fa fa-divComments"></i><span>最新评论</span></h3>
                <ul class="widget-box divComments">
                    <li class="text-one" v-if="feed" v-for="item in feed">
                        <a :href="'/index/detail?id='+item.aid" :title="item.msg">
                            {{item.msg}}
                        </a>
                    </li>
                </ul>
            </section>
            <section v-if="tags.length" class="widget wow fadeInDown" id="divTags" style="visibility: visible; animation-name: fadeInDown;">
                <h3 class="widget-title"><i class="fa fa-divTags"></i><span>标签列表</span></h3>
                <ul class="widget-box divTags">
                    <li v-for="item in tags" :class="'divTags'+item.rand">
                        <a :href="'/index/tags?tag='+item.tag" :title="item.tag">
                            {{item.tag}}
                            <span class="tag-count"> ({{item.count}})</span>
                        </a>
                    </li>
                </ul>
            </section>
            <section v-if="urlPar[1] ==''" class="widget wow fadeInDown" id="divLinkage" style="visibility: visible; animation-name: fadeInDown;">
                <h3 class="widget-title"><i class="fa fa-divLinkage"></i><span>友情链接</span></h3>
                <ul class="widget-box divLinkage">
                    <li v-for="item in link" :key="item.id">
                        <a :href="item.link" :target="item.target" :title="item.title">{{item.title}}</a>
                    </li>
                </ul>
            </section>
        </div>
    </div>
</template>

<script>
    module.exports = {
        data() {
            return {
                link:[],
                ArData:[],
                urlPar:[],
                tags:[],
                feed:[],
                arRand:[],
                month:new Date().getMonth() + 1,
                year:new Date().getFullYear(),
                pro:Math.floor(((new Date().getMonth() + 1)/12)*100),
            };
        },
        methods:{
            async linklist(){
                axios.get('/api/link').then(res=>{
                    this.link = res.data.data;
                });
            },
            async articles(){
                axios.get('/api/article/top',{
                    params:{
                        limit:8,
                        page:1,
                    }
                }).then(res=>{
                    this.ArData = res.data.data;
                });
            },
            async taglist(){
                axios.get('/api/article/taglist',{
                    params:{
                        limit:12,
                        page:1,
                    }
                }).then(res=>{
                    this.tags = res.data.data;
                });
            },
            async feedbacks(){
                axios.get('/api/feedback/datalist',{
                    params:{
                        limit:8,
                        page:1,
                        aid:'',
                        cate:'',
                    }
                }).then(res=>{
                    this.feed = res.data.data;
                });
            },
            async RandArticle(){
                axios.get('/api/article/arRand',{
                    params:{
                        limit:8
                    }
                }).then(res=>{
                    this.arRand = res.data.data;
                });
            },
        },
        mounted(){
            this.linklist();
            this.articles();
            this.taglist();
            this.feedbacks();
            this.RandArticle();
            let url = window.location.pathname;
            this.urlPar = url.split('/');
        },
        async created(){

        },
    };
</script>

<style scoped>
    .text-one{
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        font-weight: normal;
    }
</style>
