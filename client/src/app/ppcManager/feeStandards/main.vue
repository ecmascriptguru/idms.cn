
<script>
  import Vue from 'vue'
  import { mapActions, mapState } from 'vuex'
  import CcPagination from 'components/general/pagination'

  export default {
    /**
    * Components name to be displayed on
    * Vue.js Devtools
    */
    name: 'JdPpcFeeStandards',

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
          name: 'ppcManager.feeStandards.edit',
          params: { id },
          query: { page: this.currentPage, dctId: this.districtId } })
      },
      create() {
        this.$router.push({ name: 'ppcManager.feeStandards.new', query: { page: this.currentPage, dctId: this.districtId } })
      },
      hide() {
        this.$router.push({ name: 'ppcManager.feeStandards.index', query: { page: this.currentPage, dctId: this.districtId } })
      },
      /**
      * Brings actions from Vuex to the scope of
      * this component
      */
      ...mapActions(['feeStandardsSetData', 'setFetching', 'districtsSetData']),

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
        this.$router.push({ name: 'ppcManager.feeStandards.index', query: { page: obj.page } })

        /**
        * Fetch a new set of FeeStandards based on
        * current page number. Mind the nextTick()
        * which delays a the request a fraction
        * of a second. This ensures the currentPage
        * property is set before making the request.
        */
        Vue.nextTick(() => this.fetchPaginated())
      },

      /**
      * Shows a confirmation dialog
      */
      askRemove(item) {
        swal({
          title: 'Are you sure?',
          text: `User ${item.name} will be permanently removed.`,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText: 'Yes, do it!',
          closeOnConfirm: false,
        }, () => this.remove(item)) // callback executed when OK is pressed
      },

      fetchDistricts() {
        if (!this.districts.length) {
          this.setFetching({ fetching: true })
          this.$http.get('pca/districts/full-list').then(({ data }) => {
            /**
            * Vuex action to set full list array in
            * the Vuex OperatingCompanies module
            */
            this.districtsSetData({
              full_list: data.data,
            })
            this.setFetching({ fetching: false })
            this.setDistrictId()
          })
        } else {
          this.setDistrictId()
        }
      },
      
      setDistrictId() {
        this.$refs.feeStandards_district_selector.value = this.$route.query.dctId || 0
      },

      changeDistrict(event) {
        this.$router.push({ 
          name: 'ppcManager.feeStandards.index',
          query: { page: this.currentPage, dctId: event.target.value }
        })
        this.fetch()
      },

      /**
      * Makes the HTTP requesto to the API
      */
      remove(item) {
        this.$http.delete(`pca/feeStandards/${item.id}`).then(() => {
          /**
          * On success fetch a new set of FeeStandards
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
          swal('Done!', 'User removed.', 'success')

          /**
          * Redirects back to the main list,
          * in case the form is open
          */
          if (this.isFormVisible) {
            this.$router.push({ name: 'ppcManager.feeStandards.index', query: { page: this.currentPage } })
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
    * state properties to the scope of this component.
    */
    computed: {
      ...mapState({
        fetching: state => state.fetching,
        pagination: state => state.PpcManager.FeeStandards.pagination,
        list: state => state.PpcManager.FeeStandards.list,
        districts: state => state.PpcManager.Districts.full_list,
      }),
      feeStandards() {
        return this.list
      },
      currentPage() {
        return parseInt(this.$route.query.page, 10)
      },
      districtId() {
        return this.$route.query.dctId || 0
      },
      isFormVisible() {
        return this.$route.name === 'ppcManager.feeStandards.new' || this.$route.name === 'ppcManager.feeStandards.edit'
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
      if (jQuery.AdminLTE.layout) {
        jQuery.AdminLTE.layout.fix()
      } else {
        jQuery(document).ready(() => {
          jQuery.AdminLTE.layout.fix()
        })
      }
      this.fetchDistricts()
      this.setDistrictId()
    },
    /**
    * This hook is called every time DOM
    * gets updated.
    */
    updated() {
      /**
      * start Bootstrap Tooltip
      */
      jQuery('[data-toggle="tooltip"]').tooltip()
      if (jQuery.AdminLTE.layout) {
        jQuery.AdminLTE.layout.fix()
      } else {
        jQuery(document).ready(() => {
          jQuery.AdminLTE.layout.fix()
        })
      }
      this.setDistrictId()
    },
  }
</script>

<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-6">
        <h1>费用标准管理</h1>
      </div>
      <div class="col-md-6 text-right">
        <div class="button-within-header">
          <a href="#"
            v-show="!isFormVisible"
            @click.prevent="create"
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
    <div class="row">
      <div class="col-sm-4 col-xs-6">
        <select ref="feeStandards_district_selector" @change.prevent="changeDistrict" class="form-control" id="feeStandards_district_selector">
          <option value="0">全部</option>
          <option v-for="district in districts" :value="district.id">
            {{ district.name }}
          </option>
        </select>
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
          <th>操　　作</th>
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
          <td width="1%" nowrap="nowrap">
            <a href="#"
              @click.prevent="edit(item.id)"
              class="btn btn-xs btn-default"
              data-toggle="tooltip"
              data-placement="top"
              title="Edit">
              <i class="fa fa-fw fa-pencil"></i>
            </a>
            <a href="#"
              @click="askRemove(item)"
              class="btn btn-xs btn-default"
              data-toggle="tooltip"
              data-placement="top"
              title="Remove">
              <i class="fa fa-fw fa-times"></i>
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
