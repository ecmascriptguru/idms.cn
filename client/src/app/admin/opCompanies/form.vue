
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'CcOperatingCompaniesForm',

    /**
    * Component's local state
    */
    data() {
      return {
        operatingCompany: {
          id: 0,
          name: '',
          short_name: '',
          contact: '',
          phone: '',
          address: '',
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
        return this.operatingCompany.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.operatingCompany.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill company name'] })
          return false
        }
        if (this.operatingCompany.short_name === '') {
          this.setMessage({ type: 'error', message: ['Please fill company short name'] })
          return false
        }
        if (this.operatingCompany.contact === '') {
          this.setMessage({ type: 'error', message: ['Please fill company contact'] })
          return false
        }
        if (this.operatingCompany.phone === '') {
          this.setMessage({ type: 'error', message: ['Please fill company phone'] })
          return false
        }
        if (this.operatingCompany.address === '') {
          this.setMessage({ type: 'error', message: ['Please fill company address'] })
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
          this.$http.get(`admin/ops/${id}`).then((res) => {
            const { id: _id, name, short_name, contact, phone, address } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.operatingCompany.id = _id
            this.operatingCompany.name = name
            this.operatingCompany.short_name = short_name
            this.operatingCompany.contact = contact
            this.operatingCompany.phone = phone
            this.operatingCompany.address = address
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
        this.$http.post('admin/ops', 
          { 
            name: this.operatingCompany.name,
            short_name: this.operatingCompany.short_name,
            contact: this.operatingCompany.contact,
            phone: this.operatingCompany.phone,
            address: this.operatingCompany.address,
          }).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('ops.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New operating Company was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`admin/ops/${this.operatingCompany.id}`, this.operatingCompany).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('op.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'Operating Company was updated' })
        })
      },
      reset() {
        this.operatingCompany.id = 0
        this.operatingCompany.name = ''
        this.operatingCompany.short_name = ''
        this.operatingCompany.contact = ''
        this.operatingCompany.phone = ''
        this.operatingCompany.address = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">Operating Company Name</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="operatingCompany.name">
    </div>
    <div class="form-group">
      <label for="short_name" class="control-label">Short Name</label>
      <input ref="firstInput" type="text" id="short_name" class="form-control" v-model="operatingCompany.short_name">
    </div>
    <div class="form-group">
      <label for="contact" class="control-label">Contact</label>
      <input ref="firstInput" type="text" id="contact" class="form-control" v-model="operatingCompany.contact">
    </div>
    <div class="form-group">
      <label for="phone" class="control-label">Phone</label>
      <input ref="firstInput" type="text" id="phone" class="form-control" v-model="operatingCompany.phone">
    </div>
    <div class="form-group">
      <label for="address" class="control-label">Address</label>
      <input ref="firstInput" type="text" id="address" class="form-control" v-model="operatingCompany.address">
    </div>
    <button class="btn btn-primary btn-xs" type="submit">Save</button>
  </form>
</template>
