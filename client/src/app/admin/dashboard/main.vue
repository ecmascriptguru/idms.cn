<template>
  <div class="content-wrapper">
    Admin Developer Dashboard
  </div>
</template>

<script>
  import Vue from 'vue'
  import { mapActions, mapState } from 'vuex'

  export default {
    name: "JdAdminDashboard",

    components: {},

    methods: {
      correctLayout() {
        if (jQuery.AdminLTE.layout) {
          jQuery.AdminLTE.layout.fix()
        } else {
          jQuery(document).ready(() => {
            jQuery.AdminLTE.layout.fix()
          })
        }
      }
    },

    computed: {},

    mounted() {
      this.correctLayout()
    }
  }
</script>


<style lang="css">
</style>