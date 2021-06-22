<template>
  <div class="container">
    <!--別のvueを作る-->
    <!-- mainmap411 -->
    <div class="row">
      <div class="map col-md-5" ref="googleMap" style="float: left" />
        <div class="col-md-3">
          <input type="text" v-model="geo" style="width: 200px">
          <button v-on:click="geocoder"><i class="fas fa-search"></i></button>
          <div class="align-self-center">
            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
          </div>
        
          <div class="form-check">
            <input type="radio" id="one" value="岡崎橋ビル" v-model="place">
            <label for="one">岡崎橋ビル</label>
          </div>
          <div class="form-check">
            <input type="radio" id="two" value="本社" v-model="place">
            <label for="two">本社</label>
          </div>
          <!-- <span>Picked: {{ place }}</span> -->
          <div>
            <div class="form-check">
              <input type="checkbox" id="checkBox1" value="和食" v-model="genres">和食
              <input type="checkbox" id="checkBox2" value="洋食" v-model="genres">洋食
              <input type="checkbox" id="checkBox3" value="中華" v-model="genres">中華
            </div>
            <div class="form-check">
              <input type="checkbox" id="checkBox4" value="居酒屋" v-model="genres">居酒屋
              <input type="checkbox" id="checkBox5" value="ラーメン" v-model="genres">ラーメン
            </div>
            <div class="form-check">
              <input type="checkbox" id="checkBox6" value="弁当" v-model="genres">弁当
              <input type="checkbox" id="checkBox7" value="スーパー" v-model="genres">スーパー
            </div>
          </div>

          <!-- <span>Picked: {{ genres }}</span> -->
          <input type="text" v-model="genreText" value="" style="width: 200px">
          <button id="search" v-on:click="search"><i class="fas fa-search"></i></button>
          <br>
          <!-- <br>
          <span>Picked: {{ genreText }}</span> -->
          <div class="">
            <select id="log" v-model="genreText" style="width: 200px">
            </select>
          </div>
          <!-- <span>Picked: {{ optionCount }}</span> -->
          <!-- <span>Picked: {{ logs }}</span> -->
          <input type="range" min="1" max="5" step="1" v-model="sliderNum" style="width: 200px">
          <br>
          <span>スライダー: {{ sliderNum }}</span>
          <br>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" v-model="circleMode">
            <label v-if="circleMode" for="flexSwitchCheckDefault">円描画ON</label>
            <label v-else for="flexSwitchCheckDefault">円描画OFF</label>
          </div>
          <div class="mr-auto">
            <div v-if="markerCount == 0"><span class="span-header">全てのレビュー</span></div>
            <div v-else><span class="span-header">マーカーにあるレビュー</span></div>
          </div>
          <!-- <div>
            {{ reviews }}
          </div> -->
          <router-link 
            to="/create" 
            class="btn btn-primary"
            @click="onResume(review)"
            >投稿
          </router-link>　
          <table class="table table-sm" key="processes">
            <div v-if="markerCount == 0">
              <thead>
                <tr>
                  <!--<th class="text-center bg-primary text-white">ID</th>-->
                  <th class="text-center bg-primary text-white">投稿者</th>
                  <th class="text-center bg-primary text-white">店名</th>
                  <th class="text-center bg-primary text-white">コメント</th>
                  <th class="text-center bg-primary text-white">削除</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(review, index) in reviews" :key=index class="bg-white">
                  <!--<td class="text-center align-middle">{{ inventory.id }}</td>-->
                  <td class="text-center align-middle">{{ review.user_name }}</td>
                  <td class="text-center align-middle">{{ review.name }}</td>
                  <td class="text-center align-middle" style="white-space: pre;">{{ review.comment }}</td>
                  <td><button type="button" class="btn btn-danger" v-show="own.name === review.user_name" @click="onDelete(review.id)">削除</button></td>
                </tr>
              </tbody>
            </div>
            <div v-else>
              <thead>
                <tr>
                  <!--<th class="text-center bg-primary text-white">ID</th>-->
                  <th class="text-center bg-primary text-white">投稿者</th>
                  <th class="text-center bg-primary text-white">店名</th>
                  <th class="text-center bg-primary text-white">コメント</th>
                  <th class="text-center bg-primary text-white">削除</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(review, index) in reviewsFilter" :key=index class="bg-white">
                  <!--<td class="text-center align-middle">{{ inventory.id }}</td>-->
                  <td class="text-center align-middle">{{ review.user_name }}</td>
                  <td class="text-center align-middle">{{ review.name }}</td>
                  <td class="text-center align-middle" style="white-space: pre;">{{ review.comment }}</td>
                  <td><button type="button" class="btn btn-danger" v-show="own.name === review.user_name" @click="onDelete(review.id)">削除</button></td>
                </tr>
              </tbody>
            </div>       
          </table>
          <paginate
            :page-count="getPageCount"
            :page-range="10"
            :margin-pages="2"
            :click-handler="clickCallback"
            :prev-text="'＜'"
            :next-text="'＞'"
            :container-class="'pagination'"
            :page-class="'page-item'">
          </paginate>
      </div>
    </div>
  </div>
</template>

<script>
import MgPaginate from 'vue-mg-paginate'
import GoogleMapsApiLoader from 'google-maps-api-loader';
export default {
  // name: 'Map',
  data() {
    return {
      reviews: [],
      reviewsFilter: [],
      myAPI: 'AIzaSyAxisqbDtjxsblijsRF4isATj0EOFkG5bM',
      map: null,
      radius: 0,
      geo: '',
      circleMode: false,
      sliderNum: 3,
      genres: [],
      logs: [],
      genreText: null,
      searchRange: 200,
      optionCount: 0,
      pagenate: ['paginate-log'],
      place: '岡崎橋ビル', 
      dataCount: 0,
      markerCount: 0,
      latarray: [],
      lngarray: [],
      restname: [],
      openshop: [],
      BOOL: [],
      eigyou: [],
      lat: 34.68286620763049,
      lng: 135.48942106828244,
      type: '',
      keyword: '',
      selected: null,
      google: null,
      own_user_name: '',
      parpage: 5,
      currentPage: 1,
      isLoading: false,
      fullPage: false,
      currentInfoWindow: null,

      iconUrl:[],  //ここと380行目あたりをコピー
      keyarray:[],  

      placeID:[], //詳細な情報を取得するためのID
      hoga:[],
      open_Hour:'', //営業時間
      pson:[], //placeで取得したjson
      hohoho:'', //json確認要変数　検証終了後削除
      
      mapConfig: {
        // 地図の中心地点
        center: {
          lat: 34.68286620763049,
          lng: 135.48942106828244,
        },
        // 拡大率
        zoom: 15,
        // 全てのUIを消す
        disableDefaultUI: true,
        // // 地図、航空写真ボタン削除
        // mapTypeControl:false,
        // // ズームボタン削除
        // zoomControl:false,
        // // ストリートビューボタン削除
        // streetViewControl:false,
        // // フルスクリーンボタン削除
        // fullscreenControl:false,
        marker: null,
        styles: [{
          // お店アイコン
		      featureType: 'poi.business',
		      stylers: [
			      { visibility: 'off' }
		      ]},
          // 学校アイコン
          { featureType: 'poi.school',
		        stylers: [
			      { visibility: 'off' }
		      ]},
          // 白い眼玉みたいなビルアイコン
          { featureType: 'landscape.man_made',
            elementType: 'labels.icon',
		        stylers: [
			      { visibility: 'off' }
		      ]},
          // 病院アイコン
          { featureType: 'poi.medical',
		        stylers: [
			      { visibility: 'off' }
		      ]},
          // 公園アイコン
          { featureType: 'poi.park',
		        stylers: [
			      { visibility: 'off' }
		      ]},
          // 役所アイコン
          { featureType: 'poi.government',
		        stylers: [
			      { visibility: 'off' }
		      ]},
          // 神社アイコン
          { featureType: 'poi.place_of_worship',
		        stylers: [
			      { visibility: 'off' }
		      ]},
          // 観光名所アイコン
          { featureType: 'poi.attraction',
		        stylers: [
			      { visibility: 'off' }
		      ]},
          // スポーツ施設アイコン(緑のやつ)
          { featureType: 'poi.sports_complex',
		        stylers: [
			      { visibility: 'off' }
		      ]},
        ]
      },
    }
  },
  async mounted() {
    this.google = await GoogleMapsApiLoader({
      apiKey: this.myAPI
    });
    // this.initializeMap();
    this.radius = this.sliderNum*this.searchRange

    document.getElementById("log").disabled = true
    document.getElementById("search").disabled = true

    this.initializeMap()
    this.getItems();
  },
  methods: {
    async main() {
      this.dataCount = 0
      this.latarray = []
      this.lngarray = []
      this.restname = []
      this.placeID = []
      this.keyarray = []

      if(this.genres.length > 0)
      {

        for(let i = 0;i < this.genres.length;i++){
          this.keyword = this.genres[i]
          await this.getLatLng()
        }
      }
      else 
      {
        await this.getLatLng()
      }

      this.map = new this.google.maps.Map(this.$refs.googleMap, this.mapConfig);

      await this.initializeMap()
      await this.createMarker()
    },
    async getLatLng() {

      await axios.get(`https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=${this.myAPI}&location=${this.lat},${this.lng}&radius=${this.radius}&type=${this.type}&keyword=${this.keyword}&language=ja`).then(result => 
      {
        var dataCount = (Object.keys(result.data.results).length);

        for(let i=0; i< dataCount; i++)
        {
          this.latarray.push(result.data.results[i].geometry.location.lat)
          this.lngarray.push(result.data.results[i].geometry.location.lng)
          this.restname.push(result.data.results[i].name)
          this.placeID.push(result.data.results[i].place_id)
          this.keyarray.push(this.keyword)

          //営業時間がある場合true,ない場合falseを返す
          if(result.data.results[i].opening_hours != null && result.data.results[i].opening_hours.open_now != null) {
            if(result.data.results[i].opening_hours.open_now == true)
            {
              this.eigyou[i] = "<br>営業しています"
            }
            else
            {
              this.eigyou[i] = "<br>本日の営業は終了しました"
            }
            //営業しているかを判断営業していない場合、不明を返す
          }
          else 
          {
            this.eigyou[i] = ""
          }
        }
        this.dataCount += dataCount;
      })
    },
    async initializeMap() {
      this.mapConfig.center.lat = this.lat;
      this.mapConfig.center.lng = this.lng;
      this.map = new this.google.maps.Map(this.$refs.googleMap, this.mapConfig);
      
      if(this.circleMode) {
        // 円表示
       new google.maps.Circle({
        map: this.map,
        center: {lat: this.lat, lng: this.lng},
        radius: this.radius,
        fillColor: '#7cfc00',   // 塗りつぶし色
      })
      }
      else {
        this.map = new this.google.maps.Map(this.$refs.googleMap, this.mapConfig);
      }
    },
    clickCallback: function(pageNum) {
      this.currentPage = Number(pageNum);
    },
    // getItems: function() {
    //   this.isLoading = true
    //   const api = axios.create()
    //   axios.all([api.get('/api/review')]).then(
    //     axios.spread((res1, res2, res3, res4) => {
    //         //格納データ取得
    //       this.reviews = res1.data

    //       this.isLoading = false
    //     }),
    //   )
    // },
    async createMarker() {
      this.iconUrl = []
      this.markerCount = 0

      for(let i = 0; i < this.dataCount;i++) {

        switch(this.keyarray[i]) {
          case'和食':
            this.iconUrl[i] = 'images/green.png'
            break
          case'ラーメン':
            this.iconUrl[i] = 'images/orange.png'
            break
          default:
            this.iconUrl[i] = 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
        }

      }

      for(var i = 0;i < this.dataCount;i++) {
        this.markerCount++
        // ジャンル検索の時のマーカー
        if(this.genres.length > 0) {
          var markers = new google.maps.Marker({
            map: this.map,
            position: new this.google.maps.LatLng(this.latarray[i], this.lngarray[i]),
            animation: google.maps.Animation.DROP, //アニメーション　BOUNCE:常に跳ねる　DROP:設置する時に降ってくる
            icon: {
              url : this.iconUrl[i]　//変更
            },
          })
        }
        else {
          var markers = new google.maps.Marker({
            map: this.map,
            position: new this.google.maps.LatLng(this.latarray[i], this.lngarray[i]),
            animation: google.maps.Animation.DROP, //アニメーション　BOUNCE:常に跳ねる　DROP:設置する時に降ってくる
          })
        }

        var msg =`${this.restname[i]}<br/><a href="https://www.google.com/maps/search/?api=1&query=${this.restname[i]}"target="_blank" >Googleマップで見る</a>${this.eigyou[i]}` 　//営業しているかのメッセージを消去する場合、this.eigyou[i]を削除
        this.attachMessage(markers, msg, this.restname[i], this.placeID[i]); 
      }
      
      // 口コミ絞り込み処理
      var hoge = []
      this.reviewsFilter = this.reviews

      this.reviewsFilter.forEach(value => {
        this.restname.forEach(element => {
          if(value.name == element){
            hoge.push(value)
          }
        })
      })
      this.reviewsFilter = hoge
      this.getReviewsFunc()
    },

    async getHour(pID) {  //検証する場合、引数に店の名前(=restname)を入れる 

     //曜日の取得およびJS形式からGP形式へ変換
     var date = new Date()
     var dayOfWeek = date.getDay()
     var dayOfWeekStr = [ "月", "火", "水", "木", "金", "土", "日" ][dayOfWeek] //検証用
     if(dayOfWeek == 0)
       {
         dayOfWeek += 6
       }
       else
       {
         dayOfWeek --
       }

      //jsonを取得する
      // const pson = await axios.get(`https://maps.googleapis.com/maps/api/place/details/json?place_id=${pID}&key=${this.myAPI}&fields=opening_hours,business_status&language=ja`)
      // this.pson = pson
       //console.log(this.pson)  

       await axios.get(`https://maps.googleapis.com/maps/api/place/details/json?place_id=${pID}&key=${this.myAPI}&fields=opening_hours,business_status&language=ja`).then(Result => { 
        //dataの中のresultと被るので大元のresultをResultへ変更
        //一週間は7日間
        let oneWeek = 7
        //営業時間を表示していない店もあるため、その対応をしないといけない 
      var plBool = false
      if( Result.data.result.opening_hours != null)
       {
         plBool = true
         console.log("true")
       }
       else if( Result.data.result.opening_hours == null )
       {
         plBool = false
         console.log("false")
       }
       else
       {
         plBool = false
         console.log("error")
       }

      if( plBool == true ){
          this.hogs2 = Result.data.result.opening_hours.weekday_text[dayOfWeek] 
         }
      else if( plBool == false )
       {
         this.hogs2 = "営業時間を取得できませんでした"
       }
       
       this.open_Hour = this.hogs2
       console.log(this.open_Hour)
      })
        // console.log(restName)      
    },

    search() {
      // チェックボックスのチェックを外す
      document.getElementById("checkBox1").checked = false
      document.getElementById("checkBox2").checked = false
      document.getElementById("checkBox3").checked = false
      document.getElementById("checkBox4").checked = false
      document.getElementById("checkBox5").checked = false
      document.getElementById("checkBox6").checked = false
      document.getElementById("checkBox7").checked = false
      this.genres = []

      this.keyword = this.genreText
      // this.genreTextをfilterの中で使えない為
      const a = this.genreText
      this.main()
      const select = document.getElementById("log")

      // logsに検索欄と同じものが無いか
      const result = this.logs.filter(function(value){
        return value === a
      })

      // 同じものが無ければ
      if(result.length === 0)
      {
        // 同じ文字列を追加しないようにこちらで制御
        this.logs.unshift(this.genreText)
        const option = document.createElement("option")
        option.text = this.genreText
        option.value = this.genreText  
        // ここで要素を先頭に追加
        select.add(option, 0)
      }

      // プルダウンの項目数
      this.optionCount = select.length

      if(select.length > 5)
      {
        // 0から数えて5番目の要素削除
        select.remove(5)
        // 末尾削除
        this.logs.pop()
      }
      else if(select.length > 0)
      {
        // データがあればプルダウン許可
        select.disabled = false
      }

      // テキストボックスを空に
      this.genreText = ''
    },
    //住所から座標変換
    async geocoder()
    {
      //GeocodingAPIから緯度経度取得
      var latlng = await axios.get(`https://maps.googleapis.com/maps/api/geocode/json?address=${this.geo}&key=${this.myAPI}`)
      this.lat = latlng.data.results[0].geometry.location.lat
      this.lng = latlng.data.results[0].geometry.location.lng
      
      this.initializeMap()
    },
    attachMessage(marker, msg, restname, placeID) {
      // 下のfunctionの中でthisを使う場合求める参照先のthisを代入して固定する
      var self = this

      google.maps.event.addListener(marker, 'click', async function() {
        console.log(placeID)
        await self.getHour(placeID)

        var InfoWindow = new google.maps.InfoWindow({
          content: msg + `<br>${self.open_Hour}<br><button onclick='location.href="/create/${restname}"' value=''>レビューを投稿</button>`
        })

        if(self.currentInfoWindow) {
          self.currentInfoWindow.close()
        }

        InfoWindow.open(marker.getMap(), marker) 
        self.currentInfoWindow = InfoWindow
        
        return InfoWindow
      })
    },
    getReviewsFunc() {
      let current = this.currentPage * this.parpage;
      let start = current - this.parpage;

      
      // this.reviews = this.reviews.filter(value => {
      //   var hoge = []
      //   this.restname.forEach(element => {
      //     if(value.name == element) {
      //     hoge.push = value
      //   }
      //   })
      // })
      // this.reviews = hoge
      // console.log(this.restname)
      // console.log(this.reviews)
      
      if(this.markerCount == 0) {
        return this.reviews.slice(start, current).reverse();
      }
      else {
        return this.reviewsFilter.slice(start, current).reverse();
      }
      
    },
    getItems: function () {
      this.isLoading = true;
      const api = axios.create()
      axios.all([
        api.get('/api/review', {
          params: {
            own_user_name: this.own_user_name,
            offset: this.offset,
            limit: this.itemsPerPage,
          },
        }),
      ]).then(axios.spread((res1, res2, res3, res4) => {
        this.reviews = res1.data
        
        this.isLoading = false
      }))
    },
    
    onDelete: function (review_id) {
      if (!confirm('削除してもよろしいですか？')) {
        return
      }
      let _this = this
      axios.delete('/api/review/'+ review_id)
      .then(function (resp) {
        alert('削除しました。')
        _this.$router.go(0)
      })
      .catch(function (resp) {
        console.log(resp)
      })
      .finally(function () {
        //
      })
    },
    onResume(review) {
      this.$router.push({ name: 'create', params: { ReviewId: Review.id } })
    },
    onBack: function () {
      this.$router.go(-1)
    },
    // onCreate: function() {
    //     let _this = this
    //         axios.post('/api/review', {
    //             content: this.content,
    //         })
    //         .then(function (resp) {
    //             if (resp.data.result) {
    //                 alert('投稿しました。')
    //                 _this.$router.go(-1)
    //             } else {
    //                 _this.errorMessage = resp.data.errorMessage
    //                 _this.invalid = true
    //             }
    //         })
    //         .catch(function (resp) {
    //             console.log(resp)
    //         });
    //     // this.$router.push({ name: 'review', params: {review_id: review_id} })
    // },
  },
  watch: {
    place: async function(){
      if(this.place == '岡崎橋ビル')
      {
        this.lat = 34.682910319991365
        this.lng = 135.4893996106105
        // マップ再読み込み
        // this.initializeMap();
      }
      else
      {
        this.lat = 34.67981481380863
        this.lng = 135.49274708362623
        // マップ再読み込み
        // this.initializeMap();
      }

      if(this.genres.length > 0 || this.genreText != null) {
        await this.main()
      }
      else {
        await this.initializeMap()
      }
        
    },
    sliderNum: async function() {
      this.radius = this.sliderNum*this.searchRange

      switch(this.sliderNum) {
        case '1':
          this.mapConfig.zoom = 16.5
          break
        case '2':
          this.mapConfig.zoom = 15.5
          break
        case '3':
          this.mapConfig.zoom = 15
          break
        case '4':
          this.mapConfig.zoom = 14.6
          break
        case '5':
          this.mapConfig.zoom = 14.28
          break
        default:
          this.mapConfig.zoom = 15
      }

      if(this.genres.length > 0 || this.genreText != null) {
        await this.main()
      }
      else {
        await this.initializeMap()
      }
    },
    genres: async function(){
      this.getReviewsFunc()

      if(this.genres.length > 0) {
        this.main()
      }
      else {
        this.markerCount = 0
        this.getReviewsFunc()
        this.initializeMap()
      }
    },
    genreText: async function(){
      document.getElementById("search").disabled = false
    },
    circleMode: async function(){
      if(this.genres.length > 0 || this.genreText != null) {
        await this.main()
      }
      else {
        await this.initializeMap()
      }
    },
  },
  computed: {
    sortReviews() {
      if(this.markerCount == 0) {
        return this.reviews.slice().reverse();
      }
      else {
        return this.reviewsFilter.slice().reverse();
      }
    },
    own() {
      return this.$store.state.user
    },
    getPageCount() {
      if(this.markerCount == 0) {
        return Math.ceil(this.reviews.length / this.parpage)
      }
      else {
        return Math.ceil(this.reviewsFilter.length / this.parpage)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
// ダサいので円形表示は保留 border-radius: 250px;
.map {
  width: 500px;
  height: 500px;
  display: inline-block;
}
</style>