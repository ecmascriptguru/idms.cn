
<script>
  import Vue from 'vue'
  import { mapActions, mapState } from 'vuex'
  import CcPagination from 'components/general/pagination'

  export default {
    /**
    * Components name to be displayed on
    * Vue.js Devtools
    */
    name: 'JdAdminLanes',

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
          name: 'admin.lanes.edit',
          params: { id },
          query: { page: this.currentPage, pl: this.parkingLotId, guard: this.guardId } })
      },
      create() {
        this.$router.push({ 
          name: 'admin.lanes.new',
          query: { page: this.currentPage, pl: this.parkingLotId, guard: this.guardId } })
      },
      hide() {
        this.$router.push({ 
          name: 'admin.lanes.index',
          query: { page: this.currentPage, pl: this.parkingLotId, guard: this.guardId } })
      },
      /**
      * Brings actions from Vuex to the scope of
      * this component
      */
      ...mapActions(['lanesSetData', 'parkingLotsSetData', 'guardsSetData', 'setFetching']),

      fetch() {
        this.fetchPaginated()
        this.fetchFullList()
      },

      /**
      * Fetch a new set of lanes
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
        this.$http.get(`admin/lanes?page=${this.currentPage}&pl=${this.parkingLotId}&guard=${this.guardId}`).then(({ data }) => {
          /**
          * Vuex action to set pagination object in
          * the Vuex Lanes module
          */
          this.lanesSetData({
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
      * return a paginated set of lanes
      * this one returns the full set, which
      * is used by other components in the app.
      */
      fetchFullList() {
        this.setFetching({ fetching: true })
        this.$http.get('admin/lanes/full-list').then(({ data }) => {
          /**
          * Vuex action to set full list array in
          * the Vuex Lanes module
          */
          this.lanesSetData({
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
        this.$router.push({ name: 'admin.lanes.index', query: { page: obj.page } })

        /**
        * Fetch a new set of Lanes based on
        * current page number. Mind the nextTick()
        * which delays a the request a fraction
        * of a second. This ensures the currentPage
        * property is set before making the request.
        */
        Vue.nextTick(() => this.fetchPaginated())
      },

      /**
       * Fetching ParkingLots to render parkingLot drop down
       */
      fetchParkingLots() {
        if (!this.parkingLots.length) {
          this.setFetching({ fetching: true })
          this.$http.get('admin/parkingLots/full-list').then(({ data }) => {
            /**
            * Vuex action to set full list array in
            * the Vuex OperatingCompanies module
            */
            this.parkingLotsSetData({
              full_list: data.data,
            })
            this.setFetching({ fetching: false })
            this.setProvinceId()
          })
        } else {
          this.setProvinceId()
        }
      },
      
      /**
       * Fetching Guards to render parkingLot drop down
       */
      fetchGuards() {
        if (!this.guards.length) {
          this.setFetching({ fetching: true })
          this.$http.get(`admin/guards/full-list?pl=${this.parkingLotId}`).then(({ data }) => {
            /**
            * Vuex action to set full list array in
            * the Vuex OperatingCompanies module
            */
            this.guardsSetData({
              full_list: data.data,
            })
            this.setFetching({ fetching: false })
            this.setProvinceId()
          })
        } else {
          this.setProvinceId()
        }
      },

      parkingLotChanged(event) {
        this.$router.push({
          name: this.$route.name,
          query: { page: this.currentPage, pl: event.target.value, guard: this.guardId }
        })
        this.guardsSetData({full_list: []})
        this.fetchGuards()
        this.fetch()
      },

      guardChanged(event) {
        this.$router.push({
          name: this.$route.name,
          query: { page: this.currentPage, pl:this.parkingLotId, guard: event.target.value }
        })
        this.fetch()
      },

      setProvinceId() {
        this.$refs.lanes_parking_lot_id.value = this.$route.query.pl || 0
      },

      /**
      * Shows a confirmation dialog
      */
      askRemove(item) {
        swal({
          title: 'Are you sure?',
          text: `Lane ${item.name} will be permanently removed.`,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText: 'Yes, do it!',
          closeOnConfirm: false,
        }, () => this.remove(item)) // callback executed when OK is pressed
      },

      /**
      * Makes the HTTP requesto to the API
      */
      remove(item) {
        this.$http.delete(`admin/lanes/${item.id}`).then(() => {
          /**
          * On success fetch a new set of Lanes
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
          swal('Done!', 'Lane removed.', 'success')

          /**
          * Redirects back to the main list,
          * in case the form is open
          */
          if (this.isFormVisible) {
            this.$router.push({ name: 'admin.lanes.index', query: { page: this.currentPage, pl: this.parkingLotId, guard: this.guardId } })
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
        pagination: state => state.Admin.Lanes.pagination,
        list: state => state.Admin.Lanes.list,
        parkingLots: state => state.Admin.ParkingLots.full_list,
        guards: state => state.Admin.Guards.full_list,
      }),
      lanes() {
        return this.list
      },
      currentPage() {
        return parseInt(this.$route.query.page, 10)
      },
      parkingLotId() {
        return parseInt(this.$route.query.pl || 0)
      },
      guardId() {
        return parseInt(this.$route.query.guard || 0)
      },
      isFormVisible() {
        return this.$route.name === 'admin.lanes.new' || this.$route.name === 'admin.lanes.edit'
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
      this.$bus.$off('lane.created')
      this.$bus.$off('lane.updated')
      jQuery('body').off('keyup')
      next()
    },
    mounted() {
      /**
      * Listen to pagination navigate event
      */
      this.$bus.$on('navigate', obj => this.navigate(obj))
      /**
      * Lane was created or updated, refresh the list
      */
      this.$bus.$on('lane.created', this.fetch)
      this.$bus.$on('lane.updated', this.fetch)
      /**
      * Fetch data immediately after component is mounted
      */
      this.fetchParkingLots()
      this.fetchGuards()
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
      jQuery('[data-toggle="tooltip"]').tooltip()
      if (jQuery.AdminLTE.layout) {
        jQuery.AdminLTE.layout.fix()
      } else {
        jQuery(document).ready(() => {
          jQuery.AdminLTE.layout.fix()
        })
      }
      this.setProvinceId()
    },
  }
</script>

<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-6">
        <h1>Lane Management</h1>
      </div>
      <div class="col-md-6 text-right">
        <div class="button-within-header">
          <a href="#"
            v-show="!isFormVisible"
            @click.prevent="create"
            class="btn btn-xs btn-default"
            data-toggle="tooltip" data-placement="top"
            title="New Lane">
            <i class="fa fa-fw fa-plus"></i>
          </a>
          <a href="#"
            v-show="isFormVisible"
            @click.prevent="hide"
            class="btn btn-xs btn-default"
            data-toggle="tooltip" data-placement="top"
            title="New Lane">
            <i class="fa fa-fw fa-minus"></i>
          </a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4 col-xs-12">
        <select @change.prevent="parkingLotChanged" ref="lanes_parking_lot_id" class="form-control" id="lanes_parking_lot_id">
          <option value="0">全部</option>
          <option v-for="parkingLot in parkingLots" :value="parkingLot.id">
            {{ parkingLot.name }}
          </option>
        </select>
      </div>
      <div class="col-sm-4 col-xs-12">
        <select @change.prevent="guardChanged" ref="lanes_guard_id" class="form-control" id="lanes_guard_id">
          <option value="0">全部</option>
          <option v-for="guard in guards" :value="guard.id">
            {{ guard.name }}
          </option>
        </select>
      </div>
    </div>

    <!-- Form to create/edit will be inserted here  -->
    <!-- when navigate to /nova or /editar/{id}  -->
    <!-- see /src/modules/lanes/routes.js  -->
    <transition name="fade">
      <router-view></router-view>
    </transition>
    <table class="table table-bordered table-striped" style="margin-bottom:0;">
      <thead>
        <tr>
          <th>ID</th>
          <th>停车场名称</th>
          <th>岗亭名车</th>
          <th>车道编号</th>
          <th>车道名称</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in lanes">
          <td width="1%" nowrap>{{ index + 1 }}</td>
          <td>{{ item.parkingLot.name }}</td>
          <td>{{ item.guard.name }}</td>
          <td>{{ item.number }}</td>
          <td>{{ item.name }}</td>
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
