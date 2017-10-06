
<script>
  import Vue from 'vue'
  import { mapActions, mapState } from 'vuex'
  import CcPagination from 'components/general/pagination'

  export default {
    /**
    * Components name to be displayed on
    * Vue.js Devtools
    */
    name: 'JdDctFeeStandards',

    /**
    * Components registered with
    * this component
    */
    components: {
      CcPagination,
    },

    methods: {
      edit() {
        this.$router.push({
          name: 'dctManager.feeStandards.edit',
          query: { page: this.currentPage } })
      },
      hide() {
        this.$router.push({ 
          name: 'dctManager.feeStandards.index',
          query: { page: this.currentPage } 
        })
      },
      /**
      * Brings actions from Vuex to the scope of
      * this component
      */
      ...mapActions(['feeStandardsSetData', 'setFetching',]),

      fetch() {
        this.fetchPaginated()
        this.fetchFullList()
        this.setDistrictId()
      },

      /**
      * Fetch a new set of feeStandards
      * based on the current page
      */
      fetchPaginated() {
        /**
        * Vuex action to set fetching property
        * to true, thus showing the spinner
        * that is part of navbar.vue
        */
        this.setFetching({ fetching: true })

        /**
        * Makes the HTTP request to the API
        * $http is a Vue.js plugins exposing
        * an Axios object.
        * See /src/plugins/http.js
        */
        this.$http.get(`pca/feeStandards?page=${this.currentPage}&dct_id=${this.districtId}`).then(({ data }) => {
          /**
          * Vuex action to set pagination object in
          * the Vuex FeeStandards module
          */
          this.feeStandardsSetData({
            list: data.data,
            pagination: data.meta.pagination,
          })

          /**
          * Vuex action to set fetching property
          * to false, thus hiding the spinner
          * that is part of navbar.vue
          */
          this.setFetching({ fetching: false })
        })
      },

      /**
      * Differente from fetch() which always
      * return a paginated set of feeStandards
      * this one returns the full set, which
      * is used by other components in the app.
      */
      fetchFullList() {
        this.setFetching({ fetching: true })
        this.$http.get('pca/feeStandards/full-list').then(({ data }) => {
          /**
          * Vuex action to set full list array in
          * the Vuex FeeStandards module
          */
          this.feeStandardsSetData({
            full_list: data.data,
          })
          this.setFetching({ fetching: false })
          this.setDistrictId()
        })
      },

      /**
      * Navigate to a specific page, provided in the
      * obj received by the method
      */
      navigate(obj) {
        /**
        * Push the page number to the query string
        */
        this.$router.push({ name: 'dctManager.feeStandards.index', query: { page: obj.page } })

        /**
        * Fetch a new set of FeeStandards based on
        * current page number. Mind the nextTick()
        * which delays a the request a fraction
        * of a second. This ensures the currentPage
        * property is set before making the request.
        */
        Vue.nextTick(() => this.fetchPaginated())
      },

      fixLayout() {
        if (jQuery.AdminLTE.layout) {
          jQuery.AdminLTE.layout.fix()
        } else {
          jQuery(document).ready(() => {
            jQuery.AdminLTE.layout.fix()
          })
        }
      },
    },

    /**
    * mapState will bring indicated Vuex
    * state properties to the scope of this component.
    */
    computed: {
      ...mapState({
        fetching: state => state.fetching,
        pagination: state => state.PpcManager.FeeStandards.pagination,
        list: state => state.PpcManager.FeeStandards.list,
        currentUser: state => state.Auth.user,
      }),
      feeStandards() {
        return this.list
      },
      currentPage() {
        return parseInt(this.$route.query.page, 10)
      },
      districtId() {
        return this.currentUser.district_id || 0
      },
      isFormVisible() {
        return this.$route.name === 'dctManager.feeStandards.new' || this.$route.name === 'dctManager.feeStandards.edit'
      },
    },
    /**
    * Right before navigate out to another route
    * clears all event handlers, thus avoiding
    * multiple handlers to be set and the annoying
    * behaviour of multiple AJAX calls being made.
    */
    beforeRouteLeave(to, from, next) {
      this.$bus.$off('navigate')
      this.$bus.$off('feeStandard.created')
      this.$bus.$off('feeStandard.updated')
      jQuery('body').off('keyup')
      next()
    },
    mounted() {
      /**
      * Listen to pagination navigate event
      */
      this.$bus.$on('navigate', obj => this.navigate(obj))
      /**
      * User was created or updated, refresh the list
      */
      this.$bus.$on('feeStandard.created', this.fetch)
      this.$bus.$on('feeStandard.updated', this.fetch)
      /**
      * Fetch data immediately after component is mounted
      */
      this.fetchPaginated()
      this.fixLayout()
    },
    /**
    * This hook is called every time DOM
    * gets updated.
    */
    updated() {
      /**
      * start Bootstrap Tooltip
      */
      this.fixLayout()
    },
  }
</script>

<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-6">
        <h1>缴费设置</h1>
      </div>
      <div class="col-md-6 text-right">
        <div class="button-within-header">
          <a href="#"
            v-show="!isFormVisible"
            @click.prevent="edit"
            class="btn btn-xs btn-default"
            data-toggle="tooltip" data-placement="top"
            title="New User">
            <i class="fa fa-fw fa-plus"></i>
          </a>
          <a href="#"
            v-show="isFormVisible"
            @click.prevent="hide"
            class="btn btn-xs btn-default"
            data-toggle="tooltip" data-placement="top"
            title="New User">
            <i class="fa fa-fw fa-minus"></i>
          </a>
        </div>
      </div>
    </div>

    <!-- Form to create/edit will be inserted here  -->
    <!-- when navigate to /nova or /editar/{id}  -->
    <!-- see /src/modules/feeStandards/routes.js  -->
    <transition name="fade">
      <router-view></router-view>
    </transition>

    <table class="table table-bordered table-striped" style="margin-bottom:0;">
      <thead>
        <tr>
          <th>ID</th>
          <th>房屋类型</th>
          <th>物业管理费</th>
          <th>水　　费</th>
          <th>电　　费</th>
          <th>停车费</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in feeStandards">
          <td width="1%" nowrap>{{ index + 1 }}</td>
          <td>{{ item.houseType.data.name }}</td>
          <td>{{ item.property_management_fee }}</td>
          <td>{{ item.water_fee }}</td>
          <td>{{ item.electricity_fee }}</td>
          <td>{{ item.parking_fee }}</td>
        </tr>
      </tbody>
    </table>
    <div>
      <cc-pagination
        :pagination-data="pagination"
        :current-page="currentPage"
        :max-items="12">
      </cc-pagination>
    </div>
  </div>
</template>

<style scoped>
  .button-within-header {
    margin-top: 32px;
  }
  .fade-enter-active, .fade-leave-active {
    transition: opacity .7s ease;
  }
  .fade-enter, .fade-leave-active {
    opacity: 0;
  }
</style>
