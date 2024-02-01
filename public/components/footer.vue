<!--置顶-->
<template>
    <div>
        <footer class="site-footer footer">
            <div class="site-info clearfix">
                <div class="container">
                    <div class="footer-left">
                        <!--底部左边-->
                        <div class="footer-l-top clearfix">
                            <!--<a rel="nofollow" href="/" target="_blank">底部导航1</a>
                            <a rel="nofollow" href="/" target="_blank">底部导航2</a>
                            <a rel="nofollow" href="/" target="_blank">底部导航3</a>
                            <a rel="nofollow" href="/" target="_blank">底部导航4</a>-->
                            {{webInfo.web_footer_title}}
                        </div>
                        <div class="footer-l-btm">
                            <p class="top-text" v-html="webInfo.web_Copy"></p>
                            <p class="jubao"></p>
                            <p class="btm-text" v-html="webInfo.web_Copyright"></p>
                        </div>
                    </div>
                    <div class="footer-right">
                        <!--底部右边-->
                        <div class="wxcode">
                            <img alt="支付宝扫一扫" src="/images/zfb.jpg">
                        </div>
                    </div>
                    <div class="footer-right">
                        <!--底部右边-->
                        <div class="wxcode">
                            <img alt="微信扫一扫" src="/images/wx.jpg">
                        </div>
                    </div>
                </div>
            </div>
            <div id="backtop" class="backtop">
                <div class="bt-box top" style="display: none;"><i class="fa fa-angle-up fa-2x"></i></div>
                <div v-if="urlPar[2] == 'detail'" class="bt-box bt-comments">
                    <a href="#comments" target="_self" title="发表评论">
                        <i class="fa fa-comment fa-2x"></i>
                    </a>
                </div>
                <div class="bt-box bottom"><i class="fa fa-angle-down fa-2x"></i></div>
            </div>
        </footer>
    </div>
</template>

<script>
    module.exports = {
        data() {
            return {
                urlPar:[],
                webInfo:[],
            };
        },
        methods:{
            async getInfo(){
                axios.get('/api/index/webInfo').then(res=>{
                    this.webInfo = res.data;
                });
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
        async created(){

        },
        mounted(){
            this.loadScripts(['/hyy1.0.0/frontend/xy-js/wow.min.js','/hyy1.0.0/frontend/xy-js/custom.js'], () => {
                //console.log('All scripts have been loaded');
                // 在里可以执行其他操作，确保所有脚本都已加载完成
            });
            let url = window.location.pathname;
            this.urlPar = url.split('/');
            this.getInfo();
        },
    };
</script>

<style scoped>

</style>