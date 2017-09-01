
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'CcUserForm',

    /**
    * Component's local state
    */
    data() {
      return {
        user: {
          id: 0,
          name: '',
          username: '',
          phone: '',
          address: '',
          role_id: 2,
          organization_id: 2,
        },
      }
    },

    /**
    * Fetch user when component is first mounted
    */
    mounted() {
      this.fetch()
    },

    /**
    * Also fetch user every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * user id if the current actions
    * is editing instead of creating.
    */
    computed: {
      isEditing() {
        return this.user.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.user.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill user name'] })
          return false
        }
        return true
      },
    },

    methods: {
      ...mapActions(['setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the user
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
          * Fetch the user from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`users/${id}`).then((res) => {
            const { id: _id, name, username, phone, address } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.user.id = _id
            this.user.name = name
            this.user.username = username
            this.user.phone = phone
            this.user.address = address
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
        this.$http.post('users', 
          { 
            name: this.user.name,
            username: this.user.username,
            password: this.user.password,
            phone: this.user.phone,
            address: this.user.address,
            role_id: this.user.role_id,
            organization_id: this.user.organization_id,
          }).then(() => {
          /**
          * This event will notify the world about
          * the user creation. In this case
          * the user main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('user.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New user was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`users/${this.user.id}`, this.user).then(() => {
          /**
          * This event will notify the world about
          * the user creation. In this case
          * the user main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('user.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'User was updated' })
        })
      },
      reset() {
        this.user.id = 0
        this.user.name = ''
        this.user.username = ''
        this.user.phone = ''
        this.user.address = ''
        this.user.role_id = 2
        this.user.organization_id = 2
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">Full Name</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="user.name">
    </div>
    <div class="form-group">
      <label for="username" class="control-label">Username</label>
      <input ref="firstInput" type="text" id="username" class="form-control" v-model="user.username">
    </div>
    <div class="form-group">
      <label for="password" class="control-label">Password</label>
      <input ref="firstInput" type="password" id="password" class="form-control" v-model="user.password">
    </div>
    <div class="form-group">
      <label for="phone" class="control-label">Phone</label>
      <input ref="firstInput" type="text" id="phone" class="form-control" v-model="user.phone">
    </div>
    <div class="form-group">
      <label for="address" class="control-label">Address</label>
      <input ref="firstInput" type="text" id="address" class="form-control" v-model="user.address">
    </div>
    <button class="btn btn-primary btn-xs" type="submit">Salvar</button>
  </form>
</template>
