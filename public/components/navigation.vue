<template>
    <div>
        <div class="container">
            <!-- 广告位AD1  -->
            <div class="row row-position">
                <div class="col-md-12">
                    <div v-if="!datalist.acc" style="margin: 0 auto;max-width:600px;">
                        <div style="margin:15px 15px;">
                            <el-input style="width:70%;"  @keyup.enter.native="SubmitValue" placeholder="请输入访问密码：123.." v-model="searchKey" class="input-with-select">
                                <el-button slot="append" icon="el-icon-s-promotion" @click.stop="SubmitValue"></el-button>
                            </el-input>
                        </div>
                    </div>
                    <div v-for="item in datalist.data" :key="item.id" class="part" id="cate1" :data-title="item.name">
                        <p class="tt">
                            <i class="fa fa-globe"></i>
                            <strong>{{item.name}}</strong>
                            <!--<a class="more" title="" href="/">
                                更多 <i class="fa fa-angle-right"></i>
                            </a>-->
                        </p>
                        <div class="items">
                            <div class="row">
                                <article v-for="d in item.nav" class="grid col-xs-6 col-sm-4 col-md-2">
                                    <div class="item" style="text-align:center;">
                                        <a class="link" :href="d.nav_url" :target="d.target" rel="nofollow">
                                            <i class="autoleft fa fa-link" :title="d.keywords"></i>
                                        </a>

                                        <a class="a" href="javascript:;">
                                            <!--<img src="/" alt="" title="">-->
                                            <!--<h3>{{d.nav_name}}</h3>-->
                                            <el-link :href="d.nav_url" :target="d.target" :underline="false">{{d.nav_name}}</el-link>
                                            <span v-if="d.sun_name">
                                                | <el-link :href="d.sun_url" :target="d.target" :underline="false">{{d.sun_name}}</el-link>
                                            </span>
                                            <!--<p>
                                                {{d.keywords}}&#45;&#45;&#45;&#45;
                                            </p>-->
                                        </a>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        data() {
            return {
                dataUrl:'/api/navigation',
                datalist:[],
                searchKey:''
            };
        },
        methods:{
            SubmitValue(){
                if(this.searchKey){
                    axios({
                        headers: { 'Content-Type': 'application/json;charset=utf-8' },
                        method: 'post',
                        data:{pass:this.searchKey},
                        url: this.dataUrl,
                    }).then(res => {
                        if(res.data.code == 200){
                            this.$message.success(res.data.message);
                            this.datalist = res.data;
                        }else{
                            this.$message.error(res.data.message);
                        }

                    })
                }
            },
            getData(){
                axios({
                    headers: { 'Content-Type': 'application/json;charset=utf-8' },
                    method: 'GET',
                    url: this.dataUrl,
                }).then(res => {
                    this.datalist = res.data;
                })
            }
        },
        mounted(){
            this.getData();
        },
        async created(){

        },
    };
</script>

<style scoped>
    @import "/hyy1.0.0/frontend/css/navigation.css";
</style>