<template>
 <div>
      <input type="radio" id="one" value="岡崎橋ビル" v-model="place">
      <label for="one">岡崎橋ビル</label>
      <br>
      <input type="radio" id="two" value="本社" v-model="place">
      <label for="two">本社</label>
      <br>
      <span>Picked: {{ place }}</span>
      <br>
      <div class="map" ref="googleMap" />
      <div class="mr-auto">
        <span class="span-header">レビュー一覧</span>　　　
      </div>
      <router-link 
            to="/create" 
            class="btn btn-primary"
            @click="onResume(review)"
            >投稿</router-link>　
      <table class="table table-sm" key="processes">
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
                            <tr v-for="(review, index) in getReviews" :key=index class="bg-white">
                                <!--<td class="text-center align-middle">{{ inventory.id }}</td>-->
                                <td class="text-center align-middle">{{ review.user_name }}</td>
                                <td class="text-center align-middle">{{ review.name }}</td>
                                <td class="text-center align-middle" style="white-space: pre;">{{ review.comment }}</td>
                                <td><button type="button" class="btn btn-danger" v-show="own.name === review.user_name" @click="onDelete(review.id)">削除</button></td>
                            </tr>
                        </tbody>
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
</template>

<script>
import MgPaginate from 'vue-mg-paginate'
import GoogleMapsApiLoader from 'google-maps-api-loader';
export default {
  name: 'Map',
  data() {
    return {
      reviews: [],
      pagenate: ['paginate-log'],
      place: '岡崎橋ビル', 
      selected: null,
      google: null,
      own_user_name: '',
      parpage: 5,
      currentPage: 1,
      isLoading: false,
      fullPage: false,
      
      mapConfig: {
        // 地図の中心地点
        center: {
          lat: 34.68286620763049,
          lng: 135.48942106828244
        },
        // 拡大率
        zoom: 17,
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
      }
    }
  },
  async mounted() {
    this.google = await GoogleMapsApiLoader({
    //   apiKey: 'AIzaSyDoT7ZAEG-K-twxebevNoXlOXY6P7v69GA'
    });
    this.initializeMap();
    this.getItems();
  },
  methods: {
    initializeMap() {
      new this.google.maps.Map(this.$refs.googleMap, this.mapConfig);
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
      place: function(){
          if(this.place == '岡崎橋ビル')
          {
              this.mapConfig.center.lat = 34.682910319991365
              this.mapConfig.center.lng = 135.4893996106105
              // マップ再読み込み
              this.initializeMap();
          }
          else
          {
              this.mapConfig.center.lat = 34.67981481380863
              this.mapConfig.center.lng = 135.49274708362623
              // マップ再読み込み
              this.initializeMap();
          }
      }
  },
  computed: {
    sortReviews() {
      return this.reviews.slice().reverse();
    },
    own() {
      return this.$store.state.user
    },
    getReviews() {
      let current = this.currentPage * this.parpage;
      let start = current - this.parpage;
      return this.reviews.slice(start, current).reverse();
    },
    getPageCount() {
      return Math.ceil(this.reviews.length / this.parpage)
    }
  }
}
</script>

<style lang="scss" scoped>
// ダサいので円形表示は保留 border-radius: 250px;
.map {
  width: 500px;
  height: 500px;
}
</style>