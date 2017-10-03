
<script>
  import { mapActions } from 'vuex'
  import { resourceUrl } from '../../../config'
  import axios from 'axios'
  import Datepicker from 'vuejs-datepicker';
  import myVideo from 'vue-video'

  export default {
    name: 'JdDeviceAdvForm',

    /**
    * Component's local state
    */
    data() {
      return {
        deviceAdv: {
          id: 0,
          name: '',
          from: '',
          to: '',
          title: '',
          status: 1,
          file_entry_id: null,
          url: null,
        },
        media: {
          image: '',
          file: null
        },
        options: {
          autoplay: false,
          volume: 0.6,
        },
        sources: [],
      }
    },

    components: {
      Datepicker,
      myVideo
    },

    /**
    * Fetch op when component is first mounted
    */
    mounted() {
      this.fetch()
      if (jQuery.AdminLTE.layout) {
        jQuery.AdminLTE.layout.fix()
      } else {
        jQuery(document).ready(() => {
          jQuery.AdminLTE.layout.fix()
        })
      }
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
        return this.deviceAdv.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.deviceAdv.title === '') {
          this.setMessage({ type: 'error', message: ['Please fill DeviceAdv Title'] })
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
          this.$http.get(`oca/device-advs/${id}`).then((res) => {
            const { id: _id, name, from, to, title, file_entry_id, url, file } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.deviceAdv.id = _id
            this.deviceAdv.name = name
            this.deviceAdv.from = from
            $("#from").val(from)
            this.deviceAdv.to = to
            $("#to").val(to)
            this.deviceAdv.title = title
            this.deviceAdv.file_entry_id = file_entry_id
            this.deviceAdv.url = resourceUrl + url
            this.setFetching({ fetching: false })
            this.sources = [{
              src: this.deviceAdv.url,
              type: file.mime
            }]
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
        this.$http.post('oca/device-advs', 
          this.deviceAdv).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('deviceAdv.created')

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
        this.$http.put(`oca/device-advs/${this.deviceAdv.id}`, this.deviceAdv).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('deviceAdv.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'DeviceAdvertisement was updated' })
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
        let fileReader = new FileReader();
        let self = this
        fileReader.onload = function() {
          let blob = new Blob([fileReader.result], {type: file.type})
          let url = URL.createObjectURL(blob)
          let video = document.createElement('video')
          const timeupdate = function() {
            if (snapImage()) {
              video.removeEventListener('timeupdate', timeupdate)
              video.pause();
            }
          }

          video.addEventListener('loadeddata', function() {
            if (snapImage()) {
              video.removeEventListener('timeupdate', timeupdate)
            }
          })

          const snapImage = function() {
            let canvas = document.createElement('canvas')
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            let image = canvas.toDataURL();
            let success = image.length > 100000;
            if (success) {
              // var img = document.createElement('img');
              // img.src = image;
              // document.getElementsByTagName('div')[0].appendChild(img);
              self.media.image = image
              URL.revokeObjectURL(url);
            }
            return success;
          };
          video.addEventListener('timeupdate', timeupdate);
          video.preload = 'metadata';
          video.src = url;
          // Load video in Safari / IE11
          video.muted = true;
          video.playsInline = true;
          video.play();
        };
        fileReader.readAsArrayBuffer(file);
      },
      upload(){
        this.setFetching({ fetching: true })
        let self = this
        axios.post('http://localhost:8000/api/files/add', this.media.file).then(response => {
          let file = response.data.file.data
          self.deviceAdv.file_entry_id = file.id
          self.deviceAdv.url = resourceUrl + file.url
          self.sources.push({
            src: self.deviceAdv.url,
            type: file.mime
          })
          if (self.deviceAdv.id > 0) {
            self.update()
          }
        });
      },
      askRemove(item) {
        swal({
          title: 'Are you sure?',
          text: `App Advertisement <${item.title}>'s File will be permanently removed.`,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText: 'Yes, do it!',
          closeOnConfirm: false,
        }, () => this.removeImage()) // callback executed when OK is pressed
      },
      onFromChanged(val) {
        this.deviceAdv.from = val.toISOString().substr(0, 10)
      },
      onToChanged(val) {
        this.deviceAdv.to = val.toISOString().substr(0, 10)
      },
      removeImage() {
        this.deviceAdv.file_entry_id = null;
        this.update()
        swal('Done!', 'App Advertisement File removed.', 'success')
      },
      reset() {
        this.deviceAdv.id = 0
        this.deviceAdv.name = ''
        this.deviceAdv.from = ''
        this.deviceAdv.to = ''
        this.deviceAdv.title = ''
        this.deviceAdv.file_entry_id = null
        this.deviceAdv.status = 1,
        this.deviceAdv.url = null
        this.media.file = null
        this.media.image = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well" enctype="multipart/form-data">
    <div class="form-group">
      <label for="name" class="control-label">广告计划名称</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="deviceAdv.name">
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-sm-6 col-xs-12">
          <label for="from" class="control-label">开始时间</label>
          <datepicker ref="from" @selected="onFromChanged" language="zh" id="from" name="from" format="yyyy-MM-dd" input-class="form-control"></datepicker>
        </div>
        <div class="col-sm-6 col-xs-12">
          <label for="to" class="control-label">终止时间</label>
          <datepicker ref="to" @selected="onToChanged" language="zh" id="to" name="to" format="yyyy-MM-dd" input-class="form-control"></datepicker>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="title" class="control-label">广告视频标题</label>
      <input type="text" id="title" class="form-control" v-model="deviceAdv.title">
    </div>
    <div v-if="deviceAdv.file_entry_id" class="form-group">
      <div class="row">
        <div class="col-xs-12 app-adv-img-container">
          <my-video :sources="sources" :options="options"></my-video>
          <div class="app-adv-img-hover">
            <button class="btn btn-remove" @click.prevent="askRemove(deviceAdv)" title="Remove This File">
              <i class="fa fa-remove" >
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="form-group">
      <div class="">
        <label for="file" class="control-label">广告视频</label>
      </div>
      <div v-if="media.image" class="row">
        <div class="col-xs-12">
          <img :src="media.image" />
        </div>
      </div>
      <div class="row">
        <div class="col-lg-9 col-md-8 col-sm-7 col-xs-6">
          <input type="file" accept=".mp4" id="file" name="file" v-on:change="onFileChange" class="form-control">
        </div>
        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
          <button class="btn btn-success btn-block" @click.prevent="upload">Upload</button>
        </div>
      </div>
    </div>
    <input type="hidden" id="file_entry_id" v-model="deviceAdv.file_entry_id" />
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