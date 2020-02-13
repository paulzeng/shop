<template>
  <div class="order-index" ref="container">
    <div class="header acea-row">
      <router-link class="item" :to="'/customer/orders/0'">
        <div class="num">{{ census.unpaid_count }}</div>
        <div>待付款</div>
      </router-link>
      <router-link class="item" :to="'/customer/orders/1'">
        <div class="num">{{ census.unshipped_count }}</div>
        <div>待发货</div>
      </router-link>
      <router-link class="item" :to="'/customer/orders/2'">
        <div class="num">{{ census.received_count }}</div>
        <div>待收货</div>
      </router-link>
      <router-link class="item" :to="'/customer/orders/3'">
        <div class="num">{{ census.evaluated_count }}</div>
        <div>待评价</div>
      </router-link>
      <router-link class="item" :to="'/customer/orders/-3'">
        <div class="num">{{ census.refund_count }}</div>
        <div>退款</div>
      </router-link>
    </div>
    <div class="wrapper">
      <div class="title">
        <span class="iconfont icon-shujutongji"></span>数据统计
      </div>
      <div class="list acea-row">
        <router-link class="item" :to="'/customer/statistics/price/today'">
          <div class="num">{{ census.todayPrice }}</div>
          <div>今日成交额</div>
        </router-link>
        <router-link class="item" :to="'/customer/statistics/price/yesterday'">
          <div class="num">{{ census.proPrice }}</div>
          <div>昨日成交额</div>
        </router-link>
        <router-link class="item" :to="'/customer/statistics/price/month'">
          <div class="num">{{ census.monthPrice }}</div>
          <div>本月成交额</div>
        </router-link>
        <router-link class="item" :to="'/customer/statistics/order/today'">
          <div class="num">{{ census.todayCount }}</div>
          <div>今日订单数</div>
        </router-link>
        <router-link class="item" :to="'/customer/statistics/order/yesterday'">
          <div class="num">{{ census.proCount }}</div>
          <div>昨日订单数</div>
        </router-link>
        <router-link class="item" :to="'/customer/statistics/order/month'">
          <div class="num">{{ census.monthCount }}</div>
          <div>本月订单数</div>
        </router-link>
      </div>
    </div>
    <div class="public-wrapper">
      <div class="title">
        <span class="iconfont icon-xiangxishuju"></span>详细数据
      </div>
      <div class="nav acea-row row-between-wrapper">
        <div class="data">日期</div>
        <div class="browse">订单数</div>
        <div class="turnover">成交额</div>
      </div>
      <div class="conter">
        <div
          class="item acea-row row-between-wrapper"
          v-for="(item, index) in list"
          :key="index"
        >
          <div class="data">{{ item.time }}</div>
          <div class="browse">{{ item.count }}</div>
          <div class="turnover">{{ item.price }}</div>
        </div>
      </div>
    </div>
    <Loading :loaded="loaded" :loading="loading"></Loading>
  </div>
</template>
<script>
import { getStatisticsInfo, getStatisticsMonth } from "../../api/admin";
import Loading from "@components/Loading";
export default {
  name: "OrderIndex",
  components: {
    Loading
  },
  props: {},
  data: function() {
    return {
      census: [],
      list: [],
      where: {
        page: 1,
        limit: 15
      },
      loaded: false,
      loading: false
    };
  },
  mounted: function() {
    this.getIndex();
    this.getList();
    this.$scroll(this.$refs.container, () => {
      !this.loading && this.getList();
    });
  },
  methods: {
    getIndex: function() {
      var that = this;
      getStatisticsInfo().then(
        res => {
          that.census = res.data;
        },
        err => {
          that.$dialog.message(err.msg);
        }
      );
    },
    getList: function() {
      var that = this;
      if (that.loading || that.loaded) return;
      that.loading = true;
      getStatisticsMonth(that.where).then(
        res => {
          that.loading = false;
          that.loaded = res.data.length < that.where.limit;
          that.list.push.apply(that.list, res.data);
          that.where.page = that.where.page + 1;
        },
        error => {
          that.$dialog.message(error.msg);
        },
        300
      );
    }
  }
};
</script>
