
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
          title: '',
          image_title: '',
          file_entry_id: null,
          image_url: null,
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
        if (this.appAdv.title === '') {
          this.setMessage({ type: 'error', message: ['Please fill AppAdv Title'] })
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
          this.$http.get(`oca/app-advs/${id}`).then((res) => {
            console.log(res.data.data)
            const { id: _id, title, image_title, file_entry_id, image_url } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.appAdv.id = _id
            this.appAdv.title = title
            this.appAdv.image_title = image_title
            this.appAdv.file_entry_id = file_entry_id
            this.appAdv.image_url = image_url
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
        this.$http.post('oca/app-advs', 
          { 
            title: this.appAdv.title,
            image_title: this.appAdv.image_title,
            file_entry_id: this.appAdv.file_entry_id,
            image_url: this.appAdv.image_url,
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
          this.setMessage({ type: 'success', message: 'New App Advertisement was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`oca/app-advs/${this.appAdv.id}`, this.appAdv).then(() => {
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
          this.setMessage({ type: 'success', message: 'AppAdvertisement was updated' })
        })
      },
      reset() {
        this.appAdv.id = 0
        this.appAdv.title = ''
        this.appAdv.image_title = ''
        this.appAdv.file_entry_id = null
        this.appAdv.image_url = null
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="title" class="control-label">广告标题</label>
      <input ref="firstInput" type="text" id="title" class="form-control" v-model="appAdv.title">
    </div>
    <div v-if="appAdv.file_entry_id > 0" class="form-group">
      <img v-bind:src="appAdv.image_url" class="app-adv-image" />
    </div>
    <div class="form-group">
      <label for="image_file" class="control-label">Upload Image</label>
      <input type="file" name="image_file" class="form-control" />
    </div>
    <div class="form-group">
      <label for="image_title" class="control-label">图片标题</label>
      <input type="text" id="image_title" class="form-control" v-model="appAdv.image_title">
    </div>
    <input type="hidden" id="file_entry_id" v-model="appAdv.file_entry_id" />
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
