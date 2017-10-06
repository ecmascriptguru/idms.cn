
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'JdBillForm',

    /**
    * Component's local state
    */
    data() {
      return {
        bill: {
          id: 0,
          total: 0,
          is_paid: true,
        },
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
        return this.bill.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.bill.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill bill name'] })
          return false
        }
        if (this.bill.number === '') {
          this.setMessage({ type: 'error', message: ['Please fill bill name'] })
          return false
        }
        return true
      },
      currentPage() {
        return parseInt(this.$route.query.page, 10)
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
          this.$http.get(`dct/bills/${id}`).then((res) => {
            const { id: _id, total, is_paid } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.bill.id = _id
            this.bill.total = total
            this.bill.is_paid = is_paid
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
        this.$http.post('dct/bills', this.bill).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('bill.created')

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
          // this.reset()
          this.$router.push({
            name: 'dctManager.bills.index',
            query: { page: this.currentPage }
          })
        })
      },
      update() {
        this.$http.put(`dct/bills/${this.bill.id}`, this.bill).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('bill.updated')

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
        this.bill.id = 0
        this.bill.is_paid = false
      },
    },
  }
</script>

<template>
  <!-- <div class="col-xs-12"> -->
    <form @submit.prevent="submit" class="well">
      <div class="form-group">
        <label for="total" class="control-label">Year</label>
        <input type="text" ref="firstInput" id="total" class="form-control" v-model="bill.total">
      </div>
      <button class="btn btn-primary" type="submit">保存</button>
    </form>
  <!-- </div> -->
</template>
