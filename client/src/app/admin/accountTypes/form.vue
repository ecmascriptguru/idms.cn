
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'JdAdminAccountTypeForm',

    /**
    * Component's local state
    */
    data() {
      return {
        accountType: {
          id: 0,
          name: '',
        },
      }
    },

    /**
    * Fetch accountType when component is first mounted
    */
    mounted() {
      this.fetch()
    },

    /**
    * Also fetch accountType every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * accountType id if the current actions
    * is editing instead of creating.
    */
    computed: {
      isEditing() {
        return this.accountType.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.accountType.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill accountType name'] })
          return false
        }
        return true
      },
    },

    methods: {
      ...mapActions(['setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the accountType
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
          * Fetch the accountType from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`admin/accountTypes/${id}`).then((res) => {
            const { id: _id, name } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.accountType.id = _id
            this.accountType.name = name
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
        this.$http.post('admin/accountTypes', { name: this.accountType.name }).then(() => {
          /**
          * This event will notify the world about
          * the accountType creation. In this case
          * the AccountType main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('accountType.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New accountType was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`admin/accountTypes/${this.accountType.id}`, this.accountType).then(() => {
          /**
          * This event will notify the world about
          * the accountType creation. In this case
          * the AccountType main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('accountType.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'AccountType was updated' })
        })
      },
      reset() {
        this.accountType.id = 0
        this.accountType.name = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">AccountType Name</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="accountType.name">
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
