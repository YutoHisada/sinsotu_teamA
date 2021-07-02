<template>
  <div class="container-fruid">
    <div class="overflow-hidden">
      <div class="row">
        <div class="col-md-5">
          <div class="row">
            <GmapMap
              :center="maplocation"
              :zoom="zoomMap"
              :draggable="true"
              :options="{disableDefaultUI: true,
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
                ]}"
                map-type-id="roadmap"
                class="map col-md-12 relative"
                style="float: left"
                ref="mapRef" 
                >
                <div v-if="circleMode">
                  <GmapCircle
                    :center="nowMark.position"
                    :radius=this.radius
                    :options="{fillColor:'#7cfc00',fillOpacity:0.1}"
                  ></GmapCircle>
                </div>

                <GmapMarker
                  :position="nowMark.position"
                  :clickable="false"
                  :draggable="false"
                  :icon="nowMark.iconUrl"
                  :key="nowMark.id"
                ></GmapMarker>

                <GmapMarker v-for="m in markers"
                  :position="m.position"
                  :title="m.title"
                  :clickable="true"
                  :draggable="false"
                  :icon="m.icon"
                  :key="m.id"
                  @click="toggleInfoWindow(m)"
                  >
                </GmapMarker>

                <GmapInfoWindow
                  :options="infoOptions"
                  :position="infoWindowPos"
                  :opened="infoWinOpen"
                  @closeclick="infoWinOpen=false"
                ><div v-html="infoMsg"></div>
                </GmapInfoWindow>
            </GmapMap>
            <button v-if="own.is_admin && windowWidth > 800" type="button" class="btn btn-dark absolute" @click="onBack">
              戻る
            </button>
            <button v-if="own.is_admin && windowWidth <= 799" type="button" class="btn btn-dark absolute1" @click="onBack">
              戻る
            </button>
            <button v-if="windowWidth > 800" @click="randomRest(0)" class="btn btn-light absolute2" id="popover"><img src="images/random.png"></button>
            <b-popover
              title="ランダムでお店を決定します"
              target="popover"
              triggers="hover focus"
              placement="left"
              boundary="viewport"
            />
            <button v-if="windowWidth <= 799" @click="randomRest(0)" class="btn btn-light absolute3" id="popover2"><img src="images/random.png"></button>
            <b-popover 
              :show.sync="show" 
              title="ランダムでお店を決定します"
              target="popover2"
              placement="left"
              boundary="viewport"
            />
          </div>
        </div>
        <div class="col-md-7">
          <div class="row">
            <div class="col-md-4">
              <input type="radio" id="one" value="岡崎橋ビル" v-model="place"/>
              <label for="one">岡崎橋</label>
              <input type="radio" id="two" value="本社" v-model="place" />
              <label for="two">本社</label>
            </div>
            <div class="col-md-8">
              <input type="text" v-model="geo" style="width: 250px" placeholder="上記以外の場所検索はこちら" />
              <button id="geoButton" v-on:click="geocode">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div>
                <input type="checkbox"
                  id="checkBox1"
                  value="和食"
                  v-model="genres"
                />和食        
                <input
                  type="checkbox"
                  id="checkBox4"
                  value="居酒屋"
                  v-model="genres"
                />居酒屋
              </div>
              <div>
                <input
                  type="checkbox"
                  id="checkBox3"
                  value="中華"
                  v-model="genres"
                />中華
                <input
                  type="checkbox"
                  id="checkBox5"
                  value="ラーメン"
                  v-model="genres"
                />ラーメン
              </div>
              <div>
                <input
                  type="checkbox"
                  id="checkBox2"
                  value="洋食"
                  v-model="genres"
                />洋食
                <input
                  type="checkbox"
                  id="checkBox6"
                  value="弁当"
                  v-model="genres"
                />弁当
              </div>
            </div>
            <div class="col-md-8" style="float: left;">
              <input type="text" v-model="genreText" value="" placeholder="上記以外のジャンル検索はこちら" style="width: 250px" />
              <button id="searchButton" v-on:click="search">
                <i class="fas fa-search"></i>
              </button>
              <div>
                <select id="log" @change="changeSelect" style="width: 250px; margin-bottom: 20px;">
                  <option></option>
                </select>   
              </div>
            </div>
          </div>
          <div>
            <label>検索範囲</label>
            <input type="range" min="1" max="5" step="1" v-model="sliderNum" style="margin-bottom: 20px; margin-left: 10px" />
          </div>
          <div>
            <span v-if="sliderNum==1">半径：200m 片道：約3分</span>
            <span v-else-if="sliderNum==2">半径：400m 片道：約5分</span>
            <span v-else-if="sliderNum==3">半径：600m 片道：約8分</span>
            <span v-else-if="sliderNum==4">半径：800m 片道：約10分</span>
            <span v-else-if="sliderNum==5">半径：1km 片道：約13分</span>
            <label for="flexSwitchCheckDefault">検索範囲表示:</label>
            <input
              type="checkbox"
              id="flexSwitchCheckDefault"
              v-model="circleMode"
            />
          </div>
          <div class="row">
            <div class="col-md-12">
              <span class="span-header">レビュー一覧</span><router-link to="/create" class="btn btn-primary" @click="onResume(review)" style="float: right">投稿</router-link> 
                <table class="table table-sm" key="processes">
                    <thead>
                      <tr>
                        <th class="text-center bg-primary text-white" style="width: 30%;">投稿者</th>
                        <th class="text-center bg-primary text-white" style="width: 30%;">店名</th>
                        <th class="text-center bg-primary text-white" style="width: 30%;">コメント</th>
                        <th class="text-center bg-primary text-white" style="width: 10%;">削除</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(review, index) in reviews" :key="index" class="bg-white" style="font-size: 80%;">
                        <td class=""> {{ review.user_name }} </td>
                        <td class=""><a v-bind:href="'https://www.google.com/maps/search/?api=1&query=' + review.name" target="_blank">{{ review.name }}</a></td>
                        <td class="text-center align-middle" style="white-space: pre;">
                          <div v-if="review.comment.length > 10" @click="fullWindow(review.comment)">{{ review.comment|truncate }}<span style = "color: #00AEEF">...</span></div>
                          <div v-else>{{ review.comment|truncate }}</div>
                        </td>
                        <td>
                          <i class="fas fa-trash-alt fa-2x red d-flex justify-content-center" v-if="own.name === review.user_name" @click="onDelete(review.id)"></i>
                        </td>
                      </tr>
                    </tbody>
                </table>
                <pagination
                  :page="currentPage"
                  :items-per-page="itemsPerPage"
                  :max-visible-pages="maxVisiblePages"
                  :total-items="totalItems"
                  @pageChange="pageChange"
                />
              <!-- <div v-else><span class="span-header">マーカーにあるレビュー</span><router-link to="/create" class="btn btn-primary" @click="onResume(review)" style="float: right">投稿</router-link>
                <table class="table table-sm" key="processes">
                    <thead>
                      <tr>
                        <th class="text-center bg-primary text-white" style="width: 30%;">投稿者</th>
                        <th class="text-center bg-primary text-white" style="width: 30%;">店名</th>
                        <th class="text-center bg-primary text-white" style="width: 30%;">コメント</th>
                        <th class="text-center bg-primary text-white" style="width: 10%;">削除</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(review, index) in reviewsFilter" :key="index" class="bg-white" style="font-size: 80%;">
                        <td class=""> {{ review.user_name }} </td>
                        <td class=""><a v-bind:href="'https://www.google.com/maps/search/?api=1&query=' + review.name" target="_blank">{{ review.name }}</a></td>
                        <td class="text-center align-middle" style="white-space: pre;">
                          <div v-if="review.comment.length > 10" @click="fullWindow(review.comment)">{{ review.comment|truncate }}<span style = "color: #00AEEF">...</span></div>
                          <div v-else>{{ review.comment|truncate }}</div>
                        </td>
                        <td>
                          <i class="fas fa-trash-alt fa-2x red d-flex justify-content-center" v-if="own.name === review.user_name" @click="onDelete(review.id)"></i>
                        </td>
                      </tr>
                    </tbody>
                </table>
                <pagination
                  :page="currentPage"
                  :items-per-page="itemsPerPage2"
                  :max-visible-pages="maxVisiblePages"
                  :total-items="reviewsFilter.length"
                  @pageChange="pageChange"
                />
              <loading :active.sync="isLoading" :is-full-page="fullPage"></loading>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
          
  </div>
</template>
<script>
import Pagination from './commons/Pagination.vue';
export default {
  components: { Pagination },
  data() {
    return {
      maplocation:{lat:0, lng:0},
      openshop: [],
      nowMark: {
        position:{
          lat: 34.682910319991365,
          lng: 135.4893996106105,
        },
        iconUrl: 'images/nowMark.png',
      },
      markers:[],
      genres: [],
      logs: [],
      reviews: [],
      reviewsFilter: [],
      hoge: [],
      place: '岡崎橋ビル',
      markerCount: 0,
      genreCount: 6, 
      genreText: '',
      genreTextHoge: '',
      sliderNum: 3,
      range: 200,
      radius: 600,
      zoomMap: 15,
      circleMode: false,
      geo: '',
      geocoder: null,
      infoOptions: {
        // 吹き出しの表示位置調整
        pixelOffset: {
          width: 15,
          height: 0,
        }
      },
      infoWindowPos: null,
      infoWinOpen: false,
      infoMsg: '',

      own_user_name: "",
      currentPage: 0,
      itemsPerPage: 5,
      itemsPerPage2: 10,
      maxVisiblePages: 5,
      totalItems: 0,
      offset: 0,
      isLoading: false,
      fullPage: false,
      windowWidth: 0,
      sort: {
        key: "id", // ソートキー
        isAsc: false // 昇順ならtrue,降順ならfalse
      },
      show: false,
    }
  },
  async mounted() {
    // this.isLoading = true;
    this.calculateWindowWidth()
    window.addEventListener('resize', this.calculateWindowWidth)
    document.getElementById("geoButton").disabled = true
    document.getElementById("searchButton").disabled = true
    document.getElementById("log").disabled = true
    this.getItems()
    // 現在地の取得
    if (false) {
      navigator.geolocation.getCurrentPosition(
        function(position){
          let coords = position.coords;
          // 緯度経度を取得
          this.maplocation.lat = coords.latitude;
          this.maplocation.lng = coords.longitude;
          // 地図読み込み完了時のイベント
          this.$gmapApiPromiseLazy().then(() => {
            google.maps.event.addListenerOnce(this.$refs.mapRef.$mapObject, 'idle',
              function() { this.setPlaceMakers() }.bind(this)
            );
          });
        }.bind(this),
        function(error) {
          // エラーの場合は東京駅周辺に移動
          this.maplocation.lat = 35.6813092;
          this.maplocation.lng = 139.7677269;
        }
      );
    } else {
      // 現在地取得不可の場合は岡崎橋ビルを中心点
      this.maplocation.lat = 34.682910319991365
      this.maplocation.lng = 135.4893996106105
      this.$gmapApiPromiseLazy().then(() => {
        // geocoderのオブジェクト作成
        this.geocoder = new google.maps.Geocoder()

        google.maps.event.addListenerOnce(this.$refs.mapRef.$mapObject, 'idle',
          function() { /*this.setPlaceMakers()*/ }.bind(this)
        );
      });
    }

    $('[data-toggle="popover"]').popover();  //ver4.3の方法
    //スマホ画面が呼び出されたら
    if(this.windowWidth <= 799)
    {
      //画面が表示されてから100ms後にポップが表示される
      setTimeout(() => {
        this.show = true
        }
        ,100
      )
      // this.show = true
      //ポップが表示されてから3000ms後にポップを非表示にする
      setTimeout(() => {
        this.show = false
        }
        ,3000
      )
      
    }
    else if(this.windowWidth > 800)
    {
      this.show = false
    }
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.calculateWindowWidth);
  },
  methods: {
    pageChange(page, start, end) {
      if (end == 1) return
      this.currentPage = page
      this.offset = start
      this.getItems()
    },
    fullWindow(Message)
    {
      alert(Message)
    },
    onResume(review) {
      this.$router.push({ name: 'create', params: { ReviewId: Review.id } })
    },
    onBack: function () {
      this.$router.push({name: "menu"});
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
    handleResize: function() {
      if (window.innerwidth <= 800) {
        this.view = true
      } else {
        this.view = false
      }
    },
    calculateWindowWidth() {
      this.windowWidth = window.innerWidth;
    },

    async getItems() {
      this.isLoading = true
      const { data } = await axios.get('/api/review', {
        params: {
        offset: this.offset,
        limit: this.itemsPerPage,
        limit1: this.itemsPerPage2,
        sort: this.sort,
        },
      })
      this.reviews = data.reviews
      this.totalItems = data.total_items
      this.isLoading = false
    },
    async sortBy(key) {
      this.sort.isAsc = this.sort.key === key ? !this.sort.isAsc : true;
      this.sort.key = key;
      await this.getItems();
    },
    sortedClass(key) {
      return this.sort.key === key ? `sorted ${this.sort.isAsc ? "asc" : "desc"}`: ""
    },
    changeSelect() {
      this.genreText = document.getElementById("log").value
    },
    setPlaceMarkers(genre) {
      // ゴリ押しズレ直し
      this.infoOptions = {pixelOffset:{width: 0, height: -38}}

      let map = this.$refs.mapRef.$mapObject
      let placeService = new google.maps.places.PlacesService(map);
      // Places APIのnearbySearchを使用する。
      placeService.nearbySearch(
        {
          location: new google.maps.LatLng(this.maplocation.lat, this.maplocation.lng),
          radius: this.radius,
          type: '',
          keyword: genre,
        },
        function(results, status) {
          if (status == google.maps.places.PlacesServiceStatus.OK) {
            results.forEach(place => {

              let iconUrl
              switch(genre) {
                case'和食':
                  iconUrl = 'images/wasyokuIcon.png'
                  break
                case'洋食':
                  iconUrl = 'images/yousyokuIcon.png'
                  break
                case'中華':
                  iconUrl = 'images/tyuukaIcon.png'
                  break
                case'弁当':
                  iconUrl = 'images/bentouIcon.png'
                  break
                case'居酒屋':
                  iconUrl = 'images/izakayaIcon.png'
                  break
                case'ラーメン':
                  iconUrl = 'images/ra-menIcon.png'
                  break
                default:
                  iconUrl = 'images/sonotaIcon.png'
              }

              let icon = {
                url: iconUrl, // url
                // scaledSize: new google.maps.Size(30, 30), // scaled size
                origin: new google.maps.Point(0, 0), // origin
                anchor: new google.maps.Point(12, 38) // anchor
              };


              let marker = {
                position: place.geometry.location,
                icon: icon,
                title: place.name,
                place_id: place.place_id,
              };
              this.markers.push(marker);

              
              this.reviewsFilter = this.reviews
              this.reviewsFilter.forEach(value => {
                if(value.name == marker.title) {
                  this.hoge.push(value)
                }
              })
              this.reviewsFilter = this.hoge

            });
          }
        }.bind(this)
      );
    },
    async search() {
      this.resetGenre()
      this.genres = []

      const select = document.getElementById("log")

      let result = ''
      let selectOption = 0
      for(let i = 0;i < this.logs.length;i++) {
        if(this.logs[i] == this.genreText) {
          result = this.genreText
          selectOption = i
        }
      }
      // const result = this.logs.filter(function(value){
      //   return value === this.genreText
      // }.bind(this))
      // 同じものが無ければ
      if(result.length === 0)
      {
        // 同じ文字列を追加しないようにこちらで制御
        this.logs.unshift(this.genreText)
        const option = document.createElement("option")
        option.text = this.genreText
        option.value = this.genreText
        // ここで要素を二番目に追加
        select.add(option, 1)
      }
      // プルダウンの項目数
      this.optionCount = select.length

      if(select.length > 6)
      {
        // 0から数えて6番目の要素削除
        select.remove(6)
        // 末尾削除
        this.logs.pop()
      }
      else if(select.length > 0)
      {
        select.disabled = false
      }
      else {
        select.disabled = true
      }

      this.markers = []
      this.hoge = []
      this.setPlaceMarkers(this.genreText)

      this.genreTextHoge = this.genreText
      this.genreText = ''

      // 空白分があるため+1をする
      document.getElementById("log").options[selectOption+1].selected = true;
    },
    async geocode() {
      this.geocoder.geocode({
        'address': this.geo
      },(results, status) => {
        if(status === google.maps.GeocoderStatus.OK) {
          this.maplocation.lat = results[0].geometry.location.lat()
          this.maplocation.lng = results[0].geometry.location.lng()
          this.nowMark.position.lat = results[0].geometry.location.lat()
          this.nowMark.position.lng = results[0].geometry.location.lng()

          if(this.genres.length > 0) {
          this.genresFunc()
          }
          else if(this.genreTextHoge != ''){
            this.markers = []
            this.setPlaceMarkers(this.genreTextHoge)
          }
        }
      })

      this.geo = ''
      document.getElementById("geoButton").disabled = true
    },
    toggleInfoWindow(marker) {
      let msg = ''
      let map = this.$refs.mapRef.$mapObject

      new google.maps.places.PlacesService(map).getDetails({
        placeId: marker.place_id,
        fields: ['opening_hours','utc_offset_minutes'],

      }, function (place, status) {
        let todayDay = new Date().getDay()
        //googleMapの曜日とgetDayの曜日を統一する処理
        if(todayDay == 0) {
          todayDay += 6
        }
        else {
          todayDay--
        }

        if (status !== 'OK') return // something went wrong

        if(place.opening_hours != undefined) {
          if(place.opening_hours.weekday_text != undefined) {
            msg += place.opening_hours.weekday_text[todayDay] + '<br>'
          }
         
          if (place.opening_hours.isOpen() == true) {
            msg += '営業しています'
          }
          else {
            msg += '休業もしくは閉店しています'
          }
        }
        else {
          msg += '営業情報が取得できませんでした'
        }
         
        this.infoMsg = `${marker.title}<br><a href="https://www.google.com/maps/search/?api=1&query=${marker.title}"target="_blank" >Googleマップで見る</a><br>${msg}`
        this.infoMsg += `<br><button onclick='location.href="/create/${marker.title}"' value=''>レビューを投稿</button>`
        this.infoWindowPos = marker.position;
        this.infoWinOpen = true;
      }.bind(this));

     
    },
    genresFunc() {
      // マーカーを一度削除
      this.markers = []
      this.hoge = []

      if(this.genres.length > 0) {
        for(let i=0;i < this.genres.length;i++) {
          this.setPlaceMarkers(this.genres[i])
        }
      }
      else {
        this.reviewsFilter = []
      }
    },
    async randomGenre() {
      this.genres = []
      this.resetGenre()
      let randNum = Math.floor(Math.random() * this.genreCount)

      switch(randNum) {
        case 0:
          document.getElementById("checkBox1").checked = true
          this.genres.push("和食")
          break
        case 1:
          document.getElementById("checkBox2").checked = true
          this.genres.push("洋食")
          break
        case 2:
          document.getElementById("checkBox3").checked = true
          this.genres.push("中華")
          break
        case 3:
          document.getElementById("checkBox4").checked = true
          this.genres.push("居酒屋")
          break
        case 4:
          document.getElementById("checkBox5").checked = true
          this.genres.push("ラーメン")
          break
        case 5:
          document.getElementById("checkBox6").checked = true
          this.genres.push("弁当")
          break
      }
    },
    async randomRest(count) {
      if(count < 10) {
        this.infoWinOpen=false
        this.resetGenre()

        let randNum = Math.floor(Math.random() * this.genreCount)
        let randGenre
        switch(randNum) {
          case 0:
            randGenre = "和食"
            break
          case 1:
            randGenre = "洋食"
            break
          case 2:
            randGenre = "中華"
            break
          case 3:
            randGenre = "ラーメン"
            break
          case 4:
            randGenre = "弁当"
            break
          case 5:
            randGenre = "居酒屋"
            break
        }

        let iconUrl
        switch(randGenre) {
          case'和食':
            iconUrl = 'images/wasyokuIcon.png'
            break
          case'洋食':
            iconUrl = 'images/yousyokuIcon.png'
            break
          case'中華':
            iconUrl = 'images/tyuukaIcon.png'
            break
          case'弁当':
            iconUrl = 'images/bentouIcon.png'
            break
          case'居酒屋':
            iconUrl = 'images/izakayaIcon.png'
            break
          case'ラーメン':
            iconUrl = 'images/ra-menIcon.png'
            break
          default:
            iconUrl = 'images/sonotaIcon.png'
        }

        // //ランダムジャンルで店舗検索
        let map = this.$refs.mapRef.$mapObject
        let placeService = new google.maps.places.PlacesService(map)
        // // Places APIのnearbySearchを使用する。
        placeService.nearbySearch(
          {
            location: new google.maps.LatLng(this.maplocation.lat, this.maplocation.lng),
            radius: this.radius/2,
            type: '',
            keyword: randGenre,
          },
          
          function(results, status) {
            if (status == google.maps.places.PlacesServiceStatus.OK) {
              const msg = '営業しています'
              let randRest = Math.floor(Math.random() * results.length)
              let i = 0
              this.markers = []
              results.forEach(place => {
                if(i == randRest) {

                  let map = this.$refs.mapRef.$mapObject

                  new google.maps.places.PlacesService(map).getDetails({
                    placeId: place.place_id,
                    fields: ['opening_hours','utc_offset_minutes'],

                  }, function (place, status) {

                    if (status !== 'OK') return // something went wrong

                    if(place.opening_hours != undefined) {
                      if (place.opening_hours.isOpen() == false) {
                        this.randomRest(count+1)
                      }
                    }
                    else {
                      this.randomRest(count+1)
                    }

                    this.infoMsg = `${place.title}<br><a href="https://www.google.com/maps/search/?api=1&query=${place.title}"target="_blank" >Googleマップで見る</a><br>${msg}`
                    this.infoMsg += `<br><button onclick='location.href="/create/${place.title}"' value=''>レビューを投稿</button>`
                    this.infoWindowPos = place.position;
                    this.infoWinOpen = true;
                  }.bind(this));

                  let marker = {
                    position: place.geometry.location,
                    icon: iconUrl,
                    title: place.name,
                    place_id: place.place_id,
                  }
                  this.markers.push(marker);
                }

                i++
              })
            }
          }.bind(this)
        )
        //もしものゴリ押しズレ直し
        this.infoOptions = {pixelOffset:{width: -1, height: -36,}}
      }
      else {
        alert('近くに営業しているお店が見つかりませんでした。\nもう一度お試しください。')
      }
      
    },
    async resetGenre() {
      this.genres = []
      document.getElementById("checkBox1").checked = false
      document.getElementById("checkBox2").checked = false
      document.getElementById("checkBox3").checked = false
      document.getElementById("checkBox4").checked = false
      document.getElementById("checkBox5").checked = false
      document.getElementById("checkBox6").checked = false
    }
  },
  computed: {
    own() {
      return this.$store.state.user;
    },
  },
  created() {
    window.addEventListener('resize', this.handleResize)
    this.handleResize()
  },
  destroyed() {
    window.removeEventListener('resize', this.handleResize)
  },
  filters: {
    truncate: function(value) {
      const length = 10;  //区切る文字数
      const ommision = ''; //語尾
      if (value.length <= length) {
        return value;
      }
      //omissionの部分だけシアン(=#00AEEF)にしたい
      return value.substring(0, length) + ommision;
    }
  },
  watch: {
    place: async function(){

      if(this.place == '岡崎橋ビル')
      {
        this.maplocation.lat = 34.682910319991365
        this.maplocation.lng = 135.4893996106105
        this.nowMark.position.lat = 34.682910319991365
        this.nowMark.position.lng = 135.4893996106105
        if(this.genres.length > 0) {
          this.genresFunc()
        }
        else if(this.genreTextHoge != ''){
          this.markers = []
          this.setPlaceMarkers(this.genreTextHoge)
        }
      }
      else
      {
        this.maplocation.lat = 34.67981481380863
        this.maplocation.lng = 135.49274708362623
        this.nowMark.position.lat = 34.67981481380863
        this.nowMark.position.lng = 135.49274708362623
        if(this.genres.length > 0) {
          this.genresFunc()
        }
        else if(this.genreTextHoge != ''){
          this.markers = []
          this.setPlaceMarkers(this.genreTextHoge)
        }
      }
      this.infoWinOpen=false
    },
    genres: async function(){
      if(this.genres.length > 0) {
        document.getElementById("log").options[0].selected = true;
        this.genreTextHoge = ''
      }

      this.genresFunc()
      this.infoWinOpen=false
    },
    genreText: async function() {
      if(this.genreText != undefined) {
        if(this.genreText != '') {
          document.getElementById("searchButton").disabled = false
        }
        else {
          document.getElementById("searchButton").disabled = true
        }
      }
      else {
        document.getElementById("searchButton").disabled = true
      }
      
    },
    geo: async function() {
      if(this.geo.length > 0) {
        document.getElementById("geoButton").disabled = false
      }
      else {
        document.getElementById("geoButton").disabled = true
      }
      this.infoWinOpen=false
    },
    sliderNum: async function(){
      this.infoWinOpen=false
      let searchRange = this.sliderNum * this.range
      this.radius = searchRange

      switch(this.sliderNum) {
        case '1':
          this.zoomMap = 16.2
          break
        case '2':
          this.zoomMap = 15.5
          break
        case '3':
          this.zoomMap = 15
          break
        case '4':
          this.zoomMap = 14.6
          break
        case '5':
          this.zoomMap = 14.28
          break
        default:
          this.zoomMap = 15
      }
      setTimeout(() => {
        if(this.genres.length > 0) {
          this.genresFunc()
        }
        else if(this.genreTextHoge != ''){
          this.markers = []
          this.setPlaceMarkers(this.genreTextHoge)
        }
      },500)
      
    },
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
.red {
    color:red;
}
.relative {
    position: relative;
}
.absolute {
    position: absolute;
    right: 0%;
}
.absolute1 {
    position: absolute;
    right:5%;
}
.absolute2 {
  position: absolute;
  right: 0%;
  top: 10%;
}
.absolute3 {
  position: absolute;
  right: 4%;
  top: 10%;
}
.sort-clicable {
  cursor: pointer;
  position: relative;
}

// body {
//   font-family: "Dosis", Helvetica, Arial, sans-serif; 
//   background: #ecf0f1;
//   color: #34495e;
//   padding-top: 40px;
//   text-shadow: white 1px 1px 1px;
// }
input[type="range"] {
  -webkit-appearance: none;
  background-color: #bdc3c7;
  width: 200px;
  height: 5px;
  border-radius: 5px;
//   outline: 0;
}
input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  background-color: #007bff;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  border: 2px solid white;
  cursor: pointer;
  transition: .3s ease-in-out;
}​
input[type="range"]::-webkit-slider-thumb:hover {
  background-color: white;
  border: 2px solid #007bff;
}
input[type="range"]::-webkit-slider-thumb:active {
  transform: scale(1.2);
}
.clear-decoration {
    border: none;  /* 枠線を消す */
    outline: none; /* クリックしたときに表示される枠線を消す */
    background: transparent; /* 背景の灰色を消す */
}
</style>