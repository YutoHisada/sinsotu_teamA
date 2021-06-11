<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">Lesson 7</span>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>
                    
                    <div class="quesion-header">１．LaravelでAPIを作ってデータを表示する</div>
                    <p>
                        API <code>axios.get('/api/lesson/selector')</code> を使ってコントローラからデータを取得する。<br>
                        参考：<br>
                        テーブル定義：https://drive.google.com/drive/folders/1PMy8UwOmx3UN1b0nrGJSVlXofXZReBVO<br>
                        database-design_lesson.html（商品）<br>
                    </p>
                    <div class="alert alert-info" role="alert">
                        <i class="far fa-lightbulb"></i> ヒント: Laravel側（バックエンド）のエラーは「storage\logs」に出力されます。 <br>
                    </div>
                    
                    // ここにデータを表示
                    <div id="app">
                        <div>
                            <table class="table table-sm" key="processes">
                                <thead>
                                    <tr>
                                        <!--<th class="text-center bg-primary text-white">ID</th>-->
                                        <th class="text-center bg-primary text-white">ID</th>
                                        <th class="text-center bg-primary text-white">商品名</th>
                                        <th class="text-center bg-primary text-white">商品コード</th>
                                        <th class="text-center bg-primary text-white">フリガナ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in items" :key=index class="bg-white">
                                        <td class="text-center align-middle">{{ item.id }}</td>
                                        <td class="text-center align-middle">{{ item.name }}</td>
                                        <td class="text-center align-middle">{{ item.code }}</td>
                                        <td class="text-center align-middle">{{ item.phonetic_name }}</td>
                                    </tr>     
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="quesion-header">２．新規テーブル（customers）を作成してそのデータを画面に表示しましょう</div>
                    <p>
                        参考：<br>
                        テーブル定義：https://drive.google.com/drive/folders/1PMy8UwOmx3UN1b0nrGJSVlXofXZReBVO<br>
                        database-design_lesson.html（顧客）<br>
                        テーブル定義を参考にテーブルを作成する。<br>
                    </p>
                    <div class="mb-3">
                        <div class="h5">１．テーブル（マイグレーションファイル）を作成する</div>
                        コマンド：<code>php artisan make:migration create_customers_table</code>
                    </div>
                    <div class="mb-3">
                        <div class="h5">２．シーダーを作成する</div>
                        コマンド：<code>php artisan make:seeder CustomersTableSeeder</code>
                    </div>
                    <div class="mb-3">
                        <div class="h5">３．モデルを作成する</div>
                        コマンド：<code>php artisan make:model Customer</code>
                    </div>
                    <div class="mb-3">
                        <div class="h5">４．コントローラーを作成する</div>
                        コマンド：<code>php artisan make:controller API/CustomerController --api</code>
                    </div>
                    <div class="mb-3">
                        <div class="h5">５．APIのルーティングを作成する</div>
                        routes\api.phpに <code>Route::get('customer', 'API\CustomerController@index');</code> を定義
                    </div>
                    
                    // ここにデータを表示
                    <div id="app">
                        <div>
                            <table class="table table-sm" key="processes">
                                <thead>
                                    <tr>
                                        <th class="text-center bg-primary text-white">ID</th>
                                        <th class="text-center bg-primary text-white">顧客コード</th>
                                        <th class="text-center bg-primary text-white">名前</th>
                                        <th class="text-center bg-primary text-white">住所</th>
                                        <th class="text-center bg-primary text-white">アドレス</th>
                                        <th class="text-center bg-primary text-white">電話番号</th>
                                        <th class="text-center bg-primary text-white">FAX番号</th>
                                        <th class="text-center bg-primary text-white">休業日</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(customer, index) in customers" :key=index class="bg-white">
                                        
                                        <td class="text-center align-middle">{{ customer.id }}</td>
                                        <td class="text-center align-middle">{{ customer.code }}</td>
                                        <td class="text-center align-middle">{{ customer.name }}</td>
                                        <td class="text-center align-middle">{{ customer.postal_code }}</td>
                                        <td class="text-center align-middle">{{ customer.address }}</td>
                                        <td class="text-center align-middle">{{ customer.tel }}</td>
                                        <td class="text-center align-middle">{{ customer.fax }}</td>
                                        <td class="text-center align-middle">{{ customer.closing_day }}</td>
                                    </tr>     
                                </tbody>
                            </table>
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
            items: null,
            customers: null,
            test: null,
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
            // LaravelでのAPIの作成
            // routes/api.phpにルーティングを設定する
            // Route::get('lesson/selector', 'API\LessonController@index');
            // ↑これがControllerとの紐づけ定義
            const data = await axios.get('/api/item')
            this.items = data.data
            const data1 = await axios.get('/api/customer')
            console.log(data1)
            this.customers = data1.data
            // const data2 = await axios.get('/api/lesson/selector')
            // this.test = data2.data
            

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