<template>
    <div>
        <div class="main fl">
            <article class="post single-post">
                <header class="entry-header page-header">
                    <h6 class="place clearfix">
                        <span class="nat_tit">正文</span>
                        <div id="font-change" class="single-font fr">
                            <span id="font-dec"><a href="#" title="减小字体"><i class="fa fa-minus-square-o"></i></a></span>
                            <span id="font-int"><a href="#" title="默认字体"><i class="fa fa-font"></i></a></span>
                            <span id="font-inc"><a href="#" title="增大字体"><i class="fa fa-plus-square-o"></i></a></span>
                        </div>
                    </h6>
                    <h1 class="entry-title page-title">{{detail.title}}</h1>
                    <div class="contimg">
                        <a href="/">
                            <img alt="admin" src="/images/1.png" class="avatar" height="35" width="35">
                        </a>
                    </div>
                    <div class="entry-meta contpost-meta">
                        <a href="javascript;">{{detail.author}}
                            <span class="autlv aut-1 vs">V</span><span class="autlv aut-1">管理员</span>
                        </a>
                        <time datetime="detail.updateTime">
                            <span class="separator">/</span>
                            {{detail.updateTime}}
                        </time>
                        <a href="#comments">
                            <span class="separator">/</span>
                            {{feed.total}} 评论
                        </a>
                        <span class="entry-meta-viewnums">
                            <span class="separator">/</span>
                            {{detail.views}} 阅读
                        </span>
                    </div>
                    <div class="postArticle-meta">
                        <span class="entry-meta-time">
                            <span class="month">{{detail.month}}</span>
                            <span class="day">{{detail.day}}</span>
                        </span>
                    </div>
                    <div class="border-theme"></div>
                </header>
                <div id="post-category" ref="MyContent" class="show_text post-category" style="overflow:hidden;">
                    <div v-if="detail.content" v-html="detail.content" class="entry-content clearfix"></div>
                    <div v-else class="entry-content clearfix">
                        <p></p>
                    </div>
                    <div class="gaoyuan yc"></div>
                </div>
                <footer class="entry-footer">
                    <div class="post-tags">
                        <a v-for="item in detail.tags" :href="'/index/tags?tag='+item" rel="tag" :title="item">{{item}}</a>
                    </div>
                    <div class="readlist ds-reward-stl">
                        <div class="read_outer">
                            <a class="read" href="javascript:;" title="阅读模式">
                                <i class="fa fa-send"></i> 阅读
                            </a>
                        </div>
                        <!--<div class="read_outer">
                            <a class="comiis_poster_a" href="javascript:;" title="生成封面">
                                <i class="fa fa-image"></i> 海报
                            </a>
                        </div>-->
                    </div>
                </footer>
            </article>
            <nav class="single-nav">
                <div v-if="dataPrev.title" class="entry-page-prev j-lazy" :style="'background-image: url(/'+dataPrev.articleThumbImg+')'">
                    <a :href="dataPrev.cate.temp_archives+'?id='+dataPrev.id" :title="dataPrev.title">
                        <span>{{dataPrev.title}}</span>
                    </a>
                    <div class="entry-page-info">
                        <span class="pull-left">« 上一篇</span>
                        <span class="pull-right">{{dataPrev.updateTime}}</span>
                    </div>
                </div>
                <div v-if="dataNext.title" class="entry-page-next j-lazy" :style="'background-image: url(/'+dataNext.articleThumbImg+')'">
                    <a :href="dataNext.cate.temp_archives+'?id='+dataNext.id" :title="dataNext.title">
                        <span>{{dataNext.title}}</span>
                    </a>
                    <div class="entry-page-info">
                        <span class="pull-right">下一篇 »</span>
                        <span class="pull-left">{{dataNext.updateTime}}</span>
                    </div>
                </div>
            </nav>
            <div class="part-mor">
                <!--相关文章-->
                <h3 class="section-title"><span><i class="fa fa-rss-square"></i>相关阅读</span></h3>
                <ul class="section-cont-tags pic-box-list clearfix">
                    <!--相关标签-->
                    <li v-for="item in relate" :key="item.id">
                        <a :href="item.temp_archives+'?id='+item.id">
                            <i class="pic-thumb">
                                <img class="lazy" :src="'/'+item.articleThumbImg" :alt="item.title" :title="item.title" :original="'/'+item.articleThumbImg">
                            </i>
                            <h3>{{item.title}}</h3>
                            <p><b class="datetime">{{item.updateTime}}</b><span class="viewd">{{item.views}} 人在看</span></p>
                        </a>
                    </li>
                </ul>
            </div>
            <section id="comments">
                <div id="comt-respond" class="commentpost wow fadeInDown" style="visibility: hidden; animation-name: none;">
                    <h4>发表评论<span><a rel="nofollow" id="cancel-reply" href="#comment" style="display:none;"><small>取消回复</small></a></span></h4>
                    <form ref="FData" id="frmSumbit" target="_self" method="post" action="" onsubmit="return false;">
                        <input type="hidden" name="aid" id="inpId" :value="aid">
                        <input type="hidden" name="rid" id="inpRevID" value="0">
                        <input type="hidden" name="cid" :value="detail.cid">
                        <input type="hidden" name="article" :value="detail.title">
                        <input type="hidden" :value="MData.__token__" name="__token__" id="inpRevID" >
                        <div class="comt-box">
                            <div class="form-group liuyan form-name"><input type="text" name="username" id="inpName" class="text" :value="'访客'+FkId" placeholder="昵称" size="28" tabindex="1"></div>
                            <div class="form-group liuyan form-email"><input type="text" name="email" id="inpEmail" class="text" value="" placeholder="邮箱" size="28" tabindex="2"></div>
                            <div class="form-group liuyan form-www">
                                <input type="text" name="captcha" style="width:50%;float:left;" id="inpHomePage" class="text" value="" placeholder="验证码" size="28" tabindex="3">
                                <img id="refreshCaptcha" style="max-height:unset;" class="validateImg captcha" v-if="captchaT" :src="captchaT" @click="upCap()">
                            </div>
                        </div>
                        <!--verify-->
                        <div id="comment-tools">
                            <div class="tools_text">
                                <textarea placeholder="请遵守相关法律与法规，文明评论。O(∩_∩)O~~" name="msg" id="txaArticle" class="text input-block-level comt-area" cols="50" rows="4" tabindex="5"></textarea>
                            </div>
                        </div>
                        <div class="psumbit">
                            <div class="tools_title">
                                <span class="com-title com-reply">快捷回复：</span>
                                <a class="psumbit-kjhf" href="javascript:addNumber(&#39;文章不错,写的很好！&#39;)" title="文章不错,写的很好！"><i class="fa fa-thumbs-o-up"></i></a>
                                <a class="psumbit-kjhf" href="javascript:addNumber(&#39;emmmmm。。看不懂怎么破？&#39;)" title="emmmmm。。看不懂怎么破？"><i class="fa fa-thumbs-o-down"></i></a>
                                <a class="psumbit-kjhf" href="javascript:addNumber(&#39;赞、狂赞、超赞、不得不赞、史上最赞！&#39;)" title="赞、狂赞、超赞、不得不赞、史上最赞！"><i class="fa fa-heart"></i></a>
                                <!--<span class="com-title">表情：</span>
                                <a href="javascript:;" class="face-show">
                                    <i class="fa fa-smile-o"></i>
                                </a>-->
                                <div id="ComtoolsFrame" class="ComtoolsFrame" style="display: none;"></div>
                            </div>
                            <input name="sumbit" type="submit" tabindex="6" value="提交" @click="submitForm()" class="button">
                        </div>
                    </form>
                </div>
                <div class="commentlist">
                    <!--评论输出-->
                    <div class="comment-tab">
                        <div class="come-comt">
                            评论列表 <span id="comment_count">（有 <span style="color:#E1171B">{{feed.total}}</span> 条评论，
                            <span style="color:#E1171B">{{detail.views}}</span>人围观）</span></div>
                    </div>
                    <label id="AjaxCommentBegin"></label>
                    <!--评论输出结束-->
                    <div v-if="feed.data" v-for="(item,index) in feed.data" class="shadow-box msg noimg wow fadeInRight" data-wow-delay="0.25s" :id="'cmt'+item.id" style="visibility: hidden; animation-delay: 0.25s; animation-name: none;">
                        <div class="msgimg">
                            <img class="avatar" src="/images/1.png" alt="网友昵称：colorgg" title="网友昵称：colorgg">
                        </div>
                        <div class="msgtxt">
                            <div class="msgname">
                                <a href="javascript:;" rel="nofollow" >{{item.username}}</a>
                                <span class="autlv aut-6 vs">V</span>
                                <span class="autlv autlvname aut-6">游客</span>
                                <a v-if="item.rid" class="comment_at" href="javascript:;">@{{item.rep}}</a>
                                <!--<span class="dot">{{item.id}}</span>-->
                            </div>
                            <div class="interact-bar">
                                <span class="interact-time" :title="'评论时间：'+item.date">{{item.createTime}}</span>
                                <span class="spot"></span>
                                <a href="#reply" @click="clzbp(item.id)" class="comment-reply-link">回复</a>
                            </div>
                            <div class="msgarticle">
                                {{item.msg}}
                                <label :id="'AjaxComment'+item.id"></label>
                            </div>
                        </div>
                    </div>

                    <div id="com_pagination" class="pagination wow fadeInDown" style="visibility: hidden; animation-name: none;">
                        <!--评论翻页条输出-->
                        <el-pagination
                                background
                                :page-size="limit"
                                @current-change="handleCurrentChange"
                                layout="total,prev, pager, next,jumper"
                                :total="count">
                        </el-pagination>
                    </div><label id="AjaxCommentEnd"></label>
                    <!--评论翻页条输出结束-->
                </div> <span class="icon icon_comment" title="comment"></span>
            </section>
            <a v-if="dataPrev.title" :href="dataPrev.cate.temp_archives+'?id='+dataPrev.id" class="prev-article" :title="dataPrev.title">
                <i class="fa fa fa-angle-left"></i>
            </a>
            <a v-if="dataNext.title" :href="dataNext.cate.temp_archives+'?id='+dataNext.id" class="next-article" :title="dataNext.title">
                <i class="fa fa fa-angle-right"></i>
            </a>
        </div>
    </div>
</template>

<script>
    module.exports = {
        props:{
            token:{
                type:String,
                default:"",
            },
            captcha:{
                type:String,
                default:"",
            },
        },
        data() {
            return {
                captchaT:this.captcha,
                uploadUrl: '/api/feedback/saveAt',
                detail:[],
                relate:[],
                dataPrev:[],
                dataNext:[],
                MData:{__token__:this.token},
                aid:0,
                feed:[],
                limit:10,
                page:0,
                count:0,
                FkId:Math.floor(Math.random().toFixed(5)*90000)+10000,
            };
        },
        methods:{
            submitForm(){
                const form = this.$refs.FData;
                const formData = new FormData(form);
                // 将转换为普通对象
                const obj = {};
                formData.forEach((value, key) => {
                    obj[key] = value;
                });
                if(!obj.captcha){
                    this.$message.error('验证码不能为空！');
                    return;
                }
                if(!obj.msg){
                    this.$message.error('评论内容不能为空！');
                    return;
                }
                if(obj.email){
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    const isValid = emailRegex.test(obj.email);
                    if(!isValid){
                        this.$message.error('邮箱地址不正确！');
                        return;
                    }
                }
                //执行上传操作
                axios({
                    headers: { 'Content-Type': 'multipart/form-data' },
                    method: 'POST',
                    url: this.uploadUrl,   //填写自己的接口
                    data:formData,        //填写包装好的formData对象
                }).then(res => {
                    const result = JSON.parse(res.data);
                    if (result.code == 200) {
                        this.$message.success(result.message);
                        setTimeout( () => {
                            //更新token
                            this.MData.__token__ = result.token;
                            //更新验证码
                            this.upCap();
                            //重置表单
                            this.$refs.FData.reset();
                        },500)//2秒后执行
                    } else {
                        //更新验证码
                        this.upCap();
                        this.$message.error(result.message);
                    }
                })
            },
            async upCap(){
                this.captchaT = this.captchaT.replace(/\?.*$/, '')+'?'+Math.random();
            },
            handleCurrentChange(val) {
                this.page = val;
                this.feedbacks();
                //console.log(`当前页: ${val}`);
            },
            async feedbacks(){
                axios.get('/api/feedback',{
                    params:{
                        limit:this.limit,
                        page:this.page,
                        aid:this.aid,
                        cate:'',
                    }
                }).then(res=>{
                    this.feed = res.data;
                    this.count = res.data.total;
                    //console.log(res.data.data);
                });
            },
            async getDetail(){
                const searchParams = new URLSearchParams(window.location.search);
                const Id = searchParams.get('id');
                this.aid = Id;
                axios.get('/api/detail',{
                    params:{
                        id:Id
                    }
                }).then(res=>{
                    this.detail = res.data;
                    this.related(res.data.cid);
                    this.prev(res.data.id,res.data.cid);
                    this.next(res.data.id,res.data.cid);
                });
            },
            //相关阅读
            async related(id){
                axios.get('/api/article',{
                    params:{
                        limit:8,
                        page:1,
                        id:id,
                    }
                }).then(res=>{
                    this.relate = res.data.data;
                });
            },
            //上一页
            async prev(id,cid){
                axios.get('/api/detail/prev',{
                    params:{
                        id:id,
                        cid:cid,
                    }
                }).then(res=>{
                    this.dataPrev = res.data;
                });
            },
            //下一页
            async next(id,cid){
                axios.get('/api/detail/next',{
                    params:{
                        id:id,
                        cid:cid,
                    }
                }).then(res=>{
                    this.dataNext = res.data;
                });
            },
            clzbp(redid){
                var zbpConfig = {
                    comment: {
                        useDefaultEvents: true,
                    }
                };
                var zbp = new ZBP(zbpConfig);
                zbp.comment.reply(redid);
                //console.log(redid);
            },
            loadScripts(scriptUrls, callback) {
                let loadedScripts = 0;
                const checkAllScriptsLoaded = () => {
                    loadedScripts++;
                    if (loadedScripts === scriptUrls.length) {
                        callback();
                    }
                };
                scriptUrls.forEach((url) => {
                    const script = document.createElement('script');
                    script.src = url;
                    script.onload = checkAllScriptsLoaded;
                    document.body.appendChild(script);
                });
            },
        },
        mounted(){
            this.getDetail();
            this.feedbacks();
            this.loadScripts(['/hyy1.0.0/frontend/js/fancybox.js'], () => {
                this.upCap();
                this.$nextTick(() => {
                    const divElement = this.$refs.MyContent;
                    if(divElement){
                        const images = divElement.getElementsByTagName('img');
                        for (let i = 0; i < images.length; i++) {
                            const image = images[i];
                            if(image){
                                image.setAttribute('data-fancybox', 'gallery');
                            }
                        }
                    }
                });
            });
        },
        async created(){

        },
    };
</script>
<style scoped>
    @import "/hyy1.0.0/frontend/css/fancybox.css";
    .captcha{
        width: 50%;
        line-height: 44px;
        height: 44px;
        float: left;
        max-height: unset;
    }
</style>