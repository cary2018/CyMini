<template>
    <header class="top-header">
        <div class="top-bar fixed-nav fixed-appear">
            <div class="new-header container clearfix">
                <div class="top-bar-left header-nav fl" data-type="index" data-infoid="index">
                    <div class="m-nav-header">
                        <div class="m_nav-list">
                            <a href="javascript:;" class="lines js-m-navlist">
                                <i class="nav-bar"><span></span><span></span><span></span></i>
                            </a>
                        </div>
                    </div>
                    <div class="m-nav-logo">
                        <a href="/" v-if="navMenu.webInfo">
                            <img :src="'/'+navMenu.webInfo.web_logo" :alt="navMenu.webInfo.web_title">
                            <img class="night-logo" src="/images/yjlogo.png" :alt="navMenu.webInfo.web_title">
                        </a>
                    </div>
                    <div class="m-nav-search">
                        <a id="m-nav-so" href="javascript:void(0);"><i class="fa fa-search"></i></a>
                        <a title="夜间模式" class="at-night" href="javascript:switchNightMode()" target="_self">
                            <i class="wb-switch"></i>
                        </a>
                        <div class="mini-search">
                            <form name="search" class="searchform" method="get" action="/index/search">
                                <input class="searchInput" type="text" name="q" size="11" placeholder="请输入搜索内容..." :value="searchKey" id="ls">
                                <button type="submit" class="btn-search dtb2" value=""><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                    <aside class="mobile_aside mobile_nav">
                        <div class="mobile-menu">
                            <nav class="top-bar-navigation">
                                <ul class="top-bar-menu nav-pills">
                                    <li id="nvabar-item-index">
                                        <a href="/">
                                            <i class="fa fa-home"></i>首页
                                        </a>
                                    </li>
                                    <li v-for="(item,index) in navMenu.list" :id="'navbar-category-'+index" v-if="item.son.length === 0" :key="item.id">
                                        <a :href="item.isUrl?item.outUrl:item.temp_list+'?id='+item.id" :target="item.target">
                                            <i class="fa fa-cloud"></i>{{item.name}}
                                        </a>
                                    </li>

                                    <li v-else :id="'navbar-page-'+index" class="menu-item-has-children ">
                                        <a href="javascript:;" >
                                            <i class="fa fa-book"></i>{{item.name}}
                                        </a>
                                        <span class="toggle-btn"><i class="fa fa-chevron-down"></i></span>
                                        <ul class="dropdown-menu sub-menu">
                                            <li v-for="s in item.son" v-if="s.son.length === 0" :key="s.id" id="navbar-page-68">
                                                <a :href="s.isUrl?s.outUrl:s.temp_list+'?id='+s.id" :target="s.target">{{s.name}}</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                                <div id="sidebar-toggle" class="sidebar-toggle"><span></span></div>
                            </nav>
                        </div>
                    </aside>
                </div>
                <div class="top-bar-right text-right fr">
                    <div class="top-admin">
                        <div class="login">
                            <!--<a href="/" target="_self">QQ登陆</a>
                            <i class="fa fa-sign-in"></i><a href="/">注册/登录</a>-->
                            <i class="fa fa-moon-o"></i>
                        </div>
                        <a title="夜间模式" class="at-night" href="javascript:switchNightMode()" target="_self">
                            <i class="wb-switch"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="container secnav clearfix">
                <div class="fav-subnav">
                    <div class="top-bar-left pull-left navlogo">
                        <a href="/" class="logo box" v-if="navMenu.webInfo">
                            <img :src="'/'+navMenu.webInfo.web_logo" class="logo-light" id="logo-light" :alt="navMenu.webInfo.web_title">
                            <img src="/images/yjlogo.png" class="logo-dark d-none" id="logo-dark" :alt="navMenu.webInfo.web_title">
                            <b class="shan"></b>
                        </a>
                    </div>
                    <div class="search-warp clearfix">
                        <form name="search" method="get" action="/index/search">
                            <div class="search-area">
                                <input class="search-input" :value="searchKey" placeholder="搜索感兴趣的知识和文章" type="text" name="q">
                            </div>
                            <button class="showhide-search" type="submit"><i class="fa fa-search"></i>搜索一下</button>
                        </form>
                    </div>
                </div>
            </div>
            <div id="percentageCounter" style="width: 0%;"></div>
        </div>
    </header>
</template>

<script>
    module.exports = {
        data() {
            return {
                navMenu:'',
                searchKey:'',
            };
        },
        methods:{
            handleSelect(key, keyPath) {
                sessionStorage["activeIndex"] = key;
                //console.log(key, keyPath);
            },
            async dataMenu(){
                axios.get('/api/').then(res=>{
                    this.navMenu = res.data;
                });
            },
            async search(){
                const searchParams = new URLSearchParams(window.location.search);
                this.searchKey = searchParams.get('q');
            }
        },
        mounted(){
            this.dataMenu();
            this.search();
        },
        async created(){

        },
    };
</script>

<style scoped>
.NavMenu-fixed-top{
    position: fixed;
    right: 0;
    left: 0;
    z-index: 5;
    top: 0;
}
</style>
