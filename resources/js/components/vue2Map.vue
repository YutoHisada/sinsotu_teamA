<template>
  <div>
    sinki
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
     style="width: 500px; height: 300px"
     ref="mapRef" 
    >

      <GmapInfoWindow
        :options="infoOptions"
        :position="infoWindowPos"
        :opened="infoWinOpen"
        @closeclick="infoWinOpen=false"
      ><div v-html="infoMsg"></div>
      </GmapInfoWindow>

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
    </GmapMap>

    <input type="text" id="geo" v-model="geo" style="width: 200px">
    <button id="geoButton" v-on:click="geocode"><i class="fas fa-search"></i></button>

    <div class="form-check">
      <input type="radio" id="one" value="岡崎橋ビル" v-model="place">
      <label for="one">岡崎橋ビル</label>
    </div>
    <div class="form-check">
      <input type="radio" id="two" value="本社" v-model="place">
      <label for="two">本社</label>
    </div>

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

    <input type="text" id="genreTextID" v-model="genreText" value="" style="width: 200px">
    <button id="searchButton" v-on:click="search"><i class="fas fa-search"></i></button>
    <br>
    <select id="log" v-model="genreText" style="width: 200px">
    </select>
    <br>
    <input type="range" min="1" max="5" step="1" v-model="sliderNum" style="width: 200px">
    <br>
    <span v-if="sliderNum==1">半径：200m 徒歩片道：約3分</span>
    <span v-else-if="sliderNum==2">半径：400m 徒歩片道：約5分</span>
    <span v-else-if="sliderNum==3">半径：600m 徒歩片道：約8分</span>
    <span v-else-if="sliderNum==4">半径：800m 徒歩片道：約10分</span>
    <span v-else-if="sliderNum==5">半径：1km 徒歩片道：約13分</span>
    <br>

    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" v-model="circleMode">
      <label v-if="circleMode" for="flexSwitchCheckDefault">円描画ON</label>
      <label v-else for="flexSwitchCheckDefault">円描画OFF</label>
    </div>

    <router-link 
      to="/create" 
      class="btn btn-primary"
      @click="onResume(review)"
      >投稿
    </router-link>

    <div v-if="reviewsFilter.length == 0"><span class="span-header">全てのレビュー</span></div>
    <div v-else><span class="span-header">マーカーにあるレビュー</span></div>

    <table class="table table-sm" key="processes">
            <div v-if="reviewsFilter.length == 0">
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
                  <td class="text-center align-middle">
                    <a v-bind:href="'https://www.google.com/maps/search/?api=1&query=' + review.name">{{ review.name }}</a>
                  </td>
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
    
            
          
  </div>
</template>
<script>
export default {
  data() {
    return {
      maplocation:{lat:0, lng:0},
      openshop: [],
      markers:[],
      genres: [],
      logs: [],
      reviews: [],
      reviewsFilter: [],
      hoge: [],
      place: '岡崎橋ビル',
      markerCount: 0,
      genreText: '',
      genreTextHoge: '',
      keepKeyword: '',
      sliderNum: 3,
      range: 200,
      radius: 400,
      zoomMap: 15,
      circleMode: false,
      geo: '',
      geocoder: null,
      infoOptions: {
        //吹き出しの表示位置調整
        pixelOffset: {
          width: 15,
          height: 0,
        }
      },
      infoWindowPos: null,
      infoWinOpen: false,
      infoMsg: '',

    }
  },
  async mounted() {
    document.getElementById("geoButton").disabled = true
    document.getElementById("searchButton").disabled = true
    document.getElementById("log").disabled = true
    this.getItems();
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
  },
  methods: {
    onResume(review) {
      this.$router.push({ name: 'create', params: { ReviewId: Review.id } })
    },
    onBack: function () {
      this.$router.go(-1)
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
    setPlaceMarkers(genre) {
      this.keepKeyword = genre
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

              // new google.maps.places.PlacesService(map).getDetails({
              //   placeId: place.place_id,
              //   fields: ['opening_hours','utc_offset_minutes'],
              //   }, function (place, status) {
              //     console.log(place)
              //     if (status !== 'OK') return; // something went wrong
              //     const isOpenAtTime = place.opening_hours.isOpen(new Date());
              //     if (isOpenAtTime) {
              //         // We know it's open.
              //         // console.log(isOpenAtTime)
              //     }

              //     const isOpenNow = place.opening_hours.isOpen();
              //     if (isOpenNow) {
              //         // We know it's open.
              //         // console.log(isOpenNow)
              //     }
              // });

              let iconUrl
              switch(genre) {
                case'和食':
                  iconUrl = 'images/green.png'
                  break
                case'ラーメン':
                  iconUrl = 'images/orange.png'
                  break
                default:
                  iconUrl = 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
              }

              // デフォルトのアイコンが大きめなので縮小
              let icon = {
                url: iconUrl, // url
                // scaledSize: new google.maps.Size(30, 30), // scaled size
                origin: new google.maps.Point(0,0), // origin
                anchor: new google.maps.Point(0, 0) // anchor
              };

              let msg = ''
              if(place.opening_hours == undefined || place.opening_hours.open_now == undefined) {
                msg = "営業情報が取得出来ませんでした"
              } 
              else {
                if(place.opening_hours.open_now == true) {
                  msg = "営業しています"
                }
                else {
                  msg ="休憩もしくは閉店しています"
                }
              }

              let marker = {
                position: place.geometry.location,
                icon: icon,
                title: place.name,
                id: place.place_id,
                open: msg
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
      document.getElementById("checkBox1").checked = false
      document.getElementById("checkBox2").checked = false
      document.getElementById("checkBox3").checked = false
      document.getElementById("checkBox4").checked = false
      document.getElementById("checkBox5").checked = false
      document.getElementById("checkBox6").checked = false
      document.getElementById("checkBox7").checked = false
      this.genres = []

      const select = document.getElementById("log")
      const result = this.logs.filter(function(value){
        return value === this.genreText
      }.bind(this))
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
      else {
        select.disabled = true
      }

      this.markers = []
      this.hoge = []
      this.setPlaceMarkers(this.genreText)

      this.genreTextHoge = this.genreText
      this.genreText = ''
      document.getElementById("searchButton").disabled = true
    },
    async geocode() {
      this.geocoder.geocode({
        'address': this.geo
      },(results, status) => {
        if(status === google.maps.GeocoderStatus.OK) {
          this.maplocation.lat = results[0].geometry.location.lat();
          this.maplocation.lng = results[0].geometry.location.lng();

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
    },
    toggleInfoWindow(marker) {
      this.infoMsg = `${marker.title}<br><a href="https://www.google.com/maps/search/?api=1&query=${marker.title}"target="_blank" >Googleマップで見る</a><br>${marker.open}`
      this.infoMsg += `<br><button onclick='location.href="/create/${marker.title}"' value=''>レビューを投稿</button>`
      // var div = document.createElement('div')
      // div.style.display = 'none'
      // div.innerHTML = this.infoMsg
      // this.infoMsg = div
      this.infoWindowPos = marker.position;
      this.infoWinOpen = true;
    },
    genresFunc() {
      // マーカーを一度削除
      this.markers = []
      this.hoge = []

      if(this.genres.length > 0) {
        for(var i=0;i < this.genres.length;i++) {
          this.setPlaceMarkers(this.genres[i])
        }
      }
      else {
        this.reviewsFilter = []
      }
    }
  },
  computed: {
    sortReviews() {
      if(this.reviewsFilter.length == 0) {
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
      if(this.reviewsFilter.length == 0) {
        return Math.ceil(this.reviews.length / this.parpage)
      }
      else {
        return Math.ceil(this.reviewsFilter.length / this.parpage)
      }
    }
  },
  watch: {
    place: async function(){
      console.log(this.keepKeyword)

      if(this.place == '岡崎橋ビル')
      {
        this.maplocation.lat = 34.682910319991365
        this.maplocation.lng = 135.4893996106105
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
        if(this.genres.length > 0) {
          this.genresFunc()
        }
        else if(this.genreTextHoge != ''){
          this.markers = []
          this.setPlaceMarkers(this.genreTextHoge)
        }
      }
    },
    genres: async function(){
      if(this.genres.length > 0) {
        this.genreTextHoge = ''
      }

      this.genresFunc()
    },
    genreText: async function() {
      if(this.genreText.length > 0) {
        document.getElementById("searchButton").disabled = false
      }
      else {
        document.getElementById("searchButton").disabled = true
      }
    },
    geo: async function() {
      if(document.getElementById("geo").value.length > 0) {
        document.getElementById("geoButton").disabled = false
      }
      else {
        document.getElementById("geoButton").disabled = true
      }
    },
    sliderNum: async function(){
      let searchRange = this.sliderNum * this.range
      this.radius = searchRange

      switch(this.sliderNum) {
        case '1':
          this.zoomMap = 16.5
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

      if(this.genres.length > 0) {
        this.genresFunc()
      }
      else if(this.genreTextHoge != ''){
        this.markers = []
        this.setPlaceMarkers(this.genreTextHoge)
      }
    },
  }
}
</script>
