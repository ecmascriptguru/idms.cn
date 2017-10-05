
<script>
  import { mapActions, mapState } from 'vuex'

  export default {
    name: 'CcCityForm',

    /**
    * Component's local state
    */
    data() {
      return {
        city: {
          id: 0,
          province_id: null,
          name: '',
        },
      }
    },

    /**
    * Fetch city when component is first mounted
    */
    mounted() {
      this.fetchProvinces()
      this.fetch()
    },

    updated() {
      this.setProvinceId()
    },

    /**
    * Also fetch city every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * city id if the current actions
    * is editing instead of creating.
    */
    computed: {
      ...mapState({
        provinces: state => state.Admin.Provinces.full_list,
      }),
      isEditing() {
        return this.city.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.city.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill city name'] })
          return false
        }
        return true
      },
      provinceId() {
        return parseInt(this.$route.query.province_id || 0)
      },
    },

    methods: {
      ...mapActions(['provincesSetData', 'setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the city
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
          * Fetch the city from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`admin/cities/${id}`).then((res) => {
            const { id: _id, province_id, name } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.city.id = _id
            this.city.province_id = province_id
            this.city.name = name
            this.setFetching({ fetching: false })
          })
        }
      },
      setProvinceId() {
        this.$refs.city_form_province_id.value = this.city.province_id || this.provinceId
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
        this.$http.post('admin/cities', this.city).then(() => {
          /**
          * This event will notify the world about
          * the city creation. In this case
          * the City main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('city.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New city was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`admin/cities/${this.city.id}`, this.city).then(() => {
          /**
          * This event will notify the world about
          * the city creation. In this case
          * the City main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('city.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'City was updated' })
        })
      },
      /**
       * Fetching Provinces to render province drop down
       */
      fetchProvinces() {
        if (!this.provinces.length) {
          this.setFetching({ fetching: true })
          this.$http.get('admin/provinces/full-list').then(({ data }) => {
            /**
            * Vuex action to set full list array in
            * the Vuex OperatingCompanies module
            */
            this.provincesSetData({
              full_list: data.data,
            })
            this.setFetching({ fetching: false })
            this.setProvinceId()
          })
        } else {
          this.setProvinceId()
        }
      },
      reset() {
        this.city.id = 0
        this.province_id = null
        this.city.name = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="province_id" class="control-label">Province</label>
      <select ref="city_form_province_id" class="form-control" v-model="city.province_id" id="province_id" name="province_id">
        <option v-for="province in provinces" :value="province.id">
          {{ province.name }}
        </option>
      </select>
    </div>
    <div class="form-group">
      <label for="name" class="control-label">City Name</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="city.name">
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
