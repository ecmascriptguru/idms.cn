
<script>
  import { mapActions } from 'vuex'
  import { resourceUrl } from '../../../config'
  import axios from 'axios'
  // import FileUpload from 'components/tools/fileupload'

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
        media: {
          image: '',
          file: null
        }
      }
    },

    components: {
      // FileUpload
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
            const { id: _id, title, image_title, file_entry_id, image_url } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.appAdv.id = _id
            this.appAdv.title = title
            this.appAdv.image_title = image_title
            this.appAdv.file_entry_id = file_entry_id
            this.appAdv.image_url = resourceUrl + image_url
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
      onFileChange(e) {
        let files = e.target.files || e.dataTransfer.files;
        if (files.length == 0)
          return;
        
        this.media.file = new FormData()
        this.media.file.append('file', files[0])
        this.createImage(files[0]);
      },
      createImage(file) {
        let reader = new FileReader();
        let vm = this;
        reader.onload = (e) => {
          vm.media.image = e.target.result;
        };
        reader.readAsDataURL(file);
      },
      upload(){
        this.setFetching({ fetching: true })
        let self = this
        axios.post('http://localhost:8000/api/files/add', this.media.file).then(response => {
          let file = response.data.file.data
          self.appAdv.file_entry_id = file.id
          self.appAdv.image_url = resourceUrl + file.url
          if (self.appAdv.id > 0) {
            self.update()
          }
        });
      },
      askRemove(item) {
        swal({
          title: 'Are you sure?',
          text: `App Advertisement <${item.title}>'s image will be permanently removed.`,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText: 'Yes, do it!',
          closeOnConfirm: false,
        }, () => this.removeImage()) // callback executed when OK is pressed
      },
      removeImage() {
        this.appAdv.file_entry_id = null;
        this.update()
        swal('Done!', 'App Advertisement Image removed.', 'success')
      },
      reset() {
        this.appAdv.id = 0
        this.appAdv.title = ''
        this.appAdv.image_title = ''
        this.appAdv.file_entry_id = null
        this.appAdv.image_url = null
        this.media.file = null
        this.media.image = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well" enctype="multipart/form-data">
    <div class="form-group">
      <label for="title" class="control-label">广告标题</label>
      <input ref="firstInput" type="text" id="title" class="form-control" v-model="appAdv.title">
    </div>
    <div class="form-group">
      <label for="image_title" class="control-label">图片标题</label>
      <input type="text" id="image_title" class="form-control" v-model="appAdv.image_title">
    </div>
    <div v-if="appAdv.file_entry_id" class="form-group">
      <div class="row">
        <div class="col-xs-12 app-adv-img-container">
          <img :src="appAdv.image_url" class="img-responsive">
          <div class="app-adv-img-hover">
            <button class="btn btn-remove" @click.prevent="askRemove(appAdv)" title="Remove This Image">
              <i class="fa fa-remove" ></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="form-group">
      <div class="">
        <label for="file" class="control-label">图片</label>
      </div>
      <div v-if="media.image" class="row">
        <div class="col-xs-12">
          <img :src="media.image" />
        </div>
      </div>
      <div class="row">
        <div class="col-lg-9 col-md-8 col-sm-7 col-xs-6">
          <input type="file" id="file" name="file" v-on:change="onFileChange" class="form-control">
        </div>
        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
          <button class="btn btn-success btn-block" @click.prevent="upload">Upload</button>
        </div>
      </div>
    </div>
    <input type="hidden" id="file_entry_id" v-model="appAdv.file_entry_id" />
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
<style scoped>
  .app-adv-img-container {
    position: relative;}
    .app-adv-img-container .app-adv-img-hover {
      display: none;}
      .app-adv-img-container:hover .app-adv-img-hover {
        display: block;
        top: 0;
        position: absolute;
        right: 15px;
        left: 15px;
        height: 100%;
        background: rgba(0,0,0,0.3);}
        .app-adv-img-container .app-adv-img-hover button.btn-remove {
          position: absolute;
          right: 5px;
          top: 5px;}
  img{
    width: 100%;
    height: auto;
  }
</style>