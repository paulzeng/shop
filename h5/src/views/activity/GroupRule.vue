<template>
  <div class="group-con">
    <div class="header acea-row row-between-wrapper">
      <div class="pictrue">
        <img :src="storeCombination.image" />
      </div>
      <div class="text">
        <div class="line1" v-text="storeCombination.title"></div>
        <div class="money">
          ￥
          <span class="num" v-text="storeCombination.price"></span>
          <span
            class="team cart-color"
            v-text="storeCombination.people + '人拼'"
          ></span>
        </div>
      </div>
      <div v-if="pinkBool === -1" class="iconfont icon-pintuanshibai"></div>
      <div
        v-else-if="pinkBool === 1"
        class="iconfont icon-pintuanchenggong font-color-red"
      ></div>
    </div>
    <div class="wrapper">
      <div class="title acea-row row-center-wrapper">
        <div class="line"></div>
        <div class="name acea-row row-center-wrapper">
          剩余
          <CountDown
            :is-day="false"
            :tip-text="''"
            :day-text="''"
            :hour-text="' : '"
            :minute-text="' : '"
            :second-text="''"
            :datatime="pinkT.stop_time"
          ></CountDown
          >结束
        </div>
        <div class="line"></div>
      </div>
      <div class="tips font-color-red" v-if="pinkBool === 1">
        恭喜您拼团成功
      </div>
      <div class="tips" v-else-if="pinkBool === -1">
        还差{{ count }}人，拼团失败
      </div>
      <div class="tips font-color-red" v-else-if="pinkBool === 0">
        拼团中，还差{{ count }}人拼团成功
      </div>
      <div
        class="list acea-row row-middle"
        :class="[
          pinkBool === 1 || pinkBool === -1 ? 'result' : '',
          iShidden ? 'on' : ''
        ]"
      >
        <div class="pictrue">
          <img :src="pinkT.avatar" />
        </div>
        <div class="acea-row row-middle" v-if="pinkAll.length > 0">
          <div class="pictrue" v-for="(item, index) in pinkAll" :key="index">
            <img :src="item.avatar" />
          </div>
        </div>
        <div class="pictrue" v-for="index in count" :key="index">
          <img class="img-none" src="@assets/images/vacancy.png" />
        </div>
      </div>
      <div
        v-if="(pinkBool === 1 || pinkBool === -1) && count > 9"
        class="lookAll acea-row row-center-wrapper"
        @click="lookAll"
      >
        {{ iShidden ? "收起" : "查看全部" }}
        <span
          class="iconfont"
          :class="iShidden ? 'icon-xiangshang' : 'icon-xiangxia'"
        ></span>
      </div>
      <div
        class="teamBnt bg-color-red"
        v-if="userBool === 1 && isOk == 0 && pinkBool === 0"
        @click="goPoster"
      >
        邀请好友参团
      </div>
      <div
        class="teamBnt bg-color-red"
        v-else-if="userBool === 0 && pinkBool === 0 && count > 0"
        @click="pay"
      >
        我要参团
      </div>
      <div
        class="teamBnt bg-color-red"
        v-if="pinkBool === 1 || pinkBool === -1"
        @click="goDetail(storeCombination.id)"
      >
        再次开团
      </div>
      <div
        class="cancel"
        @click="getCombinationRemove"
        v-if="pinkBool === 0 && userBool === 1"
      >
        <span class="iconfont icon-guanbi3"></span>取消开团
      </div>
      <div class="lookOrder" v-if="pinkBool === 1" @click="goOrder">
        查看订单信息
        <span class="iconfont icon-xiangyou"></span>
      </div>
    </div>
    <div class="group-recommend">
      <div class="title acea-row row-between-wrapper">
        <div>大家都在拼</div>
        <div class="more" @click="goList">
          更多拼团
          <span class="iconfont icon-jiantou"></span>
        </div>
      </div>
      <div class="list acea-row row-middle">
        <div
          class="item"
          v-for="(item, index) in storeCombinationHost"
          :key="index"
          @click="goDetail(item.id)"
        >
          <div class="pictrue">
            <img :src="item.image" />
            <div class="team" v-text="item.people + '人团'"></div>
          </div>
          <div class="name line1" v-text="item.title"></div>
          <div class="money font-color-red" v-text="'￥' + item.price"></div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import CountDown from "@components/CountDown";
import { getCombinationPink, getCombinationRemove } from "@api/activity";
import { postCartAdd } from "@api/store";

const NAME = "GroupRule";
export default {
  name: NAME,
  components: {
    CountDown
  },
  props: {},
  data: function() {
    return {
      currentPinkOrder: "", //当前拼团订单
      isOk: 0, //判断拼团是否完成
      pinkBool: 0, //判断拼团是否成功|0=失败,1=成功
      userBool: 0, //判断当前用户是否在团内|0=未在,1=在
      pinkAll: [], //团员
      pinkT: [], //团长信息
      storeCombination: [], //拼团产品
      storeCombinationHost: [], //拼团推荐
      pinkId: 0,
      count: 0, //拼团剩余人数
      iShidden: false
    };
  },
  watch: {
    $route(n) {
      var that = this;
      if (n.name === NAME) {
        that.pinkId = that.$route.params.id;
        that.getCombinationPink();
      }
    }
  },
  mounted: function() {
    var that = this;
    that.pinkId = that.$route.params.id;
    that.getCombinationPink();
  },
  methods: {
    pay: function() {
      var that = this;
      var data = {};
      data.productId = that.storeCombination.product_id;
      data.cartNum = that.pinkT.total_num;
      data.uniqueId = "";
      data.combinationId = that.storeCombination.id;
      data.new = 1;
      postCartAdd(data)
        .then(res => {
          that.$router.push({
            path: "/order/submit/" + res.data.cartId,
            query: { pinkid: that.pinkId }
          });
        })
        .catch(res => {
          this.$dialog.error(res.msg);
        });
    },
    goPoster: function() {
      var that = this;
      this.$router.push({
        path: "/activity/poster/" + that.pinkId + "/1"
      });
    },
    goOrder: function() {
      var that = this;
      this.$router.push({
        path: "/order/detail/" + that.currentPinkOrder
      });
    },
    //拼团列表
    goList: function() {
      this.$router.push({
        path: "/activity/group"
      });
    },
    //拼团详情
    goDetail: function(id) {
      this.$router.push({
        path: "/activity/group_detail/" + id
      });
    },
    //拼团信息
    getCombinationPink: function() {
      var that = this;
      getCombinationPink(that.pinkId).then(res => {
        that.$set(
          that,
          "storeCombinationHost",
          res.data.store_combination_host
        );
        that.$set(that, "storeCombination", res.data.store_combination);
        that.$set(that, "pinkT", res.data.pinkT);
        that.$set(that, "pinkAll", res.data.pinkAll);
        that.$set(that, "count", res.data.count);
        that.$set(that, "userBool", res.data.userBool);
        that.$set(that, "pinkBool", res.data.pinkBool);
        that.$set(that, "isOk", res.data.is_ok);
        that.$set(that, "currentPinkOrder", res.data.current_pink_order);
      });
    },
    //拼团取消
    getCombinationRemove: function() {
      var that = this;
      getCombinationRemove({ id: that.pinkId, cid: that.storeCombination.id })
        .then(res => {
          that.$dialog.success(res.msg);
        })
        .catch(res => {
          that.$dialog.error(res.msg);
        });
    },
    lookAll: function() {
      this.iShidden = !this.iShidden;
    }
  }
};
</script>
