
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'CcCheckInTypeForm',

    /**
    * Component's local state
    */
    data() {
      return {
        checkInType: {
          id: 0,
          name: '',
        },
      }
    },

    /**
    * Fetch checkInType when component is first mounted
    */
    mounted() {
      this.fetch()
    },

    /**
    * Also fetch checkInType every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * checkInType id if the current actions
    * is editing instead of creating.
    */
    computed: {
      isEditing() {
        return this.checkInType.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.checkInType.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill checkInType name'] })
          return false
        }
        return true
      },
    },

    methods: {
      ...mapActions(['setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the checkInType
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
          * Fetch the checkInType from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`admin/checkInTypes/${id}`).then((res) => {
            const { id: _id, name } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.checkInType.id = _id
            this.checkInType.name = name
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
        this.$http.post('admin/checkInTypes', { name: this.checkInType.name }).then(() => {
          /**
          * This event will notify the world about
          * the checkInType creation. In this case
          * the CheckIn Type main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('checkInType.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New checkInType was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`admin/checkInTypes/${this.checkInType.id}`, this.checkInType).then(() => {
          /**
          * This event will notify the world about
          * the checkInType creation. In this case
          * the CheckIn Type main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('checkInType.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'CheckIn Type was updated' })
          this.$router.push({
            name: 'admin.checkInTypes.index',
          })
        })
      },
      reset() {
        this.checkInType.id = 0
        this.checkInType.name = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">门禁名称</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="checkInType.name">
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
