<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">Lesson 8</span>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>
                    
                    <div class="quesion-header">ルーティング</div>
                    <div class="mb-3">
                        <a class="btn btn-link" href="https://hackmd.io/0e8J1D-6Qhq4k3_aqUi6qw?view">Lesson8の問題へ</a>
                    </div>
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-4">
                            <button type="button" class="btn btn-primary" @click="onNext">Leeson8-1へ</button>
                            <input id="app" v-model="text">
                            <button type="button" class="btn btn-primary" @click="twoNext">Leeson8-2へ</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        //
    },
    data () {
        return {
            text: '',
        }
    },
    mounted () {
        //
    },
    watch: {
        //
    },
    computed: {
        text: {
            
        }
    },
    methods: {
        onNext() {
            this.$router.push({ name: 'lesson8-1' })
            
        },
        twoNext() {
            this.$router.push({ name: 'lesson8-2', params:{text: this.text}})
        },
        onBack() {
            this.$router.push({ name: 'home' })
        }

    },
}

</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
</style>