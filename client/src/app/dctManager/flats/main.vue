
<script>
  import Vue from 'vue'
  import { mapActions, mapState } from 'vuex'
  import CcPagination from 'components/general/pagination'

  export default {
    /**
    * Components name to be displayed on
    * Vue.js Devtools
    */
    name: 'JdDctFlats',

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
          name: 'dctManager.flats.edit',
          params: { id },
          query: { page: this.currentPage, bdId: this.buildingId } })
      },
      create() {
        this.$router.push({ name: 'dctManager.flats.new', query: { page: this.currentPage, bdId: this.buildingId } })
      },
      hide() {
        this.$router.push({ name: 'dctManager.flats.index', query: { page: this.currentPage, bdId: this.buildingId } })
      },
      /**
      * Brings actions from Vuex to the scope of
      * this component
      */
      ...mapActions(['flatsSetData', 'setFetching', 'buildingsSetData']),

      fetch() {
        this.fetchPaginated()
        this.fetchFullList()
        this.setBuildingId()
      },

      /**
      * Fetch a new set of flats
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
        this.$http.get(`dct/flats?page=${this.currentPage}&building_id=${this.buildingId}`).then(({ data }) => {
          /**
          * Vuex action to set pagination object in
          * the Vuex Flats module
          */
          this.flatsSetData({
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
      * return a paginated set of flats
      * this one returns the full set, which
      * is used by other components in the app.
      */
      fetchFullList() {
        this.setFetching({ fetching: true })
        this.$http.get('dct/flats/full-list').then(({ data }) => {
          /**
          * Vuex action to set full list array in
          * the Vuex Flats module
          */
          this.flatsSetData({
            full_list: data.data,
          })
          this.setFetching({ fetching: false })
          this.setBuildingId()
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
        this.$router.push({ name: 'dctManager.flats.index', query: { page: obj.page } })

        /**
        * Fetch a new set of Flats based on
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
            this.setBuildingId()
          })
        } else {
          this.setBuildingId()
        }
      },
      
      setBuildingId() {
        // this.$refs.flats_building_selector.value = this.$route.query.bdId || 0
      },

      buildingChanged(event) {
        this.$router.push({ 
          name: this.$route.name,
          query: { page: this.currentPage, bdId: event.target.value }
        })
        this.fetch()
      },

      /**
      * Makes the HTTP requesto to the API
      */
      remove(item) {
        this.$http.delete(`dct/flats/${item.id}`).then(() => {
          /**
          * On success fetch a new set of Flats
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
            this.$router.push({ name: 'dctManager.flats.index', query: { page: this.currentPage } })
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
        pagination: state => state.DctManager.Flats.pagination,
        list: state => state.DctManager.Flats.list,
        buildings: state => state.DctManager.Buildings.full_list,
      }),
      flats() {
        return this.list
      },
      currentPage() {
        return parseInt(this.$route.query.page, 10)
      },
      buildingId() {
        return this.$route.query.bdId || 0
      },
      isFormVisible() {
        return this.$route.name === 'dctManager.flats.new' || this.$route.name === 'dctManager.flats.edit'
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
      this.$bus.$off('flat.created')
      this.$bus.$off('flat.updated')
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
      this.$bus.$on('flat.created', this.fetch)
      this.$bus.$on('flat.updated', this.fetch)
      /**
      * Fetch data immediately after component is mounted
      */
      this.fetchPaginated()
      this.fetchBuildings()
      this.setBuildingId()
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
      this.setBuildingId()
      this.fixLayout()
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
        <select ref="flats_building_selector" @change.prevent="buildingChanged" class="form-control" id="flats_building_selector">
          <option value="0">全部</option>
          <option v-for="building in buildings" :value="building.id">
            {{ building.name }}
          </option>
        </select>
      </div>
    </div>

    <!-- Form to create/edit will be inserted here  -->
    <!-- when navigate to /nova or /editar/{id}  -->
    <!-- see /src/modules/flats/routes.js  -->
    <transition name="fade">
      <router-view></router-view>
    </transition>

    <table class="table table-bordered table-striped" style="margin-bottom:0;">
      <thead>
        <tr>
          <th>ID</th>
          <th>房屋名称</th>
          <th>房屋编号</th>
          <th>房屋类别</th>
          <th>房屋面积</th>
          <th>业主一姓名</th>
          <th>业主二姓名</th>
          <th>入住人员数量</th>
          <th>操　　作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in flats">
          <td width="1%" nowrap>{{ index + 1 }}</td>
          <td>{{ item.name }}</td>
          <td>{{ item.number }}</td>
          <td>{{ item.houseType.name }}</td>
          <td>{{ item.area }}</td>
          <td>{{ item.owner_1_name }}</td>
          <td>{{ item.owner_2_name }}</td>
          <td>0</td>
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
