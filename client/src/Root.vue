<script>
import { mapGetters } from 'vuex'
import JdHeader from './components/root/header'
import JdFooter from './components/root/footer'
import JdLeftSideBar from './components/root/leftsidebar'
import JdControlSideBar from './components/root/controlsidebar'
import JdBgSideBar from './components/root/bgsidebar'
import JdAlerts from './components/general/alerts'

export default {
  name: 'Codecasts',
  components: {
    JdHeader,
    JdFooter,
    JdLeftSideBar,
    JdControlSideBar,
    JdBgSideBar,
    JdAlerts,
  },
  computed: {
    ...mapGetters(['shouldShowNavigation']),
  },
}
</script>

<template>
  <div id="app" class="app">
    <jd-header v-show="shouldShowNavigation"></jd-header>
    <jd-left-side-bar v-show="shouldShowNavigation"></jd-left-side-bar>
    <router-view></router-view>
    <!-- <div class="container">
      <jd-alerts></jd-alerts>
    </div> -->
    <jd-footer v-show="shouldShowNavigation"></jd-footer>
    <!-- <jd-control-side-bar v-show="shouldShowNavigation"></jd-control-side-bar> -->
    <!-- <jd-bg-side-bar v-show="shouldShowNavigation"></jd-bg-side-bar> -->
  </div>
</template>
<style>
  .app {
    position: relative;
    height: 100%;
  }
</style>

<style lang="sass" src="assets/sass/app.scss"></style>
