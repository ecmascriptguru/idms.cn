
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'JdAppAdvForm',

    /**
    * Component's local state
    */
    data() {
      return {
        appAdv: {
          id: 0,
          name: '',
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
        return this.appAdv.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.appAdv.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill company name'] })
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
            this.appAdv.id = _id
            this.appAdv.name = name
            this.appAdv.short_name = short_name
            this.appAdv.contact = contact
            this.appAdv.phone = phone
            this.appAdv.address = address
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
            name: this.appAdv.name,
            short_name: this.appAdv.short_name,
            contact: this.appAdv.contact,
            phone: this.appAdv.phone,
            address: this.appAdv.address,
          }).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('appAdv.created')

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
        this.$http.put(`oca/ppcs/${this.appAdv.id}`, this.appAdv).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('appAdv.updated')

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
        this.appAdv.id = 0
        this.appAdv.name = ''
        this.appAdv.short_name = ''
        this.appAdv.contact = ''
        this.appAdv.phone = ''
        this.appAdv.address = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">物业公司名称</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="appAdv.name">
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
