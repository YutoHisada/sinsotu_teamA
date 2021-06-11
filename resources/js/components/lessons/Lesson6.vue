<template>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">Lesson 6</span>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>
                    
                    <div class="quesion-header">APIを使って取得したデータを表示しましょう</div>
                    <div class="d-flex flex-wrap justify-content-start mt-1 mb-2"  v-if="items">
                        <div class="d-flex mr-3">
                            <div class="align-self-center">例）version：</div>
                            <div class="align-self-center">
                                <input class="form-control" v-model="items.info.version">
                            </div>
                            <div class="align-self-center">results:</div>
                            <div class="align-self-center">
                                <input class="form-control" v-model="items.info.results">
                            </div> 
                        </div>
                    </div>

                    <hr>
                    <div class="alert alert-warning" role="alert">
                        
                        <i class="fas fa-book-reader"></i> 応用編：公開されているAPIを使ってデータを取得してましょう。「http://smsurf.app-rox.com/api/」
                        <div class="align-self-center">results:</div>
                    </div>
                    <div id="app">
                        <GChart
                            :settings="chartSettings"
                            type="GeoChart"
                            :data="chartData"
                            :options="chartOptions"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import { GChart } from 'vue-google-charts';
export default {
    components: {
        GChart
    },
    props: {
        //
    },
    data () {
        return {
            items: {info:{}},
            chartSettings: {
                packages: ['geochart'],
                'mapsApiKey': 'AIzaSyAIW9nb62wuyQir60BfmCQDhfc9Q3lR8F4'
            },
            chartData: [
                ['Pref', 'Population'],
                ['北海道', 5300],
                ['青森', 1300],
                ['岩手', 1300],
                ['宮城', 2300],
                ['秋田', 1000],
                ['山形', 1100],
                ['福島', 1900],
                // 以下略
            ],
            chartOptions: {
                region: 'JP',
                resolution: 'provinces',
                displayMode: 'regions',
            }
        }
    },
    mounted () {
        this.getInit()
    },
    watch: {
        //
    },
    computed: {
        //
    },
    methods: {
        async getInit() {
            // WebAPIとは
            // https://qiita.com/NagaokaKenichi/items/df4c8455ab527aeacf02
            // API呼出しの基本形
            const {data} = await axios.get('https://randomuser.me/api/')
            // // 取得したデータはchromeのデバッグツールで確認できます。
            // // https://qiita.com/nonkapibara/items/8b587013b6b817d6dfc4
            this.items = data
            // .then(response => { 
            //     console.log(data)
            // })
            // .catch(error => {
            //     console.log(error.data)
            // });
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