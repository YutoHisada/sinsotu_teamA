<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div id="app" class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">Lesson 2</span>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>
                    <div class="mb-5">
                        <div class="quesion-header">１．入力値の合計を表示してください。</div>
                        <input type="number" v-model.number="left">
                        +
                        <input type="number" v-model.number="right">
                        =
                        {{ total }}
                    </div>
                    <div class="mb-5">
                        <div class="quesion-header">２．年齢を表示してください。</div>
                        <label for="birthday">お誕生日は？</label>
                        <input type="date" v-model="birthday">
                        <p v-if="age >= 0">{{ age }} 歳ですね！</p>
                        <p v-else>お誕生日を入力してください。</p>
                    </div>
                    <div class="mb-5">
                        <div class="quesion-header">３．プラスボタン、マイナスボタンで数値を変更できるようにしてください。</div>
                        <label>カウンター</label>
                        <button v-on:click="purasu" style="width:2rem;">+</button>
                        <button v-on:click="mainasu" style="width:2rem;">-</button>
                        {{count}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import moment from 'moment';
import days from '../commons/DateIncrementPicker'
export default {
    props: {
        //
    },
    data () {
        return {
            left: 0,
            right: 0,
            birthday: null,
            count:0
        }
    },
    mounted () {
        //
    },
    watch: {
        //
    },
    computed: {
        total() {
            return this.left + this.right
        },
        age() {
            var date = this.birthday 
            var today = moment().format("YYYYMMDD")
            var birthday = moment(date).format("YYYYMMDD")
            var answer = (today - birthday) / 10000
            return Math.floor(answer)
        }
    },
    methods: {
        purasu: function() {
            this.count ++
        },
        mainasu: function() {
            this.count --
        },
        onBack() {
            this.$router.push({ name: 'home' })
        }
    }
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
</style>