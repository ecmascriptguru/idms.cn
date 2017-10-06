
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'JdBillTermForm',

    /**
    * Component's local state
    */
    data() {
      let year = (new Date).getFullYear()
      let month = (new Date).getMonth() + 1
      if (month < 10) {
        month = `0${month}`
      }

      let years = [],
          months = []
      for (let i = year - 2; i < year + 4; i ++) {
        years.push(i)
      }

      for (let i = 1; i < 13; i ++) {
        if (i < 10) {
          months.push(`0${i}`)
        } else {
          months.push(`${i}`)
        }
      }

      return {
        billTerm: {
          id: 0,
          date: '',
          year: (new Date).getFullYear(),
          month: month,
          is_released: false,
        },
        years,
        months,
      }
    },

    /**
    * Fetch op when component is first mounted
    */
    mounted() {
      this.fetch()
    },

    /**
    * Also fetch op every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * op id if the current actions
    * is editing instead of creating.
    */
    computed: {
      isEditing() {
        return this.billTerm.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.billTerm.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill billTerm name'] })
          return false
        }
        if (this.billTerm.number === '') {
          this.setMessage({ type: 'error', message: ['Please fill billTerm name'] })
          return false
        }
        return true
      },
    },

    methods: {
      ...mapActions(['setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the op
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
          * Fetch the op from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`dct/billTerms/${id}`).then((res) => {
            const { id: _id, date, is_released } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.billTerm.id = _id
            this.billTerm.date = date
            this.billTerm.is_released = is_released
            let segs = date.split("-")
            if (segs.length > 1) {
              this.billTerm.year = segs[0]
              this.billTerm.month = (segs[1].length < 2) ? ` ${segs[1]}` : segs[1]
            }
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
        this.billTerm.date = `${this.billTerm.year}-${this.billTerm.month}`
        this.$http.post('dct/billTerms', this.billTerm).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('billTerm.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'Building was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`dct/billTerms/${this.billTerm.id}`, this.billTerm).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('billTerm.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'Building was updated' })
        })
      },
      reset() {
        this.billTerm.id = 0
        this.billTerm.year = (new Date()).getFullYear()
        this.billTerm.month = (new Date()).getMonth() + 1
        if (this.billTerm.month < 10) {
          this.billTerm.month = `0${this.billTerm.month}`
        }
        this.billTerm.date = `${this.billTerm.year}-${this.billTerm.month}`
        this.billTerm.is_released = false
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="year" class="control-label">Year</label>
      <select ref="firstInput" id="year" class="form-control" v-model="billTerm.year">
        <option v-for="year in years" :value="year">
          {{ year }}
        </option>
      </select>
    </div>
    <div class="form-group">
      <label for="month" class="control-label">Month</label>
      <select id="month" class="form-control" v-model="billTerm.month">
        <option v-for="month in months" :value="month">
          {{ month }}
        </option>
      </select>
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
