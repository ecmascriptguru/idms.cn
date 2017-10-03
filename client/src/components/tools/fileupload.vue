<script>
    import axios from 'axios'
    
    export default{
        data(){
            return {
                image: '',
                file: null,
            }
        },
        methods: {
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                
                this.file = new FormData()
                this.file.append('file', files[0])
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            upload(){
                axios.post('http://localhost:8000/api/files/add', this.file).then(response => {

                });
            }
        }
    }
</script>
<template>
    <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-12">
            <img :src="image" class="img-responsive">
        </div>
        <div class="col-md-4 col-sm-3 col-xs-12">
            <input type="file" v-on:change="onFileChange" class="form-control">
        </div>
        <div class="col-md-4 col-sm-5 col-xs-12">
            <button class="btn btn-success btn-block" @click="upload">Upload</button>
        </div>
    </div>
</template>
<style scoped>
    img{
        max-height: 36px;
    }
</style>