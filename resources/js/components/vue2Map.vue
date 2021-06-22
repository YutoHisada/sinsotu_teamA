<template>
  <div>
    sinki
    <GmapMap
     :center="maplocation"
     :zoom="15"
     :draggable="true"
     map-type-id="roadmap"
     style="width: 500px; height: 300px"
     ref="mapRef" 
    >

      <GmapInfoWindow
        :options="infoOptions"
        :position="infoWindowPos"
        :opened="infoWinOpen"
        @closeclick="infoWinOpen=false"
      >{{this.infoMsg}}
      </GmapInfoWindow>

      <GmapMarker v-for="m in makers"
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

    <input type="text" v-model="geo" style="width: 200px">
    <button v-on:click="geocode"><i class="fas fa-search"></i></button>

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

    <input type="text" v-model="genreText" value="" style="width: 200px">
    <button id="search" v-on:click="search"><i class="fas fa-search"></i></button>
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

  </div>
</template>
<script>
export default {
  data() {
    return {
      maplocation:{lat:0, lng:0},
      makers:[],
      genres: [],
      place: '岡崎橋ビル',
      genreText: '',
      sliderNum: 3,
      range: 200,
      radius: 400,
      circleMode: false,
      geo: '',
      geocoder: null,
      infoOptions: {
        pixelOffset: {
          width: 0,
          height: -35
        }
      },
      infoWindowPos: null,
      infoWinOpen: false,
      infoMsg: '',

    }
  },
  async mounted() {

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
      // 現在地取得不可の場合は東京駅周辺に移動
      this.maplocation.lat = 35.6813092;
      this.maplocation.lng = 139.7677269;
      this.$gmapApiPromiseLazy().then(() => {
        // geocoderのオブジェクト作成
        this.geocoder = new google.maps.Geocoder()
        google.maps.event.addListenerOnce(this.$refs.mapRef.$mapObject, 'idle',
          function() { this.setPlaceMakers() }.bind(this)
        );
      });
    }
  },
  methods: {
    setPlaceMakers(genre) {
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
              // デフォルトのアイコンが大きめなので縮小
              let icon = {
                url: place.icon, // url
                scaledSize: new google.maps.Size(30, 30), // scaled size
                origin: new google.maps.Point(0,0), // origin
                anchor: new google.maps.Point(0, 0) // anchor
              };
              let maker = {
                position: place.geometry.location,
                icon: icon,
                title: place.name,
                id: place.place_id
              };
              this.makers.push(maker);
            });
          }
        }.bind(this)
      );
    },
    async search() {
      this.makers = []
      this.setPlaceMakers(this.genreText)
    },
    async geocode() {
      this.geocoder.geocode({
        'address': this.geo
      },(results, status) => {
        if(status === google.maps.GeocoderStatus.OK) {
          this.maplocation.lat = results[0].geometry.location.lat();
          this.maplocation.lng = results[0].geometry.location.lng();
        }
      })
    },
    toggleInfoWindow(marker) {
      console.log(marker)
      this.infoMsg = marker.title
      
      // var div = document.createElement('div')
      // div.style.display = 'none'
      // div.innerHTML = this.infoMsg
      // this.infoMsg = div
      this.infoWindowPos = marker.position;
      this.infoWinOpen = true;
    }
  },
  watch: {
    place: async function(){
      if(this.place == '岡崎橋ビル')
      {
        this.maplocation.lat = 34.682910319991365
        this.maplocation.lng = 135.4893996106105
        // マップ再読み込み
        // this.initializeMap();
      }
      else
      {
        this.maplocation.lat = 34.67981481380863
        this.maplocation.lng = 135.49274708362623
        // マップ再読み込み
        // this.initializeMap();
      }
    },
    genres: async function(){
      // マーカーを一度削除
      this.makers = []
      

      if(this.genres.length > 0) {
        for(var i=0;i < this.genres.length;i++) {
          this.setPlaceMakers(this.genres[i])
        }
      }
      else {
        // this.markerCount = 0
        // this.getReviewsFunc()
        // this.initializeMap()
      }
    },
    sliderNum: async function(){
      let searchRange = this.sliderNum * this.range
      this.radius = searchRange
    },
  }
}
</script>
