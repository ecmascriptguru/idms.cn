
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'JdBuildingForm',

    /**
    * Component's local state
    */
    data() {
      return {
        building: {
          id: 0,
          name: '',
          number: '',
        },
      }
    },

    /**
    * Fetch op when component is first mounted
    */
    mounted() {
      this.fetch()
    },

    /**
    * Also fetch op every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * op id if the current actions
    * is editing instead of creating.
    */
    computed: {
      isEditing() {
        return this.building.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.building.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill building name'] })
          return false
        }
        if (this.building.number === '') {
          this.setMessage({ type: 'error', message: ['Please fill building name'] })
          return false
        }
        return true
      },
    },

    methods: {
      ...mapActions(['setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the op
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
          * Fetch the op from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`dct/buildings/${id}`).then((res) => {
            const { id: _id, name, number, contact, phone, address } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.building.id = _id
            this.building.name = name
            this.building.number = number
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
        this.$http.post('dct/buildings', this.building).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('building.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'Building was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`dct/buildings/${this.building.id}`, this.building).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('building.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'Building was updated' })
        })
      },
      reset() {
        this.building.id = 0
        this.building.name = ''
        this.building.number = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">Building Name</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="building.name">
    </div>
    <div class="form-group">
      <label for="number" class="control-label">Building Number</label>
      <input type="text" id="number" class="form-control" v-model="building.number">
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
