
<script>
  import { mapActions, mapState } from 'vuex'

  export default {
    name: 'JdPpcUserForm',

    /**
    * Component's local state
    */
    data() {
      return {
        user: {
          id: 0,
          name: '',
          username: '',
          password: '',
          phone: '',
          address: '',
          role_id: 4,
          district_id: '',
        },
      }
    },

    /**
    * Fetch user when component is first mounted
    */
    mounted() {
      this.fetch()
      this.fetchDistricts()
      this.setDistrictId()
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
      ...mapState({
        districts: state => state.PpcManager.Districts.full_list,
      }),
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
      ...mapActions(['districtsSetData', 'setFetching', 'resetMessages', 'setMessage']),

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
          this.$http.get(`pca/users/${id}`).then((res) => {
            const { id: _id, name, username, phone, address, district_id, } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.user.id = _id
            this.user.name = name
            this.user.username = username
            this.user.phone = phone
            this.user.address = address
            this.user.district_id = district_id
            this.setFetching({ fetching: false })
          })
        }
      },
      fetchDistricts() {
        if (!this.districts.length) {
          this.setFetching({ fetching: true })
          this.$http.get('pca/districts/full-list').then(({ data }) => {
            /**
            * Vuex action to set full list array in
            * the Vuex OperatingCompanies module
            */
            this.districtsSetData({
              full_list: data.data,
            })
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
        this.$http.post('pca/users', 
          { 
            name: this.user.name,
            username: this.user.username,
            password: this.user.password,
            phone: this.user.phone,
            address: this.user.address,
            role_id: this.user.role_id,
            district_id: this.user.district_id,
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
        this.$http.put(`pca/users/${this.user.id}`, this.user).then(() => {
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
        this.user.password = ''
        this.user.role_id = 4
        this.user.district_id = ''
      },
      setDistrictId() {
        this.user.district_id = parseInt(this.$route.query.dctId || 0)
      }
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">姓名</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="user.name">
    </div>
    <div class="form-group">
      <label for="username" class="control-label">用户</label>
      <input type="text" id="username" class="form-control" v-model="user.username">
    </div>
    <div class="form-group">
      <label for="password" class="control-label">密码</label>
      <input type="password" id="password" class="form-control" v-model="user.password">
    </div>
    <div class="form-group">
      <label for="phone" class="control-label">手机</label>
      <input type="text" id="phone" class="form-control" v-model="user.phone">
    </div>
    <div class="form-group">
      <label for="address" class="control-label">地址</label>
      <input type="text" id="address" class="form-control" v-model="user.address">
    </div>
    <div class="form-group">
      <label for="district_id" class="control-label">社区</label>
      <select name="district_id" id="district_id" class="form-control" v-model="user.district_id">
        <option v-for="district in districts" :value="district.id">
          {{ district.name }}
        </option>
      </select>
    </div>
    <button class="btn btn-primary btn-xs" type="submit">Salvar</button>
  </form>
</template>
