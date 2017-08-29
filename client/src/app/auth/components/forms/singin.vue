<script>
  import { mapActions } from 'vuex'

  export default {
    /**
    * Component's local state
    */
    data() {
      return {
        username: 'admin',
        password: '123456',
      }
    },
    methods: {
      /**
      * Map the actions from Vuex to this component.
      * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Spread_operator
      */
      ...mapActions(['attemptLogin', 'setMessage']),

      /**
      * Handle form's submit event
      */
      submit() {
        const { username, password } = this // http://wesbos.com/destructuring-objects/
        this.attemptLogin({ username, password }) // this is a Vuex action
          .then((response) => {
            console.log(response);
            this.setMessage({ type: 'error', message: [] }) // this is a Vuex action

            switch (response.role.name) {
              case "Admin":
                this.$router.push({ name: "admin.dashboard" })
                break;

              default:
                this.$router.push({ name: 'dashboard.index' })
                break;
            }
          })
      },
      /**
      * Reset component's local state
      */
      reset() {
        this.username = ''
        this.password = ''
      },
    },
  }
</script>

<template>
  <form class="well" @submit.prevent="submit">
    <div class="form-group">
      <label for="username" class="control-label">Username</label>
      <input type="text" class="form-control" id="username" v-model="username">
    </div>
    <div class="form-group">
      <label for="password" class="control-label">Password</label>
      <input type="password" class="form-control" id="password" v-model="password">
    </div>
    <button class="btn btn-primary btn-block" type="submit">Login</button>
  </form>
</template>
