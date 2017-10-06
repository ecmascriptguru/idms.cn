
<script>
  import { mapActions, mapState } from 'vuex'

  export default {
    name: 'JdDctReminderForm',

    /**
    * Component's local state
    */
    data() {
      return {
        district: {
          reminder: 0,
        },
      }
    },

    /**
    * Fetch district when component is first mounted
    */
    mounted() {
      this.fetch()
    },

    updated() {
    },

    /**
    * Also fetch district every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * district id if the current actions
    * is editing instead of creating.
    */
    computed: {
      ...mapState({
        currentUser: state => state.Auth.user,
      }),
      isEditing() {
        return this.district.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.district.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill district name'] })
          return false
        }
        return true
      },
      currentPage() {
        return parseInt(this.$route.query.page, 10)
      },
      districtId() {
        return this.currentUser.district_id || 0
      },
    },

    methods: {
      ...mapActions(['setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the district
      * from the server
      */
      fetch() {
        this.$refs.firstInput.focus()
        /**
        * This same component is used for create
        * and update so we have to check if
        * ID is not undefined.
        */
        if (this.districtId !== undefined) {
          /**
          * Fetch the district from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`pca/districts/${this.districtId}`).then((res) => {
            const { 
              reminder
            } = res.data.data // http://wesbos.com/destructuring-renaming/
            
            this.district.reminder = reminder;
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

          this.update()
        }
      },
      update() {
        this.$http.post(`pca/districts/reminder`, {
            id: this.districtId,
            reminder: this.district.reminder
          }).then(() => {
          /**
          * This event will notify the world about
          * the district creation. In this case
          * the district main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('district.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'District was updated' })
          this.$router.push({ 
            name: 'dctManager.feeStandards.index',
            query: { page: this.currentPage }
          })
        })
      },
      reset() {
        this.district.name = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="reminder" class="control-label">逾期（天）门禁机进行催缴提醒</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-5 col-sm-5 col-xs-4">
          <input ref="firstInput" type="number" step="0.1" id="reminder" class="form-control" v-model="district.reminder">
        </div>
      </div>
      <div class="row" style="padding-top:10px;">
        <div class="col-lg-2 col-lg-offset-8 col-md-4 col-md-offset-7 col-sm-5 col-sm-offset-6 col-xs-12">
          <button class="btn btn-primary form-control" type="submit">保存</button>
        </div>
      </div>
    </div>
  </form>
</template>
