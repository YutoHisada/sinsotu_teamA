<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div id="app" class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">レビューを作成</span>
                        </div>
                        <div class="align-self-center mr-3">
                            <button type="button" class="btn btn-primary" @click="onStore"><i class="fas fa-plus"></i> 投稿</button>
                            <!-- ボタンの表示・非表示は後からやる -->
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>投稿者名</label>
                        <input  type="text"  readonly class="form-control" v-model="own.name">
                    </div>
                    <div class="form-group">
                        <label>店名</label>
                        <input  type="text" class="form-control" placeholder="店名を入力してください。" v-model="review.name">
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <textarea  class="form-control" style="height: 120px;" placeholder="コメント内容" v-model="review.comment"></textarea>
                        </div>
                    </div>
                    <div class="row-line">
                        <transition name="fade" mode="out-in">
                        <div class="alert alert-danger" role="alert" v-if="invalid">
                        {{errorMessage}}
                        </div>
                        </transition>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import moment from 'moment';
export default {
    props: [
    ],
    data () {
        return {
            review: {
              id: '',
              user_name: '',
              name: '',
              comment: '',  
            },
            isLoading: false,
            errorMessage: '',
            invalid: false,
        }
    },
    created () {
    },
    mounted() {
        var str = this.$route.params.restname
        // var result = str.replace('(', '\(')
        // var result2 = result.replace(')', '\)')

        return this.review.name = this.$route.params.restname
    },
    watch: {
        //
    },
    computed: {
        own() {
            return this.$store.state.user
        },
    },
    methods: {
        getItems: function () {
            this.isLoading = true;
            const api = axios.create()
            axios.all([
                api.get('/api/review'),
            ]).then(axios.spread((res1, res2, res3, res4) => {
                this.review = res1.data
                this.isLoading = false
            }))
        },
        onStore: function () {
            let _this = this
            this.errorMessage = ""

            if (!this.review.name) {
                this.errorMessage = '店名を入力してください。'
                this.invalid = true
                return 
            }
            if (!this.review.comment) {
                this.errorMessage = 'コメントを入力してください。'
                this.invalid = true
                return 
            }

            
            axios.post('/api/review', {
                review: this.review,
            })
            .then(function (resp) {
                if (resp.data.result) {
                    alert('投稿しました。')
                    _this.$router.push({name: "map"});
                } else {
                    _this.errorMessage = resp.data.errorMessage
                    _this.invalid = true
                }
            })
            .catch(function (resp) {
                console.log(resp)
            });
        },
        onCreate: function () {
            this.$router.push({ name: '/' })
        },
        onShow: function (review_id) {
            this.$router.push({ name: '/', params: {review_id: review_id} })
        },
        onBack: function () {
            this.$router.go(-1)
        },
    },
    components: {
        // Loading
    },
}
</script>
<style lang="scss" scoped>
@import "resources/sass/variables";
</style>