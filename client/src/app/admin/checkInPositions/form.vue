
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'CcCheckInPositionForm',

    /**
    * Component's local state
    */
    data() {
      return {
        checkInPosition: {
          id: 0,
          name: '',
        },
      }
    },

    /**
    * Fetch checkInPosition when component is first mounted
    */
    mounted() {
      this.fetch()
    },

    /**
    * Also fetch checkInPosition every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * checkInPosition id if the current actions
    * is editing instead of creating.
    */
    computed: {
      isEditing() {
        return this.checkInPosition.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.checkInPosition.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill checkInPosition name'] })
          return false
        }
        return true
      },
    },

    methods: {
      ...mapActions(['setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the checkInPosition
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
          * Fetch the checkInPosition from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`admin/checkInPositions/${id}`).then((res) => {
            const { id: _id, name } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.checkInPosition.id = _id
            this.checkInPosition.name = name
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
        this.$http.post('admin/checkInPositions', { name: this.checkInPosition.name }).then(() => {
          /**
          * This event will notify the world about
          * the checkInPosition creation. In this case
          * the CheckInPosition main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('checkInPosition.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New checkInPosition was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`admin/checkInPositions/${this.checkInPosition.id}`, this.checkInPosition).then(() => {
          /**
          * This event will notify the world about
          * the checkInPosition creation. In this case
          * the CheckInPosition main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('checkInPosition.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'CheckInPosition was updated' })
          this.$router.push({
            name: 'admin.checkInPositions.index',
          })
        })
      },
      reset() {
        this.checkInPosition.id = 0
        this.checkInPosition.name = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">门禁名称</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="checkInPosition.name">
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
