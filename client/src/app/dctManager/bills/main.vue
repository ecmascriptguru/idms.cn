
<script>
  import Vue from 'vue'
  import { mapActions, mapState } from 'vuex'
  import CcPagination from 'components/general/pagination'

  export default {
    /**
    * Components name to be displayed on
    * Vue.js Devtools
    */
    name: 'JdDctBills',

    /**
    * Components registered with
    * this component
    */
    components: {
      CcPagination,
    },

    methods: {
      edit(id) {
        this.$router.push({
          name: 'dctManager.bills.edit',
          params: { id },
          query: { page: this.currentPage, date: this.date, building: this.building } })
      },
      create() {
        this.$router.push({ name: 'dctManager.bills.new', query: { page: this.currentPage } })
      },
      hide() {
        this.$router.push({ name: 'dctManager.bills.index', query: { page: this.currentPage } })
      },
      /**
      * Brings actions from Vuex to the scope of
      * this component
      */
      ...mapActions(['billsSetData', 'buildingsSetData', 'setFetching']),

      fetch() {
        this.fetchBuildings()
        this.fetchPaginated()
        this.fetchFullList()
      },

      buildingChanged(event) {
        this.$router.push({
          name: this.$route.name,
          query: { page: this.currentPage, date: this.date, building: event.target.value, keyword: this.keyword }
        })
        this.fetch()
      },

      setBuilding() {
        //
      },

      fetchBuildings() {
        if (!this.buildings.length) {
          this.setFetching({ fetching: true })
          this.$http.get('dct/buildings/full-list').then(({ data }) => {
            /**
            * Vuex action to set full list array in
            * the Vuex OperatingCompanies module
            */
            this.buildingsSetData({
              full_list: data.data,
            })
            this.setFetching({ fetching: false })
            this.setBuilding()
          })
        } else {
          this.setBuilding()
        }
      },

      keywordChanged(event) {
        this.$router.push({
          name: this.$route.name,
          query: { page: this.currentPage, date: this.date, building: this.building, keyword: event.target.value }
        })
        this.fetch()
      },

      /**
      * Fetch a new set of bills
      * based on the current page
      */
      fetchPaginated() {
        /**
        * Vuex action to set fetching bills
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
        this.$http.get(`dct/bills?page=${this.currentPage}&date=${this.date}&building_id=${this.building}&keyword=${this.keyword}`).then(({ data }) => {
          /**
          * Vuex action to set pagination object in
          * the Vuex OpratingCompany module
          */
          this.billsSetData({
            list: data.data,
            pagination: data.meta.pagination,
          })

          /**
          * Vuex action to set fetching bill
          * to false, thus hiding the spinner
          * that is part of navbar.vue
          */
          this.setFetching({ fetching: false })
        })
      },

      /**
      * Differente from fetch() which always
      * return a paginated set of bills
      * this one returns the full set, which
      * is used by other components in the app.
      */
      fetchFullList() {
        this.setFetching({ fetching: true })
        this.$http.get('dct/bills/full-list').then(({ data }) => {
          /**
          * Vuex action to set full list array in
          * the Vuex Bill module
          */
          this.billsSetData({
            full_list: data.data,
          })
          this.setFetching({ fetching: false })
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
        this.$router.push({ name: 'dctManager.bills.index', query: { page: obj.page } })

        /**
        * Fetch a new set of Bill based on
        * current page number. Mind the nextTick()
        * which delays a the request a fraction
        * of a second. This ensures the currentPage
        * bill is set before making the request.
        */
        Vue.nextTick(() => this.fetchPaginated())
      },

      /**
      * Shows a confirmation dialog
      */
      askRelease(item) {
        swal({
          title: 'Are you sure?',
          text: `Bill for ${item.date} will be released.`,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText: 'Yes, do it!',
          closeOnConfirm: false,
        }, () => this.release(item)) // callback executed when OK is pressed
      },

      /**
      * Makes the HTTP requesto to the API
      */
      release(item) {
        this.$http.post(`dct/bills/release`, {id: item.id}).then(() => {
          /**
          * On success fetch a new set of Bills
          * based on current page number
          */
          this.fetchPaginated()

          /**
          * If we remove a item then
          * the full list must be refreshed
          */
          this.fetchFullList()

          /**
          * Shows a different dialog based on the result
          */
          swal('Done!', 'Bill Released.', 'success')

          /**
          * Redirects back to the main list,
          * in case the form is open
          */
          if (this.isFormVisible) {
            this.$router.push({ name: 'dctManager.bills.index', query: { page: this.currentPage } })
          }
        })
        .catch((error) => {
          /**
          * Just shows the result in a form or error.
          * The general error message is displayed by
          * the action dispatched by the interceptor.
          * See /src/plugins/http.js
          */
          swal('Falha!', error.response.data.messages[0], 'error')
        })
      },
    },

    /**
    * mapState will bring indicated Vuex
    * state bills to the scope of this component.
    */
    computed: {
      ...mapState({
        fetching: state => state.fetching,
        pagination: state => state.DctManager.Bills.pagination,
        list: state => state.DctManager.Bills.list,
        buildings: state => state.DctManager.Buildings.full_list,
      }),
      bills() {
        return this.list
      },
      currentPage() {
        return parseInt(this.$route.query.page, 10)
      },
      isFormVisible() {
        return this.$route.name === 'dctManager.bills.new'
      },
      date() {
        return this.$route.query.date || ''
      },
      building() {
        return this.$route.query.building || 0
      },
      keyword() {
        return this.$route.query.keyword || ''
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
      this.$bus.$off('bill.created')
      this.$bus.$off('bill.updated')
      jQuery('body').off('keyup')
      next()
    },
    mounted() {
      /**
      * Listen to pagination navigate event
      */
      this.$bus.$on('navigate', obj => this.navigate(obj))
      /**
      * Bill was created or updated, refresh the list
      */
      this.$bus.$on('bill.created', this.fetch)
      this.$bus.$on('bill.updated', this.fetch)
      /**
      * Fetch data immediately after component is mounted
      */
      this.fetchBuildings()
      this.fetchPaginated()
      if (jQuery.AdminLTE.layout) {
        jQuery.AdminLTE.layout.fix()
      } else {
        jQuery(document).ready(() => {
          jQuery.AdminLTE.layout.fix()
        })
      }
    },
    /**
    * This hook is called every time DOM
    * gets updated.
    */
    updated() {
      /**
      * start Bootstrap Tooltip
      */
      jQuery('[data-toggle="tooltip"]').tooltip();
      if (jQuery.AdminLTE.layout) {
        jQuery.AdminLTE.layout.fix()
      } else {
        jQuery(document).ready(() => {
          jQuery.AdminLTE.layout.fix()
        })
      }
    },
  }
</script>

<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-6">
        <h1>账单缴费</h1>
      </div>
    </div>

    <!-- Form to create/edit will be inserted here  -->
    <!-- when navigate to /nova or /editar/{id}  -->
    <!-- see /src/modules/opCompanies/routes.js  -->
    <transition name="fade">
      <router-view></router-view>
    </transition>
    <div class="col-xs-12">
      <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
          <select @change="buildingChanged" class="form-control" v-model="building" ref="building">
            <option value="0">全部</option>
            <option v-for="building in buildings" :value="building.id">
              {{ building.name }}
            </option>
          </select>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
          <input type="text" @change="keywordChanged" class="form-control" v-model="keyword" ref="keyword" placeholder="房屋编号" />
        </div>
      </div>
    </div>
    <div class="col-xs-12">
      <table class="table table-bordered table-striped" style="margin-bottom:0;">
        <thead>
          <tr>
            <th>ID</th>
            <th>楼栋编号</th>
            <th>房屋编号</th>
            <th>账期</th>
            <th>缴费金额</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in bills">
            <td width="1%" nowrap>{{ index +1 }}</td>
            <td>{{ item.building.number }}</td>
            <td>{{ item.flat.number }}</td>
            <td>{{ item.date }}</td>
            <td>{{ item.total }}</td>
            <td width="1%" nowrap="nowrap">
              <a href="#"
                @click.prevent="edit(item.id)"
                class="btn btn-xs btn-primary"
                data-toggle="tooltip"
                data-placement="top"
                title="Check Bills">
                <i class="fa fa-fw fa-pencil"></i>修改
              </a>
              <a v-if="item.is_released" href="#"
                class="btn btn-xs btn-default"
                data-toggle="tooltip"
                data-placement="top"
                disabled>
                <i class="fa fa-fw fa-check"></i>缴费
              </a>
              <a v-else href="#"
                @click="askRelease(item)"
                class="btn btn-xs btn-primary"
                data-toggle="tooltip"
                data-placement="top"
                title="Release">
                <i class="fa fa-fw fa-check"></i>缴费
              </a>
            </td>
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
