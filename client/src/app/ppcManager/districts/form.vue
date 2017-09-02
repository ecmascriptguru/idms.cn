
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'JdDistrictForm',

    /**
    * Component's local state
    */
    data() {
      return {
        district: {
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
        return this.district.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.district.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill district name'] })
          return false
        }
        if (this.district.short_name === '') {
          this.setMessage({ type: 'error', message: ['Please fill district short name'] })
          return false
        }
        if (this.district.contact === '') {
          this.setMessage({ type: 'error', message: ['Please fill district contact'] })
          return false
        }
        if (this.district.phone === '') {
          this.setMessage({ type: 'error', message: ['Please fill district phone'] })
          return false
        }
        if (this.district.address === '') {
          this.setMessage({ type: 'error', message: ['Please fill district address'] })
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
          this.$http.get(`pca/districts/${id}`).then((res) => {
            const { id: _id, name, short_name, contact, phone, address } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.district.id = _id
            this.district.name = name
            this.district.short_name = short_name
            this.district.contact = contact
            this.district.phone = phone
            this.district.address = address
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
        this.$http.post('pca/districts', 
          { 
            name: this.district.name,
            short_name: this.district.short_name,
            contact: this.district.contact,
            phone: this.district.phone,
            address: this.district.address,
          }).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('district.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New operating District was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`pca/districts/${this.district.id}`, this.district).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
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
        })
      },
      reset() {
        this.district.id = 0
        this.district.name = ''
        this.district.short_name = ''
        this.district.contact = ''
        this.district.phone = ''
        this.district.address = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">District Name</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="district.name">
    </div>
    <div class="form-group">
      <label for="short_name" class="control-label">Short Name</label>
      <input type="text" id="short_name" class="form-control" v-model="district.short_name">
    </div>
    <div class="form-group">
      <label for="contact" class="control-label">Contact</label>
      <input type="text" id="contact" class="form-control" v-model="district.contact">
    </div>
    <div class="form-group">
      <label for="phone" class="control-label">Phone</label>
      <input type="text" id="phone" class="form-control" v-model="district.phone">
    </div>
    <div class="form-group">
      <label for="address" class="control-label">Address</label>
      <input type="text" id="address" class="form-control" v-model="district.address">
    </div>
    <button class="btn btn-primary btn-xs" type="submit">Save</button>
  </form>
</template>
