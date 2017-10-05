
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'CcProvinceForm',

    /**
    * Component's local state
    */
    data() {
      return {
        province: {
          id: 0,
          name: '',
        },
      }
    },

    /**
    * Fetch province when component is first mounted
    */
    mounted() {
      this.fetch()
    },

    /**
    * Also fetch province every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * province id if the current actions
    * is editing instead of creating.
    */
    computed: {
      isEditing() {
        return this.province.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.province.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill province name'] })
          return false
        }
        return true
      },
    },

    methods: {
      ...mapActions(['setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the province
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
          * Fetch the province from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`admin/provinces/${id}`).then((res) => {
            const { id: _id, name } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.province.id = _id
            this.province.name = name
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
        this.$http.post('admin/provinces', this.province).then(() => {
          /**
          * This event will notify the world about
          * the province creation. In this case
          * the Province main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('province.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New province was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`admin/provinces/${this.province.id}`, this.province).then(() => {
          /**
          * This event will notify the world about
          * the province creation. In this case
          * the Province main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('province.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'Province was updated' })
        })
      },
      reset() {
        this.province.id = 0
        this.province.name = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">Province Name</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="province.name">
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
