
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'CcCustomFeeTypeForm',

    /**
    * Component's local state
    */
    data() {
      return {
        customFeeType: {
          id: 0,
          name: '',
        },
      }
    },

    /**
    * Fetch customFeeType when component is first mounted
    */
    mounted() {
      this.fetch()
    },

    /**
    * Also fetch customFeeType every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * customFeeType id if the current actions
    * is editing instead of creating.
    */
    computed: {
      isEditing() {
        return this.customFeeType.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.customFeeType.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill customFeeType name'] })
          return false
        }
        return true
      },
    },

    methods: {
      ...mapActions(['setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the customFeeType
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
          * Fetch the customFeeType from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`admin/customFeeTypes/${id}`).then((res) => {
            const { id: _id, name } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.customFeeType.id = _id
            this.customFeeType.name = name
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
        this.$http.post('admin/customFeeTypes', { name: this.customFeeType.name }).then(() => {
          /**
          * This event will notify the world about
          * the customFeeType creation. In this case
          * the Custom Fee Type main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('customFeeType.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New customFeeType was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`admin/customFeeTypes/${this.customFeeType.id}`, this.customFeeType).then(() => {
          /**
          * This event will notify the world about
          * the customFeeType creation. In this case
          * the Custom Fee Type main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('customFeeType.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'Custom Fee Type was updated' })
        })
      },
      reset() {
        this.customFeeType.id = 0
        this.customFeeType.name = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">Custom Fee Type Name</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="customFeeType.name">
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
