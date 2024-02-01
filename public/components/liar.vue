<template>
    <div>
        <!--进度条-->
        <div class="el-loading-mask is-fullscreen" :style="{'background-color':'rgba(0, 0, 0, 0.7)','z-index':2006,display:display}"><div class="el-loading-spinner"><i class="el-icon-loading"></i><p class="el-loading-text">{{progressPercent}}</p></div></div>
        <!-- Form -->
        <el-dialog title="添加失信者信息" :visible.sync="dialogFormVisible">
            <el-form :model="FData" ref="FData" id="FData" label-width="100px" class="demo-dynamic">
                <el-input v-show="false" v-model="FData.__token__" :label="token" ></el-input>
                <el-form-item label="失信者" prop="suspect" :rules="[{required:true,message:'必填项不能为空'}]">
                    <el-input placeholder="QQ：123456，微信：Lkf123" v-model="FData.suspect" ></el-input>
                </el-form-item>
                <el-form-item label="受害者" prop="victim" :rules="[{ required: true, message: '必填项不能为空'}]">
                    <el-input placeholder="QQ：123456，微信：Lkf123" v-model="FData.victim" ></el-input>
                </el-form-item>
                <el-form-item label="身份确认" prop="status" :rules="[{ required: true, message: '必填项不能为空'}]">
                    <el-radio-group v-model="FData.status">
                        <el-radio :label="1">确认骗子</el-radio>
                        <el-radio :label="0">疑似骗子</el-radio>
                    </el-radio-group>
                </el-form-item>
                <div  class="">
                    <el-row>
                        <el-col :span="18">
                            <el-form-item label="Email" prop="email" :rules="[{ required: true, message: '必填项不能为空'},{ type: 'email', message: '请输入正确的邮箱地址', trigger: ['blur', 'change'] }]">
                                <el-input v-model="FData.email" placeholder="Email"></el-input>
                            </el-form-item>
                        </el-col>

                        <el-col style="margin-left: 10px;" :span="5">
                            <el-button type="primary" @click="onSubmit('email')" :disabled="countingDown">{{downStr}}</el-button>
                        </el-col>
                    </el-row>
                </div>

                <el-form-item label="验证码" prop="code" :rules="[{ required: true, message: '必填项不能为空'}]">
                    <el-input placeholder="" v-model="FData.code" autocomplete="off"></el-input>
                </el-form-item>

                <el-form-item label="备注" prop="remarks">
                    <el-input type="textarea" v-model="FData.remarks"></el-input>
                </el-form-item>
                <el-form-item label="凭证" prop="thumbImg">
                    <el-upload
                            class="upload-demo"
                            ref="upload"
                            action=""
                            list-type="picture-card"
                            accept="image/*"
                            :limit="limitAction"
                            :file-list="fileList"
                            :on-exceed="handleTableExceed"
                            :on-remove="handleRemove"
                            :multiple="multiple"
                            :on-change="onChange"
                            :on-progress="onUpload"
                            :auto-upload="false">
                        <i class="el-icon-plus"></i>
                        <!--<el-button slot="trigger" size="small" type="primary">选取文件</el-button>-->
                        <!--<div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>-->
                    </el-upload>
                </el-form-item>

                <el-form-item>
                    <el-button type="success" @click="submitForm('FData')">提交</el-button>
                    <el-button @click="resetForm('FData')">重置</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>

        <!--搜索-->
        <div style="margin: 0 auto;max-width:600px;">
            <div style="margin:15px 15px;">
                <el-input style="width:70%;"  @keyup.enter.native="SearchValue" placeholder="请输入失信者搜索" v-model="searchKey" class="input-with-select">
                    <el-button slot="append" icon="el-icon-search" @click="SearchValue"></el-button>
                </el-input>
                <el-button type="primary" @click="dialogFormVisible = true">添加</el-button>
            </div>
        </div>

        <el-row v-for="item in datalist" :key="item.id">
            <el-card shadow="hover">
                <el-main>
                    <div>失信者：{{item.suspect}}</div>
                    <div>受害者：{{item.victim}}</div>
                    <div>Email：{{item.email}}</div>
                    <div v-if="item.status == 1">身份确认：确认骗子</div>
                    <div v-else>身份确认：疑似骗子</div>
                    <div>备注：{{item.remarks}}</div>
                    <div>添加时间：{{item.createTime}}</div>
                    <div>凭证：</div>
                    <el-image v-for="(img,index) in resImg(item.showImg)" :key="img.id" :initial-index="index" style="width:100px;height:100px;margin:2px;" fit="scale-down" :src="'/'+img.thumbImg" :preview-src-list="item.showImg.arrImg">
                    </el-image>
                </el-main>
            </el-card>
        </el-row>

        <el-row>
            <div style="text-align: center;">{{loadingString}}</div>
        </el-row>
    </div>
</template>
<script>
    module.exports = {
        props:{
            token:{
                type:String,
                default:"",
            }
        },
        data(){
            return{
                uploadUrl: '/index/liar/saveAt',
                emailUrl: '/index/liar/emailCode',
                datalUrl: '/api/liar',
                searchKey:'',
                datalist:[],
                dialogFormVisible: false,
                multiple:true,
                progressPercent:0,
                limitAction:9,
                display:'none',
                fileList: [],
                FData: {__token__:this.token,status:1,remarks:'信息由受骗网友提供'},
                countdown: 60, // 初始倒计时时间
                countingDown: false, // 是否正在倒计时
                downStr:'发送邮件',
                limit:10,
                page:1,
                pageCount:0,
                loading: false,
                loadingString:'数据加载中...',
            }
        },
        methods: {
            onSubmit(email){
                this.$refs.FData.validateField(email, (errorMessage) => {
                    if (errorMessage) {
                        // Validation failed
                        console.log(errorMessage);
                    } else {
                        axios({
                            headers: { 'Content-Type': 'application/json;charset=utf-8' },
                            method: 'POST',
                            url: this.emailUrl,   //填写自己的接口
                            data:{email:this.FData.email}, //填写包装好的formData对象
                        }).then(res => {
                            if (res.data.code == 200) {
                                this.$message.success(res.data.message);
                            } else {
                                this.$message.error(res.data.message);
                            }
                        })
                        if (!this.countingDown) {
                            this.countingDown = true;
                            this.downStr = '倒计时：'+this.countdown;
                            let timer = setInterval(() => {
                                this.downStr = '倒计时：'+this.countdown;
                                if (this.countdown > 0) {
                                    this.countdown--;
                                } else {
                                    clearInterval(timer);
                                    this.downStr = '发送邮件';
                                    this.countdown = 60;
                                    this.countingDown = false;
                                }
                            }, 1000);
                        }
                    }
                });
            },
            SearchValue(){
                if(this.searchKey){
                    //console.log(this.searchKey);
                    this.datalist = [];
                    this.page = 1;
                    this.dataList();
                }else{
                    this.searchKey = '';
                    this.datalist = [];
                    this.page = 1;
                    this.dataList();
                }
            },
            dataList(){
                this.loading = true;
                axios({
                    headers: { 'Content-Type': 'application/json;charset=utf-8' },
                    method: 'GET',
                    url: this.datalUrl,   //填写自己的接口
                    params:{'limit':this.limit,'page':this.page,'suspect':this.searchKey}, //填写包装好的formData对象
                }).then(res => {
                    this.datalist.push(...res.data.data);
                    this.pageCount = Math.ceil(res.data.count/this.limit);
                    this.page++;
                    if(this.limit>=res.data.count){
                        this.loadingString = '没有更多数据啦...';
                    }else{
                        this.loadingString = '数据加载中...';
                    }
                    //console.log(res.data);
                })
            },
            //上传时
            onUpload(event, file, fileList){  //三个参数看情况使用
                console.log(6666);
                this.loading = true
                this.progressPercent = Math.floor(event.percent);
            },
            submitForm(formName) {
                //console.log(this.FData);
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        if(this.fileList.length){
                            // 下面的代码将创建一个空的FormData对象:
                            const formData = new FormData()
                            // 你可以使用FormData.append来添加键/值对到表单里面；
                            this.fileList.forEach((file) => {
                                formData.append('thumbImg[]', file.raw)
                            })
                            //清除空对象
                            const filteredObj = Object.fromEntries(
                                Object.entries(this.FData).filter(([_, value]) => value != null && value !== '')
                            );
                            for (let key in filteredObj) {
                                formData.append(key,filteredObj[key]);
                            }
                            //执行上传操作
                            axios({
                                headers: { 'Content-Type': 'multipart/form-data' },
                                method: 'POST',
                                url: this.uploadUrl,   //填写自己的接口
                                data:formData,        //填写包装好的formData对象
                                //上传进度
                                onUploadProgress: progressEvent => {
                                    this.display = 'block';//显示loading进度
                                    // progressEvent.loaded: //已上传文件大小
                                    // progressEvent.total: //被上传文件的总大小
                                    this.progressPercent = Number((progressEvent.loaded / progressEvent.total * 100).toFixed(2))
                                }
                            }).then(res => {
                                //隐藏loading进度
                                this.display = 'none';
                                if (res.data.code == 200) {
                                    this.$message.success(res.data.message);
                                    setTimeout( () => {
                                        //重置表单
                                        this.resetForm(formName);
                                        //更新token
                                        this.FData.__token__ = res.data.token;
                                        //关闭表单弹窗
                                        this.dialogFormVisible = false;
                                        //重置进度
                                        this.progressPercent=0;
                                    },500)//2秒后执行
                                } else {
                                    this.$message.error(res.data.message);
                                    this.FData.__token__ = res.data.token;
                                }
                            })
                        }else{
                            this.$message({
                                message: '请上传凭证！！！',
                                type: 'error'
                            });
                        }
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            onChange(file, fileList) {
                //把当前文件赋值给 fileList
                this.fileList = fileList;
            },
            handleTableExceed() {
                this.$message.warning('最大上传：'+this.limitAction+' 张图片！');
            },
            resetForm(formName) {
                //重置表单
                this.$refs[formName].resetFields();
                //清空上传文件数组
                this.fileList.splice(0);
            },
            handleRemove(file) {
                //删除一个指定数据
                this.fileList.splice(this.fileList.indexOf(file),1);
                //console.log(file);
            },
            // 滚动回调函数
            callBackScroll(e) {
                let clientHeight = document.documentElement.clientHeight;//获取html的可视高度
                let scrollTop = document.documentElement.scrollTop;//获取html的滚动高度
                let scrollHeight = document.documentElement.scrollHeight;//获取文档的实际高度
                let th = Math.ceil(clientHeight + scrollTop);//向前取整
                //console.log(th,clientHeight, scrollTop, scrollHeight);//输出三个值
                //判断如果html的可视高度加上滚动高度等于文档的实际高度的话,就关闭开关,否则会造成数据混乱
                if (th >= scrollHeight && this.loading == true) {
                    this.loading = false;
                    if(this.page <= this.pageCount){
                        setTimeout(() => {
                            this.dataList();
                            console.log('触底啦。。。。');
                        }, 200);
                    }else{
                        console.log('没有更多数据啦。。。。');
                        this.loadingString = '没有更多数据啦...';
                    }
                }
            },
        },
        computed:{
            resImg(){
                return function(val){
                    val.arrImg = [];
                    for(let i=0;i < val.length;i++){
                        val.arrImg.push('/'+val[i].img);
                    }
                    //console.log(val);
                    return val;
                };
            }
        },
        mounted(){
            window.onscroll = async () => {
                this.callBackScroll();
            }
            this.dataList();
        },
    };
</script>

<style scoped>
    .el-aside {
        background-color: #D3DCE6;
        color: #333;
        text-align: center;
        line-height: 200px;
    }
    .el-row{
        margin-bottom: 20px;
    }
    .el-image{
        display: inline-block;
    }
</style>