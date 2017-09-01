
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'CcOperatingCompaniesForm',

    /**
    * Component's local state
    */
    data() {
      return {
        propertyCompany: {
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
        return this.propertyCompany.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.propertyCompany.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill company name'] })
          return false
        }
        if (this.propertyCompany.short_name === '') {
          this.setMessage({ type: 'error', message: ['Please fill company short name'] })
          return false
        }
        if (this.propertyCompany.contact === '') {
          this.setMessage({ type: 'error', message: ['Please fill company contact'] })
          return false
        }
        if (this.propertyCompany.phone === '') {
          this.setMessage({ type: 'error', message: ['Please fill company phone'] })
          return false
        }
        if (this.propertyCompany.address === '') {
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
          this.$http.get(`oca/ppcs/${id}`).then((res) => {
            const { id: _id, name, short_name, contact, phone, address } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.propertyCompany.id = _id
            this.propertyCompany.name = name
            this.propertyCompany.short_name = short_name
            this.propertyCompany.contact = contact
            this.propertyCompany.phone = phone
            this.propertyCompany.address = address
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
        this.$http.post('oca/ppcs', 
          { 
            name: this.propertyCompany.name,
            short_name: this.propertyCompany.short_name,
            contact: this.propertyCompany.contact,
            phone: this.propertyCompany.phone,
            address: this.propertyCompany.address,
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
        this.$http.put(`oca/ppcs/${this.propertyCompany.id}`, this.propertyCompany).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('propertyCompany.updated')

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
        this.propertyCompany.id = 0
        this.propertyCompany.name = ''
        this.propertyCompany.short_name = ''
        this.propertyCompany.contact = ''
        this.propertyCompany.phone = ''
        this.propertyCompany.address = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">Property Company Name</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="propertyCompany.name">
    </div>
    <div class="form-group">
      <label for="short_name" class="control-label">Short Name</label>
      <input ref="firstInput" type="text" id="short_name" class="form-control" v-model="propertyCompany.short_name">
    </div>
    <div class="form-group">
      <label for="contact" class="control-label">Contact</label>
      <input ref="firstInput" type="text" id="contact" class="form-control" v-model="propertyCompany.contact">
    </div>
    <div class="form-group">
      <label for="phone" class="control-label">Phone</label>
      <input ref="firstInput" type="text" id="phone" class="form-control" v-model="propertyCompany.phone">
    </div>
    <div class="form-group">
      <label for="address" class="control-label">Address</label>
      <input ref="firstInput" type="text" id="address" class="form-control" v-model="propertyCompany.address">
    </div>
    <button class="btn btn-primary btn-xs" type="submit">Save</button>
  </form>
</template>
