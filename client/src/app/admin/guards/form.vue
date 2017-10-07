
<script>
  import { mapActions, mapState } from 'vuex'

  export default {
    name: 'CcGuardForm',

    /**
    * Component's local state
    */
    data() {
      return {
        guard: {
          id: 0,
          parking_lot_id: null,
          name: '',
        },
      }
    },

    /**
    * Fetch guard when component is first mounted
    */
    mounted() {
      this.fetchParkingLots()
      this.fetch()
    },

    updated() {
      this.setParkingLotId()
    },

    /**
    * Also fetch guard every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * guard id if the current actions
    * is editing instead of creating.
    */
    computed: {
      ...mapState({
        parkingLots: state => state.Admin.ParkingLots.full_list,
      }),
      isEditing() {
        return this.guard.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.guard.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill guard name'] })
          return false
        }
        return true
      },
      parkingLotId() {
        return parseInt(this.$route.query.pl || 0)
      },
    },

    methods: {
      ...mapActions(['parkingLotsSetData', 'setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the guard
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
          * Fetch the guard from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`admin/guards/${id}`).then((res) => {
            const { id: _id, parking_lot_id, name } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.guard.id = _id
            this.guard.parking_lot_id = parking_lot_id
            this.guard.name = name
            this.setFetching({ fetching: false })
          })
        }
      },
      setParkingLotId() {
        this.$refs.guard_form_parking_lot_id.value = this.guard.parking_lot_id || this.parkingLotId
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
        this.$http.post('admin/guards', this.guard).then(() => {
          /**
          * This event will notify the world about
          * the guard creation. In this case
          * the Guard main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('guard.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New guard was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`admin/guards/${this.guard.id}`, this.guard).then(() => {
          /**
          * This event will notify the world about
          * the guard creation. In this case
          * the Guard main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('guard.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'Guard was updated' })
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
            this.setParkingLotId()
          })
        } else {
          this.setParkingLotId()
        }
      },
      reset() {
        this.guard.id = 0
        this.parking_lot_id = null
        this.guard.name = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="parking_lot_id" class="control-label">停车场</label>
      <select ref="guard_form_parking_lot_id" class="form-control" v-model="guard.parking_lot_id" id="parking_lot_id" name="parking_lot_id">
        <option v-for="parkingLot in parkingLots" :value="parkingLot.id">
          {{ parkingLot.name }}
        </option>
      </select>
    </div>
    <div class="form-group">
      <label for="name" class="control-label">Guard Name</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="guard.name">
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
