
<script>
  import { mapActions, mapState } from 'vuex'

  export default {
    name: 'CcLaneForm',

    /**
    * Component's local state
    */
    data() {
      return {
        lane: {
          id: 0,
          parking_lot_id: null,
          guard_id: null,
          name: '',
          number: '',
          control_number: '',
        },
      }
    },

    /**
    * Fetch lane when component is first mounted
    */
    mounted() {
      this.fetchParkingLots()
      this.fetchGuards()
      this.fetch()
    },

    updated() {
    },

    /**
    * Also fetch lane every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * lane id if the current actions
    * is editing instead of creating.
    */
    computed: {
      ...mapState({
        parkingLots: state => state.Admin.ParkingLots.full_list,
        guards: state => state.Admin.Guards.full_list,
      }),
      isEditing() {
        return this.lane.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.lane.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill lane name'] })
          return false
        }
        return true
      },
      parkingLotId() {
        return parseInt(this.$route.query.pl || 0)
      },
      guardId() {
        return parseInt(this.$route.query.guard || 0)
      },
    },

    methods: {
      ...mapActions(['parkingLotsSetData', 'guardsSetData', 'setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the lane
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
          * Fetch the lane from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`admin/lanes/${id}`).then((res) => {
            const { id: _id, parking_lot_id, guard_id, name, number, control_number } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.lane.id = _id
            this.lane.parking_lot_id = parking_lot_id
            this.lane.guard_id = guard_id
            this.lane.name = name
            this.lane.number = number
            this.lane.control_number = control_number
            this.setFetching({ fetching: false })
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
        this.lane.parking_lot_id = this.parkingLotId
        this.lane.guard_id = this.guardId
        this.$http.post('admin/lanes', this.lane).then(() => {
          /**
          * This event will notify the world about
          * the lane creation. In this case
          * the Lane main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('lane.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New lane was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`admin/lanes/${this.lane.id}`, this.lane).then(() => {
          /**
          * This event will notify the world about
          * the lane creation. In this case
          * the Lane main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('lane.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'Lane was updated' })
        })
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
          })
        } else {
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
          })
        } else {
        }
      },


      reset() {
        this.lane.id = 0
        this.lane.parking_lot_id = null
        this.lane.guard_id = null
        this.lane.name = ''
        this.lane.number = ''
        this.lane.control_number = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">车道名称</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="lane.name">
    </div>
    <div class="form-group">
      <label for="number" class="control-label">车道编号</label>
      <input type="text" id="number" class="form-control" v-model="lane.number">
    </div>
    <div class="form-group">
      <label for="control_number" class="control-label">车道控制编号</label>
      <input type="text" id="control_number" class="form-control" v-model="lane.control_number">
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
