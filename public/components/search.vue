<template>
    <div>
        <div class="main fl auto-multi">
            <div id="pjaxmain">
                <div class="home-main auto-main">
                    <!--页面主体信息-->
                    <article v-if="arList.length" v-for="(item,index) in arList" :key="item.id" class="post-list blockimg auto-list wow fadeInDown" :id="'list'+index" style="visibility: visible; animation-name: fadeInDown;">
                        <div class="entry-container">
                            <div class="block-image feaimg">
                                <a class="block-fea" :href="item.temp_archives+'?id='+item.id" :title="item.title">
                                    <img class="lazy" :src="'/'+item.articleThumbImg" :alt="item.keywords" :title="item.title" :original="'/'+item.articleThumbImg">
                                </a>
                                <div class="entyr-icon"><i class="fa fa-image"></i></div>
                            </div>
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <a :title="item.title" :href="item.temp_archives+'?id='+item.id">
                                        <span class="badge arc_v4">热文</span>
                                        {{item.title}}
                                    </a>
                                </h2>
                            </header>
                            <div class="entry-summary ss">
                                <p>{{item.description}}</p>
                            </div>
                            <div class="entry-meta fea-meta">
                                <a class="meta-cate" :href="item.temp_list+'?id='+item.cid" :target="item.target">
                                    <span class="separator"></span>
                                    {{item.name}}
                                </a>
                                <time :datetime="item.updateTime">
                                    <span class="separator">/</span>{{item.updateTime}}
                                </time>
                                <a class="meta-viewnums" :href="item.temp_archives+'?id='+item.id">
                                    <span class="separator">/</span>
                                    {{item.feed}} 评论
                                </a>
                                <span class="meta-viewnums">
                                    <span class="separator">/</span>
                                    {{item.views}} 阅读
                                </span>
                            </div>
                        </div>
                    </article>
                </div>
                <footer class="pagination catpage wow fadeInDown" style="visibility: hidden; animation-name: none;">
                    <el-pagination
                            background
                            :page-size="limit"
                            @current-change="handleCurrentChange"
                            layout="total,prev, pager, next,jumper"
                            :total="count">
                    </el-pagination>
                </footer>
            </div>
        </div>
    </div>
</template>
<script>
    module.exports = {
        data() {
            return {
                arList:[],
                limit:10,
                page:1,
                count:0,
            };
        },
        methods:{
            handleCurrentChange(val) {
                this.page = val;
                this.articleList();
                //console.log(`当前页: ${val}`);
            },
            async articleList(){
                const searchParams = new URLSearchParams(window.location.search);
                const searchKey = searchParams.get('q');
                axios.get('/api/article/search',{
                    params:{
                        limit:this.limit,
                        page:this.page,
                        q:searchKey,
                    }
                }).then(res=>{
                    this.arList = res.data.data;
                    this.count = res.data.count;
                });
            },
        },
        mounted(){
            this.articleList();
        },
        async created(){

        },
    };
</script>