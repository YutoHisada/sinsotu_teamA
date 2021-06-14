<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">ユーザ一覧</span>
                        </div>
                        <div class="align-self-center mr-3">
                            <button type="button" class="btn btn-primary" @click="onCreate"><i class="fas fa-plus"></i> 新規追加</button>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>

                    <table class="table-custom" key="processes">
                        <thead>
                            <tr>
                                <!-- <th class="text-center">氏名</th>
                                <th class="text-center">フリガナ</th> -->
                                <th class="text-center">ID</th>
                                <th class="text-center">権限</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr v-for="user in users" class="clickable" :key="user.id" @click="onShow(user.id)">
                                <!-- <td class="text-center align-middle">{{employee.full_name}}</td>
                                <td class="text-center align-middle">{{employee.full_phonetic_name}}</td>-->
                                <td class="text-center align-middle">{{user.name}}</td>
                                <td class="text-center align-middle">
                                    <div v-if="user.is_admin">管理者</div>
                                    <!-- <div v-if="user.is_leader">リーダー</div>-->
                                </td>
                            </tr>
                        </tbody>
                        <loading :active.sync="isLoading"></loading>
                    </table>
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
            users: [],
            isLoading: false,
        }
    },
    mounted () {
        this.inspected_on = new moment().format('YYYY-MM-DD')
        this.getItems()
    },
    watch: {
        //
    },
    computed: {
        //
    },
    methods: {
        getItems: function () {
            this.isLoading = true;
            const api = axios.create()
            axios.all([
                api.get('/api/user'),
            ]).then(axios.spread((res1, res2, res3, res4) => {
                this.users = res1.data
                
                this.isLoading = false
            }))
        },
        onCreate: function () {
            this.$router.push({ name: 'user.create' })
        },
        onShow: function (user_id) {
            this.$router.push({ name: 'user.show', params: {user_id: user_id} })
        },
        onBack: function () {
            this.$router.go(-1)
        }
    },
    components: {
        // Loading
    },
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
</style>