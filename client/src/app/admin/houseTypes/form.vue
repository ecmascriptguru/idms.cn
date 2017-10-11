
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'CcHouseTypeForm',

    /**
    * Component's local state
    */
    data() {
      return {
        houseType: {
          id: 0,
          name: '',
        },
      }
    },

    /**
    * Fetch houseType when component is first mounted
    */
    mounted() {
      this.fetch()
    },

    /**
    * Also fetch houseType every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * houseType id if the current actions
    * is editing instead of creating.
    */
    computed: {
      isEditing() {
        return this.houseType.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.houseType.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill houseType name'] })
          return false
        }
        return true
      },
    },

    methods: {
      ...mapActions(['setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the houseType
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
          * Fetch the houseType from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`admin/houseTypes/${id}`).then((res) => {
            const { id: _id, name } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.houseType.id = _id
            this.houseType.name = name
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
        this.$http.post('admin/houseTypes', { name: this.houseType.name }).then(() => {
          /**
          * This event will notify the world about
          * the houseType creation. In this case
          * the House Type main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('houseType.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New houseType was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`admin/houseTypes/${this.houseType.id}`, this.houseType).then(() => {
          /**
          * This event will notify the world about
          * the houseType creation. In this case
          * the House Type main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('houseType.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'House Type was updated' })
        })
      },
      reset() {
        this.houseType.id = 0
        this.houseType.name = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">House Type Name</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="houseType.name">
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
