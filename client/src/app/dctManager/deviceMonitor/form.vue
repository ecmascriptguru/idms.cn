
<script>
  import { mapActions, mapState } from 'vuex'

  export default {
    name: 'JdDctDevice',

    /**
    * Component's local state
    */
    data() {
      return {
        device: {
          id: 0,
          check_in_type_id: 0,
          check_in_position_id: 0,
          building_id: 0,
          name: '',
          mac_address: '',
          is_connected_to_elevator: false,
        },
      }
    },

    /**
    * Fetch device when component is first mounted
    */
    mounted() {
      this.fetchBuildings()
      this.fetchCheckInTypes()
      this.fetchCheckInPositions()
      this.fetch()
      this.setBuildingId()
    },

    updated() {
      // this.setBuildingId()
    },

    /**
    * Also fetch device every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * device id if the current actions
    * is editing instead of creating.
    */
    computed: {
      ...mapState({
        buildings: state => state.DctManager.Buildings.full_list,
        checkInTypes: state => state.Admin.CheckInTypes.full_list,
        checkInPositions: state => state.Admin.CheckInPositions.full_list,
      }),
      isEditing() {
        return this.device.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.device.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill device name'] })
          return false
        }
        return true
      },
      currentPage() {
        return parseInt(this.$route.query.page, 10)
      },
      buildingId() {
        return this.$route.query.bdId || 0
      },
    },

    methods: {
      ...mapActions(['buildingsSetData', 'checkInTypesSetData', 'checkInPositionsSetData', 'setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the device
      * from the server
      */
      fetch() {
        this.$refs.firstInput.focus()

        const id = this.$route.params.id
        /**
        * This same component is used for create
        * and update so we have to check if
        * ID is not undefined.
        */
        if (id !== undefined) {
          /**
          * Fetch the device from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`dct/devices/${id}`).then((res) => {
            const { 
              id: _id,
              check_in_type_id,
              check_in_position_id,
              building_id,
              name,
              mac_address,
              is_connected_to_elevator,
            } = res.data.data // http://wesbos.com/destructuring-renaming/

            this.device.id = _id
            this.device.building_id = building_id
            this.device.check_in_type_id = check_in_type_id
            this.device.check_in_position_id = check_in_position_id
            this.device.name = name
            this.device.mac_address = mac_address
            this.device.is_connected_to_elevator = is_connected_to_elevator
            this.setFetching({ fetching: false })
          })
        }
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
        }
      },
      fetchCheckInTypes() {
        if (!this.checkInTypes.length) {
          this.setFetching({ fetching: true })
          this.$http.get('admin/checkInTypes/full-list').then(({ data }) => {
            /**
            * Vuex action to set full list array in
            * the Vuex OperatingCompanies module
            */
            this.checkInTypesSetData({
              full_list: data.data,
            })
            this.setFetching({ fetching: false })
            this.setHouseTypeId()
          })
        }
      },
      fetchCheckInPositions() {
        if (!this.checkInPositions.length) {
          this.setFetching({ fetching: true })
          this.$http.get('admin/checkInPositions/full-list').then(({ data }) => {
            /**
            * Vuex action to set full list array in
            * the Vuex OperatingCompanies module
            */
            this.checkInPositionsSetData({
              full_list: data.data,
            })
            this.setFetching({ fetching: false })
            // this.setHouseTypeId()
          })
        }
      },
      submit() {
        /**
        * Pre-conditions are met
        */
        if (this.isValid) {
          /**
          * Shows the global spinner
          */
          this.setFetching({ fetching: true })

          if (this.isEditing) {
            this.update()
          } else {
            this.save()
          }
        }
      },
      save() {
        this.$http.post('dct/devices', this.device).then(() => {
          /**
          * This event will notify the world about
          * the device creation. In this case
          * the device main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('device.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New device was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`dct/devices/${this.device.id}`, this.device).then(() => {
          /**
          * This event will notify the world about
          * the device creation. In this case
          * the device main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('device.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'Device was updated' })
          this.$router.push({ 
            name: 'dctManager.devices.index',
            query: { page: this.currentPage, bdId: this.buildingId }
          })
        })
      },
      reset() {
        this.device.id = 0
        this.device.building_id = 0
        this.device.check_in_type_id = 0
        this.device.check_in_position_id = 0
        this.device.name = ''
        this.device.mac_address = ''
        this.device.is_connected_to_elevator = false
      },
      setBuildingId() {
        this.device.building_id = parseInt(this.$route.query.bdId || 0)
        $(`#building_id`).val(this.device.building_id)
      },
      setHouseTypeId() {
        $(`#house_type_id`).val(this.device.house_type_id)
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="name" class="control-label">门禁名称</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" step="0.1" id="name" class="form-control" v-model="device.name">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="check_in_type_id" class="control-label">门禁类型</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <select name="check_in_type_id" id="check_in_type_id" class="form-control" v-model="device.check_in_type_id">
            <option v-for="checkInType in checkInTypes" :value="checkInType.id">
              {{ checkInType.name }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="check_in_position_id" class="control-label">门禁位置</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <select name="check_in_position_id" id="check_in_position_id" class="form-control" v-model="device.check_in_position_id">
            <option v-for="checkInPosition in checkInPositions" :value="checkInPosition.id">
              {{ checkInPosition.name }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="building_id" class="control-label">所属楼栋</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <select ref="firstInput" name="building_id" id="building_id" class="form-control" v-model="device.building_id">
            <option v-for="building in buildings" :value="building.id" :selected="building.id == device.building_id">
              {{ building.name }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="is_connected_to_elevator" class="control-label">是否与梯控连接</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <select ref="firstInput" name="is_connected_to_elevator" id="is_connected_to_elevator" class="form-control" v-model="device.is_connected_to_elevator">
            <option value="0">不连按梯控</option>
            <option value="1">连按梯控</option>
          </select>
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="mac_address" class="control-label">Mac地址</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" step="0.1" id="mac_address" class="form-control" v-model="device.mac_address">
        </div>
      </div>
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
