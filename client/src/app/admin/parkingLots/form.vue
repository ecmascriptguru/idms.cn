
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'CcParkingLotForm',

    /**
    * Component's local state
    */
    data() {
      return {
        parkingLot: {
          id: 0,
          name: '',
          address: '',
          contact: '',
          phone: '',
        },
      }
    },

    /**
    * Fetch parkingLot when component is first mounted
    */
    mounted() {
      this.fetch()
    },

    /**
    * Also fetch parkingLot every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * parkingLot id if the current actions
    * is editing instead of creating.
    */
    computed: {
      isEditing() {
        return this.parkingLot.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.parkingLot.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill parkingLot name'] })
          return false
        }
        return true
      },
    },

    methods: {
      ...mapActions(['setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the parkingLot
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
          * Fetch the parkingLot from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`admin/parkingLots/${id}`).then((res) => {
            const { id: _id, name, address, contact, phone } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.parkingLot.id = _id
            this.parkingLot.name = name
            this.parkingLot.address = address
            this.parkingLot.contact = contact
            this.parkingLot.phone = phone
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
        this.$http.post('admin/parkingLots', this.parkingLot).then(() => {
          /**
          * This event will notify the world about
          * the parkingLot creation. In this case
          * the ParkingLot main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('parkingLot.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New parkingLot was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`admin/parkingLots/${this.parkingLot.id}`, this.parkingLot).then(() => {
          /**
          * This event will notify the world about
          * the parkingLot creation. In this case
          * the ParkingLot main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('parkingLot.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'ParkingLot was updated' })
        })
      },
      reset() {
        this.parkingLot.id = 0
        this.parkingLot.name = ''
        this.parkingLot.address = ''
        this.parkingLot.contact = ''
        this.parkingLot.phone = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">ParkingLot Name</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="parkingLot.name">
    </div>
    <div class="form-group">
      <label for="address" class="control-label">Address</label>
      <input ref="firstInput" type="text" id="address" class="form-control" v-model="parkingLot.address">
    </div>
    <div class="form-group">
      <label for="contact" class="control-label">Contact Name</label>
      <input ref="firstInput" type="text" id="contact" class="form-control" v-model="parkingLot.contact">
    </div>
    <div class="form-group">
      <label for="phone" class="control-label">Contact Phone</label>
      <input ref="firstInput" type="text" id="phone" class="form-control" v-model="parkingLot.phone">
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
